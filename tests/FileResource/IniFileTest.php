<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Test\FileResource;

use Medusa\FileSystem\FileResource\File;
use Medusa\FileSystem\FileResource\IniFile;
use PHPUnit\Framework\TestCase;
use const PHP_EOL;

/**
 * Class IniFileTest
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
class IniFileTest extends TestCase {

    /**
     * test that ini content setting works
     */
    public function testSetContent() {

        $iniData = 'foo=bar;';
        $expectation = ['foo' => 'bar'];

        $file = new IniFile('test.conf', true);
        $file->setContent($iniData);

        $this->assertEquals($expectation, $file->getData());
    }

    /**
     * test that ini content setting works
     */
    public function testSetContentWithSections() {

        $iniData = '[foo]bar=biz;';
        $expectation = ['foo' => ['bar' => 'biz']];

        $file = new IniFile('test.conf', true);
        $file->setContent($iniData);

        $this->assertEquals($expectation, $file->getData());
    }

    /**
     * test content parsing without sections
     */
    public function testGetContentWithoutSections() {
        $iniData = 'bar = "biz"';

        $file = new IniFile('test.conf', false);
        $file->setContent($iniData);

        $this->assertEquals($iniData, $file->getContent());
    }

    /**
     * test content parsing without sections
     */
    public function testGetContentWithArray() {
        $iniData = 'bar[] = "bin"' . PHP_EOL . 'bar[] = "baz"';

        $file = new IniFile('test.conf', false);
        $file->setContent($iniData);

        $this->assertEquals($iniData, $file->getContent());
    }

    /**
     * test sectional content parsing
     */
    public function testGetContentWithSections() {
        $iniData = PHP_EOL . '[foo]' . PHP_EOL . 'bar = "biz"';

        $file = new IniFile('test.conf', true);
        $file->setContent($iniData);

        $this->assertEquals($iniData, $file->getContent());
    }

    public function testGetContentWithSectionsAndArrays() {
        $iniData = PHP_EOL . '[foo]' . PHP_EOL . 'bar[] = "bin"' . PHP_EOL . 'bar[] = "baz"';

        $file = new IniFile('test.conf', true);
        $file->setContent($iniData);

        $this->assertEquals($iniData, $file->getContent());
    }

    /**
     * test ini file creation from file instance
     */
    public function testCreateFromFile() {
        $filePath = 'test.conf';
        $file = new File($filePath);
        $fileContent = 'foo = "bar"';
        $file->setContent($fileContent);

        $iniFile = IniFile::fromFile($file);

        $this->assertInstanceOf(IniFile::class, $iniFile);
        $this->assertEquals($filePath, $iniFile->getLocation());
        $this->assertEquals($fileContent, trim($iniFile->getContent()));
    }

    /**
     * @dataProvider getPascaleConvertData
     * @return void
     */
    public function testPascaleConvert(array $data, bool $sections, string $expectation) {

        $result = (new IniFile('test.conf'))->setData($data)->setSections($sections)->getContent();

        $this->assertEquals($expectation, $result);

    }

    public function getPascaleConvertData(): array {
        return [
            'test-case-1' => [
                'data' => ['test1' => [8, 9, 855,]], 'sections' => false,
                'expectation' => 'test1[] = "8"' . PHP_EOL . 'test1[] = "9"' . PHP_EOL . 'test1[] = "855"'
            ],
            'test-case-2' => [
                'data' => ['test2' => ['x' => 0, 'y' => 'b',]], 'sections' => false,
                'expectation' => 'test2[x] = "0"' . PHP_EOL . 'test2[y] = "b"'
            ],
            'test-case-2-sections' => [
                'data' => ['test2' => ['x' => 0, 'y' => 'b',]], 'sections' => true,
                'expectation' => PHP_EOL . '[test2]' . PHP_EOL . 'x = "0"' . PHP_EOL . 'y = "b"'
            ],
            'test-case-3' => [
                'data' => ['test3' => ['a' => [6, 7, 8,]]], 'sections' => false,
                'expectation' => 'test3[a][] = "6"' . PHP_EOL . 'test3[a][] = "7"' . PHP_EOL . 'test3[a][] = "8"'
            ],
            'test-case-3-sections' => [
                'data' => ['test3' => ['a' => [6, 7, 8,]]], 'sections' => true,
                'expectation' => PHP_EOL . '[test3]' . PHP_EOL . 'a[] = "6"' . PHP_EOL . 'a[] = "7"'. PHP_EOL . 'a[] = "8"'
            ],
            'test-case-4' => [
                'data' => ['test4' => ['inner' => ['more' => [5, 6],],],], 'sections' => false,
                'expectation' => 'test4[inner][more][] = "5"' . PHP_EOL . 'test4[inner][more][] = "6"'
            ],
            'test-case-4-sections' => [
                'data' => ['test4' => ['inner' => ['more' => [5, 6],],],], 'sections' => true,
                'expectation' => PHP_EOL . '[test4]' . PHP_EOL . 'inner[more][] = "5"' . PHP_EOL . 'inner[more][] = "6"'
            ],
        ];

    }


}