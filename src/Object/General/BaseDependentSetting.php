<?php

namespace srag\Plugins\Hub2\Object\General;

use srag\Plugins\Hub2\Object\IDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Object\General\IDependentSettings;
use Exception;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
abstract class BaseDependentSetting implements IDependentSettings
{
    protected $data = [];
    
    public function __toArray() : array
    {
        return $this->data;
    }
    
    public function __fromArray(array $data) : void
    {
        $this->data = $data;
    }
    
    protected function set(string $key, $value) : self
    {
        $this->data[$key] = $value;
        
        return $this;
    }
    
    public function serialize()
    {
        return serialize($this->__toArray());
    }
    
    public function unserialize($data)
    {
        $this->__fromArray(unserialize($data));
    }
    
    public function __toString() : string
    {
        return $this->serialize();
    }
    
    public function __fromString(string $data) : void
    {
        $this->unserialize($data);
    }
    
    public function offsetExists($offset)
    {
        return $this->data[$offset] ?? false;
    }
    
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }
    
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }
    
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
    
    public function jsonSerialize()
    {
        return $this->__toArray();
    }
    
}
