<?php

namespace srag\Plugins\Hub2\Sync\Processor\General;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\General\NewsSettings;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait NewsSettingsSyncProcessor
{
    protected function handleNewsSettings(IDataTransferObject $dto, \ilContainer $container)
    {
        $news_settings = $dto->getNewsSettings();
        if ($news_settings === null) {
            return;
        }
        $course_obj_id = $container->getId();
        /** @var NewsSettings $news_settings */
        $container->setUseNews($news_settings->isActivateNews());
        $container->setNewsBlockActivated($news_settings->isActivateNewsBlock());
        $container->setNewsTimeline($news_settings->isActivateNewsTimeline());
        $container->setNewsTimelineLandingPage($news_settings->isActivateNewsTimelineLandingPage());
        $container->setNewsTimelineAutoEntries($news_settings->isActivateNewsTimelineAutoEntries());

        // Default Access
        \ilBlockSetting::_write(
            'news',
            'default_visibility',
            $news_settings->getNewsBlockDefaultAccess(),
            0,
            $course_obj_id
        );

        // RSS Block
        \ilBlockSetting::_write(
            'news',
            'public_feed',
            $news_settings->isActivateNewsBlockRss(),
            0,
            $course_obj_id
        );

        // Timeline Block
        $show_news_after_input = $news_settings->getShowNewsAfter();

        if ($show_news_after_input instanceof \DateTimeImmutable) {
            $show_news_after = $show_news_after_input->format('Y-m-d H:i:s');
            if ($show_news_after === $show_news_after_input) {
                \ilBlockSetting::_write('news', 'hide_news_per_date', $show_news_after ? 1 : 0, 0, $course_obj_id);
                \ilBlockSetting::_write('news', 'hide_news_date', $show_news_after, 0, $course_obj_id);
            }
        } else {
            \ilBlockSetting::_write('news', 'hide_news_per_date', 0, 0, $course_obj_id);
        }
    }
}
