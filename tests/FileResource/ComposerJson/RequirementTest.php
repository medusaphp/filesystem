<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Test\FileResource\ComposerJson;

use Medusa\FileSystem\FileResource\ComposerJson\Requirement;
use PHPUnit\Framework\TestCase;

/**
 * @author anton.zoffmann
 */
class RequirementTest extends TestCase {

    /**
     * test that toString returns the correct format
     */
    public function testToString() {
        $packageName = "helloWorld";
        $version = "~0.0.1";
        $expectation = $packageName . ':' . $version;

        $requirement = new Requirement($packageName, $version);

        $this->assertEquals($expectation, $requirement->toString());
    }

}