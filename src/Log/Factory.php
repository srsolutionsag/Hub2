<?php

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use ilHub2Plugin;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\User\IUserDTO;
use srag\Plugins\Hub2\Origin\IOrigin;
use stdClass;
use Throwable;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class Factory
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements IFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IFactory
     */
    protected static $instance;
    /**
     * @var IRepository
     */
    protected $log_repo;

    public static function getInstance() : IFactory
    {
        if (self::$instance === null) {
            self::setInstance(new self());
        }

        return self::$instance;
    }

    public static function setInstance(IFactory $instance) : void/*: void*/
    {
        self::$instance = $instance;
    }

    /**
     * Factory constructor
     */
    private function __construct()
    {
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function log() : ILog
    {
        return (new Log())->withAdditionalData(clone $this->log_repo->getGlobalAdditionalData());
    }

    /**
     * @inheritdoc
     */
    public function originLog(IOrigin $origin = null, IObject $object = null, IDataTransferObject $dto = null) : ILog
    {
        $log = $this->log()->withOriginId($origin->getId())->withOriginObjectType($origin->getObjectType());

        if ($object instanceof \srag\Plugins\Hub2\Object\IObject) {
            $log->withObjectExtId($object->getExtId())
                ->withObjectIliasId($object->getILIASId())
                ->withStatus($object->getStatus())
                ->withAdditionalData((object) $object->getData()['additionalData']);
        }

        if ($dto instanceof \srag\Plugins\Hub2\Object\DTO\IDataTransferObject) {
            if (empty($log->getObjectExtId())) {
                $log->withObjectExtId($dto->getExtId());
            }

            if (method_exists($dto, "getTitle") && !empty($dto->getTitle())) {
                return $log->withTitle($dto->getTitle());
            }
            if ($dto instanceof IUserDTO) {
                if (!empty($dto->getLogin())) {
                    return $log->withTitle($dto->getLogin());
                }
                if (!empty($dto->getEmail())) {
                    return $log->withTitle($dto->getEmail());
                }
            }
        }

        return $log;
    }

    /**
     * @inheritdoc
     */
    public function exceptionLog(
        Throwable $ex,
        IOrigin $origin = null,
        IObject $object = null,
        IDataTransferObject $dto = null
    ) : ILog {
        $log = $this->originLog($origin, $object, $dto);

        $log->withLevel(ILog::LEVEL_EXCEPTION);
        $log->withMessage($ex->getMessage());
        $relevant = true;
        $filter = static function (array $stack) use (&$relevant) : bool {
            $relevant = strpos($stack["file"], 'OriginSync.php') === false && $relevant;
            return $relevant;
        };
        $stack = array_filter($ex->getTrace(), $filter);

        $closure = static function (array $stack) {
            // $file = str_replace(getcwd(), "", $stack["file"]);
            $file = basename($stack["file"]);
            return "$file({$stack["line"] })->{$stack["function"]}()";
        };
        $small_stack = array_map($closure, $stack);
        $additional = (object) $small_stack;
        $log->withAdditionalData($additional);

        return $log;
    }

    /**
     * @inheritdoc
     */
    public function fromDB(stdClass $data) : ILog
    {
        return $this->log()->withLogId($data->log_id)->withTitle($data->title)->withMessage($data->message)
                    ->withDate(
                        new ilDateTime(
                            $data->date,
                            IL_CAL_DATETIME
                        )
                    )->withLevel($data->level)->withAdditionalData(
                        json_decode(
                            $data->additional_data,
                            false,
                            512,
                            JSON_THROW_ON_ERROR
                        ) ?? new stdClass()
                    )
                    ->withOriginId($data->origin_id)->withOriginObjectType($data->origin_object_type)->withObjectExtId(
                        $data->object_ext_id
                    )
                    ->withObjectIliasId($data->object_ilias_id)
                    ->withStatus((int) $data->status);
    }
}
