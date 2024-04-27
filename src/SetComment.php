<?php
namespace Documentary;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeVisitorAbstract;

class SetComment extends NodeVisitorAbstract
{
    /** @var callable */
    private $memberComment;

    public function __construct(callable $memberComment)
    {
        $this->memberComment = $memberComment;
    }

    public function enterNode(Node $node): void
    {
        [$memberName, $memberType] = $this->member($node);
        if ($memberName) {
            $this->setComment($node, $memberName, $memberType);
        }
    }

    private function setComment(Node $node, string $memberName, ?string $memberType): void
    {
        [$comment, $type] = ($this->memberComment)($memberName, $memberType);
        if ($comment) {
            if ($type === null || $type === $memberType) {
                $node->setDocComment(new Doc($comment));
            }
        }
    }

    private function member(Node $node): ?array
    {
        if (isset($node->namespacedName)) {
            return [$node->namespacedName->toCodeString(), 'class'];
        }
        if ($node instanceof ClassMethod) {
            return [$node->name->toString(), 'method'];
        }
        if ($node instanceof Property) {
            return [$this->propertyName($node), 'property'];
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
