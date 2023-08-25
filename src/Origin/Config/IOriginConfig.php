<?php

namespace srag\Plugins\Hub2\Origin\Config;

use srag\Plugins\Hub2\Exception\ConnectionFailedException;

/**
 * Interface IOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginConfig
{
    public const CHECK_AMOUNT = 'check_amount';
    public const CHECK_AMOUNT_PERCENTAGE = 'check_amount_percentage';
    public const SHORT_LINK = 'shortlink';
    public const SHORT_LINK_FORCE_LOGIN = 'shortlink_force_login';
    public const NOTIFICATION_ERRORS = 'notification_errors';
    public const NOTIFICATION_SUMMARY = 'notification_summary';
    public const CONNECTION_TYPE = 'connection_type';
    public const PATH = 'file_path';
    public const SERVER_HOST = 'server_host';
    public const SERVER_PORT = 'server_port';
    public const SERVER_USERNAME = 'server_username';
    public const SERVER_PASSWORD = 'server_password';
    public const SERVER_DATABASE = 'server_database';
    public const SERVER_SEARCH_BASE = 'server_search_base';
    public const ACTIVE_PERIOD = 'active_period';
    public const LINKED_ORIGIN_ID = 'linked_origin_id';
    public const CONNECTION_TYPE_PATH = 1;
    public const CONNECTION_TYPE_SERVER = 2;
    public const CONNECTION_TYPE_EXTERNAL = 3;
    public const CONNECTION_TYPE_ILIAS_FILE = 4;
    public const CONNECTION_TYPE_FILE_DROP = 5;
    public const CONNECTION_TYPE_API = 6;
    public const FILE_DROP_AUTH_TOKEN = 'fd_auth_token';
    public const FILE_DROP_RID = 'fd_rid';
    // Prefix for keys that storing custom config values
    public const CUSTOM_PREFIX = 'custom_';
    public const ILIAS_FILE_REF_ID = "ilias_file_ref_id";

    /**
     * Returns all the config data as associative array
     */
    public function getData() : array;

    /**
     * @return $this
     */
    public function setData(array $data): self;

    /**
     * @return mixed
     */
    public function get(string $key);

    /**
     * Get the value of a custom config entry or NULL if no config value is found.
     * @return mixed
     */
    public function getCustom(string $key);

    /**
     * @throws ConnectionFailedException
     */
    public function getConnectionType() : int;

    /**
     * @throws ConnectionFailedException
     */
    public function getPath() : string;

    /**
     * @throws ConnectionFailedException
     */
    public function getServerHost() : string;

    /**
     * @throws ConnectionFailedException
     */
    public function getServerPort() : int;

    /**
     * @throws ConnectionFailedException
     */
    public function getServerUsername() : string;

    /**
     * @throws ConnectionFailedException
     */
    public function getServerPassword() : string;

    /**
     * @throws ConnectionFailedException
     */
    public function getServerDatabase() : string;

    /**
     * @throws ConnectionFailedException
     */
    public function getServerSearchBase() : string;

    /**
     * @throws ConnectionFailedException
     */
    public function getIliasFileRefId() : int;

    /**
     * @throws ConnectionFailedException
     */
    public function getIliasFilePath() : string;

    public function getActivePeriod() : string;

    public function getCheckAmountData() : bool;

    public function getCheckAmountDataPercentage() : int;

    public function useShortLink() : bool;

    public function useShortLinkForcedLogin() : bool;

    /**
     * Get the ID of another origin which has been selected over the configuration GUI
     */
    public function getLinkedOriginId() : int;

    public function getNotificationsSummary() : array;

    public function getNotificationsErrors() : array;
}
