<?php
namespace Documentary;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use PhpParser\Parser\Php7;
use PhpParser\PrettyPrinter\Standard;

class Project
{
    private ProjectPath $path;
    private Comments $comments;

    public function __construct(string $path)
    {
        $this->path = new ProjectPath($path);
        $this->comments = new Comments();
    }

    public function addSummary(string $memberName, string $summary, ?string $description, string $type = null, string $parent = null): void
    {
        $this->validateSummary($summary);
        $this->comments->add($memberName, $type, $parent, "/** $summary\n$description */");
    }

    public function hide(string $memberName, string $type = null): void
    {
        $this->comments->add($memberName, $type, null, "/** @internal */");
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
        $traverser->addVisitor(new ParentConnectingVisitor());
        $traverser->addVisitor(new NameResolver(null, ['replaceNodes' => false]));
        $traverser->addVisitor(new SetComment($this->comments));
        return (new Standard)->printFormatPreserving(
            $traverser->traverse($ast),
            $ast,
            $parser->getTokens());
    }
}
