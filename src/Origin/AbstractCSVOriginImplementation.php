<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyFactory;
use srag\Plugins\Hub2\Metadata\IMetadataFactory;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Taxonomy\ITaxonomyFactory;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use srag\Plugins\Hub2\Exception\BuildObjectsFailedException;
use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;

/**
 * Class AbstractCSVOriginImplementation
 */
abstract class AbstractCSVOriginImplementation extends AbstractOriginImplementation
{
    protected $file_path = '';
    
    protected $csv = [];
    
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
        $csv = array_map(function (string $line) : array {
            return str_getcsv($line, ";");
        }, file($this->file_path));
        
        $delivered_columns = array_shift($csv);
        $mandatory_columns = $this->getMandatoryColumns();
        
        if (!is_array($delivered_columns) || count(array_diff($mandatory_columns, $delivered_columns)) > 0) {
            throw new ParseDataFailedException(
                "Not all mandatory colums in csv available. received '" . implode(
                    "|",
                    (array) $delivered_columns
                ) . "', expected '" . implode(
                    "|",
                    $mandatory_columns
                ) . "' in {$this->file_path}"
            );
        }
        
        array_walk($csv, function (array &$item) use ($delivered_columns) {
            foreach ($item as $k => $v) {
                unset($item[$k]);
                $item[$delivered_columns[$k]] = $this->sanitize($v);
            }
        });
        
        $csv = array_filter($csv, function (array $item) : bool {
            $isset = true;
            foreach ($this->getMandatoryColumns() as $column) {
                $isset = $isset && isset($item[$column]) && $item[$column] !== '';
            }
            return $isset;
        });
        
        $csv = array_filter($csv, $this->getFilter());
        
        if ($this->getUniqueField() !== '') {
            $field = $this->getUniqueField();
            $unique = [];
            $csv = array_filter($csv, static function (array $item) use (&$unique, $field) : bool {
                $isset = isset($unique[$item[$field]]);
                $unique[$item[$field]] = true;
                return !$isset;
            });
        }
    
        if (count($this->getColumnMapping()) > 0) {
            $mapping = $this->getColumnMapping();
            $csv = array_map(function (array $item) use ($mapping) : array {
                foreach ($mapping as $old_key => $new_key) {
                    if (isset($item[$old_key])) {
                        $item[$new_key] = $item[$old_key];
                        unset($item[$old_key]);
                    }
                }
                return $item;
            }, $csv);
        }
        
        $this->csv = $csv;
        
        return count($this->csv);
    }
    
    protected function sanitize(string $s) : string
    {
        return utf8_encode(utf8_decode($s));
    }
    
    abstract protected function getMandatoryColumns() : array;
    
    protected function getColumnMapping() : array
    {
        return [];
    }
    
    abstract protected function getUniqueField() : string;
    
    /**
     * @param array $csv_data
     * @return IDataTransferObject[]
     */
    abstract protected function buildObjectsFromCSV(array $csv_data);
    
    /**
     * @return IDataTransferObject[]
     */
    public function buildObjects()
    {
        return $this->buildObjectsFromCSV($this->csv);
    }
    
    protected function getFilter() : \Closure
    {
        return static function (array $item) : bool {
            return true;
        };
    }
    
    public function handleLog(ILog $log)
    {
        // TODO: Implement handleLog() method.
    }
    
    public function beforeCreateILIASObject(HookObject $hook)
    {
        // TODO: Implement beforeCreateILIASObject() method.
    }
    
    public function afterCreateILIASObject(HookObject $hook)
    {
        // TODO: Implement afterCreateILIASObject() method.
    }
    
    public function beforeUpdateILIASObject(HookObject $hook)
    {
        // TODO: Implement beforeUpdateILIASObject() method.
    }
    
    public function afterUpdateILIASObject(HookObject $hook)
    {
        // TODO: Implement afterUpdateILIASObject() method.
    }
    
    public function beforeDeleteILIASObject(HookObject $hook)
    {
        // TODO: Implement beforeDeleteILIASObject() method.
    }
    
    public function afterDeleteILIASObject(HookObject $hook)
    {
        // TODO: Implement afterDeleteILIASObject() method.
    }
    
    public function beforeSync()
    {
        // TODO: Implement beforeSync() method.
    }
    
    public function afterSync()
    {
        // TODO: Implement afterSync() method.
    }
    
}
