<?php
namespace Test\Fixture;

use Test\Fixture\File\File;
use Test\Fixture\PhpDocumentor\PhpDocumentor;
use Test\Fixture\Xml\Xml;

readonly class ProjectPreview
{
    private Xml $structure;

    public function __construct(File $sourceCode)
    {
        $documentor = new PhpDocumentor(File::temporaryDirectory());
        $this->structure = new Xml($documentor->document($sourceCode));
    }

    public function classSummary(): string
    {
        return $this->structure->find('/project/file/class/docblock/description');
    }

    public function classDescription(): string
    {
        return $this->structure->find('/project/file/class/docblock/long-description');
    }
}
