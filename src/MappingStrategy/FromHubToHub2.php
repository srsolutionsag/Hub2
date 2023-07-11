<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class FromHubToHub2
 * Used to map new records from one origin in Hub1
 */
class FromHubToHub2 extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @var bool
     */
    protected $hub1_table_exists = false;
    /**
     * @var \ilDBInterface
     */
    protected $database;
    /**
     * @var int
     */
    protected $former_origin_id;

    public function __construct(\ilDBInterface $database, int $former_origin_id)
    {
        $this->database = $database;
        $this->former_origin_id = $former_origin_id;
        $this->hub1_table_exists = (bool) $this->database->tableExists('sr_hub_sync_history');
    }

    public function map(IDataTransferObject $dto) : int
    {
        if (!$this->hub1_table_exists) {
            return 0;
        }
        $q = "SELECT ilias_id, ext_id FROM sr_hub_sync_history WHERE ext_id = %s AND sr_hub_origin_id = %s";
        $r = $this->database->queryF(
            $q,
            ['text', 'integer'],
            [(string) $dto->getExtId(), $this->former_origin_id]
        );
        $d = $r->fetchObject();
        if (isset($d->ilias_id)) {
            return (int) $d->ilias_id;
        }
        return 0;
    }
}
