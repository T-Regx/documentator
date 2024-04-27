<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class MemberTypeTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function notCommentDifferentTypes()
    {
        // given
        $this->file->sourceCode(class:'Foo', properties:['bar']);
        // when
        $this->project->singleSummary('bar', 'Documented.', type:'method');
        // then
        $this->assertSame([''], $this->preview->propertySummaries());
    }

    /**
     * @test
     */
    public function acceptAllTypes()
    {
        // given
        $this->file->sourceCode(class:'Foo', properties:['bar']);
        // when
        $this->project->singleSummary('bar', 'Documented.', type:null);
        // then
        $this->assertSame(['Documented.'], $this->preview->propertySummaries());
    }

    /**
     * @test
     */
    public function acceptSameTypeProperty()
    {
        // given
        $this->file->sourceCode(class:'Foo', properties:['bar']);
        // when
        $this->project->singleSummary('bar', 'Documented.', type:'property');
        // then
        $this->assertSame(['Documented.'], $this->preview->propertySummaries());
    }

    /**
     * @test
     */
    public function acceptSameTypeMethod()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['bar']);
        // when
        $this->project->singleSummary('bar', 'Documented.', type:'method');
        // then
        $this->assertSame(['Documented.'], $this->preview->methodSummaries());
    }

    /**
     * @test
     */
    public function acceptSameTypeClass()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->project->singleSummary('Foo', 'Documented.', type:'class');
        // then
        $this->assertSame(['Documented.'], $this->preview->classSummaries());
    }

    /**
     * @test
     */
    public function acceptConflictedNamesWithType()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['Foo']);
        // when
        $this->project->summary('Foo', 'Method.', type:'method');
        $this->project->summary('Foo', 'Class.', type:'class');
        $this->project->build();
        // then
        $this->assertSame(['Method.'], $this->preview->methodSummaries());
        $this->assertSame(['Class.'], $this->preview->classSummaries());
    }
}
