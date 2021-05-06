<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource\Filter;

use RecursiveIteratorIterator;
use SplFileInfo;
use function dirname;
use function realpath;
use function preg_match;
use function var_dump;

/**
 * Class ResourceFilter
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class ResourceFilter extends DirectoryFileFilter {

    /** @var string */
    private $rootDir = '';

    /**
     * ResourceFilter constructor.
     * @param int $maxDepth
     */
    public function __construct(int $maxDepth = 0) {
        $this->setMaxDepth($maxDepth);
    }

    /**
     * @param RecursiveIteratorIterator $recursiveIterator
     */
    public function prepareRecursiveIteratorIterator(RecursiveIteratorIterator $recursiveIterator): void {
        $this->rootDir = realpath(dirname($recursiveIterator->getInnerIterator()->current()->getPathName()));
        parent::prepareRecursiveIteratorIterator($recursiveIterator);
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return bool
     */
    public function filter(SplFileInfo $fileInfo): bool {

        $filename = $fileInfo->getFilename();

        if ($filename === '..') {
            return false;
        }

        if ($this->rootDir === $fileInfo->getRealPath() && $filename === '.') {

            if ($this->empty ?? null) {
                var_dump($this->rootDir);
            }

            return false;
        }

        $pattern = $this->getPattern();

        if ($this->empty ?? null) {
            var_dump($this->rootDir);
        }

        if ($pattern && !preg_match($pattern, $filename)) {
            return false;
        }

        if (!$fileInfo->getRealPath()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isLeafsOnly(): bool {
        return false;
    }
}
