<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeVisitorAbstract;

class SetComment extends NodeVisitorAbstract
{
    public function __construct(readonly private Comments $comments)
    {
    }

    public function enterNode(Node $node): void
    {
        if (isset($node->namespacedName)) {
            $this->comment($node, 'class', $node->namespacedName->toCodeString());
        }
        if ($node instanceof Property) {
            $this->comment($node, 'property', $this->propertyName($node->props));
        }
        if ($node instanceof ClassConst) {
            $this->comment($node, 'constant', $this->propertyName($node->consts));
        }
        if ($node instanceof ClassMethod) {
            $this->comment($node, 'method', $node->name->toString());
        }
    }

    public function comment(Node $node, string $type, string $name): void
    {
        $comment = $this->comments->get($name, $type, $this->parentName($node));
        if ($comment) {
            $node->setDocComment(new Doc($comment));
        } else {
            $node->setAttribute('comments', []);
        }
    }

    private function parentName(Node $node): ?string
    {
        $parent = $node->getAttribute('parent');
        if ($parent instanceof Node\Stmt\ClassLike) {
            return $parent->namespacedName->toCodeString();
        }
        return null;
    }

    private function propertyName(array $declarations): string
    {
        if (\count($declarations) === 1) {
            return $declarations[0]->name->toString();
        }
        throw new \Exception('Failed to document many properties in a single declaration.');
    }
}
