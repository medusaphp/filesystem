<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use LogicException;
use Medusa\FileSystem\FileSystemResourceInterface;
use SplFileInfo;
use function basename;
use function copy;
use function dirname;
use function file_exists;
use function file_put_contents;
use function is_dir;
use function rename;
use function rtrim;
use function touch;
use function unlink;

/**
 * Class FileAbstract
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
abstract class FileAbstract implements FileInterface {

    /** @var SplFileInfo */
    private $splFileInfo;

    /** @var string */
    private $filename;

    /**
     * FileAbstract constructor.
     * @param string $filename
     */
    public function __construct(string $filename) {
        $this->filename = $filename;
    }

    /**
     * Create new instance
     * @param string $filename
     * @return FileInterface
     */
    public static function create(string $filename): FileInterface {
        $self = new static($filename);
        if ($self->exists()) {
            $self->load();
        }
        return $self;
    }

    /**
     * Check if file exists
     * @return bool
     */
    public function exists(): bool {
        return file_exists($this->filename);
    }

    /**
     * Get file contents
     * @return FileInterface
     */
    abstract public function load(): FileInterface;

    /**
     * @return string
     */
    public function __toString(): string {
        return $this->filename;
    }

    /**
     * Set content
     * @param string $content
     * @return FileInterface
     */
    abstract public function setContent(string $content): FileInterface;

    /**
     * Ensure file exists
     * @return void
     */
    public function ensureExists(): void {
        if (!$this->exists()) {
            $this->touch();
        }
    }

    /**
     * @return void
     */
    public function touch(): void {
        touch($this->getFilename());
    }

    /**
     * @return string
     */
    public function getFilename(): string {
        return $this->filename;
    }

    /**
     * @return SplFileInfo
     */
    public function getSplFileInfo(): SplFileInfo {
        return $this->splFileInfo ?? $this->splFileInfo = new SplFileInfo($this->getFilename());
    }

    /**
     * Set SplFileInfo
     * @param SplFileInfo $splFileInfo
     * @return \Medusa\FileSystem\FileSystemResourceInterface
     */
    public function setSplFileInfo(SplFileInfo $splFileInfo): FileSystemResourceInterface {
        $this->splFileInfo = $splFileInfo;
        return $this;
    }

    /**
     * Save content into file
     * @return void
     */
    public function save(): void {
        file_put_contents($this->getFilename(), $this->getContent());
    }

    /**
     * @return string
     */
    abstract public function getContent(): string;

    /**
     * @param \Medusa\FileSystem\FileSystemResourceInterface $resource
     * @return void
     */
    public function move(FileSystemResourceInterface $resource): void {
        rename($this->getLocation(), $resource->getLocation());
    }

    /**
     * @return string
     */
    public function getLocation(): string {
        return $this->getFilename();
    }

    /**
     * @return string
     */
    public function getDirname(): string {
        return dirname($this->getFilename());
    }

    /**
     * @param \Medusa\FileSystem\FileSystemResourceInterface $resource
     * @return void
     */
    public function copy(FileSystemResourceInterface $resource): void {
        $source = $this->getLocation();
        $target = $resource->getLocation();
        if (is_dir($target)) {
            $target = sprintf('%s/%s', rtrim($target, '/'), basename($source));
        }
        copy($source, $target);
    }

    /**
     * @param \Medusa\FileSystem\FileSystemResourceInterface $targetResource
     * @return \Medusa\FileSystem\FileSystemResourceInterface
     * @throws LogicException
     * @csIgnoreReturnType Because Interface return type is defined.
     */
    public function setSymlinkTarget(FileSystemResourceInterface $targetResource): FileSystemResourceInterface {

        if ($this->isSymlink()) {
            if ($this->getSymlinkTarget()->getLocation() === $targetResource->getLocation()) {
                return $this;
            }
            $this->unlink();
        } elseif ($this->exists()) {
            throw new LogicException('Location "' . $this->getLocation() . '" already exists. Cant create symlink');
        }

        symlink($targetResource->getLocation(), $this->getLocation());
        return $this;
    }

    /**
     * Is symlink
     * @return bool
     */
    public function isSymlink(): bool {
        return is_link($this->getLocation());
    }

    /**
     * @return FileSystemResourceInterface
     */
    public function getSymlinkTarget(): FileSystemResourceInterface {
        return new File(readlink($this->getLocation()));
    }

    /**
     * deletes a file
     * @return void
     */
    public function unlink(): void {
        unlink($this->getLocation());
    }
}
