<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class ProjectClassTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function summary()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->project->project->class('Foo')->addSummary('Class summary.');
        $this->project->project->build();
        // then
        $this->assertSame(['Class summary.'], $this->preview->classSummaries());
    }

    /**
     * @test
     */
    public function methodSummary()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['bar']);
        // when
        $this->project->project->class('Foo')->addMethodSummary('bar', 'Grouped method.');
        $this->project->project->build();
        // then
        $this->assertSame(['Grouped method.'], $this->preview->methodSummaries());
    }

    /**
     * @test
     */
    public function constantSummary()
    {
        // given
        $this->file->sourceCode(class:'Foo', constants:['bar']);
        // when
        $this->project->project->class('Foo')->addConstantSummary('bar', 'Grouped constant.');
        $this->project->project->build();
        // then
        $this->assertSame(['Grouped constant.'], $this->preview->constantSummaries());
    }

    /**
     * @test
     */
    public function filterByParent()
    {
        // given
        $this->file->sourceCode(class:'Foo', methods:['one']);
        // when
        $this->project->project->class('Bar')->addMethodSummary('one', 'First.');
        $this->project->build();
        // then
        $this->assertSame([''], $this->preview->methodSummaries());
    }

    /**
     * @test
     */
    public function filterByType()
    {
        // given
        $this->file->sourceCode(class:'Foo', properties:['bar']);
        // when
        $this->project->project->class('bar')->addSummary('Class.');
        $this->project->project->class('Foo')->addMethodSummary('bar', 'Method.');
        $this->project->project->class('Foo')->addConstantSummary('bar', 'Constant.');
        $this->project->build();
        // then
        $this->assertSame([''], $this->preview->propertySummaries());
    }

    /**
     * @test
     */
    public function propertySummary()
    {
        // given
        $this->file->sourceCode(class:'Foo', properties:['bar']);
        // when
        $this->project->project->class('Foo')->addPropertySummary('bar', 'Property.');
        $this->project->project->build();
        // then
        $this->assertSame(['Property.'], $this->preview->propertySummaries());
    }
}
