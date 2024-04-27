<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class ClassNameNamespaceTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function summaryByClassNameNamespace()
    {
        // given
        $this->file->sourceCode(namespace:'Foo', class:'Bar');
        // when
        $this->project->singleSummary('Foo\Bar', 'First.');
        // then
        $this->assertSame(['First.'], $this->preview->classSummaries());
    }
}
