<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

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
    /**
     * @var string
     */
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    private static ?IFactory $instance = null;
    /**
     * @readonly
     */
    private IRepository $log_repo;

    public static function getInstance(): IFactory
    {
        if (self::$instance === null) {
            self::setInstance(new self());
        }

        return self::$instance;
    }

    public static function setInstance(IFactory $instance): void/*: void*/
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

    public function log(): ILog
    {
        return (new Log())->withAdditionalData(clone $this->log_repo->getGlobalAdditionalData());
    }

    public function originLog(IOrigin $origin = null, IObject $object = null, IDataTransferObject $dto = null): ILog
    {
        $log = $this->log();
        if ($origin !== null) {
            $log = $log->withOriginId(
                $origin->getId()
            )->withOriginObjectType($origin->getObjectType());
            ;
        }

        if ($object instanceof IObject) {
            $log->withObjectExtId($object->getExtId())
                ->withObjectIliasId((int) $object->getILIASId())
                ->withStatus($object->getStatus())
                ->withAdditionalData((object) ($object->getData()['additionalData'] ?? new stdClass()));
        }

        if ($dto instanceof IDataTransferObject) {
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

    public function exceptionLog(
        Throwable $ex,
        IOrigin $origin = null,
        IObject $object = null,
        IDataTransferObject $dto = null
    ): ILog {
        $log = $this->originLog($origin, $object, $dto);

        $log->withLevel(ILog::LEVEL_EXCEPTION);
        $log->withMessage($ex->getMessage());
        $relevant = true;
        $filter = static function (array $stack) use (&$relevant): bool {
            $relevant = strpos($stack["file"] ?? '', 'OriginSync.php') === false && $relevant;
            return $relevant;
        };
        $stack = array_filter($ex->getTrace(), $filter);

        $closure = static function (array $stack): string {
            // $file = str_replace(getcwd(), "", $stack["file"]);
            $file = basename($stack["file"] ?? '');
            $line = $stack["line"] ?? '';
            $function = $stack["function"] ?? '';
            return "$file({$line })->{$function}()";
        };
        $small_stack = array_map($closure, $stack);
        $additional = (object) $small_stack;
        $log->withAdditionalData($additional);

        return $log;
    }

    public function fromDB(stdClass $data): ILog
    {
        return $this->log()->withLogId($data->log_id)->withTitle($data->title)->withMessage($data->message)
                    ->withDate(
                        new ilDateTime(
                            $data->date,
                            IL_CAL_DATETIME
                        )
                    )->withLevel($data->level)->withAdditionalData(
                        json_decode(
                            (string) $data->additional_data,
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
