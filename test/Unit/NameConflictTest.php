<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;

class NameConflictTest extends TestCase
{
    /**
     * @test
     */
    public function summaries(): void
    {
        $project = new Project('');
        $project->addSummary('foo', 'One.', null);
        $this->expectExceptionNameConflict('foo');
        $project->addSummary('foo', 'Two.', null);
    }

    /**
     * @test
     */
    public function hideAndSummary(): void
    {
        $project = new Project('');
        $project->hide('foo');
        $this->expectExceptionNameConflict('foo');
        $project->addSummary('foo', 'One.', null);
    }

    /**
     * @test
     */
    public function summaryAndHide(): void
    {
        $project = new Project('');
        $project->addSummary('bar', 'One.', null);
        $this->expectExceptionNameConflict('bar');
        $project->hide('bar');
    }

    private function expectExceptionNameConflict(string $memberName): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed to document element '{$memberName}' with multiple summaries.");
    }
}
