<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class ConstantSummary extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function test()
    {
        // given
        $this->file->sourceCode(class:'Foo', constants:['FOO']);
        // when
        $this->project->singleSummary('FOO', 'Constant.');
        // then
        $this->assertSame(['Constant.'], $this->preview->constantSummaries());
    }
}
