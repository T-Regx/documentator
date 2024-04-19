<?php
namespace Test\Fixture\PhpDocumentor;

use PHPUnit\Framework\TestCase;

class PhpDocumentorTest extends TestCase
{
    use Fixture\Files;

    private PhpDocumentor $phpDocumentor;

    /**
     * @before
     */
    public function phpDocumentor(): void
    {
        $this->phpDocumentor = new PhpDocumentor(\sys_get_temp_dir());
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
        $path = $this->directoryWithFiles('directory', [
            'file.php'  => $this->sourceCode('Selected'),
            'other.php' => $this->sourceCode('other'),
        ]);

        $output = $this->phpDocumentor->document($this->path([$path, 'file.php']));

        $this->assertSame(
            ['Selected'],
            $this->filesDescriptions($output));
    }
}
