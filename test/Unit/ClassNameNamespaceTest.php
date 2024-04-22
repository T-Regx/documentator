<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;

class ClassNameNamespaceTest extends TestCase
{
    use Fixture\PreviewFixture;

    /**
     * @test
     */
    public function summaryByClassNameNamespace()
    {
        // given
        $file = $this->fileWithContent('<?php namespace Foo; class Bar {}');
        // when
        $project = new Project($file->path);
        $project->addClassSummary('Foo\Bar', 'First.', null);
        $project->build();
        // then
        $this->assertSame('First.', $this->classSummary($file));
    }
}
