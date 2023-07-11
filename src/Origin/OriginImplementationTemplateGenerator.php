<?php

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;
use ilUtil;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;

/**
 * Class OriginImplementationTemplateGenerator
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginImplementationTemplateGenerator
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * Create the implementation class file from a given template at the correct location
     * based on the hub config.
     * @return bool False if file exists, true if created
     * @throws HubException
     */
    public function create(IOrigin $origin) : bool
    {
        $classFile = $this->getClassFilePath($origin);
        if ($this->classFileExists($origin)) {
            return false;
        }
        $path = $this->getPath($origin);
        if (!is_dir($path)) {
            try {
                if (!ilUtil::makeDirParents($path)) {
                    throw new HubException("Could not create directory: $path");
                }
            } catch (\Throwable $t) {
                throw new HubException("Could not create directory: $path");
            }
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

    public function classFileExists(IOrigin $origin) : bool
    {
        $classFile = $this->getClassFilePath($origin);

        return is_file($classFile);
    }

    public function getClassFilePath(IOrigin $origin) : string
    {
        $path = $this->getPath($origin);
        $className = $origin->getImplementationClassName();

        return $path . $className . '.php';
    }

    protected function getPath(IOrigin $origin) : string
    {
        $basePath = rtrim(ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH), '/') . '/';

        return $basePath . $origin->getObjectType() . '/';
    }
}
