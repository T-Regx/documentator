<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class HideClassTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function hideClass()
    {
        // given
        $this->file->sourceCodeMany(['Foo', 'Bar']);
        // when
        $this->project->summary('Foo', 'Summary.');
        $this->project->hide('Bar');
        $this->project->build();
        // then
        $this->assertSame(['Summary.'], $this->preview->classSummaries());
    }
}
