<?php
namespace Test\Fixture\File;

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    use Fixture\Assertions;

    /**
     * @test
     */
    public function path(): void
    {
        $this->assertSame('foo', (new File('foo'))->path);
    }

    /**
     * @test
     */
    public function systemTemporaryDirectory(): void
    {
        $this->assertIsTemporaryDirectory(File::temporaryDirectory()->path);
    }

    /**
     * @test
     */
    public function join(): void
    {
        $file = new File('parent');
        $joined = $file->join('child', 'grandchild', 'great-grandchild');
        $this->assertSame('parent/child/grandchild/great-grandchild', $joined->path);
    }

    /**
     * @test
     */
    public function createJoined(): void
    {
        $file = new File('grandparent', 'parent', 'child');
        $this->assertSame('grandparent/parent/child', $file->path);
    }

    /**
     * @test
     */
    public function write(): void
    {
        $file = $this->existingDirectory()->join('file.txt');
        $file->write('foo');
        $this->assertSame('foo', \file_get_contents($file->path));
    }

    /**
     * @test
     */
    public function writeAndCreateDirectory(): void
    {
        $file = File::temporaryDirectory()
            ->join('directory', 'nested', 'file.txt');
        $file->write('foo');
        $this->assertSame('foo', \file_get_contents($file->path));
    }

    private function existingDirectory(): File
    {
        $directory = File::temporaryDirectory()->join(\uniqId());
        \mkDir($directory->path);
        return $directory;
    }

    /**
     * @test
     */
    public function read(): void
    {
        $file = File::temporaryDirectory()->join('directory', 'nested', 'file.txt');
        \file_get_contents($file->path, 'foo');
        $this->assertSame('foo', $file->read());
    }

    /**
     * @test
     */
    public function baseName(): void
    {
        $file = new File('parent/child');
        $this->assertSame('child', $file->baseName());
    }

    /**
     * @test
     */
    public function dirName(): void
    {
        $file = new File('foo/parent/child');
        $this->assertSame('foo/parent', $file->parentDirectory()->path);
    }
}
