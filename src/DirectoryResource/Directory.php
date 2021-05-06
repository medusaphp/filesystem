<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource;

use LogicException;
use Medusa\FileSystem\DirectoryResource\Filter\DirectoryFileFilter;
use Medusa\FileSystem\DirectoryResource\Filter\FilterInterface;
use Medusa\FileSystem\FileResource\FileInterface;
use Medusa\FileSystem\FileSystemResourceInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function rmdir;
use function basename;
use function chdir;
use function dirname;
use function exec;
use function getcwd;
use function is_dir;
use function is_link;
use function readlink;
use function rename;
use function symlink;
use function unlink;

/**
 * Class Directory
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class Directory implements DirectoryInterface {

    /** @var string */
    private $directoryname;

    /** @var string */
    private $oldWorkingDirectory;

    /** @var SplFileInfo */
    private $splFileInfo;

    /**
     * Directory constructor.
     * @param string $directoryname
     */
    public function __construct(string $directoryname) {
        $this->directoryname = rtrim($directoryname, '/');
    }

    /**
     * @param FileSystemResourceInterface $resource
     * @return void
     */
    public function move(FileSystemResourceInterface $resource): void {
        rename($this->getLocation(), $resource->getLocation());
    }

    /**
     * @return string
     */
    public function getLocation(): string {
        return $this->getDirectoryname();
    }

    /**
     * Get directoryname
     * @return string
     */
    public function getDirectoryname(): string {
        return $this->directoryname;
    }

    /**
     * @param FileSystemResourceInterface $targetResource
     * @return FileSystemResourceInterface
     * @throws LogicException
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
        return new self(readlink($this->getLocation()));
    }

    public function remove(): void {
        rmdir($this->getLocation());
    }

    /**
     * @return void
     */
    public function unlink(): void {
        unlink($this->getLocation());
    }

    /**
     * Check if directory exists
     * @return bool
     */
    public function exists(): bool {
        return is_dir($this->directoryname);
    }

    /**
     * @param \Medusa\FileSystem\FileSystemResourceInterface $resource
     * @return void
     */
    public function copy(FileSystemResourceInterface $resource): void {
        exec('cp -R ' . $this->getLocation() . ' ' . $resource->getLocation());
    }

    /**
     * Magic to string call
     * @return string
     */
    public function __toString(): string {
        return $this->getDirectoryname();
    }

    /**
     * Chdir
     * @return DirectoryInterface
     */
    public function chdir(): DirectoryInterface {
        $cwd = getcwd();
        if ($cwd !== $this->getDirectoryname()) {
            $this->oldWorkingDirectory = $cwd;
            chdir($this->getDirectoryname());
        }

        return $this;
    }

    /**
     * Go back
     * @return DirectoryInterface
     */
    public function goBack(): DirectoryInterface {
        if ($this->oldWorkingDirectory) {
            chdir($this->oldWorkingDirectory);
            $this->oldWorkingDirectory = null;
        }

        return $this;
    }

    /**
     * @param DirectoryFileFilter|null $filter
     * @return FileSystemResourceInterface[]
     * @csIgnoreParamCount because to change this would break code in dependend projects.
     * @todo eventually rename to filterResources for next BC?
     */
    public function getResources(?FilterInterface $filter = null): array {
        $filter = $filter ?? new DirectoryFileFilter();
        $iterIterMode = ($filter->isLeafsOnly()) ? RecursiveIteratorIterator::LEAVES_ONLY : RecursiveIteratorIterator::SELF_FIRST;
        return (new FilterResult($filter))->filter(
            new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->getDirectoryname()), $iterIterMode)
        );
    }

    /**
     * @return SplFileInfo
     */
    public function getSplFileInfo(): SplFileInfo {
        return $this->splFileInfo;
    }

    /**
     * Set SplFileInfo
     * @param SplFileInfo $splFileInfo
     * @return FileSystemResourceInterface
     */
    public function setSplFileInfo(SplFileInfo $splFileInfo): FileSystemResourceInterface {
        $this->splFileInfo = $splFileInfo;
        return $this;
    }

    /**
     * Create archive
     * @param FileInterface $file
     * @return void
     */
    public function createArchive(FileInterface $file): void {
        $target = $file->getFilename();
        (new Directory(dirname($target)))->ensureExists();
        $sourceDir = $this->getDirectoryname();
        $basename = basename($sourceDir);
        $dirname = dirname($sourceDir);
        $cwd = getcwd();
        chdir($dirname);
        $cmd = 'tar -cf ' . $target . ' ' . $basename;
        exec($cmd);
        chdir($cwd);
    }

    /**
     * Ensure directory exists
     * @return void
     */
    public function ensureExists(): void {
        if (!$this->exists()) {
            $this->mkdir(true);
        }
    }

    /**
     * Make directory
     * @param bool $recursive
     * @param int  $mode
     * @return DirectoryInterface
     */
    public function mkdir(bool $recursive = false, int $mode = 0755): DirectoryInterface {
        mkdir($this->getDirectoryname(), $mode, $recursive);
        return $this;
    }
}
