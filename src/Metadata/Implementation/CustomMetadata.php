<?php

namespace SRAG\Plugins\Hub2\Metadata\Implementation;

/**
 * Class CustomMetadata
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class CustomMetadata extends AbstractImplementation implements IMetadataImplementation {

	/**
	 * @inheritDoc
	 */
	public function write() {
		$id = $this->getMetadata()->getIdentifier();
		$ilAdvancedMDValues = new \ilAdvancedMDValues(1, $this->getIliasId(), null, "-");

		$ilAdvancedMDValues->read();
		$ilADTGroup = $ilAdvancedMDValues->getADTGroup();
		$value = $this->getMetadata()->getValue();
		$ilADT = $ilADTGroup->getElement($id);

		switch (true) {
			case ($ilADT instanceof \ilADTText):
				$ilADT->setText($value);
				break;
			case ($ilADT instanceof \ilADTDate):
				$ilADT->setDate(new \ilDateTime(time(), 3));
				break;
			case ($ilADT instanceof \ilADTExternalLink):
				$ilADT->setUrl($value['url']);
				$ilADT->setTitle($value['title']);
				break;
			case ($ilADT instanceof \ilADTInternalLink):
				$ilADT->setTargetRefId($value);
				break;
		}

		$ilAdvancedMDValues->write();
	}


	/**
	 * @inheritDoc
	 */
	public function read() {
		// TODO: Implement read() method.
	}
}
