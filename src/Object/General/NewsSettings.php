<?php

namespace srag\Plugins\Hub2\Object\General;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class NewsSettings extends BaseDependentSetting implements IDependentSettings
{
    public const ACCESS_USERS = 'users';
    public const ACCESS_PUBLIC = 'public';
    public const F_ACTIVATE_NEWS = 'activate_news';
    public const F_ACTIVATE_NEWS_BLOCK = 'activate_news_block';
    public const F_NEWS_BLOCK_DEFAULT_ACCESS = 'news_block_default_access';
    public const F_ACTIVATE_NEWS_BLOCK_RSS = 'activate_news_block_rss';
    public const F_ACTIVATE_NEWS_TIMELINE = 'activate_news_timeline';
    public const F_ACTIVATE_NEWS_TIMELINE_AUTO_ENTRIES = 'activate_news_timeline_auto_entries';
    public const F_ACTIVATE_NEWS_TIMELINE_LANDING_PAGE = 'activate_news_timeline_landing_page';
    public const F_SHOW_NEWS_AFTER = 'show_news_after';
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
    protected $show_news_after;

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

    protected function set(string $key, $value) : BaseDependentSetting
    {
        $this->{$key} = $value;
        return parent::set($key, $value);
    }

    public function isActivateNews() : bool
    {
        return $this->activate_news;
    }

    public function setActivateNews(bool $activate_news) : NewsSettings
    {
        return $this->set(self::F_ACTIVATE_NEWS, $activate_news);
    }

    public function isActivateNewsBlock() : bool
    {
        return $this->activate_news_block;
    }

    public function setActivateNewsBlock(bool $activate_news_block) : NewsSettings
    {
        return $this->set(self::F_ACTIVATE_NEWS_BLOCK, $activate_news_block);
    }

    public function getNewsBlockDefaultAccess() : string
    {
        return $this->news_block_default_access;
    }

    public function setNewsBlockDefaultAccess(string $news_block_default_access) : NewsSettings
    {
        return $this->set(self::F_NEWS_BLOCK_DEFAULT_ACCESS, $news_block_default_access);
    }

    public function isActivateNewsBlockRss() : bool
    {
        return $this->activate_news_block_rss;
    }

    public function setActivateNewsBlockRss(bool $activate_news_block_rss) : NewsSettings
    {
        return $this->set(self::F_ACTIVATE_NEWS_BLOCK_RSS, $activate_news_block_rss);
    }

    public function isActivateNewsTimeline() : bool
    {
        return $this->activate_news_timeline;
    }

    public function setActivateNewsTimeline(bool $activate_news_timeline) : NewsSettings
    {
        return $this->set(self::F_ACTIVATE_NEWS_TIMELINE, $activate_news_timeline);
    }

    public function isActivateNewsTimelineAutoEntries() : bool
    {
        return $this->activate_news_timeline_auto_entries;
    }

    public function setActivateNewsTimelineAutoEntries(bool $activate_news_timeline_auto_entries) : NewsSettings
    {
        return $this->set(self::F_ACTIVATE_NEWS_TIMELINE_AUTO_ENTRIES, $activate_news_timeline_auto_entries);
    }

    public function isActivateNewsTimelineLandingPage() : bool
    {
        return $this->activate_news_timeline_landing_page;
    }

    public function setActivateNewsTimelineLandingPage(bool $activate_news_timeline_landing_page) : NewsSettings
    {
        return $this->set(self::F_ACTIVATE_NEWS_TIMELINE_LANDING_PAGE, $activate_news_timeline_landing_page);
    }

    public function getShowNewsAfter() : ?\DateTimeImmutable
    {
        return $this->show_news_after;
    }

    public function setShowNewsAfter(?\DateTimeImmutable $show_news_after) : NewsSettings
    {
        return $this->set(self::F_SHOW_NEWS_AFTER, $show_news_after);
    }

    public function __toArray() : array
    {
        return $this->data;
    }

    public function __fromArray(array $data) : void
    {
        $this->data = $data;
    }

    public function serialize() : string
    {
        return serialize($this->__toArray());
    }

    public function unserialize($data) : void
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

    public function offsetSet($offset, $value) : void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) : void
    {
        unset($this->data[$offset]);
    }
}
