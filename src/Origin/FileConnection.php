<?php

/*********************************************************************
 * This Code is licensed under the GPL-3.0 License and is Part of a
 * ILIAS Plugin developed by sr solutions ag in Switzerland.
 *
 * https://sr.solutions
 *
 *********************************************************************/

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Exception\ConnectionFailedException;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait FileConnection
{
    use APIGetter;

    protected string $file_path = '';

    public function connect(): bool
    {
        // in case of api connection, we call the API first to get the data.
        // a ConnectionFailedException is passed on deliberately, otherwise the reason
        // (e.g. the HTTP status of the API) would be lost in the sync log.
        $this->maybeGetAPIData();

        $this->file_path = $this->config()->getPath();
        if (!is_readable($this->file_path)) {
            throw new ConnectionFailedException("Cannot parse file {$this->file_path}");
        }
        return true;
    }

}
