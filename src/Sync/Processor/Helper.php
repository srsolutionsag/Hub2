<?php namespace SRAG\Hub2\Sync\Processor;

/**
 * Trait Helper
 * @package SRAG\Hub2\Sync\Processor
 */
trait Helper {

	/**
	 * @param string $string
	 * @return string
	 */
	protected function clearString($string) {
		$replaces = [
			'ä'  => 'ae',
			'å'  => 'ae',
			'ü'  => 'ue',
			'ö'  => 'oe',
			'Ä'  => 'Ae',
			'Ü'  => 'Ue',
			'Ö'  => 'Oe',
			'é'  => 'e',
			'è'  => 'e',
			'ê'  => 'e',
			'Á'  => 'A',
			'ß'  => 'ss',
			'\'' => '',
			' '  => '',
			'-'  => '',
			'.'  => '',
		];
		$string = strtr($string, $replaces);
		return strtr(
			utf8_decode($string),
			utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
			'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
		);
	}

}