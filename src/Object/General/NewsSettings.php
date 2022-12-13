<?php

namespace srag\Plugins\Hub2\Object\General;

use srag\Plugins\Hub2\Object\IDidacticTemplateAwareObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Object\IDependentSettings;
use Exception;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class NewsSettings implements IDependentSettings
{
    public const ACCESS_USERS = 'users';
    public const ACCESS_PUBLIC = 'public';
    const F_ACTIVATE_NEWS = 'activate_news';
    const F_ACTIVATE_NEWS_BLOCK = 'activate_news_block';
    const F_NEWS_BLOCK_DEFAULT_ACCESS = 'news_block_default_access';
    const F_ACTIVATE_NEWS_BLOCK_RSS = 'activate_news_block_rss';
    const F_ACTIVATE_NEWS_TIMELINE = 'activate_news_timeline';
    const F_ACTIVATE_NEWS_TIMELINE_AUTO_ENTRIES = 'activate_news_timeline_auto_entries';
    const F_ACTIVATE_NEWS_TIMELINE_LANDING_PAGE = 'activate_news_timeline_landing_page';
    const F_SHOW_NEWS_AFTER = 'show_news_after';
    /**
     * @var bool
     */
    protected $activate_news = true;
    /**
     * @var bool
     */
    protected $activate_news_block = true;
    /**
     * @var string
     */
    protected $news_block_default_access = self::ACCESS_USERS;
    /**
     * @var bool
     */
    protected $activate_news_block_rss = true;
    /**
     * @var bool
     */
    protected $activate_news_timeline = true;
    /**
     * @var bool
     */
    protected $activate_news_timeline_auto_entries = true;
    /**
     * @var bool
     */
    protected $activate_news_timeline_landing_page = true;
    /**
     * @var \DateTimeImmutable, Y-m-d H:i:s needed
     */
    protected $show_news_after = null;
    
    /**
     * @var array
     */
    private $data = [];
    
    public function __construct(
        bool $activate_news = true,
        bool $activate_news_block = true,
        string $news_block_default_access = self::ACCESS_USERS,
        bool $activate_news_block_rss = false,
        bool $activate_news_timeline = false,
        bool $activate_news_timeline_auto_entries = false,
        bool $activate_news_timeline_landing_page = false,
        ?\DateTimeImmutable $show_news_after = null
    ) {
        $this->setActivateNews($activate_news);
        $this->setActivateNewsBlock($activate_news_block);
        $this->setNewsBlockDefaultAccess($news_block_default_access);
        $this->setActivateNewsBlockRss($activate_news_block_rss);
        $this->setActivateNewsTimeline($activate_news_timeline);
        $this->setActivateNewsTimelineAutoEntries($activate_news_timeline_auto_entries);
        $this->setActivateNewsTimelineLandingPage($activate_news_timeline_landing_page);
        $this->setShowNewsAfter($show_news_after);
    }
    
    public function isActivateNews() : bool
    {
        return $this->activate_news;
    }
    
    public function setActivateNews(bool $activate_news) : CourseNewsSettings
    {
        $this->activate_news = $this->data[self::F_ACTIVATE_NEWS] = $activate_news;
        return $this;
    }
    
    public function isActivateNewsBlock() : bool
    {
        return $this->activate_news_block;
    }
    
    public function setActivateNewsBlock(bool $activate_news_block) : CourseNewsSettings
    {
        $this->activate_news_block = $this->data[self::F_ACTIVATE_NEWS_BLOCK] = $activate_news_block;
        return $this;
    }
    
    public function getNewsBlockDefaultAccess() : string
    {
        return $this->news_block_default_access;
    }
    
    public function setNewsBlockDefaultAccess(string $news_block_default_access) : CourseNewsSettings
    {
        $this->news_block_default_access = $this->data[self::F_NEWS_BLOCK_DEFAULT_ACCESS] = $news_block_default_access;
        return $this;
    }
    
    public function isActivateNewsBlockRss() : bool
    {
        return $this->activate_news_block_rss;
    }
    
    public function setActivateNewsBlockRss(bool $activate_news_block_rss) : CourseNewsSettings
    {
        $this->activate_news_block_rss = $this->data[self::F_ACTIVATE_NEWS_BLOCK_RSS] = $activate_news_block_rss;
        return $this;
    }
    
    public function isActivateNewsTimeline() : bool
    {
        return $this->activate_news_timeline;
    }
    
    public function setActivateNewsTimeline(bool $activate_news_timeline) : CourseNewsSettings
    {
        $this->activate_news_timeline = $this->data[self::F_ACTIVATE_NEWS_TIMELINE] = $activate_news_timeline;
        return $this;
    }
    
    public function isActivateNewsTimelineAutoEntries() : bool
    {
        return $this->activate_news_timeline_auto_entries;
    }
    
    public function setActivateNewsTimelineAutoEntries(bool $activate_news_timeline_auto_entries) : CourseNewsSettings
    {
        $this->activate_news_timeline_auto_entries = $this->data[self::F_ACTIVATE_NEWS_TIMELINE_AUTO_ENTRIES] = $activate_news_timeline_auto_entries;
        return $this;
    }
    
    public function isActivateNewsTimelineLandingPage() : bool
    {
        return $this->activate_news_timeline_landing_page;
    }
    
    public function setActivateNewsTimelineLandingPage(bool $activate_news_timeline_landing_page) : CourseNewsSettings
    {
        $this->activate_news_timeline_landing_page = $this->data[self::F_ACTIVATE_NEWS_TIMELINE_LANDING_PAGE] = $activate_news_timeline_landing_page;
        return $this;
    }
    
    public function getShowNewsAfter() : ?\DateTimeImmutable
    {
        return $this->show_news_after;
    }
    
    public function setShowNewsAfter(?\DateTimeImmutable $show_news_after) : CourseNewsSettings
    {
        $this->show_news_after = $this->data[self::F_SHOW_NEWS_AFTER] = $show_news_after;
        return $this;
    }
    
    public function __toArray() : array
    {
        return $this->data;
    }
    
    public function __fromArray(array $data) : void
    {
        $this->data = $data;
    }
    
    public function serialize()
    {
        return serialize($this->__toArray());
    }
    
    public function unserialize($data)
    {
        $this->__fromArray(unserialize($data));
    }
    
    public function __toString():string
    {
        return $this->serialize();
    }
  
    
    public function __fromString(string $data) : void
    {
        $this->unserialize($data);
    }
}
