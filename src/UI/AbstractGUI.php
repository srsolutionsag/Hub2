<?php

namespace srag\Plugins\Hub2\UI;

use ilHub2Plugin;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class AbstractGUI
 *
 * @package    srag\Plugins\Hub2\UI
 * @author     Fabian Schmid <fs@studer-raimann.ch>
 * @deprecated TODO: ???
 */
abstract class AbstractGUI
{

    use DICTrait;
    use Hub2Trait;
    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;


    /**
     * AbstractGUI constructor
     */
    public function __construct()
    {

    }
}
