<?php

namespace srag\Plugins\Hub2\Parser;

use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class Csv
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class Csv
{
    const ENCLOSURE_DEFAULT = '"';
    const SEPARATOR_DEFAULT = ";";
    const BAD_ENCLOSURE_REPLACEMENT = '';
    
    /**
     * @var array
     */
    protected $bad_enclosures = [];
    /**
     * @var array
     */
    protected $columns_mapping = [];
    /**
     * @var \Closure[]
     */
    protected $filters = [];
    /**
     * @var array
     */
    protected $parsed_csv = [];
    /**
     * @var string
     */
    protected $enclosure = self::ENCLOSURE_DEFAULT;
    /**
     * @var string
     */
    protected $separator = self::SEPARATOR_DEFAULT;
    /**
     * @var string
     */
    protected $file_path = '';
    /**
     * @var string
     */
    protected $unique_field = '';
    /**
     * @var array
     */
    protected $mandatory_columns = [];
    /**
     * @var array
     */
    protected $delivered_columns = [];
    /**
     * @var array
     */
    protected $header = [];
    
    /**
     * @param string      $enclosure
     * @param string      $separator
     * @param string      $file_path
     * @param string|null $unique_field
     * @param array       $mandatory_columns
     */
    public function __construct(
        string $file_path,
        string $unique_field = '',
        array $mandatory_columns = [],
        array $column_mapping = [],
        string $enclosure = self::ENCLOSURE_DEFAULT,
        string $separator = self::SEPARATOR_DEFAULT,
        array $bad_enclosures = []
    ) {
        $this->enclosure = $enclosure;
        $this->separator = $separator;
        $this->file_path = $file_path;
        $this->unique_field = $unique_field;
        $this->mandatory_columns = $mandatory_columns;
        $this->columns_mapping = $column_mapping;
        $this->bad_enclosures = $bad_enclosures;
    }
    
    /**
     * @param array $header
     */
    public function setHeader(array $header) : void
    {
        $this->header = $header;
    }
    
    
    
    public function addFilter(\Closure $filter) : void
    {
        $this->filters[] = $filter;
    }
    
    protected function applyFilters() : void
    {
        foreach ($this->filters as $filter) {
            $this->applyFilter($filter);
        }
    }
    
    protected function applyFilter(\Closure $closure) : void
    {
        $this->parsed_csv = array_filter($this->parsed_csv, $closure);
    }
    
    protected function filterMandatory() : void
    {
        $mandatory = $this->mandatory_columns;
        if ($mandatory === []) {
            return;
        }
        
        $this->applyFilter(function (array $item) use ($mandatory) : bool {
            $isset = true;
            foreach ($mandatory as $column) {
                $isset = $isset && isset($item[$column]) && $item[$column] !== '';
            }
            return $isset;
        });
    }
    
    protected function mapFieldsToTitle() : void
    {
        array_walk($this->parsed_csv, function (array &$item) {
            foreach ($item as $k => $v) {
                unset($item[$k]);
                $item[$this->delivered_columns[$k]] = $this->sanitize($v);
            }
        });
    }
    
    /**
     * @return string
     */
    protected function getEnclosure() : string
    {
        return $this->enclosure;
    }
    
    /**
     * @return string
     */
    protected function getSeparator() : string
    {
        return $this->separator;
    }
    
    protected function removeBOM(string $text) : string
    {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }
    
    protected function parseCSVFileAndApplyHeaders(string $path_to_file) : void
    {
        $this->parseCSVFile($path_to_file);
        $this->mapFieldsToTitle($this->parsed_csv);
    }
    
    protected function parseCSVFile(string $path_to_file) : void
    {
        $this->parsed_csv = array_map(function (string $line) : array {
            return str_getcsv($this->sanitizeEnclosures($this->removeBOM($line)), $this->getSeparator(), $this->getEnclosure());
        }, file($path_to_file));
    }
    
    public function parseData() : array
    {
        $this->parseCSVFile($this->file_path);
        if ($this->header === []) {
            $this->delivered_columns = array_shift($this->parsed_csv);
        } else {
            $this->delivered_columns = $this->header;
        }
        
        if (!is_array($this->delivered_columns)
            || count(array_diff($this->mandatory_columns, $this->delivered_columns)) > 0) {
            throw new ParseDataFailedException(
                "Not all mandatory colums in csv available. received '" . implode(
                    "|",
                    (array) $this->delivered_columns
                ) . "', expected '" . implode(
                    "|",
                    $this->mandatory_columns
                ) . "' in {$this->file_path}"
            );
        }
        
        $this->mapFieldsToTitle();
        $this->filterMandatory();
        $this->applyFilters();
        
        if ($this->unique_field !== '') {
            $field = $this->unique_field;
            $unique = [];
            $this->applyFilter(static function (array $item) use (&$unique, $field) : bool {
                $isset = isset($unique[$item[$field]]);
                $unique[$item[$field]] = true;
                return !$isset;
            });
        }
        
        if (count($this->columns_mapping) > 0) {
            $mapping = $this->columns_mapping;
            $this->parsed_csv = array_map(function (array $item) use ($mapping) : array {
                foreach ($mapping as $old_key => $new_key) {
                    if (isset($item[$old_key])) {
                        $item[$new_key] = $item[$old_key];
                        unset($item[$old_key]);
                    }
                }
                return $item;
            }, $this->parsed_csv);
        }
        
        return $this->parsed_csv;
    }
    
    protected function sanitize(string $s) : string
    {
        return utf8_encode(utf8_decode($s));
    }
    
    protected function sanitizeEnclosures(string $s) : string
    {
        if ($this->bad_enclosures === []) {
            return $s;
        }
        static $non_enclosures;
        if (!isset($non_enclosures)) {
            $bad_enclosured = '/[' . implode('', $this->bad_enclosures) . ']/';
            $non_enclosures = str_replace($this->getEnclosure(), '', $bad_enclosured);
        }
        
        return preg_replace($non_enclosures, self::BAD_ENCLOSURE_REPLACEMENT, $s);
    }
    
    protected function getColumnMapping() : array
    {
        return $this->columns_mapping;
    }
    
}
