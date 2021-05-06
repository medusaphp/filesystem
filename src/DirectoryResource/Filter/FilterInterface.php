<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource\Filter;

use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Interface FilterInterface
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
interface FilterInterface {

    /**
     * @param SplFileInfo $fileInfo
     * @return bool
     */
    public function filter(SplFileInfo $fileInfo): bool;

    /**
     * @return string|null
     */
    public function getPattern(): ?string;

    /**
     * Set Pattern
     * @param string|null $pattern
     * @return FilterInterface
     */
    public function setPattern(?string $pattern): FilterInterface;

    /**
     * @return bool
     */
    public function isResultAsTreeList(): bool;

    /**
     * Set ResultAsTreeList
     * @param bool $resultAsTreeList
     * @return FilterInterface
     */
    public function setResultAsTreeList(bool $resultAsTreeList): FilterInterface;

    /**
     * @return int
     */
    public function getMaxDepth(): int;

    /**
     * Set MaxDepth
     * @param int $maxDepth
     * @return FilterInterface
     */
    public function setMaxDepth(int $maxDepth): FilterInterface;

    /**
     * @param RecursiveIteratorIterator $recursiveIterator
     * @return void
     */
    public function prepareRecursiveIteratorIterator(RecursiveIteratorIterator $recursiveIterator): void;

    /**
     * provides information if the filter object needs just leaves of also branches (files or also folders)
     * @return bool
     */
    public function isLeafsOnly(): bool;
}
