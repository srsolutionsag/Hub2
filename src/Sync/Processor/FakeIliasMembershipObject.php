<?php

namespace SRAG\Plugins\Hub2\Sync\Processor;

/**
 * Class FakeIliasMembershipObject
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class FakeIliasMembershipObject extends FakeIliasObject {

	const GLUE = "|||";
	/**
	 * @var int
	 */
	protected $user_id_ilias;
	/**
	 * @var int
	 */
	protected $container_id_ilias;


	/**
	 * FakeIliasMembershipObject constructor.
	 *
	 * @param int $container_id_ilias
	 * @param int $user_id_ilias
	 */
	public function __construct($container_id_ilias, $user_id_ilias) {
		parent::__construct();
		$this->container_id_ilias = (int)$container_id_ilias;
		$this->user_id_ilias = (int)$user_id_ilias;
		$this->initId();
	}


	/**
	 * @param string $id
	 *
	 * @return FakeIliasMembershipObject
	 */
	public static function loadInstanceWithConcatenatedId(string $id) {
		list($container_id_ilias, $user_id_ilias) = explode(self::GLUE, $id);

		return new self((int)$container_id_ilias, (int)$user_id_ilias);
	}


	/**
	 * @inheritDoc
	 */
	public function getId(): string {
		return $this->id;
	}


	/**
	 * @return int
	 */
	public function getUserIdIlias(): int {
		return $this->user_id_ilias;
	}


	/**
	 * @param int $user_id_ilias
	 */
	public function setUserIdIlias(int $user_id_ilias) {
		$this->user_id_ilias = $user_id_ilias;
	}


	/**
	 * @return int
	 */
	public function getContainerIdIlias(): int {
		return $this->container_id_ilias;
	}


	/**
	 * @param int $container_id_ilias
	 */
	public function setContainerIdIlias(int $container_id_ilias) {
		$this->container_id_ilias = $container_id_ilias;
	}


	/**
	 *
	 */
	public function initId() {
		$this->setId(implode(self::GLUE, [ $this->container_id_ilias, $this->user_id_ilias ]));
	}
}
