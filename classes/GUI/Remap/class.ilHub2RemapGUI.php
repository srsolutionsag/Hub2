<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use ILIAS\UI\Factory;
use ILIAS\UI\Renderer;
use srag\Plugins\Hub2\Log\LogsTable;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use srag\Plugins\Hub2\UI\Log\LogsTableGUI;
use srag\Plugins\Hub2\Log\LogDBRepository;
use ILIAS\UI\Component\Table\PresentationRow;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Translator;
use srag\Plugins\Hub2\Jobs\Log\DeleteOldLogsJob;
use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Log\LogRepository;
use srag\Plugins\Hub2\Remap\RemapForm;
use ILIAS\GlobalScreen\Helper\BasicAccessCheckClosures;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Object\Category\ARCategory;

/**
 * @author                 Fabian Schmid <fabian@sr.solutions>
 *
 * @ilCtrl_isCalledBy      ilHub2RemapGUI: ilUIPluginRouterGUI
 */
class ilHub2RemapGUI extends ilHub2DispatchableBaseGUI
{
    public const SUBTAB_REMAP = 'subtab_remap';

    /**
     * @readonly
     */
    private Renderer $ui_renderer;
    /**
     * @readonly
     */
    private Factory $ui_factory;

    public function __construct()
    {
        parent::__construct();
        global $DIC;
        $this->ui_renderer = $DIC->ui()->renderer();
        $this->ui_factory = $DIC->ui()->factory();
    }

    public function getActiveSubTab(): ?string
    {
        return null;
    }

    public function checkAccess(): void
    {
        $access = new BasicAccessCheckClosures();
        if (!$access->hasAdministrationAccess()) {
            throw new HubException("Access denied");
        }
    }

    public function index(): void
    {
        $remap_form = new RemapForm(
            $this->ctrl->getFormActionByClass([\ilUIPluginRouterGUI::class, \ilHub2RemapGUI::class])
        );

        $data = $remap_form->getData($this->http->request());

        $redirect_after_save = $data['redirect_after_save'] ?? null;
        $ar_object_id = $data['ar_object_id'] ?? null;
        $ar_type = $data['ar_type'] ?? null;
        $new_ilias_id = $data['new_ilias_id'] ?? null;
        $current_ilias_id = $data['current_ilias_id'] ?? null;

        // FInd AR Object
        if (!class_exists($ar_type)) {
            throw new HubException("AR Object not found");
        }
        /** @var ARCourse|ARCategory|ARUser $ar_object */
        $ar_object = $ar_type::find($ar_object_id);
        if ($ar_object === null) {
            throw new HubException("AR Object not found");
        }

        switch ($ar_type) {
            case ARCourse::class:
                $is_ref_id = true;
                $target_type_matching = 'crs';
                break;
            case ARUser::class:
                $is_ref_id = false;
                $target_type_matching = 'usr';
                break;
            case ARCategory::class:
                $is_ref_id = true;
                $target_type_matching = 'cat';
                break;
            default:
                throw new HubException("Unknown or not implemented AR Object Type $ar_type");
        }

        $current_type = ilObject2::_lookupType($current_ilias_id, $is_ref_id);
        $new_type = ilObject2::_lookupType($new_ilias_id, $is_ref_id);
        if ($current_type !== $target_type_matching || $new_type !== $target_type_matching || $current_type !== $new_type) {
            throw new HubException("Object Types do not match");
        }

        $ar_object->setILIASId($new_ilias_id);
        $ar_object->update();

        if ($redirect_after_save !== null) {
            $this->main_tpl->setOnScreenMessage(
                'success',
                'Object remapped successfully. You may new delete the old object. It\'s recommended to perform a forced sync after remappings.',
                true
            );
            $this->ctrl->redirectToURL($redirect_after_save);
        }

        $this->main_tpl->setContent(
            '<pre>' . print_r($data, true) . '</pre>'
        );
    }
}
