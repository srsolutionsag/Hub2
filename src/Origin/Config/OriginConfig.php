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
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function getCustom(string $key)
    {
        $key = self::CUSTOM_PREFIX . $key;

        return $this->get($key);
    }

    public function getConnectionType(): int
    {
        return (int) $this->get(self::CONNECTION_TYPE);
    }

    public function getPath(): string
    {
        if (
            $this->getConnectionType() !== self::CONNECTION_TYPE_PATH
            && $this->getConnectionType() !== self::CONNECTION_TYPE_FILE_DROP
        ) {
            throw new ConnectionFailedException("Please set connection type to path to use getPath");
        }

        switch ($this->getConnectionType()) {
            case self::CONNECTION_TYPE_FILE_DROP:
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

    public function getServerHost(): string
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_SERVER) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerHost");
        }

        return $this->get(self::SERVER_HOST);
    }

    public function getServerPort(): int
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_SERVER) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerPort");
        }

        return (int) $this->get(self::SERVER_PORT);
    }

    public function getServerUsername(): string
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_SERVER) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerUsername");
        }

        return $this->get(self::SERVER_USERNAME);
    }

    public function getServerPassword(): string
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_SERVER) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerPassword");
        }

        return $this->get(self::SERVER_PASSWORD);
    }

    public function getServerDatabase(): string
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_SERVER) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerDatabase");
        }

        return $this->get(self::SERVER_DATABASE);
    }

    public function getServerSearchBase(): string
    {
        if ($this->getConnectionType() !== self::CONNECTION_TYPE_SERVER) {
            throw new ConnectionFailedException("Please set connection type to server to use getServerSearchBase");
        }

        return $this->get(self::SERVER_SEARCH_BASE);
    }

    public function getIliasFileRefId(): int
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

    public function getIliasFilePath(): string
    {
        $ilias_file_ref_id = $this->getIliasFileRefId();

        $ilias_file = new ilObjFile($ilias_file_ref_id, true);

        $path = $ilias_file->getFile();

        if (!file_exists($path)) {
            throw new ConnectionFailedException("The ILIAS file $path does not exists!");
        }

        return $path;
    }

    public function getActivePeriod(): string
    {
        return $this->get(self::ACTIVE_PERIOD) ?? '';
    }

    public function getCheckAmountData(): bool
    {
        return (bool) $this->get(self::CHECK_AMOUNT);
    }

    public function getCheckAmountDataPercentage(): int
    {
        return (int) $this->get(self::CHECK_AMOUNT_PERCENTAGE);
    }

    public function useShortLink(): bool
    {
        return (bool) $this->get(self::SHORT_LINK);
    }

    public function useShortLinkForcedLogin(): bool
    {
        return (bool) $this->get(self::SHORT_LINK_FORCE_LOGIN);
    }

    public function getLinkedOriginId(): int
    {
        return (int) $this->get(self::LINKED_ORIGIN_ID);
    }

    public function getNotificationsSummary(): array
    {
        return explode(',', $this->get(self::NOTIFICATION_SUMMARY));
    }

    public function getNotificationsErrors(): array
    {
        return explode(',', $this->get(self::NOTIFICATION_ERRORS));
    }
}
