<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class SetPhpDoc extends NodeVisitorAbstract
{
    public function __construct(readonly private string $phpDoc)
    {
    }

    public function enterNode(Node $node): void
    {
        $node->setDocComment(new Doc($this->phpDoc));
    }
}
