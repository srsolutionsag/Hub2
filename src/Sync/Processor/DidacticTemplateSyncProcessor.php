<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use ilObject;
use srag\Plugins\Hub2\Object\DTO\IDidacticTemplateAwareDataTransferObject;

/**
 * Trait DidacticTemplateSyncProcessor
 *
 * @package srag\Plugins\Hub2\Sync
 * @author  Thibeau Fuhrer <thf@studer-raimann.ch>
 */
trait DidacticTemplateSyncProcessor
{
    public function handleDidacticTemplate(
        IDidacticTemplateAwareDataTransferObject $dto,
        ilObject $ilias_object
    ) : void {
        if (
            null !== ($tpl_id = $dto->getDidacticTemplateId())
            // && $tpl_id !== (int) \ilDidacticTemplateObjSettings::lookupTemplateId($ilias_object->getRefId())
        ) {
            $ilias_object->applyDidacticTemplate($tpl_id);

            // Apply templates of children
            $recurser = function (int $ref_id) use (&$recurser) : void {
                global $DIC;
                foreach ($DIC->repositoryTree()->getChilds($ref_id) as $child) {
                    $child_ref_id = (int) $child['ref_id'];
                    if (($tpl_id = (int) \ilDidacticTemplateObjSettings::lookupTemplateId($child_ref_id)) > 0) {
                        foreach (\ilDidacticTemplateActionFactory::getActionsByTemplateId($tpl_id) as $action) {
                            $action->setRefId($child_ref_id);
                            $action->apply();
                        }
                    }
                    $recurser($child_ref_id);
                }
            };
            $recurser($ilias_object->getRefId());
        }
    }
}
