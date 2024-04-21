<?php
namespace Test\Fixture\PhpDocumentor;

use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;

class PhpDocumentorTest extends TestCase
{
    use Fixture\Files;

    private PhpDocumentor $phpDocumentor;

    /**
     * @before
     */
    public function phpDocumentor(): void
    {
        $this->phpDocumentor = new PhpDocumentor(File::temporaryDirectory());
    }

    /**
     * @test
     */
    public function documentString(): void
    {
        $output = $this->phpDocumentor->documentString(
            $this->sourceCode('file documentation'));

        $this->assertSame(
            'file documentation',
            $this->fileDescription($output));
    }

    /**
     * @test
     */
    public function documentDirectory(): void
    {
        $output = $this->phpDocumentor->document(
            $this->fileInDirectory('file.php', $this->sourceCode('documentation')));

        $this->assertSame(
            'documentation',
            $this->fileDescription($output));
    }

    /**
     * @test
     */
    public function documentFile(): void
    {
        $output = $this->phpDocumentor->document(
            $this->file('file.php', $this->sourceCode('documentation')));

        $this->assertSame(
            'documentation',
            $this->fileDescription($output));
    }

    /**
     * @test
     */
    public function documentFileIgnoreOtherFiles(): void
    {
        $directory = $this->directoryWithFiles('directory', [
            'file.php'  => $this->sourceCode('Selected'),
            'other.php' => $this->sourceCode('other'),
        ]);

        $output = $this->phpDocumentor->document($directory->join('file.php'));

        $this->assertSame(
            ['Selected'],
            $this->filesDescriptions($output));
    }
}
