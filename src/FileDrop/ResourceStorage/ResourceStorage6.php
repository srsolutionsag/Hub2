<?php

namespace srag\Plugins\Hub2\FileDrop\ResourceStorage;

use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\MainMenu\Storage\Identification\ResourceIdentification;
use ILIAS\MainMenu\Storage\Information\Repository\InformationARRepository;
use ILIAS\MainMenu\Storage\Resource\Repository\ResourceARRepository;
use ILIAS\MainMenu\Storage\Resource\ResourceBuilder;
use ILIAS\MainMenu\Storage\Resource\StorableFileResource;
use ILIAS\MainMenu\Storage\Revision\FileRevision;
use ILIAS\MainMenu\Storage\Revision\Repository\RevisionARRepository;
use ILIAS\MainMenu\Storage\Services;
use ILIAS\MainMenu\Storage\StorageHandler\FileSystemStorageHandler;
use ILIAS\MainMenu\Storage\Revision\Repository\RevisionRepository;

/**
 * Interface ResourceStorage
 *
 *
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class ResourceStorage6 implements ResourceStorage
{
    /**
     * @var Services
     */
    protected $services;
    /**
     * @var Stakeholder
     */
    protected $stakeholder;
    /**
     * @var ResourceBuilder
     */
    protected $resource_builder;
    /**
     * @var FileSystemStorageHandler
     */
    protected $storage_handler;
    /**
     * @var RevisionARRepository
     */
    protected $revision_repository;
    /**
     * @var ResourceARRepository
     */
    protected $resource_repository;
    /**
     * @var InformationARRepository
     */
    protected $information_repository;

    /**
     * ResourceStorage6 constructor.
     */
    public function __construct()
    {
        $this->services = new Services();
        $this->stakeholder = new Stakeholder6();

        $this->storage_handler = new FileSystemStorageHandler();
        $this->revision_repository = new RevisionARRepository();
        $this->resource_repository = new ResourceARRepository();
        $this->information_repository = new InformationARRepository();
        $this->resource_builder = new ResourceBuilder(
            $this->storage_handler,
            $this->revision_repository,
            $this->resource_repository,
            $this->information_repository
        );
    }

    public function fromUpload(UploadResult $u): string
    {
        $i = $this->services->upload($u, $this->stakeholder);
        return $i->serialize();
    }

    public function replaceUpload(UploadResult $u, string $rid_string): string
    {
        $rid = $this->services->find($rid_string);
        if ($rid === null) {
            return $this->fromUpload($u);
        }
        // Get resource builder
        $r = new \ReflectionClass($this->services);
        $p = $r->getProperty('resource_builder');
        $p->setAccessible(true);
        /** @var $resource_builder ResourceBuilder */
        $resource_builder = $p->getValue($this->services);
        // Get Revision Repo
        $r = new \ReflectionClass($resource_builder);
        $p = $r->getProperty('revision_repository');
        $p->setAccessible(true);
        /** @var $revision_repository RevisionRepository */
        $revision_repository = $p->getValue($resource_builder);

        // Get Resource
        $resource = $resource_builder->get($rid);

        // Create New Revision
        $revision = $revision_repository->blank($resource, $u);

        $info = $revision->getInformation();
        $info->setTitle($u->getName());
        $info->setMimeType($u->getMimeType());
        $info->setSize($u->getSize());
        $info->setCreationDate(new \DateTimeImmutable());
        $revision->setInformation($info);

        // Store revision
        $resource->addRevision($revision);
        $resource_builder->store($resource);

        return $resource->getIdentification()->serialize();
    }

    public function fromPath(string $u, string $mime_type = null): string
    {
        $id = $this->storage_handler->getIdentificationGenerator()->getUniqueResourceIdentification();

        $r = new StorableFileResource($id);
        $r->setStorageId($this->storage_handler->getID());

        $rev = new FileRevision($id);
        $rev->setVersionNumber(1);

        $info = $rev->getInformation();
        $info->setTitle(basename($u));
        $info->setMimeType($mime_type ?? mime_content_type($u));
        $info->setSize(filesize($u));
        $info->setCreationDate(new \DateTimeImmutable());
        $rev->setInformation($info);

        $r->addRevision($rev);

        $this->resource_builder->store($r);

        $directory = rtrim(CLIENT_DATA_DIR, "/") . "/" . FileSystemStorageHandler::BASE . '/' . str_replace(
                "-",
                "/",
                $id->serialize()
            ) . '/1/';
        $path = $directory . FileSystemStorageHandler::DATA;
        if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
        copy($u, $path);

        return $id->serialize();
    }

    public function getDataURL(string $identification): string
    {
        $id = $this->services->find($identification);
        if ($id instanceof ResourceIdentification) {
            $revision = $this->services->getRevision($id);
            $stream = $this->services->stream($id)->getStream();
            $mime_type = $revision->getInformation()->getMimeType();
            $contents = $stream->getContents();
            switch ($mime_type) {
                case 'image/svg+xml':
//                    return 'data:' . $mime_type . ';charset=UTF-8,' . ($contents).'';
                default:
                    return 'data:' . $mime_type . ';base64,' . base64_encode($contents);
            }
        }
        return '';
    }

    public function remove(string $identification): bool
    {
        $id = $this->services->find($identification);
        if ($id instanceof ResourceIdentification) {
            $this->services->remove($id);
            return true;
        }
        return false;
    }

    public function getRevisionInfo(string $identification): array
    {
        $id = $this->services->find($identification);
        if ($id instanceof ResourceIdentification) {
            $info = $this->services->getRevision($id)->getInformation();
            $title = $info->getTitle();
            $size = $info->getSize();
            $mime_type = $info->getMimeType();
        }
        return [
            'title' => $title,
            'size' => $size,
            'mime_type' => $mime_type
        ];
    }

    public function has(string $identification): bool
    {
        $id = $this->services->find($identification);
        return $id instanceof ResourceIdentification;
    }

    public function getString(string $identification): string
    {
        $id = $this->services->find($identification);
        if ($id instanceof ResourceIdentification) {
            $stream = $this->services->stream($id)->getStream();
            return $stream->getContents();
        }
        return '';
    }

    public function getPath(string $identification): string
    {
        $id = $this->services->find($identification);
        if ($id instanceof ResourceIdentification) {
            return $this->services->stream($id)->getStream()->getMetaData()['uri'];
        }
        return '';
    }

    public function fromString(string $content, string $mime_type = null): string
    {
        $id = $this->storage_handler->getIdentificationGenerator()->getUniqueResourceIdentification();

        $r = new StorableFileResource($id);
        $r->setStorageId($this->storage_handler->getID());

        $rev = new FileRevision($id);
        $rev->setVersionNumber(1);

        $info = $rev->getInformation();
        $info->setTitle('import');
        $info->setMimeType($mime_type);
        $info->setSize(strlen($content));
        $info->setCreationDate(new \DateTimeImmutable());
        $rev->setInformation($info);

        $r->addRevision($rev);

        $this->resource_builder->store($r);

        $directory = rtrim(CLIENT_DATA_DIR, "/") . "/" . FileSystemStorageHandler::BASE . '/' . str_replace(
                "-",
                "/",
                $id->serialize()
            ) . '/1/';
        $path = $directory . FileSystemStorageHandler::DATA;
        if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
        file_put_contents($path, $content);

        return $id->serialize();
    }


    public function download(string $identification): void
    {
        $id = $this->services->find($identification);
        if ($id instanceof ResourceIdentification) {
            $this->services->download($id)->run();
        }
    }

}
