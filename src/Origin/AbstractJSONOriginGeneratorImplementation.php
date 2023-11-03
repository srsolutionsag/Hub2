<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Parser\Json;

/**
 * Class AbstractJSONOriginGeneratorImplementation
 */
abstract class AbstractJSONOriginGeneratorImplementation extends AbstractOriginGeneratorImplementation
{
    /**
     * @var Json
     */
    protected $json_parser;
    protected $file_path = '';
    /**
     * @var array
     */
    protected $json = [];

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
        $this->json_parser = new Json(
            $this->file_path,
            $this->getUniqueField(),
            $this->getMandatoryColumns(),
            $this->getStringSanitizer()
        );

        foreach ($this->getFilters() as $filter) {
            $this->json_parser->addFilter($filter);
        }

        $this->json = $this->json_parser->parseData();
        return count($this->json);
    }

    abstract protected function getMandatoryColumns() : array;

    abstract protected function getUniqueField() : ?string;

    protected function getStringSanitizer() : \Closure
    {
        return static function (string $string) : string {
            return utf8_encode(utf8_decode($string));
        };
    }

    /**
     * @return IDataTransferObject[]|\Generator
     */
    abstract protected function buildObjectsFromJSON(array $json_data) : \Generator;

    /**
     * @return IDataTransferObject[]
     */
    public function buildObjects() : \Generator
    {
        yield from $this->buildObjectsFromJSON($this->json);
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
