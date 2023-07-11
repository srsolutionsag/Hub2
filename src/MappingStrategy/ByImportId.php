<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilDBConstants;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\ICategoryDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagementDTO;
use srag\Plugins\Hub2\Object\Course\ICourseDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\Group\IGroupDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\Session\ISessionDTO;
use srag\Plugins\Hub2\Object\User\IUserDTO;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;

/**
 * Class ByImportId
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ByImportId extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @var \ilDBInterface
     */
    private $db;

    public function __construct()
    {
        global $DIC;
        $this->db = $DIC->database();
    }

    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto) : int
    {
        switch (true) {
            case $dto instanceof IUserDTO:
                $object_type = "usr";
                break;

            case $dto instanceof ICourseDTO:
                $object_type = "crs";
                break;

            case $dto instanceof ICategoryDTO:
                $object_type = "cat";
                break;

            case $dto instanceof IGroupDTO:
                $object_type = "grp";
                break;

            case $dto instanceof ISessionDTO:
                $object_type = "sess";
                break;

            case $dto instanceof IOrgUnitDTO:
                $object_type = "orgu";
                break;

            case $dto instanceof ICompetenceManagementDTO:
                $object_type = "skmg";
                break;

            default:
                throw new HubException(
                    "Cannot find import id for type=" . get_class($dto) . ",ext_id=" . $dto->getExtId() . "!"
                );
        }

        if ($dto instanceof ICompetenceManagementDTO) {
            $result = $this->db->queryF(
                'SELECT obj_id FROM skl_tree_node WHERE ' . $this->db
                    ->like(
                        "import_id",
                        ilDBConstants::T_TEXT,
                        IObjectSyncProcessor::IMPORT_PREFIX . "%%_" . $dto->getExtId()
                    ),
                [],
                []
            );
        } else {
            $result = $this->db->queryF(
                'SELECT obj_id FROM object_data WHERE type=%s AND ' . $this->db
                    ->like(
                        "import_id",
                        ilDBConstants::T_TEXT,
                        IObjectSyncProcessor::IMPORT_PREFIX . "%%_" . $dto->getExtId()
                    ),
                [ilDBConstants::T_TEXT],
                [$object_type]
            );
        }

        if ($result->rowCount() > 0) {
            if ($result->rowCount() > 1) {
                throw new HubException(
                    "Multiple import id's for type=" . $object_type . ",ext_id=" . $dto->getExtId() . " found!"
                );
            }

            return (int) $result->fetchAssoc()["obj_id"];
        }

        return 0;
    }
}
