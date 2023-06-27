<?php

namespace srag\Plugins\Hub2\FileDrop\ResourceStorage;

use ILIAS\FileUpload\DTO\UploadResult;

/**
 * Interface ResourceStorage
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
interface ResourceStorage
{
    public function fromUpload(UploadResult $u): string;

    public function replaceUpload(UploadResult $u, string $rid_string): string;

    public function fromPath(string $u, string $mime_type = null): string;

    public function fromString(string $content, string $mime_type = null): string;

    public function replaceFromString(string $rid_string, string $content, string $mime_type = null): string;

    public function remove(string $identification): bool;

    public function getRevisionInfo(string $identification): array;

    public function has(string $identification): bool;

    public function getString(string $identification): string;

    public function getPath(string $identification): string;

    public function download(string $identification, string $filename = ''): void;
}
