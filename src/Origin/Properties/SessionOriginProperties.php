<?php namespace SRAG\Plugins\Hub2\Origin\Properties;

/**
 * Class SessionOriginProperties
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionOriginProperties extends OriginProperties {

	const MOVE_SESSION = 'move_session';
	const DELETE_MODE = 'delete_mode';
	const DELETE_MODE_NONE = 0;
	const DELETE_MODE_DELETE = 2;
	const DELETE_MODE_MOVE_TO_TRASH = 4;
}