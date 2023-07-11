<?php

namespace srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel;

/**
 * Class SkillLevel
 * @package srag\Plugins\Hub2\Object\CompetenceManagement\SkillLevel
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SkillLevel implements ISkillLevel
{
    /**
     * @var string
     */
    protected $ext_id = "";
    /**
     * @var string
     */
    protected $title = "";
    /**
     * @var string
     */
    protected $description = "";

    /**
     * SkillLevel constructor
     */
    public function __construct(string $ext_id, string $title = "", string $description = "")
    {
        $this->ext_id = $ext_id;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @inheritdoc
     */
    public function getExtId() : string
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function setExtId(string $ext_id) : ISkillLevel
    {
        $this->ext_id = $ext_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title) : ISkillLevel
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description) : ISkillLevel
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     * @return array{ext_id: string, title: string, description: string}
     */
    public function jsonSerialize() : array
    {
        return [
            "ext_id" => $this->ext_id,
            "title" => $this->title,
            "description" => $this->description,
        ];
    }
}
