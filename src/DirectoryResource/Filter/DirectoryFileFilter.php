<?php declare(strict_types = 1);
namespace Medusa\FileSystem\DirectoryResource\Filter;

/**
 * Class DirectoryFilter
 * @package medusa/filesystem
 * @author Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class DirectoryFileFilter extends DirectoryFileFilterAbstract implements FilterInterface {

    /**
     * @return bool
     */
    public function isLeafsOnly(): bool {
        return true;
    }

}
