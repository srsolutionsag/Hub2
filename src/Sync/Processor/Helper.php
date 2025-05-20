<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Sync\Processor;

/**
 * Trait Helper
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait Helper
{
    /**
     * @param string $string
     */
    protected function clearString($string): string
    {
        $replaces = [
            'ä' => 'ae',
            'å' => 'ae',
            'ü' => 'ue',
            'ö' => 'oe',
            'Ä' => 'Ae',
            'Ü' => 'Ue',
            'Ö' => 'Oe',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'Á' => 'A',
            'ß' => 'ss',
            '\'' => '',
            ' ' => '',
            '-' => '',
            '.' => '',
        ];
        $string = strtr($string, $replaces);

        return strtr(
            @mb_convert_encoding($string, 'ISO-8859-1'),
            @mb_convert_encoding('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ', 'ISO-8859-1'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
        );
    }
}
