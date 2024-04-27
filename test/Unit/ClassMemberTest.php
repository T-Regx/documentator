<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class ClassMemberTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function sameMethodInDifferentClasses()
    {
        // given
        $this->file->sourceCodeMany(classes:['Foo', 'Bar'], method:'one');
        // when
        $this->project->summary('one', 'First.', parent:'Foo');
        $this->project->summary('one', 'Second.', parent:'Bar');
        $this->project->build();
        // then
        $this->assertSame(
            ['First.', 'Second.'],
            $this->preview->methodSummaries());
    }

    /**
     * @test
     */
    public function typeAndParent()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['bar']);
        // when
        $this->project->singleSummary('bar', 'Documented.', type:'method', parent:'Foo');
        // then
        $this->assertSame(
            ['Documented.'],
            $this->preview->methodSummaries());
    }

    /**
     * @test
     */
    public function prioritizeSpecificParent()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['bar']);
        // when
        $this->project->summary('bar', 'Specific.', parent:'Foo');
        $this->project->summary('bar', 'General.');
        $this->project->build();
        // then
        $this->assertSame(['Specific.'], $this->preview->methodSummaries());
    }
}
