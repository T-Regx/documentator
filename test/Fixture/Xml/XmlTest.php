<?php
namespace Test\Fixture\Xml;

use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    /**
     * @test
     */
    public function outputsFormattedString(): void
    {
        $xml = new Xml('<div><p>Foo</p></div>');
        $this->assertSame('<div>
  <p>Foo</p>
</div>', $xml->toString());
    }

    /**
     * @test
     */
    public function find(): void
    {
        $xml = new Xml('<div><p>Foo</p></div>');
        $this->assertSame('Foo', $xml->find('/div/p/text()'));
    }

    /**
     * @test
     */
    public function findNoSuchElement(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to locate node by xPath: /div/p/text()');
        // when
        $xml = new Xml('<div></div>');
        $xml->find('/div/p/text()');
    }

    /**
     * @test
     */
    public function findNotUnique(): void
    {
        // then
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to locate a unique node by xPath: /div/p');
        // when
        $xml = new Xml('<div><p></p><p></p></div>');
        $xml->find('/div/p');
    }

    /**
     * @test
     */
    public function findMany(): void
    {
        $xml = new Xml('<div><p>Foo</p><p>Bar</p></div>');
        $this->assertSame(['Foo', 'Bar'], $xml->findMany('/div/p/text()'));
    }
}
