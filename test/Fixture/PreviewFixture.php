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

    function methodSummary(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->methodSummary();
    }

    function methodSummaries(File $sourceCode): array
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->methodSummaries();
    }

    function classDescription(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classDescription();
    }

    function propertySummary(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->propertySummary();
    }

    function propertySummaries(File $sourceCode): array
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->propertySummaries();
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
