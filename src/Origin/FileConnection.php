<?php

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
        // in case of api connection, we call the API first to get the data
        try {
            $this->maybeGetAPIData();
        } catch (ConnectionFailedException $e) {
            return false;
        }

        $this->file_path = $this->config()->getPath();
        if (!is_readable($this->file_path)) {
            throw new ConnectionFailedException("Cannot parse file {$this->file_path}");
        }
        return true;
    }

}
