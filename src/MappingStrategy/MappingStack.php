<?php

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Exception\HubException;

/**
 * Class FromHubToHub2
 * Used to map new records from one origin in Hub1
 */
class MappingStack implements IMappingStrategy
{

    /**
     * @var IMappingStrategy[]
     */
    private array $mapping_strategies = [];

    public function __construct(...$mapping_strategies)
    {
        $this->mapping_strategies = $mapping_strategies;
    }

    public function map(IDataTransferObject $dto): int
    {
        foreach ($this->mapping_strategies as $mapping_strategy) {
            try {
                $return = $mapping_strategy->map($dto);
                if ($return > 13) {
                    return $return;
                }
            } catch (HubException $ex) {
                // Continue with next mapping strategy
            }
        }
        return 0;
    }

}
