<?php

namespace srag\Plugins\Hub2\Sync\Processor\Course;

use ilCalendarCategory;
use ilContainer;
use ilContainerSortingSettings;
use ilCopyWizardOptions;
use ilLink;
use ilMailMimeSenderFactory;
use ilMD;
use ilMDLanguageItem;
use ilMimeMail;
use ilObjCategory;
use ilObjCourse;
use ilSession;
use ilSoapFunctions;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Origin\Config\Course\CourseOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\Sync\Processor\DidacticTemplateSyncProcessor;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ParentResolver\CourseParentResolver;
use srag\Plugins\Hub2\Sync\Processor\General\NewsSettingsSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\General\LearningProgressSettingsSyncProcessor;

/**
 * Class CourseSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseSyncProcessor extends ObjectSyncProcessor implements ICourseSyncProcessor
{
    use TaxonomySyncProcessor;
    use MetadataSyncProcessor;
    use DidacticTemplateSyncProcessor;
    use NewsSettingsSyncProcessor;
    use LearningProgressSettingsSyncProcessor;

    /**
     * @var CourseProperties
     */
    protected $props;
    /**
     * @var CourseOriginConfig
     */
    protected $config;
    /**
     * @var ICourseActivities
     */
    protected $courseActivities;
    /**
     * @var array
     */
    protected static $properties
        = [
            'title',
            'description',
            'importantInformation',
            'contactResponsibility',
            'contactEmail',
            'owner',
            'subscriptionLimitationType',
            'viewMode',
            'contactName',
            'syllabus',
            'contactConsultation',
            'contactPhone',
            'activationType',
            'numberOfPreviousSessions',
            'numberOfNextSessions',
            'orderType',
            'activationStart',
            'activationEnd',
            'targetGroup',
        ];
    /**
     * @var CourseParentResolver
     */
    protected $parent_resolver;
    /**
     * @var \ilTree
     */
    private $tree;
    /**
     * @var \ilObjectDefinition
     */
    private $object_definition;
    /**
     * @var \ilObjUser
     */
    private $user;
    /**
     * @var \ilSetting
     */
    private $settings;
    /**
     * @var \ilObjectDataCache
     */
    private $obj_data_cache;

    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition,
        ICourseActivities $courseActivities
    ) {
        global $DIC;
        $this->tree = $DIC['tree'];
        $this->object_definition = $DIC['objDefinition'];
        $this->user = $DIC->user();
        $this->settings = $DIC->settings();
        $this->obj_data_cache = $DIC['ilObjDataCache'];
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
        $this->courseActivities = $courseActivities;
        $this->parent_resolver = new CourseParentResolver(
            $this->config->getParentRefIdIfNoParentIdFound(),
            $this->config->getLinkedOriginId()
        );
    }

    /**
     * @return array
     */
    public static function getProperties()
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param CourseDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        // Find the refId under which this course should be created
        $parentRefId = $this->determineParentRefId($dto);
        // Check if we should create some dependence categories
        $parentRefId = $this->buildDependenceCategories($dto, $parentRefId);

        if (($template_id = $dto->getTemplateId()) !== 0) {
            // copy from template
            if (!ilObjCourse::_exists($template_id, true)) {
                throw new HubException(
                    'Creation of course with ext_id = ' . $dto->getExtId() . ' failed: template course with ref_id = '
                    . $template_id . ' does not exist in ILIAS'
                );
            }
            $return = $this->cloneAllObject($parentRefId, $template_id, $this->getCloneOptions($template_id));
            $this->current_ilias_object = $ilObjCourse = new ilObjCourse($return);
        } else {
            // create new one
            $this->current_ilias_object = $ilObjCourse = new ilObjCourse();
            $ilObjCourse->setImportId($this->getImportId($dto));
            $ilObjCourse->create();
            $ilObjCourse->createReference();
            $ilObjCourse->putInTree($parentRefId);
            $ilObjCourse->setPermissions($parentRefId);
            $this->writeRBACLog($ilObjCourse->getRefId());
        }

        // Pass properties from DTO to ilObjUser
        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null && $setter !== "setActivationType") {
                if ($property === "activationStart" || $property === "activationEnd") {
                    $ilObjCourse->$setter($dto->$getter() !== null ? $dto->$getter()->get(IL_CAL_UNIX) : null);
                } else {
                    $ilObjCourse->$setter($dto->$getter());
                }
            }
        }

        // Course Start and Ane are handled differently in ILIAS 5.4 and 6
        if ($dto->getCourseStart() && $dto->getCourseEnd()) {
            $ilObjCourse->setCoursePeriod($dto->getCourseStart(), $dto->getCourseEnd());
        }

        if ($this->props->get(CourseProperties::SET_ONLINE)) {
            $ilObjCourse->setOfflineStatus(false);
            //Does not exist in 5.4
            //$ilObjCourse->setActivationType(IL_CRS_ACTIVATION_UNLIMITED);
        }

        if ($this->props->get(CourseProperties::SEND_CREATE_NOTIFICATION)) {
            $this->sendMailNotifications($dto, $ilObjCourse);
        }
        $this->setSubscriptionType($dto, $ilObjCourse);

        $this->setLanguage($dto, $ilObjCourse);
        $ilObjCourse->enableSessionLimit($dto->isSessionLimitEnabled());

        $this->handleNewsSettings($dto, $ilObjCourse);

        $this->handleLPSettings($dto, $ilObjCourse);

        $ilObjCourse->update();

        $this->handleOrdering($dto, $ilObjCourse);

        $this->handleAppointementsColor($ilObjCourse, $dto);
    }

    protected function handleOrdering(IDataTransferObject $dto, ilObjCourse $ilObjCourse)
    {
        $settings = new ilContainerSortingSettings($ilObjCourse->getId());
        $settings->setSortMode($dto->getOrderType());

        switch ($dto->getOrderType()) {
            case ilContainer::SORT_TITLE:
            case ilContainer::SORT_ACTIVATION:
            case ilContainer::SORT_CREATION:
                $settings->setSortDirection($dto->getOrderDirection());
                break;
            case ilContainer::SORT_MANUAL:
                /**
                 * TODO: set order direction for manual sorting
                 */
                break;
        }

        $settings->update();
    }

    /**
     * @param $source_id
     * @return array<int|string, array{type: int}>
     */
    protected function getCloneOptions($source_id) : array
    {
        $options = [];
        foreach ($this->tree->getSubTree($root = $this->tree->getNodeData($source_id)) as $node) {
            if ($node['type'] == 'rolf') {
                continue;
            }

            if ($this->object_definition->allowCopy($node['type'])) {
                $options[$node['ref_id']] = ['type' => ilCopyWizardOptions::COPY_WIZARD_COPY];
            }
            // this should be a config
            //            else if (self::LINK_IF_COPY_NOT_POSSIBLE && self::dic()->objDefinition()->allowLink($node['type'])) {
            //                $options[$node['ref_id']] = ['type' => ilCopyWizardOptions::COPY_WIZARD_LINK];
            //            }
        }

        return $options;
    }

    /**
     * This is a leaner version of ilContainer::cloneAllObject, which doens't use soap
     * @return int $ref_id
     */
    public function cloneAllObject(int $parent_ref_id, int $clone_source, array $options) : int
    {
        // Save wizard options
        $copy_id = ilCopyWizardOptions::_allocateCopyId();
        $wizard_options = ilCopyWizardOptions::_getInstance($copy_id);
        $wizard_options->saveOwner($this->user->getId());
        $wizard_options->saveRoot($clone_source);

        // add entry for source container
        $wizard_options->initContainer($clone_source, $parent_ref_id);

        foreach ($options as $source_id => $option) {
            $wizard_options->addEntry($source_id, $option);
        }
        $wizard_options->read();
        $wizard_options->storeTree($clone_source);

        // Duplicate session to avoid logout problems with backgrounded SOAP calls
        $new_session_id = ilSession::_duplicate($_COOKIE['PHPSESSID']);

        $wizard_options->disableSOAP();
        $wizard_options->read();

        require_once __DIR__ . '/webservice/soap/include/inc.soap_functions.php';

        return ilSoapFunctions::ilClone($new_session_id . '::' . $_COOKIE['ilClientId'], $copy_id);
    }

    protected function setLanguage(CourseDTO $dto, ilObjCourse $ilObjCourse)
    {
        $md_general = (new ilMD($ilObjCourse->getId()))->getGeneral();
        $getLanguageIds = $md_general->getLanguageIds();
        //Note: this is terribly stupid, but the best (only) way if found to get to the
        //lang id of the primary language of some object. There seems to be multy lng
        //support however, not through the GUI. Maybe there is some bug in the generation
        //of the respective metadata form. See: initQuickEditForm() in ilMDEditorGUI
        $language = $md_general->getLanguage(array_pop($getLanguageIds));
        $lng_obj = new ilMDLanguageItem($dto->getLanguageCode());
        $language->setLanguage($lng_obj);
        $language->update();
    }

    protected function setSubscriptionType(CourseDTO $dto, ilObjCourse $ilObjCourse)
    {
        //There is some weird connection between subscription limitation type ond subscription type, see e.g. ilObjCourseGUI
        $ilObjCourse->setSubscriptionType($dto->getSubscriptionLimitationType());
        if ($dto->getSubscriptionLimitationType() == CourseDTO::SUBSCRIPTION_TYPE_DEACTIVATED) {
            $ilObjCourse->setSubscriptionLimitationType(IL_CRS_SUBSCRIPTION_DEACTIVATED);
        } else {
            $ilObjCourse->setSubscriptionLimitationType(IL_CRS_SUBSCRIPTION_UNLIMITED);
        }
    }

    protected function sendMailNotifications(CourseDTO $dto, ilObjCourse $ilObjCourse)
    {
        $mail = new ilMimeMail();
        $sender_factory = new ilMailMimeSenderFactory($this->settings);
        if ($this->props->get(CourseProperties::CREATE_NOTIFICATION_FROM)) {
            $sender = $sender_factory->userByEmailAddress(
                $this->props->get(CourseProperties::CREATE_NOTIFICATION_FROM)
            );
        } else {
            $sender = $sender_factory->system();
        }
        $mail->From($sender);
        $mail->To($dto->getNotificationEmails());
        $mail->Subject($this->props->get(CourseProperties::CREATE_NOTIFICATION_SUBJECT));
        $mail->Body(
            $this->replaceBodyTextForMail(
                $this->props->get(CourseProperties::CREATE_NOTIFICATION_BODY),
                $ilObjCourse
            )
        );
        $mail->Send();
    }

    protected function replaceBodyTextForMail($body, ilObjCourse $ilObjCourse)
    {
        foreach (CourseProperties::$mail_notification_placeholder as $ph) {
            $replacement = '[' . $ph . ']';

            switch ($ph) {
                case 'title':
                    $replacement = $ilObjCourse->getTitle();
                    break;
                case 'description':
                    $replacement = $ilObjCourse->getDescription();
                    break;
                case 'responsible':
                    $replacement = $ilObjCourse->getContactResponsibility();
                    break;
                case 'notification_email':
                    $replacement = $ilObjCourse->getContactEmail();
                    break;
                case 'shortlink':
                    $replacement = ilLink::_getStaticLink($ilObjCourse->getRefId(), 'crs');
                    break;
            }
            $body = str_ireplace('[' . strtoupper($ph) . ']', $replacement, $body);
        }

        return $body;
    }

    /**
     * @inheritdoc
     * @param CourseDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCourse = $this->findILIASCourse($ilias_id);
        if (!$ilObjCourse instanceof \ilObjCourse) {
            return;
        }
        // Update some properties if they should be updated depending on the origin config
        foreach (self::getProperties() as $property) {
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null && $setter !== "setActivationType") {
                if ($property === "activationStart" || $property === "activationEnd") {
                    $ilObjCourse->$setter($dto->$getter() !== null ? $dto->$getter()->get(IL_CAL_UNIX) : null);
                } else {
                    $ilObjCourse->$setter($dto->$getter());
                }
            }
        }
        $courseStart = $this->props->updateDTOProperty("courseStart");
        $courseEnd = $this->props->updateDTOProperty("courseEnd");
        if ($courseStart || $courseEnd) {
            $start = $courseStart
                ? $dto->getCourseStart()
                : $ilObjCourse->getCourseStart();
            $end = $courseEnd
                ? $dto->getCourseEnd()
                : $ilObjCourse->getCourseEnd();
            $ilObjCourse->setCoursePeriod($start, $end);
        }
        if ($this->props->updateDTOProperty("enableSessionLimit")) {
            $ilObjCourse->enableSessionLimit($dto->isSessionLimitEnabled());
        }
        if ($this->props->updateDTOProperty("subscriptionLimitationType")) {
            $this->setSubscriptionType($dto, $ilObjCourse);
        }
        if ($this->props->updateDTOProperty("languageCode")) {
            $this->setLanguage($dto, $ilObjCourse);
        }
        if ($this->props->get(CourseProperties::SET_ONLINE_AGAIN)) {
            $ilObjCourse->setOfflineStatus(false);
            //Does not exist in 5.4
            //$ilObjCourse->setActivationType(IL_CRS_ACTIVATION_UNLIMITED);
        }

        if ($this->props->updateDTOProperty("enableSessionLimit")) {
            $ilObjCourse->enableSessionLimit($dto->isSessionLimitEnabled());
        }

        // News Settings
        if ($this->props->updateDTOProperty("newsSettings")) {
            $this->handleNewsSettings($dto, $ilObjCourse);
        }

        // LP Settings
        if ($this->props->updateDTOProperty("learningProgressSettings")) {
            $this->handleLPSettings($dto, $ilObjCourse);
        }

        // move/put in tree
        // Find the refId under which this course should be created
        $parent_ref_id = $this->determineParentRefId($dto);
        // Check if we should create some dependence categories
        $parent_ref_id = $this->buildDependenceCategories($dto, $parent_ref_id);
        $ref_id = (int) $ilObjCourse->getRefId();

        if ($this->parent_resolver->isRefIdDeleted($ref_id)) {
            $this->parent_resolver->restoreRefId($ref_id, $parent_ref_id);
        } elseif ($this->props->get(CourseProperties::MOVE_COURSE)) {
            $this->parent_resolver->move($ref_id, $parent_ref_id);
        }

        if ($this->props->updateDTOProperty("appointementsColor")) {
            $this->handleAppointementsColor($ilObjCourse, $dto);
        }

        $ilObjCourse->update();
    }

    protected function handleAppointementsColor(ilObjCourse $ilObjCourse, CourseDTO $dto)
    {
        if (!empty($dto->getAppointementsColor())) {
            $this->obj_data_cache->deleteCachedEntry($ilObjCourse->getId());
            /**
             * @var $cal_cat ilCalendarCategory
             */
            $cal_cat = ilCalendarCategory::_getInstanceByObjId($ilObjCourse->getId());
            $cal_cat->setColor($dto->getAppointementsColor());
            $cal_cat->update();
        }
    }

    /**
     * @inheritdoc
     * @param CourseDTO $dto
     */
    protected function handleDelete(IDataTransferObject $dto, $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCourse = $this->findILIASCourse($ilias_id);
        if (!$ilObjCourse instanceof \ilObjCourse) {
            return;
        }
        if ($this->props->get(CourseProperties::DELETE_MODE) == CourseProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(CourseProperties::DELETE_MODE)) {
            case CourseProperties::DELETE_MODE_OFFLINE:
                $ilObjCourse->setOfflineStatus(true);
                $ilObjCourse->update();
                break;
            case CourseProperties::DELETE_MODE_DELETE:
                $ilObjCourse->delete();
                break;
            case CourseProperties::DELETE_MODE_MOVE_TO_TRASH:
                $this->tree->moveToTrash($ilObjCourse->getRefId(), true);
                break;
            case CourseProperties::DELETE_MODE_DELETE_OR_OFFLINE:
                if ($this->courseActivities->hasActivities($ilObjCourse)) {
                    $ilObjCourse->setOfflineStatus(true);
                    $ilObjCourse->update();
                } else {
                    $this->tree->moveToTrash($ilObjCourse->getRefId(), true);
                }
                break;
        }
    }

    /**
     * @throws HubException
     */
    protected function determineParentRefId(CourseDTO $course) : int
    {
        return $this->parent_resolver->resolveParentRefId($course);
    }

    /**
     * @param int $parentRefId
     * @return int
     */
    protected function buildDependenceCategories(CourseDTO $object, $parentRefId)
    {
        if ($object->getFirstDependenceCategory() !== null) {
            $parentRefId = $this->buildDependenceCategory($object->getFirstDependenceCategory(), $parentRefId, 1);
        }
        if ($object->getFirstDependenceCategory() !== null
            && $object->getSecondDependenceCategory() !== null
        ) {
            $parentRefId = $this->buildDependenceCategory($object->getSecondDependenceCategory(), $parentRefId, 2);
        }
        if ($object->getFirstDependenceCategory() !== null
            && $object->getSecondDependenceCategory() !== null
            && $object->getThirdDependenceCategory() !== null
        ) {
            $parentRefId = $this->buildDependenceCategory($object->getThirdDependenceCategory(), $parentRefId, 3);
        }
        if ($object->getFirstDependenceCategory() !== null
            && $object->getSecondDependenceCategory() !== null
            && $object->getThirdDependenceCategory() !== null
            && $object->getFourthDependenceCategory() !== null
        ) {
            $parentRefId = $this->buildDependenceCategory($object->getFourthDependenceCategory(), $parentRefId, 4);
        }

        return $parentRefId;
    }

    /**
     * Creates a category under the given $parentRefId if it does not yet exist.
     * Note that this implementation is copied over from the old hub plugin: We check if we
     * find a category having the same title. If not, a new category is created.
     * It would be better to identify the category over the unique import ID and then update
     * the title of the category, if necessary.
     * @param string $title
     * @param int    $parent_ref_id
     * @param int    $level
     * @return int
     */
    protected function buildDependenceCategory($title, $parent_ref_id, $level)
    {
        static $cache = [];
        // We use a cache for created dependence categories to save some SQL queries
        $cacheKey = hash("sha256", $title . $parent_ref_id . $level);
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }
        $categories = $this->tree->getChildsByType($parent_ref_id, 'cat');
        $matches = array_filter($categories, static function ($category) use ($title) : bool {
            return $category['title'] === $title;
        });
        if ($matches !== []) {
            $category = array_pop($matches);
            $cache[$cacheKey] = $category['ref_id'];

            return $category['ref_id'];
        }
        // No category with the given title found, create it!
        $import_id = self::IMPORT_PREFIX . implode(
            '_',
            [
                    $this->origin->getId(),
                    $parent_ref_id,
                    'depth',
                    $level,
                ]
        );
        $ilObjCategory = new ilObjCategory();
        $ilObjCategory->setTitle($title);
        $ilObjCategory->setImportId($import_id);
        $ilObjCategory->create();
        $ilObjCategory->createReference();
        $ilObjCategory->putInTree($parent_ref_id);
        $ilObjCategory->setPermissions($parent_ref_id);
        $this->writeRBACLog($ilObjCategory->getRefId());
        $cache[$cacheKey] = $ilObjCategory->getRefId();

        return $ilObjCategory->getRefId();
    }

    /**
     * @param int $iliasId
     * @return ilObjCourse|null
     */
    protected function findILIASCourse($iliasId)
    {
        if (!ilObjCourse::_exists($iliasId, true)) {
            return null;
        }

        return new ilObjCourse($iliasId);
    }
}
