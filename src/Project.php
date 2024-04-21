<?php
namespace Documentary;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\Parser\Php7;
use PhpParser\PrettyPrinter\Standard;

readonly class Project
{
    public function __construct(private string $path)
    {
    }

    public function addClassSummary(string $summary, ?string $description): void
    {
        $this->validateSummary($summary);
        $this->documentFile($summary, $description);
    }

    private function validateSummary(string $summary): void
    {
        $trim = \trim($summary);
        if (\str_contains($trim, "\n")) {
            throw new \Exception('Failed to document class with multiline summary.');
        }
        if (empty($trim)) {
            throw new \Exception('Failed to document class with blank summary.');
        }
        if (!\str_ends_with($trim, '.')) {
            throw new \Exception('Failed to document class with a summary not ending with a period.');
        }
    }

    private function documentFile(string $summary, ?string $description): void
    {
        $content = \file_get_contents($this->path);
        \file_put_contents($this->path,
            $this->documentedSourceCode($content, $summary, $description));
    }

    private function documentedSourceCode(string $sourceCode, string $summary, ?string $description): string
    {
        $parser = new Php7(new Lexer());
        $ast = $parser->parse($sourceCode);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        $traverser->addVisitor(new SetPhpDoc("/** $summary\n$description */"));
        return (new Standard)->printFormatPreserving(
            $traverser->traverse($ast),
            $ast,
            $parser->getTokens());
    }
}
