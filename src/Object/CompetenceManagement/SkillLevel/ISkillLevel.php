<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel;

use JsonSerializable;

/**
 * Interface ISkillLevel
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ISkillLevel extends JsonSerializable
{
    public function getExtId(): string;

    public function setExtId(string $ext_id): self;

    public function getTitle(): string;

    public function setTitle(string $title): self;

    public function getDescription(): string;

    public function setDescription(string $description): self;

    public function jsonSerialize(): array;
}
