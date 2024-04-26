<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeVisitorAbstract;

class SetPhpDoc extends NodeVisitorAbstract
{
    /** @var callable */
    private $memberPhpDoc;

    public function __construct(callable $memberPhpDoc)
    {
        $this->memberPhpDoc = $memberPhpDoc;
    }

    public function enterNode(Node $node): void
    {
        $memberName = $this->memberName($node);
        if ($memberName) {
            $this->setPhpDoc($node, $memberName);
        }
    }

    private function setPhpDoc(Node $node, string $memberName): void
    {
        $phpDoc = ($this->memberPhpDoc)($memberName);
        if ($phpDoc) {
            $node->setDocComment(new Doc($phpDoc));
        }
    }

    private function memberName(Node $node): ?string
    {
        if (isset($node->namespacedName)) {
            return $node->namespacedName->toCodeString();
        }
        if ($node instanceof ClassMethod) {
            return $node->name->toString();
        }
        if ($node instanceof Property) {
            return $this->propertyName($node);
        }
        return null;
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
