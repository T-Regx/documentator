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
    private array $phpDocs;

    public function __construct(readonly private string $path)
    {
        $this->phpDocs = [];
    }

    public function addSummary(string $memberName, string $summary, ?string $description): void
    {
        $this->validateSummary($summary);
        $this->phpDocs[] = [$memberName, "/** $summary\n$description */"];
    }

    public function hide(string $memberName): void
    {
        $this->phpDocs[] = [$memberName, "/** @internal */"];
    }

    public function build(): void
    {
        foreach ($this->phpDocs as [$memberName, $phpDoc]) {
            $this->documentFile($memberName, $phpDoc);
        }
    }

    private function validateSummary(string $summary): void
    {
        $trim = \trim($summary);
        if (\str_contains($trim, "\n")) {
            throw new \Exception('Failed to document a member with multiline summary.');
        }
        if (empty($trim)) {
            throw new \Exception('Failed to document a member with blank summary.');
        }
        if (!\str_ends_with($trim, '.')) {
            throw new \Exception('Failed to document a member with a summary not ending with a period.');
        }
    }

    private function documentFile(string $memberName, string $phpDoc): void
    {
        foreach ($this->projectFiles() as $path) {
            $content = \file_get_contents($path);
            \file_put_contents($path,
                $this->documentedSourceCode($content, $memberName, $phpDoc));
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

    private function documentedSourceCode(string $sourceCode, string $memberName, string $phpDoc): string
    {
        $parser = new Php7(new Lexer());
        $ast = $parser->parse($sourceCode);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        $traverser->addVisitor(new NameResolver(null, ['replaceNodes' => false]));
        $traverser->addVisitor(new SetPhpDoc($memberName, $phpDoc));
        return (new Standard)->printFormatPreserving(
            $traverser->traverse($ast),
            $ast,
            $parser->getTokens());
    }
}
