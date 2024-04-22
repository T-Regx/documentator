<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture;
use Test\Fixture\File\File;

class MultipleFilesTest extends TestCase
{
    use Fixture\PreviewFixture;

    /**
     * @test
     */
    public function manyFiles()
    {
        // given
        $projectDirectory = $this->directoryWithFiles([
            'foo.php' => $this->sourceCode('Foo'),
            'bar.php' => $this->sourceCode('Bar'),
        ]);
        // when
        $project = new Project($projectDirectory->path);
        $project->addClassSummary('Foo', 'Lorem.', null);
        $project->addClassSummary('Bar', 'Ipsum.', null);
        $project->build();
        // then
        $this->assertSame(
            ['Ipsum.', 'Lorem.'],
            $this->classSummaries($projectDirectory));
    }

    /**
     * @test
     */
    public function nestedChildren()
    {
        // given
        $directory = $this->fileInPath(['nested', 'file.php'], $this->sourceCode('Foo'));
        // when
        $project = new Project($directory->path);
        $project->addClassSummary('Foo', 'Winter is coming.', null);
        $project->build();
        // then
        $this->assertSame(
            'Winter is coming.',
            $this->classSummary($directory));
    }

    private function sourceCode(string $className): string
    {
        return "<?php class $className {}";
    }

    private function directoryWithFiles(array $files): File
    {
        $directory = File::temporaryDirectory()->join(\uniqid());
        foreach ($files as $name => $content) {
            $directory->join($name)->write($content);
        }
        return $directory;
    }

    private function fileInPath(array $names, string $content): File
    {
        $directory = File::temporaryDirectory()->join(\uniqid());
        $directory->join(...$names)->write($content);
        return $directory;
    }
}
