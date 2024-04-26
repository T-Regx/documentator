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
    private ProjectPath $path;
    private array $comments;

    public function __construct(string $path)
    {
        $this->comments = [];
        $this->path = new ProjectPath($path);
    }

    public function addSummary(string $memberName, string $summary, ?string $description): void
    {
        $this->validateSummary($summary);
        $this->addComment($memberName, "/** $summary\n$description */");
    }

    public function hide(string $memberName): void
    {
        $this->addComment($memberName, "/** @internal */");
    }

    private function addComment(string $memberName, string $comment): void
    {
        if (\array_key_exists($memberName, $this->comments)) {
            throw new \Exception("Failed to document element '$memberName' with multiple summaries.");
        }
        $this->comments[$memberName] = $comment;
    }

    public function build(): void
    {
        foreach ($this->path->projectFiles() as $path) {
            $content = \file_get_contents($path);
            \file_put_contents($path,
                $this->documentedSourceCode($content));
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

    private function documentedSourceCode(string $sourceCode): string
    {
        $parser = new Php7(new Lexer());
        $ast = $parser->parse($sourceCode);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        $traverser->addVisitor(new NameResolver(null, ['replaceNodes' => false]));
        $traverser->addVisitor(new SetComment($this->memberComment(...)));
        return (new Standard)->printFormatPreserving(
            $traverser->traverse($ast),
            $ast,
            $parser->getTokens());
    }

    private function memberComment(string $name): ?string
    {
        return $this->comments[$name] ?? null;
    }
}
