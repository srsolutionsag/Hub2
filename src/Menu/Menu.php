<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Menu;

use hub2MainGUI;
use ilAdministrationGUI;
use ilHub2ConfigGUI;
use ilHub2Plugin;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use ILIAS\MainMenu\Provider\StandardTopItemsProvider;
use ilObjComponentSettingsGUI;
use srag\Plugins\Hub2\Config\ArConfig;

/**
 * Class Menu
 *
 * @package srag\Plugins\Hub2\Menu
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @since   ILIAS 5.4
 */
class Menu extends AbstractStaticPluginMainMenuProvider
{
    public function getStaticTopItems(): array
    {
        return [];
    }



    public function getStaticSubItems(): array
    {
        $obj_id = array_key_first(\ilObject2::_getObjectsByType('cmps') ?? []);
        if (!$obj_id) {
            return [];
        }
        if (class_exists(StandardTopItemsProvider::class)) {
            $s = StandardTopItemsProvider::getInstance();
            $parent = $s->getAdministrationIdentification();
        } else {
            global $DIC;
            $p = new \ilAdmGlobalScreenProvider($DIC);
            $parent = $p->getTopItem();
        }

        $ref_id = array_key_first(\ilObject2::_getAllReferences($obj_id) ?? []);
        $this->dic->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "ref_id", $ref_id);
        $this->dic->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "ctype", 'Service');
        $this->dic->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "cname", "Cron");
        $this->dic->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "slot_id", "crnhk");
        $this->dic->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "pname", ilHub2Plugin::PLUGIN_NAME);
        $this->dic->ctrl()->setParameterByClass(ilHub2ConfigGUI::class, "plugin_id", ilHub2Plugin::PLUGIN_ID);

        $action = $this->dic->ctrl()->getLinkTargetByClass(
            [
                ilAdministrationGUI::class,
                ilObjComponentSettingsGUI::class,
                \ilHub2ConfigGUI::class
            ],
            \ilHub2DispatchableGUI::CMD_INDEX
        );
        return [
            $this->symbol(
                $this->mainmenu->link($this->if->identifier(ilHub2Plugin::PLUGIN_ID . "_configuration"))
                               ->withParent($parent)
                               ->withTitle('HUB Sync')
                               ->withAction($action)
                               ->withAvailableCallable(
                                   fn (): bool => $this->plugin->isActive()
                               )
                               ->withVisibilityCallable(
                                   function (): bool {
                                       $config = ArConfig::find(ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS);
                                       if (null !== $config) {
                                           // replace outer brackets from array string and convert values to int
                                           $roles = preg_replace("/[\[\]']+/", '', $config->getValue());
                                           $roles = array_map('intval', explode(',', $roles));
                                           // add at least default admin role id (doesn't matter if it's repeatedly)
                                           $roles[] = 2;
                                       } else {
                                           $roles = [2];
                                       }

                                       return $this->dic->rbac()->review()->isAssignedToAtLeastOneGivenRole(
                                           $this->dic->user()->getId(),
                                           $roles
                                       );
                                   }
                               )
            ),
        ];
    }

    protected function symbol(AbstractBaseItem $entry): AbstractBaseItem
    {
        return $entry->withSymbol(
            $this->dic->ui()->factory()->symbol()->icon()->custom(
                './Customizing/global/plugins/Services/Cron/CronHook/Hub2/templates/hub2_icon_outlined.svg',
                ilHub2Plugin::PLUGIN_NAME
            )
        );
    }
}
