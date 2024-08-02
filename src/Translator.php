<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface Translator
{
    public function txt(string $key): string;
}
