<?php

namespace SRAG\Plugins\Hub2\Object;

use ilHub2Plugin;
use ilObject;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Exception\HubException;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Object\DTO\NullDTO;
use SRAG\Plugins\Hub2\Sync\Processor\FakeIliasObject;

/**
 * Class HookObject
 *
 * @package SRAG\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class HookObject {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IDataTransferObject
	 */
	protected $dto;
	/**
	 * @var IObject
	 */
	private $object;
	/**
	 * @var ilObject
	 */
	private $ilias_object;


	/**
	 * @param IObject $object
	 */
	public function __construct(IObject $object, IDataTransferObject $dto) {
		$this->object = $object;
		$this->dto = $dto;
	}


	/**
	 * Get the external ID of the object helps to identify the object
	 *
	 * @return string
	 */
	public function getExtId() {
		return $this->object->getExtId();
	}


	/**
	 * Get the current status, see constants in IObject
	 *
	 * @return int
	 */
	public function getStatus() {
		return $this->object->getStatus();
	}


	/**
	 * @param int $status
	 *
	 * @throws HubException
	 */
	public function overrideStatus(int $status) {
		if ($this->getDTO() instanceof NullDTO) {
			throw new HubException("Overriding status for NullDTOs is not supported!");
		}
		$this->object->setStatus($status);
	}


	/**
	 * @param ilObject|FakeIliasObject $object
	 *
	 * @return HookObject
	 */
	public function withILIASObject($object) {
		$clone = clone $this;
		$clone->ilias_object = $object;

		return $clone;
	}


	/**
	 * Get the ILIAS object which has been processed.
	 * Note that this object is only available in the
	 * IOriginImplementation::after(Create|Update|Delete)Object callbacks, it is NOT set for any
	 * before callbacks
	 *
	 * @return ilObject|FakeIliasObject|null
	 */
	public function getILIASObject() {
		return $this->ilias_object;
	}


	/**
	 * Get the ID of the linked ILIAS object.
	 * Note that this ID may be the object or ref-ID depending on the synced object.
	 * Also note that this ID may be NULL if the ILIAS object has not been created yet, e.g.
	 * in the case of IOriginImplementation::beforeCreateILIASObject()
	 *
	 * @return int
	 */
	public function getILIASId() {
		return $this->object->getILIASId();
	}


	/**
	 * @return IDataTransferObject
	 */
	public function getDTO(): IDataTransferObject {
		return $this->dto;
	}


	/**
	 * @return IObject the internal AR Object, not the ILIAS Object
	 */
	public function getObject(): IObject {
		return $this->object;
	}
}
