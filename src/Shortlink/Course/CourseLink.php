<?php

namespace srag\Plugins\Hub2\Shortlink\Course;

use srag\Plugins\Hub2\Shortlink\AbstractRepositoryLink;
use srag\Plugins\Hub2\Shortlink\IObjectLink;
use ILIAS\Data\URI;

/**
 * Class CourseLink
 * @package srag\Plugins\Hub2\Shortlink\Course
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseLink extends AbstractRepositoryLink implements IObjectLink
{
    public function getAccessDeniedLink(): string
    {
        $link = \ilLink::_getLink($this->getILIASId());

        $uri_builder = new URI($link);
        $query = $uri_builder->getQuery();
        $uri = '' . $uri_builder->getPath();
        if ($query) {
            $uri .= '?' . $query;
        }

        return $uri;
    }
}
