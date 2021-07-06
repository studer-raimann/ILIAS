<?php declare(strict_types=1);

namespace ILIAS\ResourceStorage\StorageHandler;

use ILIAS\Filesystem\Filesystem;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\Filesystem\Util\LegacyPathHelper;
use ILIAS\FileUpload\Location;
use ILIAS\ResourceStorage\Identification\IdentificationGenerator;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use ILIAS\ResourceStorage\Identification\UniqueIDIdentificationGenerator;
use ILIAS\ResourceStorage\Resource\StorableResource;
use ILIAS\ResourceStorage\Revision\CloneRevision;
use ILIAS\ResourceStorage\Revision\FileStreamRevision;
use ILIAS\ResourceStorage\Revision\Revision;
use ILIAS\ResourceStorage\Revision\UploadedFileRevision;

/**
 * Class FileSystemStorage
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package ILIAS\ResourceStorage\Storage
 * @internal
 */
class FileSystemStorageHandler implements StorageHandler
{
    const BASE = "storage";
    const DATA = 'data';
    /**
     * @var bool
     */
    protected $links_possible = false;
    /**
     * @var Filesystem
     */
    private $fs;
    /**
     * @var UniqueIDIdentificationGenerator
     */
    private $id;
    /**
     * @var int
     */
    private $location;

    /**
     * FileSystemStorageHandler constructor.
     * @param Filesystem $filesystem
     * @param int $location
     * @internal
     */
    public function __construct(Filesystem $filesystem, int $location = Location::STORAGE)
    {
        $this->fs = $filesystem;
        $this->location = $location;
        $this->id = new UniqueIDIdentificationGenerator();
        $this->links_possible = $this->determineLinksPossible();
    }

    private function determineLinksPossible(): bool
    {
        $random_filename = "test_" . random_int(10000, 99999);
        $original_filename = self::BASE . "/" . $random_filename . '_orig';
        $linked_filename = self::BASE . "/" . $random_filename . '_link';
        $cleaner = function () use ($original_filename, $linked_filename) {
            try {
                $this->fs->delete($original_filename);
                $this->fs->delete($linked_filename);
            } catch (\Throwable $t) {

            }
        };

        try {
            // remove existing files
            $cleaner();

            // create file
            $this->fs->write($original_filename, 'data');
            $stream = $this->fs->readStream($original_filename);

            // determine absolute pathes
            $original_absolute_path = $stream->getMetadata('uri');
            $linked_absolute_path = dirname($original_absolute_path) . "/" . $random_filename . '_link';

            // try linking and unlinking
            $linking = @link($original_absolute_path, $linked_absolute_path);
            $unlinking = @unlink($original_absolute_path);
            if ($linking && $unlinking && $this->fs->has($linked_filename)) {
                $cleaner();
                return true;
            }
            $cleaner();
        } catch (\Throwable $t) {
            return false;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getID(): string
    {
        return 'fsv1';
    }

    /**
     * @inheritDoc
     */
    public function getIdentificationGenerator(): IdentificationGenerator
    {
        return $this->id;
    }

    public function has(ResourceIdentification $identification): bool
    {
        return $this->fs->has($this->getBasePath($identification));
    }

    /**
     * @inheritDoc
     */
    public function getStream(Revision $revision): FileStream
    {
        return $this->fs->readStream($this->getRevisionPath($revision) . '/' . self::DATA);
    }

    public function storeUpload(UploadedFileRevision $revision): bool
    {
        global $DIC;

        $DIC->upload()->moveOneFileTo($revision->getUpload(), $this->getRevisionPath($revision), $this->location,
            self::DATA);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function storeStream(FileStreamRevision $revision): bool
    {
        try {
            if ($revision->keepOriginal()) {
                $stream = $revision->getStream();
                $this->fs->writeStream($this->getRevisionPath($revision) . '/' . self::DATA, $stream);

            } else {
                $target = $revision->getStream()->getMetadata('uri');
                if ($this->links_possible) {
                    $test = link($target, $this->getAbsoluteRevisionPath($revision));
                    unlink($target);
                } else {
                    $this->fs->rename(LegacyPathHelper::createRelativePath($target),
                        $this->getRevisionPath($revision) . '/' . self::DATA);
                }
            }
        } catch (\Throwable $t) {
            return false;
        }

        return true;
    }

    public function cloneRevision(CloneRevision $revision): bool
    {
        $stream = $this->getStream($revision->getRevisionToClone());
        try {
            $this->fs->writeStream($this->getRevisionPath($revision) . '/' . self::DATA, $stream);
        } catch (\Throwable $t) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteRevision(Revision $revision): void
    {
        $this->fs->deleteDir($this->getRevisionPath($revision));
    }

    /**
     * @inheritDoc
     */
    public function deleteResource(StorableResource $resource): void
    {
        $this->fs->deleteDir($this->getBasePath($resource->getIdentification()));
    }

    private function getBasePath(ResourceIdentification $identification): string
    {
        return self::BASE . '/' . str_replace("-", "/", $identification->serialize());
    }

    private function getRevisionPath(Revision $revision): string
    {
        return $this->getBasePath($revision->getIdentification()) . '/' . $revision->getVersionNumber();
    }

    private function getAbsoluteRevisionPath(Revision $revision): string
    {
        $str = rtrim(CLIENT_DATA_DIR, "/") . "/" . ltrim($this->getRevisionPath($revision), "/");
        return $str;
    }

    public function movementImplementation(): string
    {
        return $this->links_possible ? 'link' : 'rename';
    }


}
