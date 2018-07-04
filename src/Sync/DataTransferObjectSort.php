<?php

namespace SRAG\Plugins\Hub2\Sync;

use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class DataTransferObjectSort
 *
 * @package SRAG\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
final class DataTransferObjectSort implements IDataTransferObjectSort {

	/**
	 * @var IDataTransferObject
	 */
	private $dto_object;
	/**
	 * @var int
	 */
	private $level = 1;


	/**
	 * @param IDataTransferObject $dto_object
	 */
	public function __construct(IDataTransferObject $dto_object) {
		$this->dto_object = $dto_object;
	}


	/**
	 * @inheritdoc
	 */
	public function getDtoObject(): IDataTransferObject {
		return $this->dto_object;
	}


	/**
	 * @param IDataTransferObject $dto_object
	 */
	public function setDtoObject(IDataTransferObject $dto_object) {
		$this->dto_object = $dto_object;
	}


	/**
	 * @inheritdoc
	 */
	public function getLevel(): int {
		return $this->level;
	}


	/**
	 * @inheritdoc
	 */
	public function setLevel(int $level) {
		$this->level = $level;
	}
}
