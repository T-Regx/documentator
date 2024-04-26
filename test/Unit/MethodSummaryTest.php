<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;

class MethodSummaryTest extends TestCase
{
    use Fixture\PreviewFixture;

    /**
     * @test
     */
    public function test()
    {
        // given
        $file = $this->fileWithContent('<?php class Foo { function make() {} }');
        // when
        $project = new Project($file->path);
        $project->addClassSummary('make', 'Method.', null);
        $project->build();
        // then
        $this->assertSame('Method.', $this->methodSummary($file));
    }
}
