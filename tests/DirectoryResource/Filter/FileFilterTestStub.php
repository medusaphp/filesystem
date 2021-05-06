<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Tests\DirectoryResource\Filter;

use Medusa\FileSystem\DirectoryResource\Filter\DirectoryFileFilterAbstract;

/**
 * @author anton.zoffmann
 */
class FileFilterTestStub extends DirectoryFileFilterAbstract {

    public function isLeafsOnly(): bool {
        return false;
    }

}