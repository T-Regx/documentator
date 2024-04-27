<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class SummaryTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function summary()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->project->singleSummary('Foo', 'Summary.');
        // then
        $this->assertSame(['Summary.'], $this->preview->classSummaries());
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
        $this->project->singleSummary('Foo', '  ');
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
        $this->project->singleSummary('Foo', 'No ending period');
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
        $this->project->singleSummary('Foo', "Foo\nBar.");
    }

    /**
     * @test
     */
    public function summaryTrailingNewline()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->project->singleSummary('Foo', "Summary.\n");
        // then
        $this->assertSame(['Summary.'], $this->preview->classSummaries());
    }

    /**
     * @test
     */
    public function description()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->project->singleSummary('Foo', 'Summary.', 'This is a description.');
        // then
        $this->assertSame('This is a description.', $this->preview->classDescription());
    }

    /**
     * @test
     */
    public function summaryByClassName()
    {
        // given
        $this->file->sourceCodeMany(['Foo', 'Bar']);
        // when
        $this->project->summary('Foo', 'First.');
        $this->project->summary('Bar', 'Second.');
        $this->project->build();
        // then
        $this->assertSame(['First.', 'Second.'], $this->preview->classSummaries());
    }
}
