<?php
namespace Test\Fixture;

use Test\Fixture\File\File;

trait PreviewFixture
{
    function classSummary(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classSummary();
    }

    function classDescription(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classDescription();
    }

    function classSummaries(File $sourceCode): array
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classSummaries();
    }

    function fileWithContent(string $content): File
    {
        $file = File::temporaryDirectory()->join('file.php');
        $file->write($content);
        return $file;
    }
}
