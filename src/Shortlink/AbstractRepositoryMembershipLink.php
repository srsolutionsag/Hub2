<?php

namespace srag\Plugins\Hub2\Shortlink;

use srag\Plugins\Hub2\Object\IObjectRepository;

/**
 * Class AbstractRepositoryMembershipLink
 *
 * @author Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 */
abstract class AbstractRepositoryMembershipLink extends AbstractRepositoryLink implements IObjectLink {
	protected function getILIASId(){
		list($container_id) = explode(IObjectRepository::GLUE, $this->object->getILIASId());
		return $container_id;
	}
}
