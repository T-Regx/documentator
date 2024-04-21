<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture\PhpDocumentor\PhpDocumentor;
use Test\Fixture\Xml\Xml;

class ClassSummaryTest extends TestCase
{
    /**
     * @test
     */
    public function summary()
    {
        // given
        $file = $this->sourceCodeFile();
        // when
        $project = new Project($file);
        $project->addClassSummary('Summary.', null);
        // then
        $this->assertSame('Summary.', $this->classSummary($file));
    }

    /**
     * @test
     */
    public function summaryEmpty(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document class with blank summary.');
        // when
        $project = new Project('');
        $project->addClassSummary('  ', 'Bar');
    }

    /**
     * @test
     */
    public function summaryUnterminated(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document class with a summary not ending with a period.');
        // when
        $project = new Project('');
        $project->addClassSummary('Word', 'Bar');
    }

    /**
     * @test
     */
    public function summaryWithNewline(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document class with multiline summary.');
        // when
        $project = new Project('');
        $project->addClassSummary("Foo\nBar.", 'Bar');
    }

    /**
     * @test
     */
    public function summaryTrailingNewline()
    {
        // given
        $file = $this->sourceCodeFile();
        // when
        $project = new Project($file);
        $project->addClassSummary("Summary.\n", null);
        // then
        $this->assertSame('Summary.', $this->classSummary($file));
    }

    /**
     * @test
     */
    public function description()
    {
        // given
        $file = $this->sourceCodeFile();
        // when
        $project = new Project($file);
        $project->addClassSummary('Summary.', 'This is a description.');
        // then
        $this->assertSame('This is a description.', $this->classDescription($file));
    }

    private function classSummary(string $path): string
    {
        return $this->phpDocumentorField($path,
            '/project/file/class/docblock/description');
    }

    private function classDescription(string $path): string
    {
        return $this->phpDocumentorField($path,
            '/project/file/class/docblock/long-description');
    }

    private function phpDocumentorField(string $path, string $xPath): string
    {
        $documentor = new PhpDocumentor(\sys_get_temp_dir());
        $output = new Xml($documentor->document($path));
        return $output->find($xPath);
    }

    private function sourceCodeFile(): string
    {
        $path = \sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'document' . DIRECTORY_SEPARATOR . 'file.php';
        \file_put_contents($path, '<?php class Foo {}');
        return $path;
    }
}
