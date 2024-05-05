<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class InterfaceTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function test()
    {
        // given
        $this->file->sourceCode(interface:'Foo');
        // when
        $this->project->singleSummary('Foo', 'Summary.');
        // then
        $this->assertSame(['Summary.'], $this->preview->interfaceSummaries());
    }

    /**
     * @test
     */
    public function parent()
    {
        // given
        $this->file->sourceCode(interface:'Foo', methods:['bar']);
        // when
        $this->project->singleSummary('bar', 'Summary.', parent:'Foo');
        // then
        $this->assertSame(['Summary.'], $this->preview->methodSummaries());
    }
}
