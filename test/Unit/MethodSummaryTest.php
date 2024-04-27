<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class MethodSummaryTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function test()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['make']);
        // when
        $this->project->singleSummary('make', 'Method.');
        // then
        $this->assertSame(['Method.'], $this->preview->methodSummaries());
    }

    /**
     * @test
     */
    public function summaries()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['car', 'bike']);
        // when
        $this->project->summary('car', 'One.');
        $this->project->summary('bike', 'Two.');
        $this->project->build();
        // then
        $this->assertSame(['One.', 'Two.'], $this->preview->methodSummaries());
    }
}
