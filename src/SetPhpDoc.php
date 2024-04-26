<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class SetPhpDoc extends NodeVisitorAbstract
{
    public function __construct(
        readonly private string $memberName,
        readonly private string $phpDoc)
    {
    }

    public function enterNode(Node $node): void
    {
        if (isset($node->namespacedName)) {
            if ($node->namespacedName->toCodeString() === $this->memberName) {
                $node->setDocComment(new Doc($this->phpDoc));
            }
        }
        if ($node instanceof ClassMethod) {
            if ($node->name->toString() === $this->memberName) {
                $node->setDocComment(new Doc($this->phpDoc));
            }
        }
    }
}
