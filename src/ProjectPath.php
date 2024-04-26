<?php
namespace Documentary;

readonly class ProjectPath
{
    public function __construct(private string $path)
    {
    }

    public function projectFiles(): array
    {
        if (\is_dir($this->path)) {
            return $this->children($this->path);
        }
        return [$this->path];
    }

    private function children(string $path): array
    {
        $result = [];
        foreach (\scanDir($path) as $child) {
            if (!\in_array($child, ['.', '..'])) {
                \array_push($result, ...$this->fileNames($path . \DIRECTORY_SEPARATOR . $child));
            }
        }
        return $result;
    }

    private function fileNames(string $path): array
    {
        if (\is_file($path)) {
            return [$path];
        }
        return $this->children($path);
    }
}
