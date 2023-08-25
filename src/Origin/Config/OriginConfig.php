<?php

namespace srag\Plugins\Hub2\Origin\Config;

use ilObjFile;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\Factory;

/**
 * Class OriginConfig
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Origin\Config
 */
class OriginConfig implements IOriginConfig
{
    /**
     * @var array
     */
    protected $data
        = [
            self::CHECK_AMOUNT => false,
            self::CHECK_AMOUNT_PERCENTAGE => 10,
            self::SHORT_LINK => false,
            self::SHORT_LINK_FORCE_LOGIN => false,
            self::NOTIFICATION_ERRORS => '',
            self::NOTIFICATION_SUMMARY => '',
            self::CONNECTION_TYPE => IOriginConfig::CONNECTION_TYPE_FILE_DROP,
            self::PATH => '',
            self::SERVER_HOST => '',
            self::SERVER_PORT => '',
            self::SERVER_USERNAME => '',
            self::SERVER_PASSWORD => '',
            self::SERVER_DATABASE => '',
            self::SERVER_SEARCH_BASE => '',
            self::ACTIVE_PERIOD => '',
            self::LINKED_ORIGIN_ID => 0,
            self::ILIAS_FILE_REF_ID => 0,
            self::FILE_DROP_AUTH_TOKEN => null
        ];

    public function __construct(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @inheritdoc
     */
    public function getData() : array
    {
        return $this->data;
    }

    public function setData(array $data): IOriginConfig
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function getCustom(string $key)
    {
        $key = self::CUSTOM_PREFIX . $key;

        return $this->get($key);
    }

    /**
     * @inheritdoc
     */
    public function getConnectionType() : int
    {
        return (int) ($this->get(self::CONNECTION_TYPE)??self::CONNECTION_TYPE_SERVER);
    }

    /**
     * @inheritdoc
     */
    public function getPath() : string
    {
        if (!in_array(
            $this->getConnectionType(),
            [self::CONNECTION_TYPE_PATH, self::CONNECTION_TYPE_FILE_DROP, self::CONNECTION_TYPE_API],
            true
        )) {
            throw new ConnectionFailedException("Please set connection type to path to use getPath");
        }

        switch ($this->getConnectionType()) {
            case self::CONNECTION_TYPE_FILE_DROP:
            case self::CONNECTION_TYPE_API:
                $f = new Factory();
                $path = $f->storage()->getPath($this->get(self::FILE_DROP_RID));
                break;
            default:
            case self::CONNECTION_TYPE_PATH:
                $path = $this->get(self::PATH);
                break;
        }

        if (empty($path)) {
            throw new ConnectionFailedException("Please set a path to use getPath");
        }

        if (!file_exists($path)) {
            throw new ConnectionFailedException("The path $path does not exists!");
        }

        return $path;
    }

    /**
     * @inheritdoc
     */
    public function getServerHost() : string
    {
        if (!in_array($this->getConnectionType(), [self::CONNECTION_TYPE_SERVER, self::CONNECTION_TYPE_API ], true)) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerHost");
        }

        return $this->get(self::SERVER_HOST);
    }

    /**
     * @inheritdoc
     */
    public function getServerPort() : int
    {
        if (!in_array($this->getConnectionType(), [self::CONNECTION_TYPE_SERVER, self::CONNECTION_TYPE_API ], true)) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerPort");
        }

        return (int) $this->get(self::SERVER_PORT);
    }

    /**
     * @inheritdoc
     */
    public function getServerUsername() : string
    {
        if (!in_array($this->getConnectionType(), [self::CONNECTION_TYPE_SERVER, self::CONNECTION_TYPE_API ], true)) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerUsername");
        }

        return $this->get(self::SERVER_USERNAME);
    }

    /**
     * @inheritdoc
     */
    public function getServerPassword() : string
    {
        if (!in_array($this->getConnectionType(), [self::CONNECTION_TYPE_SERVER, self::CONNECTION_TYPE_API ], true)) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerPassword");
        }

        return $this->get(self::SERVER_PASSWORD);
    }

    /**
     * @inheritdoc
     */
    public function getServerDatabase() : string
    {
        if (!in_array($this->getConnectionType(), [self::CONNECTION_TYPE_SERVER, self::CONNECTION_TYPE_API ], true)) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerDatabase");
        }

        return $this->get(self::SERVER_DATABASE);
    }

    /**
     * @inheritdoc
     */
    public function getServerSearchBase() : string
    {
        if (!in_array($this->getConnectionType(), [self::CONNECTION_TYPE_SERVER, self::CONNECTION_TYPE_API ], true)) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerSearchBase");
        }

        return $this->get(self::SERVER_SEARCH_BASE);
    }

    /**
     * @inheritdoc
     */
    public function getIliasFileRefId() : int
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_ILIAS_FILE) {
            throw new ConnectionFailedException("Please set connection type to ILIAS file to use getIliasFileRefId");
        }

        $ilias_file_ref_id = (int) $this->get(self::ILIAS_FILE_REF_ID);

        if (empty($ilias_file_ref_id)) {
            throw new ConnectionFailedException("Please select an ILIAS file to use getIliasFileRefId");
        }

        return $ilias_file_ref_id;
    }

    /**
     * @inheritdoc
     */
    public function getIliasFilePath() : string
    {
        $ilias_file_ref_id = $this->getIliasFileRefId();

        $ilias_file = new ilObjFile($ilias_file_ref_id, true);

        $path = $ilias_file->getFile();

        if (!file_exists($path)) {
            throw new ConnectionFailedException("The ILIAS file $path does not exists!");
        }

        return $path;
    }

    /**
     * @inheritdoc
     */
    public function getActivePeriod() : string
    {
        return $this->get(self::ACTIVE_PERIOD) ?? '';
    }

    /**
     * @inheritdoc
     */
    public function getCheckAmountData() : bool
    {
        return (bool) $this->get(self::CHECK_AMOUNT);
    }

    /**
     * @inheritdoc
     */
    public function getCheckAmountDataPercentage() : int
    {
        return (int) $this->get(self::CHECK_AMOUNT_PERCENTAGE);
    }

    /**
     * @inheritdoc
     */
    public function useShortLink() : bool
    {
        return (bool) $this->get(self::SHORT_LINK);
    }

    /**
     * @inheritdoc
     */
    public function useShortLinkForcedLogin() : bool
    {
        return (bool) $this->get(self::SHORT_LINK_FORCE_LOGIN);
    }

    /**
     * @inheritdoc
     */
    public function getLinkedOriginId() : int
    {
        return (int) $this->get(self::LINKED_ORIGIN_ID);
    }

    /**
     * @inheritdoc
     */
    public function getNotificationsSummary() : array
    {
        return explode(',', $this->get(self::NOTIFICATION_SUMMARY));
    }

    /**
     * @inheritdoc
     */
    public function getNotificationsErrors() : array
    {
        return explode(',', $this->get(self::NOTIFICATION_ERRORS));
    }
}
