<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;
use Test\Fixture\ProjectPreview;

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
        $project = new Project($file->path);
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
        $sourceCode = $this->sourceCodeFile();
        // when
        $project = new Project($sourceCode->path);
        $project->addClassSummary("Summary.\n", null);
        // then
        $this->assertSame('Summary.', $this->classSummary($sourceCode));
    }

    /**
     * @test
     */
    public function description()
    {
        // given
        $file = $this->sourceCodeFile();
        // when
        $project = new Project($file->path);
        $project->addClassSummary('Summary.', 'This is a description.');
        // then
        $this->assertSame('This is a description.', $this->classDescription($file));
    }

    private function classSummary(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classSummary();
    }

    private function classDescription(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classDescription();
    }

    private function sourceCodeFile(): File
    {
        return $this->fileWithContent('<?php class Foo {}');
    }

    private function fileWithContent(string $content): File
    {
        $file = File::temporaryDirectory()->join('file.php');
        $file->write($content);
        return $file;
    }
}
