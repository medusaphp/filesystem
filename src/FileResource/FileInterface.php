<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use Medusa\FileSystem\FileSystem\FileSystemResourceInterface;
use SplFileInfo;

/**
 * Class File
 * @package medusa/filesystem
 * @author Pascal Schnell <pascal.schnell@getmedusa.org>
 */
interface FileInterface extends FileSystemResourceInterface {

    /**
     * Removes file
     * @return void
     */
    public function unlink(): void;

    /**
     * Exec touch
     * @return void
     */
    public function touch(): void;

    /**
     * Get file contents
     * @return FileInterface
     */
    public function load(): FileInterface;

    /**
     * @return string
     */
    public function getFilename(): string;

    /**
     * Save content into file
     * @return void
     */
    public function save(): void;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * Set content
     * @param string $content
     * @return FileInterface
     */
    public function setContent(string $content): FileInterface;

    /**
     * @return SplFileInfo
     */
    public function getSplFileInfo(): SplFileInfo;

    /**
     * @return string
     */
    public function getDirname(): string;
}
