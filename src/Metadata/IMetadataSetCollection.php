<?php

namespace SRAG\Hub2\Metadata;

/**
 * Interface IMetadataSetCollection
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataSetCollection {

	/**
	 * @param $id
	 *
	 * @return \SRAG\Hub2\Metadata\IMetadataSet
	 */
	public function getById($id);


	/**
	 * @return \SRAG\Hub2\Metadata\IMetadataSet[]
	 */
	public function getAll();
}
