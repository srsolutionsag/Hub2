<?php

namespace srag\Plugins\Hub2\Origin;

use ActiveRecord;
use ilHub2Plugin;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;

/**
 * Class OriginFactory
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFactory implements IOriginFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var \ilDBInterface
     */
    private $db;

    /**
     *
     */
    public function __construct()
    {
        global $DIC;
        $this->db = $DIC->database();
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $sql = 'SELECT object_type FROM ' . AROrigin::TABLE_NAME . ' WHERE id = %s';
        $set = $this->db->queryF($sql, ['integer'], [$id]);
        $type = $this->db->fetchObject($set)->object_type;
        if (!$type) {
            //throw new HubException("Can not get type of origin id (probably deleted): ".$id);
            return null;
        }
        $class = $this->getClass($type);

        return $class::find($id);
    }

    /**
     * @inheritdoc
     */
    public function createByType(string $type) : IOrigin
    {
        $class = $this->getClass($type);

        return new $class();
    }

    /**
     * @inheritdoc
     */
    public function getAllActive() : array
    {
        $sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' WHERE active = %s ORDER BY sort';
        $set = $this->db->queryF($sql, ['integer'], [1]);
        $origins = [];
        while ($data = $this->db->fetchObject($set)) {
            $origins[] = $this->getById($data->id);
        }

        return $origins;
    }

    /**
     * @inheritdoc
     */
    public function getAll() : array
    {
        $origins = [];

        $sql = 'SELECT id FROM ' . AROrigin::TABLE_NAME . ' ORDER BY sort';
        $set = $this->db->query($sql);
        while ($data = $this->db->fetchObject($set)) {
            $origins[] = $this->getById($data->id);
        }

        return $origins;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getClass($type)
    {
        $ucfirst = ucfirst($type);

        return "srag\\Plugins\\Hub2\\Origin\\{$ucfirst}\\AR{$ucfirst}Origin";
    }

    public function delete(int $origin_id) : void/*: void*/
    {
        /**
         * @var ActiveRecord $object
         */

        foreach (DataTableGUI::$classes as $class) {
            foreach ($class::where(["origin_id" => $origin_id])->get() as $object) {
                $object->delete();
            }
        }

        $object = $this->getById($origin_id);
        $object->delete();
    }
}
