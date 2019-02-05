<?php

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilADTDate;
use ilADTExternalLink;
use ilADTInternalLink;
use ilADTText;
use ilAdvancedMDValues;
use ilDateTime;

/**
 * Class CustomMetadata
 *
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CustomMetadata extends AbstractImplementation implements IMetadataImplementation {

	/**
	 * @inheritdoc
	 */
	public function write() {
		$id = $this->getMetadata()->getIdentifier();

		$ilAdvancedMDValues = new ilAdvancedMDValues($this->getMetadata()->getRecordId(), $this->getIliasId(), NULL, "-");

		$ilAdvancedMDValues->read();
		$ilADTGroup = $ilAdvancedMDValues->getADTGroup();
		$value = $this->getMetadata()->getValue();
		$ilADT = $ilADTGroup->getElement($id);

		switch (true) {
			case ($ilADT instanceof ilADTText):
				$ilADT->setText($value);
				break;
			case ($ilADT instanceof ilADTDate):
				$ilADT->setDate(new ilDateTime(time(), IL_CAL_UNIX));
				break;
			case ($ilADT instanceof ilADTExternalLink):
				$ilADT->setUrl($value['url']);
				$ilADT->setTitle($value['title']);
				break;
			case ($ilADT instanceof ilADTInternalLink):
				$ilADT->setTargetRefId($value);
				break;
		}

		$ilAdvancedMDValues->write();
	}


	/**
	 * @inheritdoc
	 */
	public function read() {
		// no need for a read-Method since wo have to update them anyways due to performance-issues when reading all metadata everytime
	}
}
