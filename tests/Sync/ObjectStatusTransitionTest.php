<?php

require_once __DIR__ . "/../AbstractHub2Tests.php";

use Mockery\MockInterface;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Sync\ObjectStatusTransition;

/**
 * Class ObjectStatusTransitionTest
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ObjectStatusTransitionTest extends AbstractHub2Tests
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function test_intermediate_to_final()
    {
        $config = Mockery::mock(IOriginConfig::class);
        $transition = new ObjectStatusTransition($config);

        // TO_CREATE -> CREATED
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_TO_CREATE);
        //$this->assertEquals(IObject::STATUS_CREATED, $transition->intermediateToFinal($object));

        // TO_UPDATE -> UPDATED
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_TO_UPDATE);
        //$this->assertEquals(IObject::STATUS_UPDATED, $transition->intermediateToFinal($object));

        // NEWLY_DELIVERD -> UPDATED
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_TO_RESTORE);
        //$this->assertEquals(IObject::STATUS_UPDATED, $transition->intermediateToFinal($object));

        // TO_DELETE -> DELETED
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_TO_OUTDATED);
        //$this->assertEquals(IObject::STATUS_OUTDATED, $transition->intermediateToFinal($object));
    }

    public function test_final_to_intermediate()
    {
        $config = Mockery::mock(IOriginConfig::class);
        $config->shouldReceive('getActivePeriod')->andReturn('Period1');
        $transition = new ObjectStatusTransition($config);

        // NEW -> TO_CREATE
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_NEW, 'Period1');
        $this->assertEquals(IObject::STATUS_TO_CREATE, $transition->finalToIntermediate($object));

        // CREATED -> TO_UPDATE
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_CREATED, 'Period1');
        $this->assertEquals(IObject::STATUS_TO_UPDATE, $transition->finalToIntermediate($object));

        // UPDATED -> TO_UPDATE
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_UPDATED, 'Period1');
        $this->assertEquals(IObject::STATUS_TO_UPDATE, $transition->finalToIntermediate($object));

        // DELETED -> TO_UPDATE_NEWLY_DELIVERED
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_OUTDATED, 'Period1');
        $this->assertEquals(IObject::STATUS_TO_RESTORE, $transition->finalToIntermediate($object));
    }

    public function test_status_is_to_create_if_ignored_and_no_ilias_object_exists()
    {
        $config = Mockery::mock(IOriginConfig::class);
        $config->shouldReceive('getActivePeriod')->andReturn('Period1');
        $transition = new ObjectStatusTransition($config);
        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_IGNORED, 'Period1');
        $object->shouldReceive('getILIASId')->andReturn(null);
        $this->assertEquals(IObject::STATUS_TO_CREATE, $transition->finalToIntermediate($object));
    }

    public function test_status_is_to_update_if_ignored_and_ilias_object_exists()
    {
        $config = Mockery::mock(IOriginConfig::class);
        $config->shouldReceive('getActivePeriod')->andReturn('Period1');
        $transition = new ObjectStatusTransition($config);

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_IGNORED, 'Period1');
        $object->shouldReceive('getILIASId')->andReturn(123);
        $this->assertEquals(IObject::STATUS_TO_UPDATE, $transition->finalToIntermediate($object));

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_IGNORED, 'Period2');
        $object->shouldReceive('getILIASId')->andReturn(123);
        $this->assertEquals(IObject::STATUS_IGNORED, $transition->finalToIntermediate($object));
    }

    public function test_status_gets_ignored_if_period_does_not_match()
    {
        $config = Mockery::mock(IOriginConfig::class);
        $config->shouldReceive('getActivePeriod')->andReturn('ActualPeriod');
        $transition = new ObjectStatusTransition($config);

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_NEW, 'AnotherPeriod');
        $this->assertEquals(IObject::STATUS_IGNORED, $transition->finalToIntermediate($object));

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_NEW, 'ActualPeriod');
        $this->assertNotEquals(IObject::STATUS_IGNORED, $transition->finalToIntermediate($object));
    }

    public function test_intermediate_to_final_status_does_not_change_if_already_final()
    {
        $config = Mockery::mock(IOriginConfig::class);
        $transition = new ObjectStatusTransition($config);

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_UPDATED);
        //$this->assertEquals(IObject::STATUS_UPDATED, $transition->intermediateToFinal($object));

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_CREATED);
        //$this->assertEquals(IObject::STATUS_CREATED, $transition->intermediateToFinal($object));

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_OUTDATED);
        //$this->assertEquals(IObject::STATUS_OUTDATED, $transition->intermediateToFinal($object));

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_IGNORED);
        //$this->assertEquals(IObject::STATUS_IGNORED, $transition->intermediateToFinal($object));

        $object = $this->getObjectMockWithStatusAndPeriod(IObject::STATUS_NEW);
        //$this->assertEquals(IObject::STATUS_NEW, $transition->intermediateToFinal($object));
    }

    /**
     * @param int    $status
     * @param string $period
     * @return MockInterface
     */
    protected function getObjectMockWithStatusAndPeriod($status, $period = '')
    {
        $object = Mockery::mock(IObject::class);
        $object->shouldReceive('getStatus')->andReturn($status);
        if ($period) {
            $object->shouldReceive('getPeriod')->andReturn($period);
        }

        return $object;
    }
}
