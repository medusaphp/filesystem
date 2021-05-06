<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Tests\DirectoryResource;

use Medusa\FileSystem\DirectoryResource\Directory;
use PHPUnit\Framework\TestCase;

/**
 * Class DirectoryTest
 * @package Medusa\FileSystem\Tests\DirectoryResource
 */
class DirectoryTest extends TestCase {

    const TEST_DIR = __DIR__ . '/DirectoryTest';

    public static function setUpBeforeClass(): void
    {
        exec('rm -r ' . self::TEST_DIR);
        mkdir(self::TEST_DIR);
    }

    /**
     * test that a directory is copied correctly to its new location
     */
    public function testMoveEmpty() {
        $sourceDirName = self::TEST_DIR . '/testMoveEmptySource';
        $targetDirName = self::TEST_DIR . '/testMoveEmptyTarget';

        mkdir($sourceDirName);

        $directoryHandle = new Directory($sourceDirName);

        $directoryHandle->move(new Directory($targetDirName));

        $this->assertDirectoryExists($targetDirName);
        $this->assertDirectoryNotExists($sourceDirName);
    }

    /**
     * test that contained directories will also be moved
     */
    public function testMoveRecursive() {
        $sourceDirName = self::TEST_DIR . '/testMoveRecursiveSource/hierachy1';
        $targetDirName = self::TEST_DIR . '/testMoveRecursiveTarget/hierachy1';

        mkdir(self::TEST_DIR . '/testMoveRecursiveSource/hierachy1', 0777, true);

        $directoryHandle = new Directory(self::TEST_DIR . '/testMoveRecursiveSource');

        $directoryHandle->move(new Directory(self::TEST_DIR . '/testMoveRecursiveTarget'));

        $this->assertDirectoryExists($targetDirName);
        $this->assertDirectoryNotExists(self::TEST_DIR . '/testMoveRecursiveSource');
    }

    /**
     * test that a directory is copied correctly
     */
    public function testCopy() {
        $sourceDirName = self::TEST_DIR . '/testCopyDirectorySource';
        $targetDirName = self::TEST_DIR . '/testCopyDirectoryTarget';

        mkdir($sourceDirName);

        $directoryHandle = new Directory($sourceDirName);

        $directoryHandle->copy(new Directory($targetDirName));

        $this->assertDirectoryExists($targetDirName);
        $this->assertDirectoryExists($sourceDirName);
    }

    /**
     * test that chdir works
     */
    public function testChDir() {
        $currentDir = getcwd();
        $parentDir = dirname($currentDir);

        $handle = new Directory($parentDir);
        $handle->chdir();

        $this->assertEquals($parentDir, getcwd());
        chdir($currentDir);
    }

    /**
     * test that goBack works
     */
    public function testGoBack() {
        $currentDir = getcwd();
        $parentDir = dirname($currentDir);

        $handle = new Directory($parentDir);
        $handle->chdir()->goBack();

        $this->assertEquals($currentDir, getcwd());
        chdir($currentDir);
    }

    /**
     * test that ensureExists creates an directory if not existing
     */
    public function testEnsureExists() {
        $testDirName = self::TEST_DIR . '/testEnsureExists';

        (new Directory($testDirName))->ensureExists();

        $this->assertDirectoryExists($testDirName);
    }

    /**
     * test that exists delivers correct data
     */
    public function testExists() {
        $testDirName = self::TEST_DIR . '/testExists';

        $handle = (new Directory($testDirName));

        mkdir($testDirName);
        $this->assertTrue($handle->exists());

        rmdir($testDirName);
        $this->assertFalse($handle->exists());
    }

    /**
     * test that mkdir creates a new directory
     */
    public function testMkdir() {
        $testDirName = self::TEST_DIR . '/testMkdir';

        (new Directory($testDirName))->mkdir();

        $this->assertDirectoryExists($testDirName);
    }

    /**
     * test that recursive mkdir works
     */
    public function testMkdirRecursive() {
        $testDirName = self::TEST_DIR . '/testMkdirRecursive/recursiveFlag';

        (new Directory($testDirName))->mkdir(true);

        $this->assertDirectoryExists($testDirName);
    }
    /**
     * test that mkdir sets correct permissions
     */
    public function testMkdirPermissions() {
        $testDirName = self::TEST_DIR . '/testMkdirPermissions';
        $permissions = 0754;
        (new Directory($testDirName))->mkdir(false, $permissions);

        $this->assertEquals('0754', substr(sprintf('%o', fileperms($testDirName)), -4));
    }

}