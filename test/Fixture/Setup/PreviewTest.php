<?php
namespace Test\Fixture\Setup;

use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;

class PreviewTest extends TestCase
{
    private File $file;
    private Preview $preview;

    /**
     * @before
     */
    public function initialize(): void
    {
        $this->file = File::temporaryDirectory()->join('file.php');
        $this->preview = new Preview($this->file);
    }

    /**
     * @test
     */
    public function string(): void
    {
        $this->file->write('content');
        $this->assertSame('content', $this->preview->read());
    }
}
