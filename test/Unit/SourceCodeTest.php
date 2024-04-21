<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture\PhpDocumentor\PhpDocumentor;
use Test\Fixture\Xml\Xml;

class SourceCodeTest extends TestCase
{
    /**
     * @test
     */
    public function classNoNamespace()
    {
        $this->assertIsDocumented($this->sourceCode('<?php class Foo {}'));
    }

    /**
     * @test
     */
    public function classNamespace()
    {
        $this->assertIsDocumented($this->sourceCode('<?php
            namespace Foo;
            class Bar {}
        '));
    }

    private function assertIsDocumented(string $directory): void
    {
        $this->document($directory, 'Summary.');
        if ($this->classSummary($directory) !== 'Summary.') {
            $this->fail('Failed to assert that source code was properly documented.');
        } else {
            $this->assertTrue(true);
        }
    }

    private function document(string $directory, string $summary): void
    {
        $project = new Project($directory);
        $project->addClassSummary($summary, null);
    }

    private function sourceCode(string $sourceCode): string
    {
        $file = \sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'document' . DIRECTORY_SEPARATOR . 'file.php';
        \file_put_contents($file, $sourceCode);
        return $file;
    }

    private function classSummary(string $directory): string
    {
        return $this->phpDocumentorField($directory,
            '/project/file/class/docblock/description');
    }

    private function phpDocumentorField(string $inputDirectory, string $xPath): string
    {
        $documentor = new PhpDocumentor(\sys_get_temp_dir());
        $documentorOutput = new Xml($documentor->document($inputDirectory));
        return $documentorOutput->find($xPath);
    }
}
