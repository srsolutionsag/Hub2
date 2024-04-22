<?php

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Exception\ConnectionFailedException;
use srag\Plugins\Hub2\Parser\Json;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\Factory;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\ResourceStorage;

/**
 * @author Fabian Schmid <fabian@sr.solutions>
 */
trait APIGetter
{

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
            $token = $config->getServerPassword() ?? null;

            $connection = curl_init($api);
            curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
            if (!empty($token)) {
                curl_setopt($connection, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token
                ]);
            }

            $response = curl_exec($connection);
            if ($response === false) {
                throw new ConnectionFailedException("Cannot connect to API");
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
