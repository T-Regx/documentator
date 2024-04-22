<?php
namespace Documentary;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser\Php7;
use PhpParser\PrettyPrinter\Standard;

class Project
{
    private array $classSummaries;

    public function __construct(readonly private string $path)
    {
        $this->classSummaries = [];
    }

    public function addClassSummary(string $className, string $summary, ?string $description): void
    {
        $this->validateSummary($summary);
        $this->classSummaries[] = [$className, $summary, $description];
    }

    public function build(): void
    {
        foreach ($this->classSummaries as [$className, $summary, $description]) {
            $this->documentFile($className, $summary, $description);
        }
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

    private function documentFile(string $className, string $summary, ?string $description): void
    {
        foreach ($this->projectFiles() as $path) {
            $content = \file_get_contents($path);
            \file_put_contents($path,
                $this->documentedSourceCode($content, $className, $summary, $description));
        }
    }

    private function projectFiles(): array
    {
        if (\is_dir($this->path)) {
            return $this->children($this->path);
        }
        return [$this->path];
    }

    private function children(string $path): array
    {
        $result = [];
        foreach (\scanDir($path) as $child) {
            if (\in_array($child, ['.', '..'])) {
                continue;
            }
            $childPath = $path . \DIRECTORY_SEPARATOR . $child;
            if (\is_file($childPath)) {
                $result[] = $childPath;
            } else {
                \array_push($result, ...$this->children($childPath));
            }
        }
        return $result;
    }

    private function documentedSourceCode(string $sourceCode, string $className, string $summary, ?string $description): string
    {
        $parser = new Php7(new Lexer());
        $ast = $parser->parse($sourceCode);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        $traverser->addVisitor(new NameResolver(null, ['replaceNodes' => false]));
        $traverser->addVisitor(new SetPhpDoc($className, "/** $summary\n$description */"));
        return (new Standard)->printFormatPreserving(
            $traverser->traverse($ast),
            $ast,
            $parser->getTokens());
    }
}
