<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
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
            $this->comment($node, 'property', $this->propertyName($node));
        }
        if ($node instanceof ClassMethod) {
            $this->comment($node, 'method', $node->name->toString());
        }
    }

    public function comment(Node $node, string $type, string $name): void
    {
        $comment = $this->comments->get($name, $type);
        if ($comment) {
            $node->setDocComment(new Doc($comment));
        }
    }

    private function propertyName(Property $node): string
    {
        $names = $this->declarationNames($node);
        if (\count($names) === 1) {
            return $names[0];
        }
        throw new \Exception('Failed to document many properties in a single declaration.');
    }

    private function declarationNames(Property $node): array
    {
        $name = [];
        foreach ($node->props as $prop) {
            $name[] = $prop->name->toString();
        }
        return $name;
    }
}
