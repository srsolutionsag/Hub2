<?php

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;

/**
 * Class GlobalHook
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Timon Amstutz
 */
final class GlobalHook implements IGlobalHook {
    /**
     * @var IGlobalHook
     */
	protected $global_hook;

    /**
     * GlobalHook constructor.
     * @throws HubException
     */
	public function __construct(){
	    if(ArConfig::isGlobalHookActive()){
	        $this->global_hook = $this->instantiateGlobalHook();
        }
    }

    /**
     * @throws HubException
     */
    protected function instantiateGlobalHook(){
        if(!file_exists(ArConfig::getGlobalHookPath())){
            throw new HubException("File ".ArConfig::getGlobalHookClass()." doest not Exist");
        }
        include_once (ArConfig::getGlobalHookPath());
        $class_name = ArConfig::getGlobalHookClass();
        if(!class_exists($class_name)){
            throw new HubException("Class ".$class_name." not found. Note that namespaces need to be entered completely");

        }
        $global_hook = new $class_name();
        if(!($global_hook instanceof IGlobalHook)){
            throw new HubException("Class ".$class_name." is not an instance of BaseCustomViewGUI");
        }
        return $global_hook;
    }

    /**
     * @inheritdoc
     */
    public function beforeSync($active_orgins) :bool{
        if($this->global_hook){
            $this->global_hook->beforeSync($active_orgins);
        }
    }


    /**
     * @inheritdoc
     */
    public function afterSync($active_orgins){
        if($this->global_hook){
            $this->global_hook->afterSync($active_orgins);
        }
    }

    /**
     * @inheritdoc
     */
    public function handleExceptions($exceptions){
        if($this->global_hook){
            $this->global_hook->handleExceptions($exceptions);
        }
    }
}
