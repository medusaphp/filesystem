<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource;

use Medusa\FileSystem\FileSystem\FileSystemResourceInterface;

/**
 * Class Directory
 * @package medusa/filesystem
 * @author Pascal Schnell <pascal.schnell@getmedusa.org>
 */
interface DirectoryInterface extends FileSystemResourceInterface {

    /**
     * Chdir
     * @return DirectoryInterface
     */
    public function chdir(): DirectoryInterface;

    /**
     * @return string
     */
    public function getDirectoryname(): string;

    /**
     * Go back
     * @return DirectoryInterface
     */
    public function goBack(): DirectoryInterface;

    /**
     * Make directory
     * @param bool $recursive
     * @param int  $mode
     * @return DirectoryInterface
     */
    public function mkdir(bool $recursive = false, int $mode = 0766): DirectoryInterface;
}
