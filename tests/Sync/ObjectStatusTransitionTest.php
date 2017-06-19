<?php

use SRAG\Hub2\Sync\ObjectStatusTransition;
use SRAG\Hub2\Object\IObject;

/**
 * Class ObjectStatusTransitionTest
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ObjectStatusTransitionTest extends \PHPUnit\Framework\TestCase {

	use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

	public function test_intermediate_to_final() {
		$origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$transition = new ObjectStatusTransition($origin);

		// TO_CREATE -> CREATED
		$object = $this->getObjectMock(IObject::STATUS_TO_CREATE);
		$this->assertEquals(IObject::STATUS_CREATED, $transition->intermediateToFinal($object));

		// TO_UPDATE -> UPDATED
		$object = $this->getObjectMock(IObject::STATUS_TO_UPDATE);
		$this->assertEquals(IObject::STATUS_UPDATED, $transition->intermediateToFinal($object));

		// NEWLY_DELIVERD -> UPDATED
		$object = $this->getObjectMock(IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED);
		$this->assertEquals(IObject::STATUS_UPDATED, $transition->intermediateToFinal($object));

		// TO_DELETE -> DELETED
		$object = $this->getObjectMock(IObject::STATUS_TO_DELETE);
		$this->assertEquals(IObject::STATUS_DELETED, $transition->intermediateToFinal($object));
	}

	public function test_final_to_intermediate() {
		$config = \Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig");
		$config->shouldReceive('getActivePeriod')
			->andReturn('Period1');
		$origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$origin->shouldReceive("config")
			->andReturn($config);
		$transition = new ObjectStatusTransition($origin);

		// NEW -> TO_CREATE
		$object = $this->getObjectMock(IObject::STATUS_NEW, 'Period1');
		$this->assertEquals(IObject::STATUS_TO_CREATE, $transition->finalToIntermediate($object));

		// CREATED -> TO_UPDATE
		$object = $this->getObjectMock(IObject::STATUS_CREATED, 'Period1');
		$this->assertEquals(IObject::STATUS_TO_UPDATE, $transition->finalToIntermediate($object));

		// UPDATED -> TO_UPDATE
		$object = $this->getObjectMock(IObject::STATUS_UPDATED, 'Period1');
		$this->assertEquals(IObject::STATUS_TO_UPDATE, $transition->finalToIntermediate($object));

		// DELETED -> TO_UPDATE_NEWLY_DELIVERED
		$object = $this->getObjectMock(IObject::STATUS_DELETED, 'Period1');
		$this->assertEquals(IObject::STATUS_TO_UPDATE_NEWLY_DELIVERED, $transition->finalToIntermediate($object));
	}

	public function test_status_is_to_create_if_ignored_and_no_ilias_object_exists() {
		$config = \Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig");
		$config->shouldReceive('getActivePeriod')
			->andReturn('Period1');
		$origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$origin->shouldReceive("config")
			->andReturn($config);
		$transition = new ObjectStatusTransition($origin);
		$object = $this->getObjectMock(IObject::STATUS_IGNORED, 'Period1');
		$object->shouldReceive('getILIASId')
			->andReturn(null);
		$this->assertEquals(IObject::STATUS_TO_CREATE, $transition->finalToIntermediate($object));
	}

	public function test_status_is_to_update_if_ignored_and_ilias_object_exists() {
		$config = \Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig");
		$config->shouldReceive('getActivePeriod')
			->andReturn('Period1');
		$origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$origin->shouldReceive("config")
			->andReturn($config);
		$transition = new ObjectStatusTransition($origin);

		$object = $this->getObjectMock(IObject::STATUS_IGNORED, 'Period1');
		$object->shouldReceive('getILIASId')
			->andReturn(123);
		$this->assertEquals(IObject::STATUS_TO_UPDATE, $transition->finalToIntermediate($object));

		$object = $this->getObjectMock(IObject::STATUS_IGNORED, 'Period2');
		$object->shouldReceive('getILIASId')
			->andReturn(123);
		$this->assertEquals(IObject::STATUS_IGNORED, $transition->finalToIntermediate($object));
	}


	public function test_status_gets_ignored_if_period_does_not_match() {
		$config = \Mockery::mock("SRAG\Hub2\Origin\Config\IOriginConfig");
		$config->shouldReceive('getActivePeriod')
			->andReturn('ActualPeriod');
		$origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$origin->shouldReceive("config")
			->andReturn($config);
		$transition = new ObjectStatusTransition($origin);

		$object = $this->getObjectMock(IObject::STATUS_NEW, 'AnotherPeriod');
		$this->assertEquals(IObject::STATUS_IGNORED, $transition->finalToIntermediate($object));

		$object = $this->getObjectMock(IObject::STATUS_NEW, 'ActualPeriod');
		$this->assertNotEquals(IObject::STATUS_IGNORED, $transition->finalToIntermediate($object));
	}

	public function test_intermediate_to_final_status_does_not_change_if_already_final() {
		$origin = \Mockery::mock("SRAG\Hub2\Origin\IOrigin");
		$transition = new ObjectStatusTransition($origin);

		$object = $this->getObjectMock(IObject::STATUS_UPDATED);
		$this->assertEquals(IObject::STATUS_UPDATED, $transition->intermediateToFinal($object));

		$object = $this->getObjectMock(IObject::STATUS_CREATED);
		$this->assertEquals(IObject::STATUS_CREATED, $transition->intermediateToFinal($object));

		$object = $this->getObjectMock(IObject::STATUS_DELETED);
		$this->assertEquals(IObject::STATUS_DELETED, $transition->intermediateToFinal($object));

		$object = $this->getObjectMock(IObject::STATUS_IGNORED);
		$this->assertEquals(IObject::STATUS_IGNORED, $transition->intermediateToFinal($object));

		$object = $this->getObjectMock(IObject::STATUS_NEW);
		$this->assertEquals(IObject::STATUS_NEW, $transition->intermediateToFinal($object));
	}

	/**
	 * @param int $status
	 * @param string $period
	 * @return \Mockery\MockInterface
	 */
	protected function getObjectMock($status, $period = '') {
		$object = \Mockery::mock("SRAG\Hub2\Object\IObject");
		$object->shouldReceive('getStatus')
			->andReturn($status);
		if ($period) {
			$object->shouldReceive('getPeriod')
				->andReturn($period);
		}
		return $object;
	}
}