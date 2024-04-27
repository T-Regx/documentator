<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class NameConflictTest extends TestCase
{
    use Fixture\Setup\GhostProject;

    /**
     * @test
     */
    public function summaries(): void
    {
        $this->project->summary('foo', 'One.');
        $this->expectExceptionNameConflict('foo');
        $this->project->summary('foo', 'Two.');
    }

    /**
     * @test
     */
    public function hideAndSummary(): void
    {
        $this->project->hide('foo');
        $this->expectExceptionNameConflict('foo');
        $this->project->summary('foo', 'One.');
    }

    /**
     * @test
     */
    public function summaryAndHide(): void
    {
        $this->project->summary('bar', 'One.');
        $this->expectExceptionNameConflict('bar');
        $this->project->hide('bar');
    }

    private function expectExceptionNameConflict(string $memberName): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed to document element '$memberName' with multiple summaries.");
    }
}
