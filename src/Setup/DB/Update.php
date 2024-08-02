<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Setup\DB;

use ILIAS\Setup\UnachievableException;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Update implements \ilDatabaseUpdateSteps
{
    private ?\ilDBInterface $db = null;
    private ?\ilDBManager $manager = null;

    public function prepare(\ilDBInterface $db): void
    {
        $this->db = $db;
        $this->manager = $this->db->loadModule(\ilDBConstants::MODULE_MANAGER);
    }

    public function step_1(): void
    {
        // try to remove all indices and add them again
        try {
            foreach ($this->manager->listTableIndexes('sr_hub2_log') as $idx_name) {
                try {
                    $this->db->dropIndex('sr_hub2_log', $idx_name);
                } catch (\Throwable $ex) {
                    $ex = $ex;
                }
            }
        } catch (\Throwable $ex) {
            $ex = $ex;
        }
        /*try {
            $this->db->manipulate('CHECK TABLE sr_hub2_log;');
            $this->db->manipulate('OPRIMIZE TABLE sr_hub2_log;');
        } catch (\Throwable $t) {
            throw new UnachievableException(
                'cannot optimize table sr_hub2_log, maybe theres not enough space left on the server?'
            );
        }*/

        $fields = [
            'status',
            'origin_id',
            'object_ext_id',
            'date',
            ['origin_id', 'object_ext_id', 'status']
        ];

        foreach ($fields as $k => $field) {
            if (!is_array($field)) {
                $field = [$field];
            }
            // add several indices to the log table
            if ($this->db->indexExistsByFields('sr_hub2_log', $field)) {
                try {
                    $this->db->dropIndexByFields('sr_hub2_log', $field);
                } catch (\Throwable $ex) {
                    $ex = $ex;
                }
            }
            try {
                $this->db->addIndex('sr_hub2_log', $field, 'i' . ($k + 1));
            } catch (\Throwable $ex) {
                $ex = $ex;
            }
        }
    }

}
