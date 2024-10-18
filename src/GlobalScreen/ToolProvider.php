<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\GlobalScreen;

use ILIAS\UI\Component\Legacy\Legacy;
use ILIAS\GlobalScreen\Scope\Tool\Provider\AbstractDynamicToolPluginProvider;
use ILIAS\GlobalScreen\ScreenContext\Stack\ContextCollection;
use ILIAS\GlobalScreen\ScreenContext\Stack\CalledContexts;
use ILIAS\GlobalScreen\Scope\Tool\Factory\Tool;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Remap\RemapForm;
use ILIAS\GlobalScreen\Helper\BasicAccessCheckClosures;

class ToolProvider extends AbstractDynamicToolPluginProvider
{
    public $if;

    public function getToolsForContextStack(CalledContexts $called_contexts): array
    {
        $ref_id = $called_contexts->current()->hasReferenceId() ? $called_contexts->current()->getReferenceId() : null;
        if ($ref_id === null) {
            return [];
        }

        // Currently for courses only
        $res = $this->dic->database()->query(
            'SELECT id, ilias_id FROM sr_hub2_course WHERE ilias_id = ' . $ref_id->toInt()
        )->fetchObject();

        if ((int) ($res->ilias_id ?? -1) !== $ref_id->toInt()) {
            return [];
        }

        /**
         * @var ARCourse $hub_course
         */
        $hub_course = ARCourse::find($res->id);

        return [
            $this->factory->tool($this->if->identifier('hub2'))
                          ->withVisibilityCallable(function (): bool {
                              $access = new BasicAccessCheckClosures();
                              return $access->hasAdministrationAccess()();
                          })
                          ->withTitle('HUB2')
                          ->withContentWrapper(function () use ($hub_course): Legacy {
                              $factory = $this->dic->ui()->factory();
                              $listing_data = [
                                  'Ext ID' => $hub_course->getExtId(),
                                  'Origin ID' => $hub_course->getOriginId(),
                                  'Last Delivery Date' => $hub_course->getDeliveryDate()->format('d.m.Y H:i:s'),
                                  'Last Processing Date' => $hub_course->getProcessedDate()->format('d.m.Y H:i:s'),
                              ];

                              $data = array_filter($hub_course->getData(), fn ($value, $key): bool => is_string($value), ARRAY_FILTER_USE_BOTH);

                              $listing_data = array_merge($listing_data, $data);
                              $listing = $factory->listing()->descriptive($listing_data);

                              $info_panel = $factory->panel()->secondary()->legacy(
                                  'Infos',
                                  $factory->legacy(
                                      $this->dic->ui()->renderer()->render($listing)
                                  )
                              );

                              $remap_form = new RemapForm(
                                  $this->dic->ctrl()->getFormActionByClass(
                                      [\ilUIPluginRouterGUI::class, \ilHub2RemapGUI::class]
                                  ),
                                  $hub_course,
                                  $this->dic->http()->request()->getUri()->__toString()
                              );

                              $form_panel = $factory->panel()->secondary()->legacy(
                                  'Remap',
                                  $factory->legacy(
                                      $this->dic->ui()->renderer()->render($remap_form->getForm())
                                  )
                              );

                              // Form

                              return $factory->legacy(
                                  $this->dic->ui()->renderer()->render([
                                      $form_panel,
                                      $info_panel,
                                  ])
                              );
                          })
        ];
    }

    public function isInterestedInContexts(): ContextCollection
    {
        return $this->context_collection->repository();
    }

}
