<?php

namespace srag\Plugins\Hub2\FileDrop;

use ilContext;
use ilHub2Plugin;
use ilInitialisation;
use srag\Plugins\Hub2\Exception\ShortlinkException;
use ILIAS\DI\HTTPServices;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\FileDrop\ResourceStorage\Factory;
use srag\Plugins\Hub2\FileDrop\Exceptions\InternalError;
use srag\Plugins\Hub2\FileDrop\Exceptions\AccessDenied;
use srag\Plugins\Hub2\FileDrop\Exceptions\NotFound;
use srag\Plugins\Hub2\FileDrop\Exceptions\Success;
use ILIAS\Filesystem\Stream\Streams;
use Psr\Http\Message\RequestInterface;

/**
 * Class Token
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Token
{
    const LENGTH = 16;
    const BEARER = 'Bearer';

    public function generate(): string
    {
        try {
            $token = bin2hex(random_bytes(self::LENGTH));
        } catch (\Throwable $t) {
            $token = hash('sha256', uniqid((string) time(), true));
        }

        return substr($token, 0, self::LENGTH);
    }

    public function fromRequest(RequestInterface $request): ?string
    {
        $token = $request->getHeaderLine('Authorization');
        if (empty($token)) {
            return null;
        }
        $slit_token = explode(':', str_replace(" ", "", $token));
        if ($slit_token[0] !== self::BEARER || !isset($slit_token[1]) || $slit_token[1] == '') {
            return null;
        }

        return $slit_token[1] ?? null;
    }

}
