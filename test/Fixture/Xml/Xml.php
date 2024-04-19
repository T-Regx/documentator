<?php
namespace Test\Fixture\Xml;

use DOMDocument;
use DOMXPath;

readonly class Xml
{
    private DOMDocument $document;
    private DOMXPath $xPath;

    public function __construct(string $xml)
    {
        $this->document = $this->xmlDocument($xml);
        $this->xPath = new DOMXPath($this->document);
    }

    private function xmlDocument(string $xml): DOMDocument
    {
        $document = new DOMDocument();
        $document->formatOutput = true;
        $document->preserveWhiteSpace = false;
        $document->loadXML($xml);
        return $document;
    }

    public function find(string $xPath): string
    {
        $query = $this->xPath->query($xPath);
        if ($query->count() > 1) {
            throw new \Exception("Failed to locate a unique node by xPath: $xPath");
        }
        foreach ($query as $element) {
            return $element->textContent;
        }
        throw new \Exception("Failed to locate node by xPath: $xPath");
    }

    public function findMany(string $xPath): array
    {
        $textNodes = [];
        foreach ($this->xPath->query($xPath) as $element) {
            $textNodes[] = $element->textContent;
        }
        return $textNodes;
    }

    public function toString(): string
    {
        return $this->document->saveXML($this->document->documentElement);
    }
}
