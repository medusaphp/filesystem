<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Test\FileResource;

use Medusa\FileSystem\FileResource\ComposerJson;
use PHPUnit\Framework\TestCase;
use function current;

/**
 * Class ComposerJsonTest
 * @package Medusa\FileSystem\Test\FileResource
 */
class ComposerJsonTest extends TestCase {

    const TEST_DIR = __DIR__ . '/ComposerJsonTest';

    public static function setUpBeforeClass(): void {
//        exec('rm -r ' . self::TEST_DIR);
//        mkdir(self::TEST_DIR);
    }

    /**
     * test that an empty array is returned if no property exists within the composerJson implementation
     */
    public function testJsonSerializeEmpty() {
        $composerJson = new ComposerJson(self::TEST_DIR . '/composer.json');

        $this->assertEquals([], $composerJson->jsonSerialize());
    }

    /**
     * test that an repository is correctly passed to the jsonSerialize method
     */
    public function testJsonSerializeRepositories() {
        $composerJson = new ComposerJson(self::TEST_DIR . '/composer.json');
        $url = 'url';
        $type = 'type';
        $repository = new ComposerJson\Repository($url, $type);
        $composerJson->addRepository($repository);

        $this->assertEquals(['repositories' => [$repository]], $composerJson->jsonSerialize());
    }

    /**
     * test that an requirement is passed to the jsonSerialize properties correctly
     */
    public function testJsonSerializeRequirements() {
        $composerJson = new ComposerJson(self::TEST_DIR . '/composer.json');

        $requirement = new ComposerJson\Requirement('package', 'version');
        $composerJson->addRequirement($requirement);

        $expectation = [
            $requirement->getPackagename() => $requirement->getVersion()
        ];

        $this->assertEquals($expectation, $composerJson->jsonSerialize()['require']);
    }

    /**
     * test that an added requirement also adds an repository if provided
     */
    public function testJsonSerializeRepositoryAddedByRequirement() {
        $composerJson = new ComposerJson(self::TEST_DIR . '/composer.json');

        $url = 'url';
        $type = 'type';
        $repository = new ComposerJson\Repository($url, $type);
        $composerJson->addRepository($repository);

        $requirement = new ComposerJson\Requirement('package', 'version');
        $requirement->setSource($repository);
        $composerJson->addRequirement($requirement);

        $this->assertEquals([$repository], $composerJson->jsonSerialize()['repositories']);
    }

    /**
     * test that dev requirements are serialized through the require-dev property
     */
    public function testJsonSerializeDevRequirement() {
        $composerJson = new ComposerJson(self::TEST_DIR . '/composer.json');

        $requirement = new ComposerJson\Requirement('package', 'version');
        $requirement->setDev(true);
        $composerJson->addRequirement($requirement);

        $expectation = [
            $requirement->getPackagename() => $requirement->getVersion()
        ];

        $this->assertEquals($expectation, $composerJson->jsonSerialize()['require-dev']);
    }

    /**
     * test that an autoload resource defined in a composer.json file is hydrated into a AutoloadResource object correctly on load
     * @return void
     */
    public function testLoadAutoloadResources() {

        $expectation = new ComposerJson\AutoloadResource('\test\namespace', 'src/', 'psr-0');

        $assertion = new ComposerJson(self::TEST_DIR . '/composer.test-load-autoload-resources.json');
        $assertion->load();

        $this->assertEquals($expectation, current($assertion->getAutoloadResources()));
    }

    /**
     * test that an autoload resource defined in the psr-4 namespace of a composer.json file is hydrated into a PSR4AutoloadResource object correctly on load
     * @return void
     */
    public function testLoadPSR4AutoloadResources() {

        $expectation = new ComposerJson\Psr4AutoloadResource('\test\namespace', 'src/');

        $assertion = new ComposerJson(self::TEST_DIR . '/composer.test-load-psr4-autoload-resources.json');
        $assertion->load();

        $this->assertEquals($expectation, current($assertion->getAutoloadResources()));
    }

    /**
     * test that an autoload resource defined in the autoload-dev node of a composer.json file is correctly marked as dev resource on load
     * @return void
     */
    public function testLoadDevAutoloadResources() {

        $expectation = new ComposerJson\Psr4AutoloadResource('\test\namespace', 'src/');
        $expectation->setDev(true);

        $assertion = new ComposerJson(self::TEST_DIR . '/composer.test-load-dev-autoload-resources.json');
        $assertion->load();

        $this->assertEquals($expectation, current($assertion->getAutoloadResources()));
    }

    /**
     * test that an autoload resource defined in the autoload[files] node of a composer.json file is correctly loaded
     * @return void
     */
    public function testLoadFilesAutoloadResources() {

        $expectation = new ComposerJson\AutoloadResource(null, 'src/foo/bar.php', 'files');
        $expectation->setDev(false);

        $assertion = new ComposerJson(self::TEST_DIR . '/composer.test-load-files-autoload-resources.json');
        $assertion->load();

        $this->assertEquals($expectation, current($assertion->getAutoloadResources()));
    }

    /**
     * test that autoload[files] is serialized from object to json as expected
     * @return void
     */
    public function testJsonSerializeFilesAutoloadResources() {
        $resource = new ComposerJson\AutoloadResource(null, 'src/foo/bar.php', 'files');
        $resource->setDev(false);

        $file = new ComposerJson(self::TEST_DIR . '/composer.test-load-files-autoload-resources.json');
        $file->addAutoloadResource($resource);

        $serializationResult = json_encode($file);

        $file = new ComposerJson(self::TEST_DIR . '/composer.test-load-files-autoload-resources.json');
        $file->load();
        $resource = json_encode($file);

        $this->assertEquals($resource, $serializationResult);
    }
}
