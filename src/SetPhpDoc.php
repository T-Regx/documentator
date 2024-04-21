<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class SetPhpDoc extends NodeVisitorAbstract
{
    public function __construct(
        readonly private string $className,
        readonly private string $phpDoc)
    {
    }

    public function enterNode(Node $node): void
    {
        if (isset($node->namespacedName)) {
            if ($node->namespacedName->toCodeString() === $this->className) {
                $node->setDocComment(new Doc($this->phpDoc));
            }
        }
    }
}
