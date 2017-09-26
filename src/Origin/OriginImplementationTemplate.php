<?php namespace SRAG\Hub2\Origin;

use SRAG\Hub2\Exception\BuildObjectsFailedException;
use SRAG\Hub2\Exception\ConnectionFailedException;
use SRAG\Hub2\Exception\ParseDataFailedException;
use SRAG\Hub2\Object\HookObject;
use SRAG\Hub2\Object\IDataTransferObject;

/**
 * Class Template
 *
 * @package SRAG\Hub2\Origin
 */
class Template extends AbstractOriginImplementation {

	/**
	 * @inheritdoc
	 */
	public function connect() {
		// TODO: Implement connect() method.
	}

	/**
	 * @inheritdoc
	 */
	public function parseData() {
		// TODO: Implement parseData() method.
	}

	/**
	 * @inheritdoc
	 */
	public function buildObjects() {
		// TODO: Implement buildObjects() method.
	}

	/**
	 * @inheritdoc
	 */
	public function handleException(\Exception $e) {
		// TODO: Implement handleException() method.
	}

	/**
	 * @inheritdoc
	 */
	public function beforeCreateILIASObject(HookObject $object) {
		// TODO: Implement beforeCreateILIASObject() method.
	}

	/**
	 * @inheritdoc
	 */
	public function afterCreateILIASObject(HookObject $object) {
		// TODO: Implement afterCreateILIASObject() method.
	}

	/**
	 * @inheritdoc
	 */
	public function beforeUpdateILIASObject(HookObject $object) {
		// TODO: Implement beforeUpdateILIASObject() method.
	}

	/**
	 * @inheritdoc
	 */
	public function afterUpdateILIASObject(HookObject $object) {
		// TODO: Implement afterUpdateILIASObject() method.
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDeleteILIASObject(HookObject $object) {
		// TODO: Implement beforeDeleteILIASObject() method.
	}

	/**
	 * @inheritdoc
	 */
	public function afterDeleteILIASObject(HookObject $object) {
		// TODO: Implement afterDeleteILIASObject() method.
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSync() {
		// TODO: Implement beforeSync() method.
	}

	/**
	 * @inheritdoc
	 */
	public function afterSync() {
		// TODO: Implement afterSync() method.
	}
}