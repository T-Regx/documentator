<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;
use Test\Fixture\File\File;

class SummaryTest extends TestCase
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
        $project->addSummary('Foo', 'Summary.', null);
        $project->build();
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
        $this->expectExceptionMessage('Failed to document a member with blank summary.');
        // when
        $project = $this->project();
        $project->addSummary('Foo', '  ', 'Bar');
        $project->build();
    }

    /**
     * @test
     */
    public function summaryUnterminated(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document a member with a summary not ending with a period.');
        // when
        $project = $this->project();
        $project->addSummary('Foo', 'Word', 'Bar');
        $project->build();
    }

    /**
     * @test
     */
    public function summaryWithNewline(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document a member with multiline summary.');
        // when
        $project = $this->project();
        $project->addSummary('Foo', "Foo\nBar.", 'Bar');
        $project->build();
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
        $project->addSummary('Foo', "Summary.\n", null);
        $project->build();
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
        $project->addSummary('Foo', 'Summary.', 'This is a description.');
        $project->build();
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
        $project->addSummary('Foo', 'First.', null);
        $project->addSummary('Bar', 'Second.', null);
        $project->build();
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
