<?php
namespace Test\Fixture\Setup;

use Test\Fixture\File\File;

readonly class FileWrapper
{
    public function __construct(private File $file)
    {
    }

    public function sourceCode(
        string $namespace = null,
        string $class = null,
        string $interface = null,
        array  $methods = [],
        array  $properties = [],
        array  $constants = []): void
    {
        $classBody = \implode(' ', [
            ...$this->formatEach($methods, 'function %() {}'),
            ...$this->formatEach($properties, 'var $%;'),
            ...$this->formatEach($constants, 'const % = 2;'),
        ]);
        $this->write(\trim(
            $this->namespace($namespace) .
            $this->parent($class, $interface) . " { $classBody }"
        ));
    }

    private function namespace(?string $namespace): string
    {
        if ($namespace) {
            return "namespace $namespace;";
        }
        return '';
    }

    private function parent(?string $class, ?string $interface): string
    {
        if ($interface === null) {
            return " class $class";
        }
        return " interface $interface";
    }

    public function sourceCodeMany(array $classes, string $method = null): void
    {
        $this->write(\implode(' ', $this->formatEach($classes, $this->manyFormat($method))));
    }

    private function manyFormat(?string $method): string
    {
        if ($method) {
            return "class % { function $method() {} }";
        }
        return 'class % {}';
    }

    private function formatEach(array $values, string $format): array
    {
        return \array_map(
            fn(string $value): string => \str_replace('%', $value, $format),
            $values,
        );
    }

    public function write(string $sourceCode): void
    {
        $this->file->write("<?php $sourceCode");
    }
}
