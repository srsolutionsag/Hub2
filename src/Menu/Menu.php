<?php

namespace srag\Plugins\Hub2\Menu;

use hub2ConfigOriginsGUI;
use hub2MainGUI;
use ilAdministrationGUI;
use ilHub2ConfigGUI;
use ilHub2Plugin;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ILIAS\MainMenu\Provider\StandardTopItemsProvider;
use ILIAS\UI\Component\Symbol\Icon\Standard;
use ilObjComponentSettingsGUI;
use srag\DIC\Hub2\DICTrait;
use srag\Plugins\Hub2\Utils\Hub2Trait;

/**
 * Class Menu
 * @package srag\Plugins\Hub2\Menu
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @since   ILIAS 5.4
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{

    use DICTrait;
    use Hub2Trait;

    const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function getStaticTopItems() : array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getStaticSubItems() : array
    {
        //polyfill
        if (!function_exists('array_key_first')) {
            function array_key_first(array $arr)
            {
                foreach ($arr as $key => $unused) {
                    return $key;
                }
                return null;
            }
        }

        $obj_id = array_key_first(\ilObject2::_getObjectsByType('cmps') ?? []);
        if (!$obj_id) {
            return [];
        }
        $s      = StandardTopItemsProvider::getInstance();
        $parent = $s->getAdministrationIdentification();
        $ref_id = array_key_first(\ilObject2::_getAllReferences($obj_id) ?? []);

        self::dic()->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "ref_id", $ref_id);
        self::dic()->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "ctype", IL_COMP_SERVICE);
        self::dic()->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "cname", "Cron");
        self::dic()->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "slot_id", "crnhk");
        self::dic()->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "pname", ilHub2Plugin::PLUGIN_NAME);

        return [
            $this->symbol($this->mainmenu->link($this->if->identifier(ilHub2Plugin::PLUGIN_ID . "_configuration"))
                                         ->withParent($parent)
                                         ->withTitle(ilHub2Plugin::PLUGIN_NAME)
                                         ->withAction(self::dic()->ctrl()->getLinkTargetByClass([
                                             ilAdministrationGUI::class,
                                             ilObjComponentSettingsGUI::class,
                                             ilHub2ConfigGUI::class,
                                             hub2MainGUI::class,
                                             hub2ConfigOriginsGUI::class
                                         ], hub2ConfigOriginsGUI::CMD_INDEX))
                                         ->withAvailableCallable(function () : bool {
                                             return self::plugin()->getPluginObject()->isActive();
                                         })
                                         ->withVisibilityCallable(function () : bool {
                                             return self::dic()->rbacreview()->isAssigned(self::dic()->user()->getId(), 2); // Default admin role
                                         }))
        ];
    }

    /**
     * @param AbstractBaseItem $entry
     * @return AbstractBaseItem
     */
    protected function symbol(AbstractBaseItem $entry) : AbstractBaseItem
    {
        if (self::version()->is6()) {
            $entry = $entry->withSymbol(self::dic()->ui()->factory()->symbol()->icon()->standard(Standard::RFIL, ilHub2Plugin::PLUGIN_NAME)->withIsOutlined(true));
        }

        return $entry;
    }
}
