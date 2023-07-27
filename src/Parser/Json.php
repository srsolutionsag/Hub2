<?php

namespace srag\Plugins\Hub2\Parser;

use srag\Plugins\Hub2\Exception\ParseDataFailedException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class Json
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class Json
{
    /**
     * @var array[]
     */
    private $parsed_json;
    /**
     * @var string
     */
    private $file_path;
    /**
     * @var string|null
     */
    private $unique_field;
    /**
     * @var array
     */
    private $mandatory_columns = [];
    /**
     * @var array
     */
    private $columns_mapping = [];
    /**
     * @var array
     */
    private $filters = [];
    /**
     * @var \Closure
     */
    private $string_sanitizer;

    /**
     *
     */
    public function __construct(
        string $file_path,
        ?string $unique_field,
        array $mandatory_columns = [],
        \Closure $string_sanitizer
    ) {
        $this->file_path = $file_path;
        $this->unique_field = $unique_field;
        $this->mandatory_columns = $mandatory_columns;
        $this->string_sanitizer = $string_sanitizer;
    }

    public function addFilter(\Closure $filter): void
    {
        $this->filters[] = $filter;
    }

    protected function applyFilters(): void
    {
        foreach ($this->filters as $filter) {
            $this->applyFilter($filter);
        }
    }

    protected function applyFilter(\Closure $closure): void
    {
        $this->parsed_json = array_filter($this->parsed_json, $closure);
    }

    protected function filterMandatory(): void
    {
        $mandatory = $this->mandatory_columns;
        if ($mandatory === []) {
            return;
        }

        $this->applyFilter(function (array $item) use ($mandatory): bool {
            $isset = true;
            foreach ($mandatory as $column) {
                $isset = $isset && isset($item[$column]) && $item[$column] !== '';
            }
            return $isset;
        });
    }

    protected function mapFieldsToTitle(): void
    {
        array_walk($this->parsed_json, function (array &$item) {
            foreach ($item as $k => $v) {
                unset($item[$k]);
                $item[$this->delivered_columns[$k]] = $this->sanitize($v);
            }
        });
    }

    protected function removeBOM(string $text): string
    {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }

    protected function parseCSVFileAndApplyHeaders(string $path_to_file): void
    {
        $this->parseJSONFile($path_to_file);
        $this->mapFieldsToTitle($this->parsed_json);
    }

    protected function parseJSONFile(string $path_to_file): void
    {
        $raw_json = json_decode(file_get_contents($path_to_file), true, 512);

        $recursive_string_sanitizer = function (array $item) use (&$recursive_string_sanitizer): array {
            foreach ($item as $k => $v) {
                if (is_array($v)) {
                    $item[$k] = $recursive_string_sanitizer($v);
                } elseif (is_string($v)) {
                    $item[$k] = $this->sanitize($v);
                } elseif (is_int($v)) {
                    $item[$k] = (int) $v;
                } else {
                    $item[$k] = $v;
                }
            }
            return $item;
        };

        $this->parsed_json = array_map($recursive_string_sanitizer, $raw_json);
    }

    public function parseData(): array
    {
        $this->parseJSONFile($this->file_path);

        /*
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
        */
//        $this->mapFieldsToTitle();
        $this->filterMandatory();
        $this->applyFilters();

        return $this->parsed_json;
    }

    protected function sanitize(string $s): string
    {
        $sanitizer = $this->string_sanitizer;
        return $sanitizer($s);
    }
}
