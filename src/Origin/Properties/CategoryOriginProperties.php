<?php namespace SRAG\Hub2\Origin\Properties;

/**
 * Class CategoryOriginProperties
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin\Properties
 */
class CategoryOriginProperties extends OriginProperties {

	const SHOW_INFO_TAB = 'show_info_tab';
	const SHOW_NEWS = 'show_news';

	/**
	 * @var array
	 */
	protected $data = [
		self::SHOW_INFO_TAB => false,
		self::SHOW_NEWS => false,
	];

}