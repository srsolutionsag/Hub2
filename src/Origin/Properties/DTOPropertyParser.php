<?php

namespace srag\Plugins\Hub2\Origin\Properties;

use ReflectionClass;
use ReflectionProperty;

/**
 * Class DTOPropertyParser
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DTOPropertyParser
{
    /**
     * @var string
     */
    private $dtoClass;

    /**
     * @param string $dtoClass Fully qualified name of a DTO class, e.g. UserDTO
     */
    public function __construct($dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }

    /**
     * @return DTOProperty[]
     */
    public function getProperties() : array
    {
        $reflection = new ReflectionClass($this->dtoClass);
        $reflectionProperties = $reflection->getProperties(ReflectionProperty::IS_PROTECTED);
        $properties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            // Look for a @description php doc block
            $out = [];
            preg_match('/@description\s(\w+)/', $reflectionProperty->getDocComment(), $out);
            $descriptionKey = count($out) ? $out[1] : '';
            $properties[] = new DTOProperty($reflectionProperty->name, $descriptionKey);
        }

        return $properties;
    }
}
