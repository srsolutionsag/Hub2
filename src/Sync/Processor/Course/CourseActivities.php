<?php

namespace srag\Plugins\Hub2\Sync\Processor\Course;

use ilDB;
use ilDBInterface;
use ilObjCourse;
use ilObject2;

/**
 * Class CourseActivities
 * @package srag\Plugins\Hub2\Sync\Processor\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseActivities implements ICourseActivities
{
    /**
     * @var ilDB
     */
    protected $db;

    public function __construct(ilDBInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritdoc
     */
    public function hasActivities(ilObjCourse $ilObjCourse)
    {
        $sql = "SELECT 
				    wre.*, dat.*, rbac_ua.*
				FROM
				    catch_write_events AS wre
				        JOIN
				    obj_members AS mem ON mem.obj_id = wre.obj_id AND mem.usr_id = wre.usr_id	        
				        JOIN object_reference AS ref ON ref.obj_id = wre.obj_id				        
				        JOIN object_data AS dat ON dat.type = 'role' AND dat.title = CONCAT('il_crs_member_', ref.ref_id)				        
				        JOIN rbac_ua ON rbac_ua.rol_id = dat.obj_id AND rbac_ua.usr_id = wre.usr_id				        
				WHERE
				    wre.obj_id = " . $this->db->quote(ilObject2::_lookupObjId($ilObjCourse->getRefId()), 'integer');
        $query = $this->db->query($sql);

        return ($this->db->numRows($query) > 0);
    }
}
