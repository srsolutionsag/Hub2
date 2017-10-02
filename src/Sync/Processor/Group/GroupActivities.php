<?php
namespace SRAG\Hub2\Sync\Processor\Course;

/**
 * Class GroupActivities
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupActivities implements IGroupActivities {

	/**
	 * @var \ilDB
	 */
	protected $db;


	/**
	 * @param \ilDBInterface $db
	 */
	public function __construct(\ilDBInterface $db) {
		$this->db = $db;
	}


	/**
	 * @inheritdoc
	 */
	public function hasActivities(\ilObjGroup $ilObjGroup) {
		$sql = "SELECT 
				    wre.*, dat.*, rbac_ua.*
				FROM
				    catch_write_events AS wre
				        JOIN
				    obj_members AS mem ON mem.obj_id = wre.obj_id AND mem.usr_id = wre.usr_id	        
				        JOIN object_reference AS ref ON ref.obj_id = wre.obj_id				        
				        JOIN object_data AS dat ON dat.type = 'role' AND dat.title = CONCAT('il_grp_member_', ref.ref_id)				        
				        JOIN rbac_ua ON rbac_ua.rol_id = dat.obj_id AND rbac_ua.usr_id = wre.usr_id				        
				WHERE
				    wre.obj_id = "
		       . $this->db->quote(\ilObject2::_lookupObjId($ilObjGroup->getRefId()), 'integer');
		$query = $this->db->query($sql);

		return ($this->db->numRows($query) > 0);
	}
}