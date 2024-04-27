<?php
namespace Test\Fixture\PhpDocumentor;

use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;
use Test\Fixture\Xml\Xml;

class PhpDocumentorRelativeTest extends TestCase
{
    use Fixture\Files;

    private PhpDocumentor $phpDocumentor;
    private string $systemWorkingDirectory;

    /**
     * @before
     */
    public function phpDocumentor(): void
    {
        $file = File::temporaryDirectory()->join('phpDoc');
        $file->createDirectory();
        $this->phpDocumentor = new PhpDocumentor($file);
    }

    /**
     * @before
     */
    public function workingDirectory(): void
    {
        $this->systemWorkingDirectory = \getCwd();
        \chDir($this->temporaryWorkingDirectory()->path);
    }

    /**
     * @after
     */
    public function restoreWorkingDirectory(): void
    {
        \chDir($this->systemWorkingDirectory);
    }

    /**
     * @test
     */
    public function relativePath(): void
    {
        // given
        $file = $this->otherDirectoryWithContent(['parent', 'source.php'], '<?php');
        // when
        $output = $this->phpDocumentor->document($file);
        // then
        $this->assertSame(
            ['parent/source.php'],
            (new Xml($output))->findMany('/project/file/@path'));
    }

    private function otherDirectoryWithContent(array $path, string $content): File
    {
        $file = $this->otherDirectory();
        $file->join(...$path)->write($content);
        return $file;
    }

    private function temporaryWorkingDirectory(): File
    {
        $parent = File::temporaryDirectory()->join('other');
        $parent->createDirectory();
        return $parent;
    }

    private function otherDirectory(): File
    {
        return new File('relative', \uniqId());
    }
}
