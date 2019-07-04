<?php

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\User\IUserDTO;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;
use stdClass;
use Throwable;

/**
 * Class Factory
 *
 * @package srag\Plugins\Hub2\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements IFactory {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IFactory
	 */
	protected static $instance = null;


	/**
	 * @return IFactory
	 */
	public static function getInstance(): IFactory {
		if (self::$instance === null) {
			self::setInstance(new self());
		}

		return self::$instance;
	}


	/**
	 * @param IFactory $instance
	 */
	public static function setInstance(IFactory $instance)/*: void*/ {
		self::$instance = $instance;
	}


	/**
	 * Factory constructor
	 */
	private function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function log(): ILog {
		$log = (new Log())->withAdditionalData(clone self::logs()->getGlobalAdditionalData());

		return $log;
	}


	/**
	 * @inheritdoc
	 */
	public function originLog(IOrigin $origin = null, IObject $object = null, IDataTransferObject $dto = null): ILog {
		$log = $this->log()->withOriginId($origin->getId())->withOriginObjectType($origin->getObjectType());

		if ($object !== null) {
			$log->withObjectExtId($object->getExtId())->withObjectIliasId($object->getILIASId());
		}

		if ($dto !== null) {
			if (empty($log->getObjectExtId())) {
				$log->withObjectExtId($dto->getExtId());
			}

			if (method_exists($dto, "getTitle")) {
				if (!empty($dto->getTitle())) {
					$log = $log->withTitle($dto->getTitle());

					return $log;
				}
			}
			if ($dto instanceof IUserDTO) {
				if (!empty($dto->getLogin())) {
					$log = $log->withTitle($dto->getLogin());

					return $log;
				}
				if (!empty($dto->getEmail())) {
					$log = $log->withTitle($dto->getEmail());

					return $log;
				}
			}
		}

		return $log;
	}


	/**
	 * @inheritdoc
	 */
	public function exceptionLog(Throwable $ex, IOrigin $origin = null, IObject $object = null, IDataTransferObject $dto = null): ILog {
		$log = $this->originLog($origin, $object, $dto);

		$log->withLevel(ILog::LEVEL_EXCEPTION);

		$log->withMessage($ex->getMessage());

		return $log;
	}


	/**
	 * @inheritdoc
	 */
	public function fromDB(stdClass $data): ILog {
		$log = $this->log()->withLogId($data->log_id)->withTitle($data->title)->withMessage($data->message)
			->withDate(new ilDateTime($data->date, IL_CAL_DATETIME))->withLevel($data->level)->withAdditionalData(json_decode($data->additional_data))
			->withOriginId($data->origin_id)->withOriginObjectType($data->origin_object_type)->withObjectExtId($data->object_ext_id)
			->withObjectIliasId($data->object_ilias_id);

		return $log;
	}
}
