<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;
use Test\Fixture\ProjectPreview;

class SourceCodeTest extends TestCase
{
    /**
     * @test
     */
    public function classNoNamespace()
    {
        $this->assertIsDocumented($this->sourceCode('<?php class Foo {}'));
    }

    /**
     * @test
     */
    public function classNamespace()
    {
        $this->assertIsDocumented($this->sourceCode('<?php
            namespace Foo;
            class Bar {}
        '));
    }

    private function assertIsDocumented(File $project): void
    {
        $this->document($project, 'Summary.');
        if ($this->classSummary($project) !== 'Summary.') {
            $this->fail('Failed to assert that source code was properly documented.');
        } else {
            $this->assertTrue(true);
        }
    }

    private function document(File $projectLocation, string $summary): void
    {
        $project = new Project($projectLocation->path);
        $project->addClassSummary($summary, null);
    }

    private function sourceCode(string $sourceCode): File
    {
        $file = File::temporaryDirectory()->join('file.php');
        $file->write($sourceCode);
        return $file;
    }

    private function classSummary(File $sourceCode): string
    {
        $preview = new ProjectPreview($sourceCode);
        return $preview->classSummary();
    }
}
