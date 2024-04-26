<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
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
        if ($node instanceof Property) {
            if ($this->propertyName($node) === $this->memberName) {
                $node->setDocComment(new Doc($this->phpDoc));
            }
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
