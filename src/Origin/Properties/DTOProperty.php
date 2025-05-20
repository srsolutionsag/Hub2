<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin\Properties;

/**
 * Class DTOProperty
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DTOProperty
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $descriptionKey = '';
    /**
     * @param string $name
     * @param string $descriptionKey
     */
    public function __construct($name, $descriptionKey = '')
    {
        $this->name = $name;
        $this->descriptionKey = $descriptionKey;
    }
}
