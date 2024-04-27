<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class SourceCodeTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function classNoNamespace()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->documentClass('Foo');
        // then
        $this->assertClassDocumented();
    }

    /**
     * @test
     */
    public function classNamespace()
    {
        // given
        $this->file->sourceCode(namespace:'Foo', class:'Bar');
        // when
        $this->documentClass('Foo\Bar');
        // then
        $this->assertClassDocumented();
    }

    private function documentClass(string $str): void
    {
        $this->project->singleSummary($str, 'magic value.');
    }

    private function assertClassDocumented(): void
    {
        $this->assertSame(['magic value.'], $this->preview->classSummaries());
    }
}
