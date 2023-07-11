<?php

//namespace srag\Plugins\Hub2\UI\Data;

use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use ILIAS\Filesystem\Stream\Streams;

/**
 * Class DataGUI
 * @package srag\Plugins\Hub2\UI\Data
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2DataGUI extends hub2MainGUI
{
    /**
     *
     */
    public function executeCommand()
    {
        $this->initTabs();
        $cmd = $this->ctrl->getCmd(self::CMD_INDEX);
        $this->{$cmd}();
    }

    /**
     *
     */
    protected function index()
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $this->tpl->setContent($table->getHTML());
    }

    /**
     *
     */
    protected function applyFilter()
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $table->writeFilterToSession();
        $table->resetOffset();
        //self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
        $this->index(); // Fix reset offset
    }

    /**
     *
     */
    protected function resetFilter()
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $table->resetFilter();
        $table->resetOffset();
        //self::dic()->ctrl()->redirect($this, self::CMD_INDEX);
        $this->index(); // Fix reset offset
    }

    /**
     *
     */
    protected function initTabs()
    {
        $this->tabs->activateSubTab(hub2ConfigOriginsGUI::SUBTAB_DATA);
    }

    /**
     *
     */
    protected function renderData()
    {
        $ext_id = $this->http->request()->getQueryParams()[DataTableGUI::F_EXT_ID];
        $origin_id = $this->http->request()->getQueryParams()[DataTableGUI::F_ORIGIN_ID];

        $origin_factory = new OriginFactory();
        $object_factory = new ObjectFactory($origin_factory->getById($origin_id));

        $object = $object_factory->undefined($ext_id);

        $factory = $this->ui->factory();

        /*$properties = array_merge([
            "period" => $object->getPeriod(),
            "delivery_date" => $object->getDeliveryDate()->format(DATE_ATOM),
            "processed_date" => $object->getProcessedDate()->format(DATE_ATOM),
            "ilias_id" => $object->getILIASId(),
            "status" => $object->getStatus(),
        ], $object->getData());*/
        $properties = $object->getData(); // Only dto properties

        if ($object instanceof IMetadataAwareObject) {
            foreach ($object->getMetaData() as $metadata) {
                $properties["metadata." . $metadata->getIdentifier()] = $metadata->getValue();
            }
        }

        if ($object instanceof ITaxonomyAwareObject) {
            foreach ($object->getTaxonomies() as $taxonomy) {
                $properties["taxonomy." . $taxonomy->getTitle()] = $taxonomy->getNodeTitlesAsArray();
            }
        }

        $filtered = [];
        foreach ($properties as $key => $property) {
            if (!is_null($property)) {
                $filtered[$key] = is_array($property) ? implode(',', $property) : (string) $property;
            }
            if ($property === '') {
                $filtered[$key] = "&nbsp;";
            }
        }

        ksort($filtered);

        $data_table = $factory->listing()->descriptive($filtered);

        $modal = $factory->modal()->roundtrip(
            $this->plugin->txt("data_table_header_data")
            . "<br>" . vsprintf($this->plugin->txt("data_table_hash"), [$object->getHashCode()]), //, "",
            $data_table
        )->withCancelButtonLabel("close");

        $this->http->saveResponse(
            $this->http->response()->withBody(
                Streams::ofString($this->ui->renderer()->renderAsync($modal))
            )
        );
        $this->http->sendResponse();
        $this->http->close();
    }
}
