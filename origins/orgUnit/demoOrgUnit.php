<?php

namespace srag\Plugins\Hub2\Origin;

use ilCSVReader;
use srag\Plugins\Hub2\Exception\BuildObjectsFailedException;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use stdClass;

/**
 * Class demoOrgUnit
 *
 * @package srag\Plugins\Hub2\Origin
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class demoOrgUnit extends AbstractOriginImplementation
{
    /**
     * Connect to the service providing the sync data.
     * Throw a ConnectionFailedException to abort the sync if a connection is not possible.
     *
     * @throws ConnectionFailedException
     * @return bool
     */
    public function connect(): bool
    {
        $csv_file = $this->config()->getPath();

        if ($this->config()->getConnectionType() != IOriginConfig::CONNECTION_TYPE_PATH || !file_exists($csv_file)) {
            // CSV file not found!
            throw new ConnectionFailedException("The csv file $csv_file does not exists!");
        }

        return true;
    }


    /**
     * Parse and prepare (sanitize/validate) the data to fill the DTO objects.
     * Return the number of data. Note that this number is used to check if the amount of delivered
     * data is sufficent to continue the sync, depending on the configuration of the origin.
     *
     * Throw a ParseDataFailedException to abort the sync if your data cannot be parsed.
     *
     * @throws ParseDataFailedException
     * @return int
     */
    public function parseData(): int
    {
        // Parse csv file
        $csv_file = $this->config()->getPath();

        $csv = new ilCSVReader();
        $csv->setSeparator(",");
        $csv->open($csv_file);
        $rows = $csv->getDataArrayFromCSVFile();
        $csv->close();

        // Map columns
        $columns_map = [
            "Titel Organisationseinheit" => "title",
            "Externe ID" => "ext_id",
            "Parent ID" => "parent_id",
            "Org Type" => "org_unit_type"
        ];
        $columns = array_map(function (string $column) use (&$columns_map): string {
            if (isset($columns_map[$column])) {
                return $columns_map[$column];
            } else {
                // Optimal column
                return "";
            }
        }, array_shift($rows));
        foreach ($columns_map as $key => $value) {
            if (!in_array($value, $columns)) {
                // Column missing!
                throw new ParseDataFailedException("Column <b>$key ($value)</b> does not exists in <b>{$csv_file}</b>!");
            }
        }

        // Get data
        foreach ($rows as $rowId => $row) {
            if ($row === [ 0 => "" ]) {
                continue; // Skip empty rows
            }

            $data = new stdClass();

            foreach ($row as $cellI => $cell) {
                if (!isset($columns[$cellI])) {
                    // Column missing!
                    throw new ParseDataFailedException("<b>Row $rowId, column $cellI</b> does not exists in <b>{$csv_file}</b>!");
                }

                if ($columns[$cellI] != "") { // Skip optimal columns
                    $data->{$columns[$cellI]} = $cell;
                }
            }

            $this->data[] = $data;
        }

        return count($this->data);
    }


    /**
     * Build the hub DTO objects from the parsed data.
     * An instance of such objects MUST be obtained over the DTOObjectFactory. The factory
     * is available via $this->factory().
     *
     * Example for an origin syncing users:
     *
     * $user = $this->factory()->user($data->extId) {   }
     * $user->setFirstname($data->firstname)
     *  ->setLastname($data->lastname)
     *  ->setGender(UserDTO::GENDER_FEMALE) {   }
     *
     * Throw a BuildObjectsFailedException to abort the sync at this stage.
     *
     * @throws BuildObjectsFailedException
     * @return IDataTransferObject[]
     */
    public function buildObjects(): array
    {
        return array_map(function (stdClass $data): IOrgUnitDTO {
            $org_unit = $this->factory()->orgUnit($data->ext_id);

            $org_unit->setTitle($data->title);

            $org_unit->setParentId(intval($data->parent_id));
            $org_unit->setParentIdType(IOrgUnitDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID);

            $org_unit->setOrgUnitType($data->org_unit_type);

            return $org_unit;
        }, $this->data);
    }


    // HOOKS
    // ------------------------------------------------------------------------------------------------------------

    /**
     * Called if any exception occurs during processing the ILIAS objects. This hook can be used to
     * influence the further processing of the current origin sync or the global sync:
     *
     * - Throw an AbortOriginSyncException to stop the current sync of this origin.
     *   Any other following origins in the processing chain are still getting executed normally.
     * - Throw an AbortOriginSyncOfCurrentTypeException to abort the current sync of the origin AND
     *   all also skip following syncs from origins of the same object type, e.g. User, Course etc.
     * - Throw an AbortSyncException to stop the global sync. The sync of any other following
     * origins in the processing chain is NOT getting executed.
     *
     * Note that if you do not throw any of the exceptions above, the sync will continue.
     *
     * @param ILog $log
     */
    public function handleLog(ILog $log)
    {
    }


    /**
     * @param HookObject $hook
     */
    public function beforeCreateILIASObject(HookObject $hook)
    {
    }


    /**
     * @param HookObject $hook
     */
    public function afterCreateILIASObject(HookObject $hook)
    {
    }


    /**
     * @param HookObject $hook
     */
    public function beforeUpdateILIASObject(HookObject $hook)
    {
    }


    /**
     * @param HookObject $hook
     */
    public function afterUpdateILIASObject(HookObject $hook)
    {
    }


    /**
     * @param HookObject $hook
     */
    public function beforeDeleteILIASObject(HookObject $hook)
    {
    }


    /**
     * @param HookObject $hook
     */
    public function afterDeleteILIASObject(HookObject $hook)
    {
    }


    /**
     * Executed before the synchronization of the origin is executed.
     */
    public function beforeSync()
    {
    }


    /**
     * Executed after the synchronization of the origin has been executed.
     */
    public function afterSync()
    {
    }
}
