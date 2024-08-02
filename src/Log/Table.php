<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Log;

use ILIAS\HTTP\Services;
use ILIAS\UI\Component\Input\Container\Filter\Standard;
use ILIAS\UI\Factory;
use ILIAS\UI\Component\Table\DataRetrieval;
use ILIAS\Data\Order;
use ILIAS\UI\Component\Table\DataRowBuilder;
use ILIAS\Data\Range;
use Generator;
use ILIAS\UI\Component\Table\Data;
use srag\Plugins\Hub2\Translator;
use ILIAS\Data\DateFormat\DateFormat;
use srag\Plugins\Hub2\Shortlink\ObjectLinkFactory;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Table implements DataRetrieval
{
    private Translator $translator;
    private LogRepository $repository;
    /**
     * @readonly
     */
    private Factory $ui_factory;
    /**
     * @readonly
     */
    private \ILIAS\Data\Factory $data_factory;
    /**
     * @readonly
     */
    private Services $http;
    /**
     * @readonly
     */
    private \ilCtrlInterface $ctrl;
    /**
     * @readonly
     */
    private \ilUIFilterService $filter_service;
    /**
     * @readonly
     */
    private \ilDBInterface $db;
    private ?array $filter_data = null;
    /**
     * @readonly
     */
    private ObjectLinkFactory $object_link;

    public function __construct(
        LogRepository $repository,
        Translator $translator
    ) {
        global $DIC;
        $this->translator = $translator;
        $this->repository = $repository;
        $this->data_factory = new \ILIAS\Data\Factory();
        $this->ui_factory = $DIC->ui()->factory();
        $this->http = $DIC->http();
        $this->ctrl = $DIC->ctrl();
        $this->filter_service = $DIC->uiService()->filter();
        $this->db = $DIC->database();
        $this->object_link = new ObjectLinkFactory();
    }

    protected function getFilter(string $target): Standard
    {
        $res = $this->db->query(
            'SELECT id, title FROM sr_hub2_origin  ORDER BY title ASC'
        );
        $origin_ids = [];
        while ($row = $this->db->fetchAssoc($res)) {
            $origin_ids[$row['id']] = $row['title'];
        }

        return $this->filter_service->standard(
            'hub2_logs_filter',
            $target,
            [
                'date' => $this->ui_factory->input()->field()->text(
                    $this->translator->txt('logs_date')
                )->withValue(date('Y-m-d')),
                'origin_id' => $this->ui_factory->input()->field()->select(
                    $this->translator->txt('logs_origin_id'),
                    $origin_ids
                ),
                'object_ext_id' => $this->ui_factory->input()->field()->text(
                    $this->translator->txt('logs_object_ext_id')
                ),
                'object_ilias_id' => $this->ui_factory->input()->field()->text(
                    $this->translator->txt('logs_object_ilias_id')
                ),
            ],
            [
                'date' => true,
                'origin_id' => true,
                'object_ext_id' => true,
                'object_ilias_id' => false,
            ],
            true,
            true
        );
    }

    public function getWithFilters(string $target): array
    {
        $filter = $this->getFilter($target);
        $this->filter_data = $this->filter_service->getData($filter);
        return [
            $filter,
            $this->get()
        ];
    }

    public function get(): Data
    {
        return $this->ui_factory
            ->table()
            ->data(
                $this->translator->txt('logs'),
                [
                    'date' => $this->ui_factory->table()->column()->date(
                        $this->translator->txt('data_table_header_processed_date'),
                        $this->data_factory->dateFormat()->withTime24(
                            $this->data_factory->dateFormat()->standard()
                        )
                    )->withIsSortable(true),
                    'origin_id' => $this->ui_factory->table()->column()->number(
                        $this->translator->txt('logs_origin_id')
                    )->withIsSortable(false),
                    'origin_object_type' => $this->ui_factory->table()->column()->text(
                        $this->translator->txt('logs_origin_object_type')
                    )->withIsSortable(false),
                    'status' => $this->ui_factory->table()->column()->text($this->translator->txt('logs_status')),
                    'object_ext_id' => $this->ui_factory->table()->column()->text(
                        $this->translator->txt('data_table_header_ext_id')
                    ),
                    'object_ilias_id' => $this->ui_factory->table()->column()->link(
                        $this->translator->txt('data_table_header_ilias_id')
                    ),
                    'level' => $this->ui_factory->table()->column()->text($this->translator->txt('logs_level')),
                    'additional_data' => $this->ui_factory->table()->column()->text(
                        $this->translator->txt('logs_additional_data')
                    )->withIsSortable(false),
                ],
                $this
            )
            ->withOrder($this->data_factory->order('date', 'DESC'))
            ->withRange($this->data_factory->range(0, 50))
            ->withRequest($this->http->request());
    }

    public function getRows(
        DataRowBuilder $row_builder,
        array $visible_column_ids,
        Range $range,
        Order $order,
        ?array $filter_data,
        ?array $additional_parameters
    ): Generator {
        $order_field = key($order->get()) ?? 'date';
        $order_direction = $order->get()[$order_field] ?? 'DESC';
        $items = $this->repository->getFiltered(
            $this->filter_data,
            $range->getStart(),
            $range->getEnd(),
            $order_field,
            $order_direction
        );
        foreach ($items as $item) {
            $ilias_id = $item['object_ilias_id'] ?? '';
            $link = $this->object_link->findByExtId($item['object_ext_id'] ?? '');

            yield $row_builder->buildDataRow(
                $item['log_id'],
                [
                    'date' => new \DateTimeImmutable($item['date']),
                    'origin_id' => (int) $item['origin_id'],
                    'origin_object_type' => $item['origin_object_type'],
                    'status' => $this->translator->txt('log_status_' . $item['status']),
                    'object_ext_id' => $item['object_ext_id'],
                    'object_ilias_id' => $this->ui_factory->link()->standard(
                        $ilias_id,
                        $link->getAccessGrantedInternalLink()
                    )->withOpenInNewViewport(true),
                    'level' => $this->translator->txt('logs_level_' . $item['level']),
                    'additional_data' => $this->printAdditioanData($item)
                ]
            );
        }
    }

    private function printAdditioanData(array $item): string
    {
        $additional_data = json_decode($item['additional_data'] ?? '', true) ?? [];
        $additional_data = array_map($this->unserializer(), $additional_data);

        return $this->nestedImploder($additional_data);
    }

    private function nestedImploder(array $data): string
    {
        $result = '';
        foreach ($data as $key => $value) {
            if (!is_numeric($key)) {
                $result .= $key . ': ';
            } else {
                $result .= ' ';
            }
            if (is_array($value)) {
                $result .= $this->nestedImploder($value);
            } else {
                $result .= $value;
            }
        }
        return $result;
    }

    protected function unserializer(): \Closure
    {
        static $unserializer;
        if ($unserializer === null) {
            $unserializer = function ($value) {
                try {
                    $unserialized = unserialize($value, ['allowed_classes' => true]);
                } catch (\Throwable $ex) {
                    return $value;
                }
                if ($unserialized !== false && $unserialized instanceof \Serializable) {
                    if ($json = json_decode($unserialized->serialize(), true)) {
                        return $json;
                    }
                    return $unserialized->serialize();
                }
                return $value;
            };
        }

        return $unserializer;
    }

    public function getTotalRowCount(?array $filter_data, ?array $additional_parameters): ?int
    {
        return $this->repository->total($this->filter_data);
    }

}
