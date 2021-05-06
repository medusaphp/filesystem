<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource\Filter;

use RecursiveIteratorIterator;
use SplFileInfo;
use function preg_match;

/**
 * Class DirectoryFileFilterAbstract
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
abstract class DirectoryFileFilterAbstract implements FilterInterface {

    /** @var bool */
    private $resultAsTreeList = false;

    /** @var int */
    private $maxDepth = 0;

    /** @var string|null */
    private $pattern;

    /**
     * @return bool
     */
    public function isResultAsTreeList(): bool {
        return $this->resultAsTreeList;
    }

    /**
     * Set ResultAsTreeList
     * @param bool $resultAsTreeList
     * @return DirectoryFileFilter
     */
    public function setResultAsTreeList(bool $resultAsTreeList): FilterInterface {
        $this->resultAsTreeList = $resultAsTreeList;
        return $this;
    }

    /**
     * @param RecursiveIteratorIterator $recursiveIterator
     * @return void
     */
    public function prepareRecursiveIteratorIterator(RecursiveIteratorIterator $recursiveIterator): void {
        $recursiveIterator->setMaxDepth($this->getMaxDepth());
    }

    /**
     * @return int
     */
    public function getMaxDepth(): int {
        return $this->maxDepth;
    }

    /**
     * Set MaxDepth
     * @param int $maxDepth
     * @return FilterInterface
     */
    public function setMaxDepth(int $maxDepth): FilterInterface {
        $this->maxDepth = $maxDepth;
        return $this;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return bool
     */
    public function filter(SplFileInfo $fileInfo): bool {

        $filename = $fileInfo->getFilename();

        if ($filename === '.' || $filename === '..') {
            return false;
        }

        $pattern = $this->getPattern();

        if ($pattern && !preg_match($pattern, $filename)) {
            return false;
        }

        if (!$fileInfo->getRealPath()) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function getPattern(): ?string {
        return $this->pattern;
    }

    /**
     * Set Pattern
     * @param string|null $regex
     * @return FilterInterface
     */
    public function setPattern(?string $regex): FilterInterface {
        $this->pattern = $regex;
        return $this;
    }
}
