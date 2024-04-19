<?php
namespace Test\Fixture\PhpDocumentor\Fixture;

use Test\Fixture\Xml\Xml;

trait Files
{
    private function fileDescription(string $xmlStructure): string
    {
        $actual = new Xml($xmlStructure);
        return $actual->find('/project/file/docblock/description');
    }

    private function filesDescriptions(string $xmlStructure): array
    {
        $actual = new Xml($xmlStructure);
        return $actual->findMany('/project/file/docblock/description');
    }

    private function sourceCode(string $fileSummary): string
    {
        return "<?php /** $fileSummary */";
    }

    private function file(string $fileName, string $content): string
    {
        $path = $this->path([\sys_get_temp_dir(), \uniqid(), $fileName]);
        $this->createFileInDirectory($path, $content);
        return $path;
    }

    private function fileInDirectory(string $fileName, string $content): string
    {
        return $this->createFileInDirectory(
            $this->path([\sys_get_temp_dir(), \uniqid(), $fileName]),
            $content);
    }

    private function path(array $files): string
    {
        return \implode(\DIRECTORY_SEPARATOR, $files);
    }

    private function createFileInDirectory(string $path, string $content): string
    {
        $directory = \dirName($path);
        $this->createDirectory($directory);
        \file_put_contents($path, $content);
        return $directory;
    }

    private function createDirectory(string $path): void
    {
        if (!\file_exists($path)) {
            \mkDir($path);
        }
    }

    private function directoryWithFiles(string $name, array $files): string
    {
        $path = $this->path([\sys_get_temp_dir(), $name]);
        $this->createDirectory($path);
        foreach ($files as $fileName => $content) {
            \file_put_contents($this->path([$path, $fileName]), $content);
        }
        return $path;
    }
}
