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
        array  $methods = [],
        array  $properties = []): void
    {
        $this->write($this->sourceCodeAsString($namespace, $class, $methods, $properties));
    }

    private function sourceCodeAsString(?string $namespace, ?string $class, array $methods, array $properties): string
    {
        $classBody = \implode(' ', [
            ...$this->formatEach($methods, 'function %() {}'),
            ...$this->formatEach($properties, 'var $%;'),
        ]);
        return \trim($this->namespace($namespace) . " class $class { $classBody }");
    }

    private function namespace(?string $namespace): string
    {
        if ($namespace) {
            return "namespace $namespace;";
        }
        return '';
    }

    public function sourceCodeMany(array $classes): void
    {
        $this->write(\implode(' ', $this->formatEach($classes, 'class % {}')));
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