<?php
namespace Test\Fixture\File;

readonly class File
{
    public string $path;

    public function __construct(string $path, string...$children)
    {
        $this->path = \implode(\DIRECTORY_SEPARATOR, [$path, ...$children]);
    }

    public static function temporaryDirectory(): self
    {
        return new self(\sys_get_temp_dir());
    }

    public function join(string $path, string...$paths): self
    {
        return new File($this->path, $path, ...$paths);
    }

    public function read(): string
    {
        return \file_get_contents($this->path);
    }

    public function write(string $content): void
    {
        $this->createDirectoryIfNotExists(\dirName($this->path));
        \file_put_contents($this->path, $content);
    }

    private function createDirectoryIfNotExists(string $path): void
    {
        if (!\file_exists($path)) {
            \mkDir($path, recursive:true);
        }
    }

    public function parentDirectory(): File
    {
        return new self(\dirName($this->path));
    }

    public function baseName(): string
    {
        return \baseName($this->path);
    }
}
