<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Config;

/**
 * Class ActiveRecordConfigFactory
 *
 * @package    srag\ActiveRecordConfig\Hub2
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Do not use - only used for be compatible with old version
 */
final class ActiveRecordConfigFactory extends AbstractFactory
{
    /**
     * @deprecated
     */
    private static ?\srag\Plugins\Hub2\Config\ActiveRecordConfigFactory $instance = null;

    /**
     * @deprecated
     */
    public static function getInstance(): self
    {
        if (!self::$instance instanceof \srag\Plugins\Hub2\Config\ActiveRecordConfigFactory) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
