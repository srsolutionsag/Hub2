<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use ilDBConstants;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Category\ICategoryDTO;
use srag\Plugins\Hub2\Object\CompetenceManagement\ARCompetenceManagement;
use srag\Plugins\Hub2\Object\CompetenceManagement\ICompetenceManagementDTO;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\Course\ICourseDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\Group\IGroupDTO;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\Session\ISessionDTO;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Object\User\IUserDTO;

/**
 * Class ByExtId
 * Used to map new records from one origin to existing records of other origins
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ByExtId extends AMappingStrategy implements IMappingStrategy
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
     * @inheritDoc
     */
    public function map(IDataTransferObject $dto) : int
    {
        switch (true) {
            case $dto instanceof IUserDTO:
                $table_name = ARUser::TABLE_NAME;
                break;

            case $dto instanceof ICourseDTO:
                $table_name = ARCourse::TABLE_NAME;
                break;

            case $dto instanceof ICategoryDTO:
                $table_name = ARCategory::TABLE_NAME;
                break;

            case $dto instanceof IGroupDTO:
                $table_name = ARGroup::TABLE_NAME;
                break;

            case $dto instanceof ISessionDTO:
                $table_name = ARSession::TABLE_NAME;
                break;

            case $dto instanceof IOrgUnitDTO:
                $table_name = AROrgUnit::TABLE_NAME;
                break;

            case $dto instanceof ICompetenceManagementDTO:
                $table_name = ARCompetenceManagement::TABLE_NAME;
                break;

            default:
                throw new HubException(
                    "Cannot find ILIAS id for type=" . get_class($dto) . ",ext_id=" . $dto->getExtId() . "!"
                );
        }

        $result = $this->db->queryF(
            'SELECT DISTINCT ilias_id FROM ' . $table_name . ' WHERE ext_id=%s',
            [ilDBConstants::T_TEXT],
            [$dto->getExtId()]
        );

        if ($result->rowCount() > 0) {
            if ($result->rowCount() > 1) {
                throw new HubException(
                    "Multiple ILIAS id's for type=" . $table_name . ",ext_id=" . $dto->getExtId() . " found!"
                );
            }

            return (int) $result->fetchAssoc()["ilias_id"];
        }

        return 0;
    }
}
