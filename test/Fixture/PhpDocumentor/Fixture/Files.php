<?php
namespace Test\Fixture\PhpDocumentor\Fixture;

use Test\Fixture\File\File;
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

    private function file(string $fileName, string $content): File
    {
        $file = File::temporaryDirectory()->join($fileName);
        $file->write($content);
        return $file;
    }

    private function fileInDirectory(string $fileName, string $content): File
    {
        $directory = File::temporaryDirectory()->join(\uniqId());
        $directory->join($fileName)->write($content);
        return $directory;
    }

    private function directoryWithFiles(string $name, array $files): File
    {
        $file = File::temporaryDirectory()->join($name);
        foreach ($files as $fileName => $content) {
            $file->join($fileName)->write($content);
        }
        return $file;
    }
}
