<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource;

use Medusa\FileSystem\DirectoryResource\Filter\FilterInterface;
use Medusa\FileSystem\FileResource\File;
use Medusa\FileSystem\FileSystemResourceInterface;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Class FilterResult
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class FilterResult {

    /** @var FilterInterface */
    private $filter;

    /**
     * FilterResult constructor.
     * @param FilterInterface $filter
     */
    public function __construct(FilterInterface $filter) {
        $this->filter = $filter;
    }

    /**
     * @param RecursiveIteratorIterator $recursiveIterator
     * @return FileSystemResourceInterface[]
     */
    public function filter(RecursiveIteratorIterator $recursiveIterator): array {

        $filter = $this->filter;
        $filter->prepareRecursiveIteratorIterator($recursiveIterator);

        $result = [];

        foreach ($recursiveIterator as $item) {

            /** @var SplFileInfo $item */
            if ($filter->filter($item) === false) {
                continue;
            }

            $itemRealpath = $item->getRealPath();

            if (!$itemRealpath) {
                continue;
            }

            $resource = ($item->isDir()) ? new Directory($itemRealpath) : new File($itemRealpath);

            $resource->setSplFileInfo($item);

            if (!$filter->isResultAsTreeList()) {
                $result[$itemRealpath] = $resource;
                continue;
            }

            $dirParts = explode('/', $resource->getDirname());
            unset($dirParts[0]);

            $tmp = &$result;

            foreach ($dirParts as $dirPart) {

                if (!isset($tmp[$dirPart])) {
                    $tmp[$dirPart] = [];
                }

                $tmp = &$tmp[$dirPart];
            }

            $tmp[$itemRealpath] = $resource;
            unset($tmp);
        }

        return $result;
    }
}
