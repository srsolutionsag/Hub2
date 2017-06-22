<?php namespace SRAG\Hub2\Object;

/**
 * Class HookObject
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Object
 */
class HookObject {

	/**
	 * @var IObject
	 */
	private $object;
	/**
	 * @var \ilObject
	 */
	private $ilias_object;

	/**
	 * @param IObject $object
	 */
	public function __construct(IObject $object) {
		$this->object = $object;
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
	 * @param \ilObject $object
	 * @return HookObject
	 */
	public function withILIASObject(\ilObject $object) {
		$clone = clone $this;
		$clone->ilias_object = $object;
		return $clone;
	}

	/**
	 * Get the ILIAS object which has been processed.
	 * Note that this object is only available in the IOriginImplementation::after(Create|Update|Delete)Object
	 * callbacks, it is NOT set for any before callbacks
	 *
	 * @return \ilObject|null
	 */
	public function getILIASObject() {
		return $this->ilias_object;
	}

	/**
	 * Get the ID of the linked ILIAS object
	 * Note that this ID may be the object or ref-ID depending on the synced object
	 * Also note that this ID may be NULL if the ILIAS object has not been created yet, e.g.
	 * in the case of IOriginImplementation::beforeCreateILIASObject()
	 *
	 * @return int
	 */
	public function getILIASId() {
		return $this->object->getILIASId();
	}

}