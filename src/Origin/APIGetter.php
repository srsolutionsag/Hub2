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
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\Factory;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\ResourceStorage;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait APIGetter
{
    protected function getUserAgent(): string
    {
        // some WAFs reject requests without a User-Agent with a 403
        return 'ILIAS Hub2';
    }

    protected function getResourceStorage(): ResourceStorage
    {
        return (new Factory())->storage();
    }

    abstract protected function config(): IOriginConfig;

    abstract protected function origin(): IOrigin;

    protected function maybeGetAPIData(): void
    {
        $config = $this->config();
        if ($config->getConnectionType() === IOriginConfig::CONNECTION_TYPE_API) {
            // call the API here
            $api = $config->getServerHost();
            $token = trim((string) ($config->getServerPassword() ?? ''));

            $connection = curl_init($api);
            curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($connection, CURLOPT_USERAGENT, $this->getUserAgent());
            if ($token !== '') {
                curl_setopt($connection, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token
                ]);
            }

            $response = curl_exec($connection);
            $status = (int) curl_getinfo($connection, CURLINFO_HTTP_CODE);
            $error = curl_error($connection);
            curl_close($connection);

            if ($response === false) {
                throw new ConnectionFailedException("Cannot connect to API: " . $error);
            }

            // an error response must never replace the last known good data
            if ($status < 200 || $status >= 300) {
                throw new ConnectionFailedException(
                    "API returned HTTP {$status}: " . substr((string) $response, 0, 500)
                );
            }

            $storage = $this->getResourceStorage();
            $identification = $config->get(IOriginConfig::FILE_DROP_RID);

            if (empty($identification)) {
                // create new resource
                $identification = $storage->fromString($response);
                $config->setData([IOriginConfig::FILE_DROP_RID => $identification]);
                $this->origin()->store();
            } else {
                // update existing resource
                $storage->replaceFromString($identification, $response);
            }
        }
    }
}
