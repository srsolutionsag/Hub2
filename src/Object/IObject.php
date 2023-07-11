<?php

namespace srag\Plugins\Hub2\Object;

use DateTime;

/**
 * Describes common properties among the different hub object types
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObject
{
    /**
     * Initial status indicating that this object is new and an ILIAS object needs to be created by
     * the sync.
     */
    public const STATUS_NEW = 1;
    /**
     * Intermediate status indicating that a corresponding ILIAS object must be created.
     */
    public const STATUS_TO_CREATE = 2;
    /**
     * Final status indicating that the corresponding ILIAS object has been created.
     */
    public const STATUS_CREATED = 4;
    /**
     * Intermediate status indicating that the corresponding ILIAS object must be updated.
     */
    public const STATUS_TO_UPDATE = 8;
    /**
     * Final status indicating that the corresponding ILIAS object has been updated.
     */
    public const STATUS_UPDATED = 16;
    /**
     * Intermediate status indicating that the corresponding ILIAS object must be deleted.
     */
    public const STATUS_TO_OUTDATED = 32;
    /**
     * Final status indicating that the corresponding ILIAS object has been deleted.
     */
    public const STATUS_OUTDATED = 64;
    /**
     * Intermediate status indicating that the object was deleted an has now been delivered again.
     */
    public const STATUS_TO_RESTORE = 128;
    /**
     * Something on ILIAS side by processing the object goes wrong
     */
    public const STATUS_FAILED = 256;
    /**
     * Final status indicating that the object is ignored and not processed by the sync,
     * e.g. the period of the object does not match the actual period defined by the origin.
     */
    public const STATUS_IGNORED = 4096;

    /**
     * Get a unique ID of this object.
     * @return mixed
     */
    public function getId();

    /**
     * Get the external ID of this object. This ID serves as primary key to identify an object
     * inside an origin.
     * @return string
     */
    public function getExtId();

    /**
     * Set the external ID of this object. This ID serves as primary key to identify an object.
     * @param string $id
     * @return $this
     */
    public function setExtId($id);

    /**
     * Get the date where the data of this object was delivered from the external system, e.g. via
     * CSV.
     */
    public function getDeliveryDate() : DateTime;

    /**
     * @return $this
     */
    public function setDeliveryDate(int $unix_timestamp);

    /**
     * Get the date where the sync processed this object, e.g. to create/update the corresponding
     * ILIAS object depending on the status.
     * @return DateTime
     */
    public function getProcessedDate();

    /**
     * @return $this
     */
    public function setProcessedDate(int $unix_timestamp);

    /**
     * Get the ID of this object in ILIAS. Depending on the object, this can either be the ILIAS
     * object-ID or ref-ID.
     * @return int|string
     */
    public function getILIASId();

    /**
     * @param int|string $id
     * @return $this
     */
    public function setILIASId($id);

    /**
     * Get the status of this object.
     */
    public function getStatus() : int;

    /**
     * @return $this
     */
    public function setStatus(int $status);

    /**
     * Get the period (aka semester) where this object belongs to. The origin sync only processes
     * this object if the current period equals the period returned here.
     * Return an empty string if this object is active for any period.
     * @return string
     */
    public function getPeriod();

    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod($period);

    /**
     * Compute a hashcode of this object hashing all relevant properties.
     * This hashcode is for example used when processing the ILIAS object during a sync. If the
     * current hashcode is identical to the one in the database, no properties of the object were
     * changed. This means that the sync can skip processing the ILIAS object.
     * Note: Different objects MAY have identical hashcodes.
     */
    public function computeHashCode() : string;

    /**
     * Get the current hash code of this object, e.g. the hash stored in db. May not be up to date!
     * Use computeHashCode() to get the actual hashcode.
     * @return string
     */
    public function getHashCode();

    /**
     * @return array
     */
    public function getData();

    /**
     * Set properties from an associative array.
     * @return $this
     */
    public function setData(array $data);

    /**
     * Persist data in database.
     */
    public function store();
}
