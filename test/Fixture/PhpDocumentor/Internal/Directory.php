<?php
namespace Test\Fixture\PhpDocumentor\Internal;

readonly class Directory
{
    public string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        if (!\is_dir($this->path)) {
            \mkdir($this->path, recursive:true);
        }
    }

    public function join(string $path): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $path;
    }

    public function write(string $filePath, string $content): void
    {
        $this->writeFile($this->path . DIRECTORY_SEPARATOR . $filePath, $content);
    }

    private function writeFile(string $filePath, string $content): void
    {
        $directory = \dirName($filePath);
        if (!\file_exists($directory)) {
            \mkDir($directory, recursive:true);
        }
        \file_put_contents($filePath, $content);
    }
}
