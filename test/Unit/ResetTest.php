<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Test\Fixture;

class ResetTest extends TestCase
{
    use Fixture\Setup\SingleFileProject;

    /**
     * @test
     */
    public function test(): void
    {
        // given
        $this->file->write('/** previous */ class Foo {}');
        // then
        $this->project->build();
        // then
        $this->assertSame([''], $this->preview->classSummaries());
    }
}
