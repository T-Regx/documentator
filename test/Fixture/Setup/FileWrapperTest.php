<?php
namespace Test\Fixture\Setup;

use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;

class FileWrapperTest extends TestCase
{
    private File $file;
    private FileWrapper $wrapper;

    /**
     * @before
     */
    public function initialize(): void
    {
        $this->file = File::temporaryDirectory()->join('file.php');
        $this->wrapper = new FileWrapper($this->file);
    }

    /**
     * @test
     */
    public function namespaceClass(): void
    {
        $this->wrapper->sourceCode(namespace:'Ns', class:'Something');
        $this->assertFile('<?php namespace Ns; class Something {  }');
    }

    /**
     * @test
     */
    public function topLevelClass(): void
    {
        $this->wrapper->sourceCode(class:'Something');
        $this->assertFile('<?php class Something {  }');
    }

    /**
     * @test
     */
    public function classMethod(): void
    {
        $this->wrapper->sourceCode(class:'Something', methods:['make']);
        $this->assertFile('<?php class Something { function make() {} }');
    }

    /**
     * @test
     */
    public function classMethods(): void
    {
        $this->wrapper->sourceCode(class:'Something', methods:['a', 'b']);
        $this->assertFile('<?php class Something { function a() {} function b() {} }');
    }

    /**
     * @test
     */
    public function classProperties(): void
    {
        $this->wrapper->sourceCode(class:'Something', properties:['a', 'b']);
        $this->assertFile('<?php class Something { var $a; var $b; }');
    }

    /**
     * @test
     */
    public function classes(): void
    {
        $this->wrapper->sourceCodeMany(['a', 'b']);
        $this->assertFile('<?php class a {} class b {}');
    }

    /**
     * @test
     */
    public function classesMethodMany(): void
    {
        $this->wrapper->sourceCodeMany(['a', 'b'], method:'foo');
        $this->assertFile('<?php class a { function foo() {} } class b { function foo() {} }');
    }

    private function assertFile(string $expectedContent): void
    {
        $this->assertSame($expectedContent, $this->file->read());
    }
}
