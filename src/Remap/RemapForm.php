<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Remap;

use ILIAS\UI\Factory;
use ILIAS\UI\Component\Input\Container\Form\Standard;
use srag\Plugins\Hub2\Object\ARObject;
use Psr\Http\Message\RequestInterface;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class RemapForm
{
    /**
     * @readonly
     */
    private ?ARObject $object = null;
    /**
     * @readonly
     */
    private ?string $redirect_after_save = null;
    /**
     * @var string
     */
    public $target;
    public const F_REDIRECT_AFTER_SAVE = 'redirect_after_save';
    public const F_AR_OBJECT_ID = 'ar_object_id';
    public const F_AR_TYPE = 'ar_type';
    public const F_NEW_ILIAS_ID = 'new_ilias_id';
    public const F_CURRENT_ILIAS_ID = 'current_ilias_id';
    /**
     * @readonly
     */
    private Factory $ui_factory;

    public function __construct(
        string $target,
        ?ARObject $object = null,
        ?string $redirect_after_save = null
    ) {
        $this->object = $object;
        $this->redirect_after_save = $redirect_after_save;
        global $DIC;
        $this->target = $target;
        $this->ui_factory = $DIC->ui()->factory();
    }

    public function getForm(?RequestInterface $request = null): Standard
    {
        $form = $this->ui_factory->input()->container()->form()->standard(
            $this->target,
            $this->getInputs()
        );

        if ($request !== null) {
            return $form->withRequest($request);
        }

        return $form;
    }

    private function getInputs(): array
    {
        $inputs = [];

        // Redirect After
        $redirect_after = $this->ui_factory
            ->input()
            ->field()
            ->hidden(self::F_REDIRECT_AFTER_SAVE);

        if ($this->redirect_after_save !== null) {
            $redirect_after = $redirect_after->withValue($this->redirect_after_save);
        }
        $inputs[self::F_REDIRECT_AFTER_SAVE] = $redirect_after;

        // Ar Object ID
        $ar_object_id = $this->ui_factory
            ->input()
            ->field()
            ->hidden(self::F_AR_OBJECT_ID);

        if ($this->object !== null) {
            $ar_object_id = $ar_object_id->withValue($this->object->getId());
        }

        $inputs[self::F_AR_OBJECT_ID] = $ar_object_id;

        // AR Type
        $ar_type = $this->ui_factory
            ->input()
            ->field()
            ->hidden(self::F_AR_TYPE);

        if ($this->object !== null) {
            $ar_type = $ar_type->withValue(get_class($this->object));
        }

        $inputs[self::F_AR_TYPE] = $ar_type;

        // New ILIAS ID
        $new_ilias_id = $this->ui_factory
            ->input()
            ->field()
            ->numeric('New ILIAS-ID', 'May be a ILIAS Ref-ID or the ILIAS Object-ID.')
            ->withAdditionalOnLoadCode(fn (string $id): string => "let input = document.getElementById('$id');
            input.parentElement.style.width = '100%';
            input.style.width = '100%';
            let label = input.parentElement.parentElement.querySelector('label');
            label.style.width = '100%';
            ");

        if ($this->object !== null) {
            $new_ilias_id = $new_ilias_id->withValue($this->object->getILIASId());
        }

        $inputs[self::F_NEW_ILIAS_ID] = $new_ilias_id;

        // Current ILIAS ID
        $current_ilias_id = $this->ui_factory
            ->input()
            ->field()
            ->hidden(self::F_CURRENT_ILIAS_ID);

        if ($this->object !== null) {
            $current_ilias_id = $current_ilias_id->withValue($this->object->getILIASId());
        }

        $inputs[self::F_CURRENT_ILIAS_ID] = $current_ilias_id;

        return $inputs;
    }

    public function getData(RequestInterface $request): array
    {
        return $this->getForm($request)->getData();
    }
}
