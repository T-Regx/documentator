<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class PhpDocTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function summary()
    {
        // given
        $this->file->sourceCode(class:'Foo');
        // when
        $this->project->singleSummary('Foo', 'Summary.');
        // then
        $this->assertFileContent('<?php /** 
 * Summary.
 */
class Foo {  }');
    }

    /**
     * @test
     */
    public function multilineDescription()
    {
        // given
        $this->file->sourceCode(class:'Project');
        // when
        $this->project->singleSummary('Project', 'Summary.', "First.\nSecond.");
        // then
        $this->assertFileContent('<?php /** 
 * Summary.
 * First.
 * Second.
 */
class Project {  }');
    }

    private function assertFileContent(string $expectedContent): void
    {
        $this->assertSame($expectedContent, $this->preview->read());
    }
}
