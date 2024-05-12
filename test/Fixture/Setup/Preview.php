<?php
namespace Test\Fixture\Setup;

use Test\Fixture\File\File;
use Test\Fixture\PhpDocumentor\PhpDocumentor;
use Test\Fixture\Xml\Xml;

readonly class Preview
{
    private PhpDocumentor $documentor;

    public function __construct(private File $sourceCode)
    {
        $this->documentor = new PhpDocumentor(File::temporaryDirectory());
    }

    public function classSummaries(): array
    {
        return $this->structure()->findMany('/project/file/class/docblock/description');
    }

    public function interfaceSummaries(): array
    {
        return $this->structure()->findMany('/project/file/interface/docblock/description');
    }

    public function methodSummaries(): array
    {
        return $this->structure()->findMany('/project/file/*/method/docblock/description');
    }

    public function constantSummaries(): array
    {
        return $this->structure()->findMany('/project/file/class/constant/docblock/description');
    }

    public function classDescription(): string
    {
        return $this->structure()->find('/project/file/class/docblock/long-description');
    }

    public function propertySummaries(): array
    {
        return $this->structure()->findMany('/project/file/class/property/docblock/description');
    }

    private function structure(): Xml
    {
        return new Xml($this->documentor->document($this->sourceCode));
    }

    public function read(): string
    {
        return $this->sourceCode->read();
    }
}
