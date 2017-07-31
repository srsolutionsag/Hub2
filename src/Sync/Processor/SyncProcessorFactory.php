<?php namespace SRAG\Hub2\Sync\Processor;


use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Sync\IObjectStatusTransition;

/**
 * Class SyncProcessorFactory
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
 */
class SyncProcessorFactory implements ISyncProcessorFactory {

	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var IObjectStatusTransition
	 */
	protected $statusTransition;

	/**
	 * @param IOrigin $origin
	 * @param IObjectStatusTransition $statusTransition
	 */
	public function __construct(IOrigin $origin, IObjectStatusTransition $statusTransition) {
		$this->origin = $origin;
		$this->statusTransition = $statusTransition;
	}

	/**
	 * @inheritdoc
	 */
	public function userProcessor() {
		return new UserSyncProcessor($this->origin, $this->statusTransition);
	}

	/**
	 * @inheritdoc
	 */
	public function courseProcessor() {
		global $DIC;
		return new CourseSyncProcessor($this->origin, $this->statusTransition, new CourseActivities($DIC->database()));
	}

	/**
	 * @inheritdoc
	 */
	public function categoryProcessor() {
		return new CategorySyncProcessor($this->origin, $this->statusTransition);
	}
}