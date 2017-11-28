<?php

namespace SRAG\Plugins\Hub2\Object\DTO;

/**
 * Class TaxonomyAndMetadataAwareDataTransferObject
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomyAndMetadataAwareDataTransferObject {

	use MetadataAwareDataTransferObject;
	use TaxonomyAwareDataTransferObject;
}