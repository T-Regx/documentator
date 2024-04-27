<?php
namespace Test\Unit;

use Documentary\Project;
use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;
use Test\Fixture\Setup\Preview;

class MultipleFilesTest extends TestCase
{
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
        $project->addSummary('Foo', 'Lorem.', null);
        $project->addSummary('Bar', 'Ipsum.', null);
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
        $project->addSummary('Foo', 'Winter is coming.', null);
        $project->build();
        // then
        $this->assertSame(
            ['Winter is coming.'],
            $this->classSummaries($directory));
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

    function classSummaries(File $sourceCode): array
    {
        $preview = new Preview($sourceCode);
        return $preview->classSummaries();
    }
}
