<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;
use ILIAS\Filesystem\Stream\Streams;
use srag\Plugins\Hub2\Exception\HubException;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ilHub2DataGUI extends ilHub2DispatchableBaseGUI
{
    public function checkAccess(): void
    {
        // TODO: Implement checkAccess() method.
    }

    public function index(): void
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $this->main_tpl->setContent($table->getHTML());
    }

    protected function applyFilter(): void
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        try {
            $table->resetOffset();
            $table->writeFilterToSession();
            $table->storeNavParameter();
        } catch (Throwable $t) {
            $table->resetFilter();
            // Ignore
        }

        $this->ctrl->redirect($this, self::CMD_INDEX);
    }

    protected function resetFilter(): void
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $table->resetFilter();
        $table->resetOffset();
        $table->storeNavParameter();

        $this->ctrl->redirect($this, self::CMD_INDEX);
    }

    public function getActiveSubTab(): ?string
    {
        return 'subtab_data';
    }

    protected function renderData(): void
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
