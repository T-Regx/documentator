<?php
namespace Test\Fixture\File;

use PHPUnit\Framework\TestCase;

class FilePathTest extends TestCase
{
    /**
     * @test
     */
    public function test(): void
    {
        $file = new File('parent', 'child');
        if ($this->windows()) {
            $this->assertSame('parent\child', $file->path);
        } else {
            $this->assertSame('parent/child', $file->path);
        }
    }

    private function windows(): bool
    {
        // "WIN32", "WINNT", "Windows"
        return \strToLower(subStr(\PHP_OS, 0, 3)) === 'win';
    }
}
