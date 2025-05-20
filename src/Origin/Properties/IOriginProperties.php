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
 * Interface Properties
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginProperties
{
    public const PREFIX_UPDATE_DTO = 'update_dto_';

    /**
     * Get a property value by key, returns NULL if no property is found.
     * @return mixed
     */
    public function get(string $key);

    /**
     * Checks if the given property of a DTO object should be updated on the ILIAS object,
     * e.g. the first- or lastname of a user.
     */
    public function updateDTOProperty(string $property): bool;

    /**
     * Get all properties as associative array
     */
    public function getData(): array;

    /**
     * Set all properties as associative array
     * @return $this
     */
    public function setData(array $data): self;
}
