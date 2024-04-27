<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class PropertyTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function properties()
    {
        // given
        $this->file->sourceCode(class:'Foo', properties:['one', 'two']);
        // when
        $this->project->summary('two', 'Second.');
        $this->project->summary('one', 'First.');
        $this->project->build();
        // then
        $this->assertSame(['First.', 'Second.'], $this->preview->propertySummaries());
    }

    /**
     * @test
     */
    public function rejectMultiplePropertyDeclaration()
    {
        // given
        $this->file->write('class Foo { var $one, $two; }');
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document many properties in a single declaration.');
        // when
        $this->project->build();
    }
}
