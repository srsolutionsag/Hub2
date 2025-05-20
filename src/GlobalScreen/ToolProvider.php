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
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Remap\RemapForm;
use ILIAS\GlobalScreen\Helper\BasicAccessCheckClosures;
use ILIAS\GlobalScreen\Identification\IdentificationProviderInterface;
use ILIAS\GlobalScreen\ScreenContext\ScreenContext;
use srag\Plugins\Hub2\Object\ARObject;
use srag\Plugins\Hub2\Object\User\ARUser;

class ToolProvider extends AbstractDynamicToolPluginProvider
{
    protected IdentificationProviderInterface $if;

    protected function resolveObject(ScreenContext $context): ?ARObject
    {
        $ref_id = $this->dic->http()->request()->getQueryParams()['ref_id'] ?? null;

        if ($ref_id === null) {
            return null;
        }

        // determine type
        $type = $this->dic->database()->queryF(
            'SELECT o.type FROM object_data o JOIN object_reference r on r.obj_id = o.obj_id WHERE r.ref_id = %s',
            ['integer'],
            [(int) $ref_id]
        )->fetchObject();

        $hub_object_id = null;
        $class = null;

        switch ($type->type ?? null) {
            case 'usrf':
                $usr_id = $this->dic->http()->request()->getQueryParams()['obj_id'] ?? null;
                if ($usr_id === null) {
                    return null;
                }

                $res = $this->dic->database()->queryF(
                    'SELECT id, ilias_id FROM sr_hub2_user WHERE ilias_id = %s',
                    ['integer'],
                    [(int) $usr_id]
                )->fetchObject();
                $hub_object_id = $res->id ?? null;
                $class = ARUser::class;
                break;
            case 'crs':
                $res = $this->dic->database()->queryF(
                    'SELECT id, ilias_id FROM sr_hub2_course WHERE ilias_id = %s',
                    ['integer'],
                    [(int) $ref_id]
                )->fetchObject();

                $hub_object_id = $res->id ?? null;
                $class = ARCourse::class;
                break;
            default:
                return null;
        }

        if ($hub_object_id === null || $class === null) {
            return null;
        }

        return $class::find($hub_object_id);
    }

    public function getToolsForContextStack(CalledContexts $called_contexts): array
    {
        $object = $this->resolveObject($called_contexts->current());
        if ($object === null) {
            return [];
        }

        return [
            $this->factory->tool($this->if->identifier('hub2'))
                          ->withVisibilityCallable(function (): bool {
                              $access = new BasicAccessCheckClosures();
                              return $access->hasAdministrationAccess()();
                          })
                          ->withTitle('HUB2')
                          ->withContentWrapper(function () use ($object): Legacy {
                              $factory = $this->dic->ui()->factory();
                              $listing_data = [
                                  'Ext ID' => $object->getExtId(),
                                  'Origin ID' => $object->getOriginId(),
                                  'Last Delivery Date' => $object->getDeliveryDate()->format('d.m.Y H:i:s'),
                                  'Last Processing Date' => $object->getProcessedDate()->format('d.m.Y H:i:s'),
                              ];

                              $data = array_filter(
                                  $object->getData(),
                                  static fn ($value, $key): bool => is_string($value),
                                  ARRAY_FILTER_USE_BOTH
                              );

                              $listing_data = array_merge($listing_data, $data);
                              $listing = $factory->listing()->descriptive(
                                  array_map(
                                      static fn ($value): string => (string) $value,
                                      $listing_data
                                  )
                              );

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
                                  $object,
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
        return $this->context_collection->repository()->administration();
    }

}
