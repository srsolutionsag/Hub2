<?php

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Object\Group\GroupRepository;
use srag\Plugins\Hub2\Object\Session\SessionRepository;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class ObjectRepository
 *
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ObjectRepository implements IObjectRepository {

	use DICTrait;
	use Hub2Trait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var IOrigin
	 */
	protected $origin;
	/**
	 * @var array
	 */
	protected static $classmap = [];


	/**
	 * ObjectRepository constructor
	 *
	 * @param IOrigin $origin
	 */
	public function __construct(IOrigin $origin) {
		$this->origin = $origin;
	}


	/**
	 * @inheritdoc
	 */
	public function all(): array {
		$class = $this->getClass();

		/** @var ActiveRecord $class */
		return $class::where([ 'origin_id' => $this->origin->getId() ])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function getByStatus(int $status): array {
		$class = $this->getClass();

		/** @var ActiveRecord $class */
		return $class::where([
			'origin_id' => $this->origin->getId(),
			'status' => (int)$status,
		])->get();
	}


	/**
	 * @inheritdoc
	 */
	public function getToDeleteByParentScope(array $ext_ids, array $parent_ext_ids): array {
		$glue = self::GLUE;
		$class = $this->getClass();

		if (count($parent_ext_ids) > 0) {
			if (count($ext_ids) > 0) {
				$existing_ext_id_query = " AND ext_id NOT IN ('" . implode("','", $ext_ids) . "') ";
			}
			if ($this instanceof GroupRepository || $this instanceof SessionRepository) {
				$parent_scope_query = " AND (";
				foreach ($parent_ext_ids as $parent_ext_id) {
					$parent_scope_query .= " data LIKE '%\"parentId\":\"$parent_ext_id\"%' OR";
				}
				$parent_scope_query = rtrim($parent_scope_query, "OR");
				$parent_scope_query .= ")";
			} else {
				$parent_scope_query = " AND SUBSTRING_INDEX(ext_id,'" . $glue . "',1) IN ('" . implode("','", $parent_ext_ids) . "') ";
			}

			return $class::where("origin_id = " . $this->origin->getId() . " AND status IN ('" . implode("','", [
					IObject::STATUS_CREATED,
					IObject::STATUS_UPDATED,
					IObject::STATUS_IGNORED
				]) . "') " . $existing_ext_id_query . $parent_scope_query

			)->get();
		}

		return [];
	}


	/**
	 * @inheritdoc
	 */
	public function getToDelete(array $ext_ids): array {
		$class = $this->getClass();

		if (count($ext_ids) > 0) {
			/** @var ActiveRecord $class */
			return $class::where([
				'origin_id' => $this->origin->getId(),
				// We only can transmit from final states CREATED and UPDATED to TO_DELETE
				// E.g. not from OUTDATED or IGNORED
				'status' => [ IObject::STATUS_CREATED, IObject::STATUS_UPDATED, IObject::STATUS_IGNORED ],
				'ext_id' => $ext_ids,
			], [ 'origin_id' => '=', 'status' => 'IN', 'ext_id' => 'NOT IN' ])->get();
		} else {
			/** @var ActiveRecord $class */
			return $class::where([
				'origin_id' => $this->origin->getId(),
				// We only can transmit from final states CREATED and UPDATED to TO_DELETE
				// E.g. not from OUTDATED or IGNORED
				'status' => [ IObject::STATUS_CREATED, IObject::STATUS_UPDATED, IObject::STATUS_IGNORED ],
			], [ 'origin_id' => '=', 'status' => 'IN' ])->get();
		}
	}


	/**
	 * @inheritdoc
	 */
	public function count(): int {
		$class = $this->getClass();

		/** @var ActiveRecord $class */
		return $class::where([ 'origin_id' => $this->origin->getId() ])->count();
	}


	/**
	 * Returns the active record class name for the origin
	 *
	 * @return string
	 */
	protected function getClass() {
		$object_type = $this->origin->getObjectType();

		if (isset(self::$classmap[$object_type])) {
			return self::$classmap[$object_type];
		}

		$ucfirst = ucfirst($object_type);
		self::$classmap[$object_type] = "srag\\Plugins\\Hub2\\Object\\" . $ucfirst . "\\AR" . $ucfirst;

		return self::$classmap[$object_type];
	}
}
