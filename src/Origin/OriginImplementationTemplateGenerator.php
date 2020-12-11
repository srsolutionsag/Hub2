<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use ilUtil;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class OriginImplementationTemplateGenerator
 *
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginImplementationTemplateGenerator
{

    use DICTrait;
    use Hub2Trait;
    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


    /**
     * OriginImplementationTemplateGenerator constructor
     */
    public function __construct()
    {

    }


    /**
     * Create the implementation class file from a given template at the correct location
     * based on the hub config.
     *
     * @param IOrigin $origin
     *
     * @return bool False if file exists, true if created
     * @throws HubException
     */
    public function create(IOrigin $origin)
    {
        $classFile = $this->getClassFilePath($origin);
        if ($this->classFileExists($origin)) {
            return false;
        }
        $path = $this->getPath($origin);
        if (!is_dir($path)) {
            if (!ilUtil::makeDirParents($path)) {
                throw new HubException("Could not create directory: $path");
            }
        }
        if (!is_writable($classFile)) {
            throw new HubException("Class file not writable: $classFile");
        }
        $template = file_get_contents(__DIR__ . '/../../templates/OriginImplementationTemplate.tpl');
        if ($template === false) {
            throw new HubException("Could not load template: $template");
        }
        $className = $origin->getImplementationClassName();
        $namespace = $origin->getImplementationNamespace();
        $content = str_replace('[[CLASSNAME]]', $className, $template);
        $content = str_replace('[[NAMESPACE]]', $namespace, $content);
        $result = file_put_contents($classFile, $content);
        if ($result === false) {
            throw new HubException("Unable to create template for origin implementation");
        }

        return true;
    }


    public function classFileExists(IOrigin $origin)
    {
        $classFile = $this->getClassFilePath($origin);

        return is_file($classFile);
    }


    /**
     * @param IOrigin $origin
     *
     * @return string
     */
    public function getClassFilePath(IOrigin $origin)
    {
        $path = $this->getPath($origin);
        $className = $origin->getImplementationClassName();
        $classFile = $path . $className . '.php';

        return $classFile;
    }


    /**
     * @param IOrigin $origin
     *
     * @return string
     */
    protected function getPath(IOrigin $origin)
    {
        $basePath = rtrim(ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH), '/') . '/';
        $path = $basePath . $origin->getObjectType() . '/';

        return $path;
    }
}
