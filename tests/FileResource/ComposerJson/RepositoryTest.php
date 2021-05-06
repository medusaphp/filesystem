<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Test\FileResource\ComposerJson;

use Medusa\FileSystem\FileResource\ComposerJson\Repository;
use PHPUnit\Framework\TestCase;

/**
 * @author anton.zoffmann
 */
class RepositoryTest extends TestCase {

    /**
     * test that toString returns the right formatted string
     */
    public function testToString() {
        $url = 'https://www.getmedusa.org';
        $type = 'composer';

        $expectation = $url . '-' . $type;

        $repo = new Repository($url, $type);

        $this->assertEquals($expectation, $repo->toString());

    }
}