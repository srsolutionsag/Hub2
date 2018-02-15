<?php namespace SRAG\Plugins\Hub2\Origin;

/**
 * Interface IOriginFactory
 *
 * @package SRAG\Plugins\Hub2\Origin
 */
interface IOriginFactory {

	/**
	 * Get the concrete origin by ID, e.g. returns a IUserOrigin if the given ID belongs
	 * to a origin of object type 'user'.
	 *
	 * @param int $id
	 *
	 * @return IOrigin
	 */
	public function getById($id): IOrigin;


	/**
	 * @param string $type
	 *
	 * @return IOrigin
	 */
	public function createByType(string $type);


	/**
	 * @return IOrigin[]
	 */
	public function getAllActive(): array;
}