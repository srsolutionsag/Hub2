<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;
use ilObject;
use ilObjOrgUnit;
use ilObjUser;
use ilRbacLog;
use ilSkillProfile;
use ilSkillTreeNode;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Exception\ILIASObjectNotFoundException;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use Throwable;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class ObjectProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ObjectSyncProcessor implements IObjectSyncProcessor
{
    use Helper;

    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var \srag\Plugins\Hub2\Log\IRepository
     */
    protected $log_repo;

    /**
     * @var IOrigin
     */
    protected $origin;
    /**
     * @var IObjectStatusTransition
     */
    protected $transition;
    /**
     * @var IOriginImplementation
     */
    protected $implementation;
    /**
     * @var ilObject|FakeIliasObject|null
     */
    protected $current_ilias_object;
    /**
     * @var \ilRbacReview
     */
    private $rbacreview;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->rbacreview = $DIC['rbacreview'];
        $this->origin = $origin;
        $this->transition = $transition;
        $this->implementation = $implementation;
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * @inheritdoc
     */
    final public function process(IObject $hub_object, IDataTransferObject $dto, bool $force = false)
    {
        // The HookObject is filled with the object (known Data in HUB) and the DTO delivered with
        // your origin. Additionally, if available, the HookObject is filled with the given
        // ILIAS-Object, too.
        $hook_object = new HookObject($hub_object, $dto);

        // We pass the HookObject to the OriginImplementaion which could override the status
        $this->implementation->overrideStatus($hook_object);

        // We keep the old data if the object is getting deleted, as there is no "real" DTO available, because
        // the data has not been delivered...

        // We check if there is another mapping strategy than "None" and check for existing objects in ILIAS
        if ($hub_object->getStatus(
            ) === IObject::STATUS_TO_CREATE && $dto instanceof IMappingStrategyAwareDataTransferObject) {
            $m = $dto->getMappingStrategy();
            $ilias_id = $m->map($dto);
            if ($ilias_id > 0) {
                $hub_object->setStatus(IObject::STATUS_TO_UPDATE);
                $hub_object->setILIASId($ilias_id);
                $this->log_repo->factory()->originLog(
                    (new OriginFactory())->getById($this->origin->getId()),
                    $hub_object
                )->write(
                    "Existing object found by Mapping Strategy"
                );
            } elseif ($ilias_id < 0) {
                throw new HubException("Mapping strategy " . get_class($m) . " returns negative value");
            }
            $hub_object->store();
        }

        $time = time();

        $this->current_ilias_object = null;

        switch ($hub_object->getStatus()) {
            case IObject::STATUS_TO_CREATE:
                $this->implementation->beforeCreateILIASObject($hook_object);

                try {
                    $this->handleCreate($dto);
                } catch (Throwable $ex) {
                    // Store new possible ilias id on exception
                    $hub_object->setILIASId($this->getILIASId($this->current_ilias_object));

                    throw $ex;
                }

                if ($this instanceof IMetadataSyncProcessor && $hub_object instanceof IMetadataAwareObject && $dto instanceof IMetadataAwareDataTransferObject) {
                    $this->handleMetadata($dto, $hub_object, $this->current_ilias_object);
                }

                if ($this instanceof ITaxonomySyncProcessor && $hub_object instanceof ITaxonomyAwareObject && $dto instanceof ITaxonomyAwareDataTransferObject) {
                    $this->handleTaxonomies($dto, $hub_object, $this->current_ilias_object);
                }

                if ($this instanceof IDidacticTemplateSyncProcessor && $dto instanceof IDidacticTemplateAwareDataTransferObject) {
                    $this->handleDidacticTemplate($dto, $this->current_ilias_object);
                }

                $hub_object->setILIASId($this->getILIASId($this->current_ilias_object));

                $this->implementation->afterCreateILIASObject(
                    $hook_object->withILIASObject($this->current_ilias_object)
                );

                $hub_object->setStatus(IObject::STATUS_CREATED);
                $hub_object->setProcessedDate($time);
                break;

            case IObject::STATUS_TO_UPDATE:
            case IObject::STATUS_TO_RESTORE:
                // Updating the ILIAS object is only needed if some properties were changed
                if (($dto->computeHashCode() !== $hub_object->getHashCode()) || $force || $hub_object->getStatus(
                    ) === iObject::STATUS_TO_RESTORE) {
                    $this->implementation->beforeUpdateILIASObject($hook_object);

                    try {
                        $this->handleUpdate($dto, $hub_object->getILIASId());
                    } catch (Throwable $ex) {
                        // Store new possible ilias id on exception
                        $hub_object->setILIASId($this->getILIASId($this->current_ilias_object));

                        throw $ex;
                    }

                    if ($this->current_ilias_object === null) {
                        throw new ILIASObjectNotFoundException($hub_object);
                    }

                    if ($this instanceof IMetadataSyncProcessor && $hub_object instanceof IMetadataAwareObject && $dto instanceof IMetadataAwareDataTransferObject) {
                        $this->handleMetadata($dto, $hub_object, $this->current_ilias_object);
                    }

                    if ($this instanceof ITaxonomySyncProcessor && $hub_object instanceof ITaxonomyAwareObject && $dto instanceof ITaxonomyAwareDataTransferObject) {
                        $this->handleTaxonomies($dto, $hub_object, $this->current_ilias_object);
                    }

                    if ($this instanceof IDidacticTemplateSyncProcessor && $dto instanceof IDidacticTemplateAwareDataTransferObject) {
                        $this->handleDidacticTemplate($dto, $this->current_ilias_object);
                    }

                    $hub_object->setILIASId($this->getILIASId($this->current_ilias_object));

                    $this->implementation->afterUpdateILIASObject(
                        $hook_object->withILIASObject($this->current_ilias_object)
                    );

                    $hub_object->setStatus(IObject::STATUS_UPDATED);
                    $hub_object->setProcessedDate($time);
                } else {
                    $hub_object->setStatus(IObject::STATUS_IGNORED);
                }
                break;

            case IObject::STATUS_TO_OUTDATED:
                $this->implementation->beforeDeleteILIASObject($hook_object);

                $this->handleDelete($dto, $hub_object->getILIASId());

                if ($this->current_ilias_object === null) {
                    throw new ILIASObjectNotFoundException($hub_object);
                }

                $this->implementation->afterDeleteILIASObject(
                    $hook_object->withILIASObject($this->current_ilias_object)
                );

                $hub_object->setStatus(IObject::STATUS_OUTDATED);
                $hub_object->setProcessedDate($time);
                break;

            case IObject::STATUS_IGNORED:
            case IObject::STATUS_FAILED:
                // Nothing to do here, object is ignored
                break;

            default:
                throw new HubException(
                    "Unrecognized intermediate status '{$hub_object->getStatus()}' while processing {$hub_object}"
                );
        }

        if ($hub_object->getStatus() !== IObject::STATUS_TO_OUTDATED) {
            $hub_object->setData($dto->getData());
            if ($dto instanceof IMetadataAwareDataTransferObject
                && $hub_object instanceof IMetadataAwareObject
            ) {
                $hub_object->setMetaData($dto->getMetaData());
            }
            if ($dto instanceof ITaxonomyAwareDataTransferObject
                && $hub_object instanceof ITaxonomyAwareObject
            ) {
                $hub_object->setTaxonomies($dto->getTaxonomies());
            }
        }

        $hub_object->store();
    }

    /**
     * @param ilObject|FakeIliasObject|null $object
     * @return int|null
     */
    protected function getILIASId($object)
    {
        if ($object === null) {
            return null;
        }

        if ($object instanceof ilObjUser || $object instanceof ilObjOrgUnit || $object instanceof ilSkillTreeNode || $object instanceof ilSkillProfile
            || $object instanceof FakeIliasObject
            || $object instanceof FakeIliasMembershipObject
        ) {
            return $object->getId();
        }

        return $object->getRefId();
    }

    /**
     * The import ID is set on the ILIAS object.
     * @return string
     */
    protected function getImportId(IDataTransferObject $object)
    {
        return self::IMPORT_PREFIX . $this->origin->getId() . '_' . $object->getExtId();
    }

    /**
     * use this every time a new repository object is created
     */
    protected function writeRBACLog(int $ref_id)/*: void*/
    {
        // rbac log
        $rbac_log_roles = $this->rbacreview->getParentRoleIds($ref_id, false);
        $rbac_log = ilRbacLog::gatherFaPa($ref_id, array_keys($rbac_log_roles), true);
        ilRbacLog::add(ilRbacLog::CREATE_OBJECT, $ref_id, $rbac_log);
    }

    /**
     * @inheritdoc
     */
    public function handleSort(array $sort_dtos) : bool
    {
        return false;
    }

    /**
     * Create a new ILIAS object from the given data transfer object.
     * @return void
     * @throws HubException
     */
    abstract protected function handleCreate(IDataTransferObject $dto)/*: void*/
    ;

    /**
     * Update the corresponding ILIAS object.
     * Return the processed ILIAS object or null if the object was not found, e.g. it is deleted in
     * ILIAS.
     * @param int $iliasId
     * @return void
     * @throws HubException
     */
    abstract protected function handleUpdate(IDataTransferObject $dto, $iliasId)/*: void*/
    ;

    /**
     * Delete the corresponding ILIAS object.
     * Return the deleted ILIAS object or null if the object was not found in ILIAS.
     * @param int $ilias_id
     * @return void
     * @throws HubException
     */
    abstract protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    ;
}
