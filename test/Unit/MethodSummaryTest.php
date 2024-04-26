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
        $project->addSummary('make', 'Method.', null);
        $project->build();
        // then
        $this->assertSame('Method.', $this->methodSummary($file));
    }

    /**
     * @test
     */
    public function summaries()
    {
        // given
        $file = $this->fileWithContent('<?php class Foo { function car() {} function bike() {} }');
        // when
        $project = new Project($file->path);
        $project->addSummary('car', 'One.', null);
        $project->addSummary('bike', 'Two.', null);
        $project->build();
        // then
        $this->assertSame(['One.', 'Two.'], $this->methodSummaries($file));
    }
}
