<?php

namespace srag\Plugins\Hub2\Sync\Processor;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\General\IDependentSettings;

/**
 * Trait HashCodeComputer
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait HashCodeComputer
{
    public function computeHashCode() : string
    {
        switch (true) {
            case $this instanceof IDataTransferObject:
                $data = $this->getData();
                if ($this instanceof IMetadataAwareDataTransferObject) {
                    $data = array_merge($data, $this->getMetadata());
                }
                if ($this instanceof ITaxonomyAwareDataTransferObject) {
                    $data = array_merge($data, $this->getTaxonomies());
                }
                break;
            case $this instanceof IObject:
                $data = $this->data;
                if (property_exists($this, 'meta_data') && $this->meta_data !== null) {
                    $data = array_merge($data, $this->meta_data);
                }
                if (property_exists($this, 'taxonomies') && $this->taxonomies !== null) {
                    $data = array_merge($data, $this->taxonomies);
                }
                break;
            default:
                throw new \LogicException("Cannot compute hash code for " . get_class($this));
        }
        $stringified_data = $this->flattenArray($data);

        return md5($stringified_data);
    }

    private function flattenArray(array $array) : string
    {
        $flat = '';
        foreach ($array as $value) {
            if ($value instanceof IDependentSettings) {
                $value = $value->__toArray();
            }
            if (is_array($value)) {
                $flat .= $this->flattenArray($value);
            } else {
                $flat .= $value;
            }
        }
        return $flat;
    }
}
