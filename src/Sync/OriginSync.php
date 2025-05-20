<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Log\IRepository;
use srag\Plugins\Hub2\Exception\AbortOriginSyncException;
use srag\Plugins\Hub2\Exception\AbortOriginSyncOfCurrentTypeException;
use srag\Plugins\Hub2\Exception\AbortSyncException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\NullDTO;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\IObjectFactory;
use srag\Plugins\Hub2\Object\IObjectRepository;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use Throwable;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Jobs\Notifier;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class Sync
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSync implements IOriginSync
{
    protected IOrigin $origin;
    protected IObjectRepository $repository;
    protected IObjectFactory $factory;
    protected IObjectStatusTransition $status_transition;
    protected IRepository $log_repo;
    /**
     * @var mixed[]|\Generator
     */
    protected $dto_objects = [];
    protected IObjectSyncProcessor $processor;

    protected ?IOriginImplementation $implementation = null;
    protected int $delivered_counter = 0;

    protected array $processed_counter = [
        IObject::STATUS_CREATED => 0,
        IObject::STATUS_UPDATED => 0,
        IObject::STATUS_OUTDATED => 0,
        IObject::STATUS_IGNORED => 0,
        IObject::STATUS_FAILED => 0,
    ];

    public function __construct(
        IOrigin $origin,
        IObjectRepository $repository,
        IObjectFactory $factory,
        IObjectStatusTransition $status_transition
    ) {
        $this->origin = $origin;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->status_transition = $status_transition;
        $this->log_repo = LogRepository::getInstance();
    }

    public function execute(Notifier $notifier): void
    {
        global $hub_notifier;
        /** @var Notifier $hub_notifier */
        $hub_notifier = $notifier;

        //        \arObjectCache::$enabled = false;

        // Any exception during the three stages (connect/parse/build hub objects) is forwarded to the global sync
        // as the sync of this origin cannot continue.
        $this->implementation->beforeSync();
        $notifier->reset();

        $notifier->notify('------------------------------------------------');
        $notifier->notify('Start sync of origin ' . $this->origin->getTitle());
        $notifier->notify('------------------------------------------------');

        $notifier->notify('connect');
        if (!$this->implementation->connect()) {
            throw new ConnectionFailedException('could not connect() in origin');
        }

        $notifier->notify('start parsing data');
        $count = $this->implementation->parseData();
        $notifier->notify('end parsing data');

        $this->delivered_counter = $count;

        // Check if the origin aborts its sync if the amount of delivered data is not enough
        if ($this->origin->config()->getCheckAmountData()) {
            $threshold = $this->origin->config()->getCheckAmountDataPercentage();
            $total = $this->repository->count();
            $percentage = ($total > 0 && $count > 0) ? (100 / $total * $count) : 0;
            if ($total > 0 && ($percentage < $threshold)) {
                $msg = "Amount of delivered data not sufficient: Got {$count} datasets,
					which is " . number_format($percentage, 2) . "% of the existing data in hub,
					need at least {$threshold}% according to origin config";
                throw new AbortOriginSyncException($msg);
            }
        }
        $notifier->notify('start building objects');
        $this->dto_objects = $this->implementation->buildObjects();
        $notifier->notify('end building objects');

        $type = $this->origin->getObjectType();

        // Sort dto objects
        if (is_array($this->dto_objects)) { // Only possible for
            $this->dto_objects = $this->sortDtoObjects($this->dto_objects);
        }

        // Start SYNC of delivered objects --> CREATE & UPDATE
        // ======================================================================================================
        // 1. Update current status to an intermediate status so the processor knows if it must CREATE/UPDATE/DELETE
        // 2. Let the processor process the corresponding ILIAS object

        $objects_to_outdated_map = new \SplObjectStorage();
        $ext_ids_delivered = [];
        $notifier->notify('start looping DTOs');
        foreach ($this->dto_objects as $dto) {
            $notifier->notifySometimes('processed DTOs');

            $ext_ids_delivered[] = $dto->getExtId();
            /** @var IObject $object */
            $object = $this->factory->$type($dto->getExtId());

            $object->setDeliveryDate(time());

            if (!$dto->shouldDeleted()) {
                // We merge the existing data with the new data
                $data = array_merge($object->getData(), $dto->getData());
                $dto->setData($data);
                // Set the intermediate status before processing the ILIAS object
                $object->setStatus($this->status_transition->finalToIntermediate($object));
                $this->processObject($object, $dto);
            } else {
                $objects_to_outdated_map->attach($object);
            }
            unset($dto);
        }
        $notifier->notify('end looping DTOs');

        // Start SYNC of objects not being delivered --> DELETE
        // ======================================================================================================
        if (!$this->origin->isAdHoc()) {
            foreach ($this->repository->getToDelete($ext_ids_delivered) as $item) {
                if (!$objects_to_outdated_map->contains($item)) {
                    $objects_to_outdated_map->attach($item);
                }
            }
        } elseif ($this->origin->isAdHoc() && $this->origin->isAdhocParentScope()) {
            $adhoc_parent_ids = $this->implementation->getAdHocParentScopesAsExtIds();
            $objects_in_parent_scope_not_delivered = $this->repository->getToDeleteByParentScope(
                $ext_ids_delivered,
                $adhoc_parent_ids
            );
            foreach ($objects_in_parent_scope_not_delivered as $item) {
                if (!$objects_to_outdated_map->contains($item)) {
                    $objects_to_outdated_map->attach($item);
                }
            }
        }
        $notifier->notify('start processing outdated DTOs');
        foreach ($objects_to_outdated_map as $object) {
            $nullDTO = new NullDTO(
                $object->getExtId()
            ); // There is no DTO available / needed for the deletion process (data has not been delivered)
            $object->setStatus(IObject::STATUS_TO_OUTDATED);
            $this->processObject($object, $nullDTO);
        }
        $notifier->notify('end processing outdated DTOs');

        if (
            ($all_hook = $this->implementation->hookConfig()->hasAllObjectHook())
            || ($no_longer_hook = $this->implementation->hookConfig()->hasNoLongerDeliveredObjectHook())) {
            $all_ext_ids = $this->factory->{$type . 'sExtIds'}();

            if ($all_hook) {
                $notifier->notify('start handle all objects');
                foreach ($all_ext_ids as $all_ext_id) {
                    $notifier->notifySometimes('processing all objects');
                    $hook_object = new HookObject(
                        $object = $this->factory->$type($all_ext_id), new NullDTO($all_ext_id)
                    );
                    $this->implementation->handleAllObjects($hook_object);
                    unset($hook_object);
                    $object->flush();
                }
                $notifier->notify('end handle all objects');
            }

            if ($no_longer_hook) {
                // After that we propose all objects to the origin which are no longer devlivered
                $missing = array_diff($all_ext_ids, $ext_ids_delivered);
                foreach ($missing as $missing_ext_id) {
                    $notifier->notifySometimes('processing missing objects');
                    $hook_object = new HookObject(
                        $object = $this->factory->$type($missing_ext_id),
                        new NullDTO($missing_ext_id)
                    );
                    $this->implementation->handleNoLongerDeliveredObject($hook_object);
                    unset($hook_object);
                    $object->flush();
                }
            }

            unset($all_ext_ids, $ext_ids_delivered, $missing);
        }

        $this->implementation->afterSync();



        $origin = $this->getOrigin();
        $origin->setLastRunToNow();
        $origin->update();
        $notifier->notify('finished');
        $notifier->gc();

        $this->processor->teardown();

        $this->implementation = null; // unset to free memory
    }

    /**
     * @param IDataTransferObject[] $dtos
     * @return IDataTransferObject[]
     */
    protected function sortDtoObjects(array $dtos): array
    {
        // Create IDataTransferObjectSort objects
        $sort_dtos = array_map(
            fn (IDataTransferObject $dto): IDataTransferObjectSort => new DataTransferObjectSort($dto),
            $dtos
        );

        // Request processor to set sort levels
        if ($this->processor->handleSort($sort_dtos)) {
            // Sort by level
            usort(
                $sort_dtos,
                fn (IDataTransferObjectSort $sort_dto1, IDataTransferObjectSort $sort_dto2): int => $sort_dto1->getLevel(
                ) - $sort_dto2->getLevel()
            );

            // Back to IDataTransferObject objects
            $dtos = array_map(
                fn (IDataTransferObjectSort $sort_dto): IDataTransferObject => $sort_dto->getDtoObject(),
                $sort_dtos
            );
        }

        return $dtos;
    }

    public function getTotalByStatus(int $status): ?int
    {
        return $this->processed_counter[$status];
    }

    public function getProcessedTotal(): int
    {
        return (int) array_sum($this->processed_counter);
    }

    public function getDeliveredTotal(): int
    {
        return $this->delivered_counter;
    }

    /**
     * @throws Throwable
     */
    protected function processObject(IObject $object, IDataTransferObject $dto): void
    {
        try {
            $this->processor->process($object, $dto, $this->origin->isUpdateForced());
            $this->incrementProcessed($object->getStatus());
        } catch (AbortSyncException $ex) {
            // Any exceptions aborting the global or current sync are forwarded to global sync
            $object->store();
            unset($object);
            unset($dto);
            throw $ex;
        } catch (AbortOriginSyncOfCurrentTypeException|AbortOriginSyncException $ex) {
            $object->store();
            unset($object);
            unset($dto);
            throw $ex;
        } catch (Throwable $ex) {
            $object->setStatus(IObject::STATUS_FAILED);
            $this->incrementProcessed($object->getStatus());
            $object->store();
            $log = $this->log_repo->factory()->exceptionLog($ex, $this->origin, $object, $dto);
            $this->log_repo->storeLog($log);

            $this->implementation->handleLog($log);
        } finally {
            unset($object);
            unset($dto);
        }
    }

    protected function incrementProcessed(int $status): void
    {
        $this->processed_counter[$status]++;
    }

    public function getOrigin(): IOrigin
    {
        return $this->origin;
    }

    public function setOrigin(IOrigin $origin): void
    {
        $this->origin = $origin;
    }

    public function getRepository(): IObjectRepository
    {
        return $this->repository;
    }

    public function setRepository(IObjectRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function getFactory(): IObjectFactory
    {
        return $this->factory;
    }

    public function setFactory(IObjectFactory $factory): void
    {
        $this->factory = $factory;
    }

    public function getProcessor(): IObjectSyncProcessor
    {
        return $this->processor;
    }

    public function setProcessor(IObjectSyncProcessor $processor): void
    {
        $this->processor = $processor;
    }

    /**
     * @deprecated
     */
    public function getStatusTransition(): IObjectStatusTransition
    {
        return $this->status_transition;
    }

    /**
     * @deprecated
     */
    public function setStatusTransition(IObjectStatusTransition $status_transition): void
    {
        $this->status_transition = $status_transition;
    }

    public function getImplementation(): IOriginImplementation
    {
        return $this->implementation;
    }

    public function setImplementation(IOriginImplementation $implementation): void
    {
        $this->implementation = $implementation;
    }
}
