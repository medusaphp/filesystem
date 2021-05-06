<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource\Filter;

use SplFileInfo;

/**
 * Class DirectoryFilter
 * @package medusa-tools/installer
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
class DirectoryFilter extends DirectoryFileFilterAbstract {

    /**
     * @return bool
     */
    public function isLeafsOnly(): bool {
        return false;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return bool
     */
    public function filter(SplFileInfo $fileInfo): bool {
        if (!$fileInfo->isDir()) {
            return false;
        }

        return parent::filter($fileInfo);
    }
}
