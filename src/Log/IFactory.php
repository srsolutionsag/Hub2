<?php

namespace srag\Plugins\Hub2\Log;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\IOrigin;
use stdClass;
use Throwable;

/**
 * Interface IFactory
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IFactory
{
    /**
     * @return ILog
     */
    public function log(): ILog;

    /**
     * @param IOrigin|null             $origin
     * @param IObject|null             $object
     * @param IDataTransferObject|null $dto
     * @return ILog
     */
    public function originLog(IOrigin $origin = null, IObject $object = null, IDataTransferObject $dto = null): ILog;

    /**
     * @param Throwable                $ex
     * @param IOrigin|null             $origin
     * @param IObject|null             $object
     * @param IDataTransferObject|null $dto
     * @return ILog
     */
    public function exceptionLog(
        Throwable $ex,
        IOrigin $origin = null,
        IObject $object = null,
        IDataTransferObject $dto = null
    ): ILog;

    /**
     * @param stdClass $data
     * @return ILog
     */
    public function fromDB(stdClass $data): ILog;
}
