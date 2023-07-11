<?php

namespace srag\Plugins\Hub2\Sync\GlobalHook;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Log\ILog;

/**
 * Class GlobalHook
 * @package srag\Plugins\Hub2\Sync\GlobalHook
 * @author  Timon Amstutz
 */
final class GlobalHook implements IGlobalHook
{
    /**
     * @var self
     */
    protected static $instance;

    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @var IGlobalHook
     */
    protected $global_hook;

    /**
     * GlobalHook constructor
     * @throws HubException
     */
    private function __construct()
    {
        if (ArConfig::getField(ArConfig::KEY_GLOBAL_HOCK_ACTIVE)) {
            $this->global_hook = $this->instantiateGlobalHook();
        }
    }

    /**
     * @throws HubException
     */
    protected function instantiateGlobalHook()
    {
        $class_path = ArConfig::getField(ArConfig::KEY_GLOBAL_HOCK_PATH);
        if (!file_exists($class_path)) {
            throw new HubException("File " . $class_path . " doest not Exist");
        }
        require_once $class_path;

        $class_name = ArConfig::getField(ArConfig::KEY_GLOBAL_HOCK_CLASS);
        if (!class_exists($class_name)) {
            throw new HubException(
                "Class " . $class_name . " not found. Note that namespaces need to be entered completely"
            );
        }

        $global_hook = new $class_name();
        if (!($global_hook instanceof IGlobalHook)) {
            throw new HubException("Class " . $class_name . " is not an instance of BaseCustomViewGUI");
        }

        return $global_hook;
    }

    /**
     * @inheritdoc
     */
    public function beforeSync(array $active_orgins) : bool
    {
        if ($this->global_hook) {
            return $this->global_hook->beforeSync($active_orgins);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSync(array $active_orgins) : bool
    {
        if ($this->global_hook) {
            return $this->global_hook->afterSync($active_orgins);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function handleLog(ILog $log) : void
    {
        if ($this->global_hook) {
            $this->global_hook->handleLog($log);
        }
    }
}
