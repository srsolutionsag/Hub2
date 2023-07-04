<?php

namespace srag\Plugins\Hub2\Origin;

/**
 * Class AbstractOriginImplementation
 * Any implementation of a origin MUST extend this class.
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractOriginImplementation extends AbstractOriginBaseImplementation implements
    IOriginArrayImplementation
{
}
