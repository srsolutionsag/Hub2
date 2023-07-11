<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Parser\Csv;

/**
 * Class AbstractCSVOriginGeneratorImplementation
 */
abstract class AbstractCSVOriginGeneratorImplementation extends AbstractOriginGeneratorImplementation
{
    /**
     * @var Csv
     */
    protected $csv_parser;
    protected $file_path = '';
    /**
     * @var array
     */
    protected $csv = [];

    protected function getEnclosure() : string
    {
        return '"';
    }

    protected function getSeparator() : string
    {
        return ";";
    }

    public function connect() : bool
    {
        $this->file_path = $this->config()->getPath();
        if (!is_readable($this->file_path)) {
            throw new ConnectionFailedException("Cannot parse file {$this->file_path}");
        }
        return true;
    }

    public function parseData() : int
    {
        $this->csv_parser = new Csv(
            $this->file_path,
            $this->getUniqueField(),
            $this->getMandatoryColumns(),
            $this->getColumnMapping(),
            $this->getEnclosure(),
            $this->getSeparator()
        );

        foreach ($this->getFilters() as $filter) {
            $this->csv_parser->addFilter($filter);
        }

        $this->csv = $this->csv_parser->parseData();
        return count($this->csv);
    }

    abstract protected function getMandatoryColumns() : array;

    protected function getColumnMapping() : array
    {
        return [];
    }

    abstract protected function getUniqueField() : string;

    /**
     * @return IDataTransferObject[]|\Generator
     */
    abstract protected function buildObjectsFromCSV(array $csv_data) : \Generator;

    /**
     * @return IDataTransferObject[]
     */
    public function buildObjects() : \Generator
    {
        yield from $this->buildObjectsFromCSV($this->csv);
    }

    protected function getFilter() : \Closure
    {
        return static function (array $item) : bool {
            return true;
        };
    }

    protected function getFilters() : array
    {
        return [
            $this->getFilter()
        ];
    }

    public function handleLog(ILog $log) : void
    {
        // TODO: Implement handleLog() method.
    }

    public function beforeCreateILIASObject(HookObject $hook) : void
    {
        // TODO: Implement beforeCreateILIASObject() method.
    }

    public function afterCreateILIASObject(HookObject $hook) : void
    {
        // TODO: Implement afterCreateILIASObject() method.
    }

    public function beforeUpdateILIASObject(HookObject $hook) : void
    {
        // TODO: Implement beforeUpdateILIASObject() method.
    }

    public function afterUpdateILIASObject(HookObject $hook) : void
    {
        // TODO: Implement afterUpdateILIASObject() method.
    }

    public function beforeDeleteILIASObject(HookObject $hook) : void
    {
        // TODO: Implement beforeDeleteILIASObject() method.
    }

    public function afterDeleteILIASObject(HookObject $hook) : void
    {
        // TODO: Implement afterDeleteILIASObject() method.
    }

    public function beforeSync() : void
    {
        // TODO: Implement beforeSync() method.
    }

    public function afterSync() : void
    {
        // TODO: Implement afterSync() method.
    }
}
