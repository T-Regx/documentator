<?php
namespace Test\Fixture\File\Fixture;

trait Assertions
{
    private function assertIsTemporaryDirectory(string $path): void
    {
        $this->assertTrue($this->isTemporaryDirectory($path));
    }

    private function isTemporaryDirectory(string $path): bool
    {
        if (\DIRECTORY_SEPARATOR === '/') {
            return \str_starts_with($path, '/tmp');
        }
        return \str_contains($path, '/Temp/');
    }
}
