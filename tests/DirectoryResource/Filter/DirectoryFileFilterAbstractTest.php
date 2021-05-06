<?php declare(strict_types = 1);

namespace Medusa\FileSystem\Tests\DirectoryResource\Filter;

use PHPUnit\Framework\TestCase;

//require __DIR__ . '/FileFilterTestStub.php';

/**
 * Class FilterRestultTest
 * @package Medusa\FileSystem\Tests\DirectoryResource
 */
class DirectoryFileFilterAbstractTest extends TestCase {

    /**
     * test that the value injected via setResultAsTreeList is retrievable via its getter
     * @testWith    [true]
     *              [false]
     */
    public function testResultAsTreeListPassthrough(bool $testValue) {

        /** @var FileFilterTestStub $filter */
        $filter = new FileFilterTestStub();

        $filter->setResultAsTreeList($testValue);

        $result = $filter->isResultAsTreeList();

        $this->assertEquals($testValue, $result);
    }

    /**
     * test that the maxDepth setting works on the injected class
     */
    public function testPrepareRecursiveIteratorIterator() {
        // PREPARATION
        $filter = new FileFilterTestStub();
        $maxDepth = 222;
        $recursiveIteratorMock = $this->getMockBuilder(\RecursiveIteratorIterator::class)
            ->setConstructorArgs(array(new \RecursiveArrayIterator()))
            ->getMock();

        $filter->setMaxDepth($maxDepth);

        // EXPECTATION
        $recursiveIteratorMock
            ->expects($this->once())
            ->method('setMaxDepth')
            ->with($this->equalTo($maxDepth));

        // EXECUTION
        $filter->prepareRecursiveIteratorIterator($recursiveIteratorMock);
    }

    /**
     * test aht the filter function works as expected
     * @dataProvider getFilterTestData
     */
    public function testFilter(bool $expectation, array $fileinfo) {

        $fileInfoMock = $this->getMockBuilder(\SplFileInfo::class)
            ->disableOriginalConstructor()
            ->getMock();

        if (array_key_exists('filename', $fileinfo)) {
            $fileInfoMock->method('getFilename')->willReturn($fileinfo['filename']);
        }

        if (array_key_exists('realpath', $fileinfo)) {
            $fileInfoMock->method("getRealPath")->willReturn($fileinfo['realpath']);
        }

        /** @var FileFilterTestStub $filter */
        $filter = new FileFilterTestStub();

        if (array_key_exists('pattern', $fileinfo)) {
            $filter->setPattern($fileinfo['pattern']);
        }


        $result = $filter->filter($fileInfoMock);

        $this->assertEquals($expectation, $result);
    }

    /**
     * dataProvider for testFilter
     * @return array
     */
    public function getFilterTestData(): array {
        return [
            'current directory' => [
                'expectation' => false,
                'testData' => [
                    'filename' => '.'
                ]
            ],
            'parent directory' => [
                'expectation' => false,
                'testData' => [
                    'filename' => '..'
                ]
            ],
            'negative pattern matching' => [
                'expectation' => false,
                'testData' => [
                    'filename' => 'b',
                    'pattern' => '/a/',
                    'realpath' => true
                ]
            ],
            'positive pattern matching' => [
                'expectation' => true,
                'testData' => [
                    'filename' => 'b',
                    'pattern' => '/b/',
                    'realpath' => true
                ]
            ],
            'positive realpath directive' => [
                'expectation' => true,
                'testData' => [
                    'filename' => 'b',
                    'pattern' => '/b/',
                    'realpath' => true
                ]
            ],
            'negative realpath directive' => [
                'expectation' => false,
                'testData' => [
                    'filename' => 'b',
                    'pattern' => '/b/',
                    'realpath' => false
                ]
            ]
        ];
    }

}