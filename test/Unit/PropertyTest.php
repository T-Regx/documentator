<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;

class PropertyTest extends TestCase
{
    use Fixture\PreviewFixture;

    /**
     * @test
     */
    public function test()
    {
        // given
        $file = $this->fileWithContent('<?php class Foo { var $prop; }');
        // when
        $project = new Project($file->path);
        $project->addSummary('prop', 'Property.', null);
        $project->build();
        // then
        $this->assertSame('Property.', $this->propertySummary($file));
    }

    /**
     * @test
     */
    public function properties()
    {
        // given
        $file = $this->fileWithContent('<?php class Foo { var $one; var $two; }');
        // when
        $project = new Project($file->path);
        $project->addSummary('two', 'Second.', null);
        $project->addSummary('one', 'First.', null);
        $project->build();
        // then
        $this->assertSame(['First.', 'Second.'], $this->propertySummaries($file));
    }

    /**
     * @test
     */
    public function rejectMultiplePropertyDeclaration()
    {
        $file = $this->fileWithContent('<?php class Foo { var $one, $two; }');
        $project = new Project($file->path);
        $project->hide('foo');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to document many properties in a single declaration.');
        $project->build();
    }
}
