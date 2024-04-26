<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;

class HideClassTest extends TestCase
{
    use Fixture\PreviewFixture;

    /**
     * @test
     */
    public function hideClass()
    {
        // given
        $file = $this->fileWithContent('<?php class Foo {} class Bar {}');
        // when
        $project = new Project($file->path);
        $project->addSummary('Foo', 'Summary.', null);
        $project->hide('Bar');
        $project->build();
        // then
        $this->assertSame('Summary.', $this->classSummary($file));
    }
}
