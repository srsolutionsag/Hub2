<?php

namespace srag\Plugins\Hub2\Origin;

/**
 * Interface IOriginFactory
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginFactory
{
    /**
     * Get the concrete origin by ID, e.g. returns a IUserOrigin if the given ID belongs
     * to a origin of object type 'user'.
     * @param int $id
     * @return IOrigin|null
     */
    public function getById($id); //Correct return type would by : ?IOrigin, but this is PHP7.1+

    public function createByType(string $type) : IOrigin;

    /**
     * @return IOrigin[]
     */
    public function getAllActive() : array;

    /**
     * @return IOrigin[]
     */
    public function getAll() : array;

    public function delete(int $origin_id)/*: void*/
    ;
}
