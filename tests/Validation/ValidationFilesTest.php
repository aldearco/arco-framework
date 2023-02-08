<?php

namespace Arco\Tests\Validation;

use Arco\Storage\File;
use Arco\Validation\File as ValidationFile;
use PHPUnit\Framework\TestCase;

class ValidationFilesTest extends TestCase {
    protected array $data;

    protected function setUp(): void {
        $path1 = __DIR__.'/files/test.txt';
        $path2 = __DIR__.'/files/arco.png';

        $file1 = new File(
            file_get_contents($path1),
            finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path1),
            $path1,
            filesize($path1)
        );

        $file2 = new File(
            file_get_contents($path2),
            finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path2),
            $path2,
            filesize($path2)
        );

        $this->data = [
            "file1" => $file1,
            "file2" => $file2
        ];
    }

    public function test_file_is_image() {
        $expectedFalse = ValidationFile::image()->isValid('file1', $this->data);
        $expectedTrue = ValidationFile::image()->isValid('file2', $this->data);

        $this->assertFalse($expectedFalse);
        $this->assertTrue($expectedTrue);
    }

    public function test_file_type_is_allowed() {
        $expectedFalse = ValidationFile::types(['doc', 'docx'])->isValid('file1', $this->data);
        $expectedTrue = ValidationFile::types(['jpg', 'png'])->isValid('file2', $this->data);

        $this->assertFalse($expectedFalse);
        $this->assertTrue($expectedTrue);
    }

    public function test_file_dont_reach_maximum_size_allowed() {
        $expectedFalse = ValidationFile::max('1KB')->isValid('file1', $this->data);
        $expectedTrue = ValidationFile::max('2MB')->isValid('file2', $this->data);

        $this->assertFalse($expectedFalse);
        $this->assertTrue($expectedTrue);
    }

    public function test_file_reach_the_minimum_size_allowed() {
        $expectedFalse = ValidationFile::min('5KB')->isValid('file1', $this->data);
        $expectedTrue = ValidationFile::min('2KB')->isValid('file2', $this->data);

        $this->assertFalse($expectedFalse);
        $this->assertTrue($expectedTrue);
    }

    public function test_file_size_is_within_a_minimum_and_maximum_range() {
        $expectedFalse = ValidationFile::within('5KB', '1MB')->isValid('file1', $this->data);
        $expectedTrue = ValidationFile::within('2KB', '2MB')->isValid('file2', $this->data);

        $this->assertFalse($expectedFalse);
        $this->assertTrue($expectedTrue);
    }
}
