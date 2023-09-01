<?php

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;
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
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const NOTIFY_ALL_X_DTOS = 500;
    /**
     * @var \srag\Plugins\Hub2\Log\IRepository
     */
    protected $log_repo;
    /**
     * @var IOrigin
     */
    protected $origin;
    /**
     * @var IObjectRepository
     */
    protected $repository;
    /**
     * @var IObjectFactory
     */
    protected $factory;
    /**
     * @var IDataTransferObject[]|\Generator
     */
    protected $dtoObjects = [];
    /**
     * @var IObjectSyncProcessor
     */
    protected $processor;
    /**
     * @var IObjectStatusTransition
     * @deprecated
     */
    protected $statusTransition;
    /**
     * @var IOriginImplementation
     */
    protected $implementation;
    /**
     * @var int
     */
    protected $countDelivered = 0;
    /**
     * @var array
     */
    protected $countProcessed
        = [
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
        IObjectStatusTransition $transition
    ) {
        $this->origin = $origin;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->statusTransition = $transition;
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function execute(Notifier $notifier)
    {
        // Any exception during the three stages (connect/parse/build hub objects) is forwarded to the global sync
        // as the sync of this origin cannot continue.
        $this->implementation->beforeSync();
        $notifier->reset();
        $notifier->notify('connect');
        if (!$this->implementation->connect()) {
            throw new ConnectionFailedException('could not connect() in origin');
        }

        $notifier->notify('start parsing data');
        $count = $this->implementation->parseData();
        $notifier->notify('end parsing data');

        $this->countDelivered = $count;

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
        $this->dtoObjects = $this->implementation->buildObjects();
        $notifier->notify('end building objects');

        $type = $this->origin->getObjectType();

        // Sort dto objects
        if (is_array($this->dtoObjects)) { // Only possible for
            $this->dtoObjects = $this->sortDtoObjects($this->dtoObjects);
        }

        // Start SYNC of delivered objects --> CREATE & UPDATE
        // ======================================================================================================
        // 1. Update current status to an intermediate status so the processor knows if it must CREATE/UPDATE/DELETE
        // 2. Let the processor process the corresponding ILIAS object

        $objects_to_outdated_map = new \SplObjectStorage();
        $ext_ids_delivered = [];
        $notifier->notify('start looping DTOs');
        foreach ($this->dtoObjects as $dto) {
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
                $object->setStatus($this->statusTransition->finalToIntermediate($object));
                $this->processObject($object, $dto);
            } else {
                $objects_to_outdated_map->attach($object);
            }
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

        $all_ext_ids = $this->factory->{$type . 'sExtIds'}();
        if ($this->implementation->hookConfig()->hasAllObjectHook()) {
            $notifier->notify('start handle all objects');
            foreach ($all_ext_ids as $all_ext_id) {
                $hook_object = new HookObject($object = $this->factory->$type($all_ext_id), new NullDTO($all_ext_id));
                $this->implementation->handleAllObjects($hook_object);
            }
            $notifier->notify('end handle all objects');
        }

        // After that we propose all objects to the origin which are no longer devlivered
        $missing = array_diff($all_ext_ids, $ext_ids_delivered);
        foreach ($missing as $missing_ext_id) {
            $hook_object = new HookObject(
                $object = $this->factory->$type($missing_ext_id),
                new NullDTO($missing_ext_id)
            );
            $this->implementation->handleNoLongerDeliveredObject($hook_object);
        }

        $this->implementation->afterSync();
        
        $origin = $this->getOrigin();
        $origin->setLastRunToNow();
        $origin->update();
        $notifier->notify('finished');
    }

    /**
     * @param IDataTransferObject[] $dtos
     * @return IDataTransferObject[]
     */
    protected function sortDtoObjects(array $dtos) : array
    {
        // Create IDataTransferObjectSort objects
        $sort_dtos = array_map(
            function (IDataTransferObject $dto) : IDataTransferObjectSort {
                return new DataTransferObjectSort($dto);
            },
            $dtos
        );

        // Request processor to set sort levels
        if ($this->processor->handleSort($sort_dtos)) {
            // Sort by level
            usort(
                $sort_dtos,
                function (IDataTransferObjectSort $sort_dto1, IDataTransferObjectSort $sort_dto2) : int {
                    return ($sort_dto1->getLevel() - $sort_dto2->getLevel());
                }
            );

            // Back to IDataTransferObject objects
            $dtos = array_map(
                function (IDataTransferObjectSort $sort_dto) : IDataTransferObject {
                    return $sort_dto->getDtoObject();
                },
                $sort_dtos
            );
        }

        return $dtos;
    }

    /**
     * @inheritdoc
     */
    public function getCountProcessedByStatus($status)
    {
        return $this->countProcessed[$status];
    }

    /**
     * @inheritdoc
     */
    public function getCountProcessedTotal()
    {
        return array_sum($this->countProcessed);
    }

    /**
     * @inheritdoc
     */
    public function getCountDelivered()
    {
        return $this->countDelivered;
    }

    /**
     * @throws Throwable
     */
    protected function processObject(IObject $object, IDataTransferObject $dto)
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

    protected function incrementProcessed(int $status)
    {
        $this->countProcessed[$status]++;
    }

    /**
     * @inheritdoc
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin(IOrigin $origin) : void
    {
        $this->origin = $origin;
    }

    public function getRepository() : IObjectRepository
    {
        return $this->repository;
    }

    public function setRepository(IObjectRepository $repository) : void
    {
        $this->repository = $repository;
    }

    public function getFactory() : IObjectFactory
    {
        return $this->factory;
    }

    public function setFactory(IObjectFactory $factory) : void
    {
        $this->factory = $factory;
    }

    public function getProcessor() : IObjectSyncProcessor
    {
        return $this->processor;
    }

    public function setProcessor(IObjectSyncProcessor $processor) : void
    {
        $this->processor = $processor;
    }

    /**
     * @deprecated
     */
    public function getStatusTransition() : IObjectStatusTransition
    {
        return $this->statusTransition;
    }

    /**
     * @deprecated
     */
    public function setStatusTransition(IObjectStatusTransition $statusTransition) : void
    {
        $this->statusTransition = $statusTransition;
    }

    public function getImplementation() : IOriginImplementation
    {
        return $this->implementation;
    }

    public function setImplementation(IOriginImplementation $implementation) : void
    {
        $this->implementation = $implementation;
    }
}
