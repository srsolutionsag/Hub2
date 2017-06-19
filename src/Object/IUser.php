<?php namespace SRAG\Hub2\Object;


interface IUser extends IObject {

	const GENDER_MALE = 'm';
	const GENDER_FEMALE = 'f';
	const ACCOUNT_TYPE_ILIAS = 1;
	const ACCOUNT_TYPE_SHIB = 2;
	const ACCOUNT_TYPE_LDAP = 3;
	const ACCOUNT_TYPE_RADIUS = 4;

}