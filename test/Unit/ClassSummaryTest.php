<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;
use Test\Fixture\File\File;

class ClassSummaryTest extends TestCase
{
    use Fixture\PreviewFixture;

    /**
     * @test
     */
    public function summary()
    {
        // given
        $file = $this->sourceCodeFile();
        // when
        $project = new Project($file->path);
        $project->addClassSummary('Foo', 'Summary.', null);
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
        $this->project()->addClassSummary('Foo', '  ', 'Bar');
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
        $this->project()->addClassSummary('Foo', 'Word', 'Bar');
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
        $this->project()->addClassSummary('Foo', "Foo\nBar.", 'Bar');
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
        $project->addClassSummary('Foo', "Summary.\n", null);
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
        $project->addClassSummary('Foo', 'Summary.', 'This is a description.');
        // then
        $this->assertSame('This is a description.', $this->classDescription($file));
    }

    /**
     * @test
     */
    public function summaryByClassName()
    {
        // given
        $file = $this->fileWithContent('<?php class Foo {} class Bar {}');
        // when
        $project = new Project($file->path);
        $project->addClassSummary('Foo', 'First.', null);
        $project->addClassSummary('Bar', 'Second.', null);
        // then
        $this->assertSame(['First.', 'Second.'], $this->classSummaries($file));
    }

    private function sourceCodeFile(): File
    {
        return $this->fileWithContent('<?php class Foo {}');
    }

    private function project(): Project
    {
        return new Project('');
    }
}
