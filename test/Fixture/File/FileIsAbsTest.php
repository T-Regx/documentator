<?php
namespace Test\Fixture\File;

use PHPUnit\Framework\TestCase;

class FileIsAbsTest extends TestCase
{
    /**
     * @test
     */
    public function relativeIsNotAbsolute(): void
    {
        $this->assertFalse((new File('path'))->isAbs());
    }

    /**
     * @test
     */
    public function absoluteIsAbsolute(): void
    {
        $this->assertTrue($this->absoluteFile()->isAbs());
    }

    private function absoluteFile(): File
    {
        return File::temporaryDirectory();
    }
}
