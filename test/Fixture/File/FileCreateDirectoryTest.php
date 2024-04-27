<?php
namespace Test\Fixture\File;

use PHPUnit\Framework\TestCase;

class FileCreateDirectoryTest extends TestCase
{
    private File $parent;

    /**
     * @before
     */
    public function uniqueParent(): void
    {
        $this->parent = File::temporaryDirectory()->join(\uniqId());
    }

    /**
     * @test
     */
    public function directory(): void
    {
        $directory = $this->parent->join('directory');
        $directory->createDirectory();
        $this->assertDirectoryExists($directory->path);
    }

    /**
     * @test
     */
    public function createDirectoryIfNotExists(): void
    {
        // given
        $directory = $this->existingDirectory('directory');
        // when
        $directory->createDirectory();
        // then
        $this->assertTrue(\is_dir($directory->path));
    }

    private function existingDirectory(string $path): File
    {
        $directory = $this->parent->join($path);
        \mkDir($directory->path, recursive:true);
        return $directory;
    }
}
