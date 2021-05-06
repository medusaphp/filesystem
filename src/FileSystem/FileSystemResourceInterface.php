<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileSystem;

use SplFileInfo;

/**
 * Interface FileSystemResourceInterface
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
interface FileSystemResourceInterface {

    /**
     * @return string
     */
    public function getLocation(): string;

    /**
     * Ensure directory exists
     * @return void
     */
    public function ensureExists(): void;

    /**
     * Check if directory exists
     * @return bool
     */
    public function exists(): bool;

    /**
     * @param FileSystemResourceInterface $resource
     * @return void
     */
    public function move(FileSystemResourceInterface $resource): void;

    /**
     * @param SplFileInfo $splFileInfo
     * @return FileSystemResourceInterface
     */
    public function setSplFileInfo(SplFileInfo $splFileInfo): FileSystemResourceInterface;

    /**
     * @param FileSystemResourceInterface $resource
     * @return void
     */
    public function copy(FileSystemResourceInterface $resource): void;

    /**
     * Is symlink
     * @return bool
     */
    public function isSymlink(): bool;

    /**
     * @param FileSystemResourceInterface $targetResource
     * @return FileSystemResourceInterface
     */
    public function setSymlinkTarget(FileSystemResourceInterface $targetResource): FileSystemResourceInterface;

    /**
     * @return FileSystemResourceInterface
     */
    public function getSymlinkTarget(): FileSystemResourceInterface;
}
