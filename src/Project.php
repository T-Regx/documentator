<?php
namespace Documentary;

readonly class Project
{
    public function __construct(private string $path)
    {
    }

    public function addClassSummary(string $summary, ?string $description): void
    {
        $this->validateSummary($summary);
        $this->documentFile($summary, $description);
    }

    private function validateSummary(string $summary): void
    {
        $trim = \trim($summary);
        if (\str_contains($trim, "\n")) {
            throw new \Exception('Failed to document class with multiline summary.');
        }
        if (empty($trim)) {
            throw new \Exception('Failed to document class with blank summary.');
        }
        if (!\str_ends_with($trim, '.')) {
            throw new \Exception('Failed to document class with a summary not ending with a period.');
        }
    }

    private function documentFile(string $summary, ?string $description): void
    {
        $content = \file_get_contents($this->path);
        \file_put_contents($this->path,
            $this->documentedSourceCode($content, $summary, $description));
    }

    private function documentedSourceCode(string $sourceCode, string $summary, ?string $description): string
    {
        return \subStr_replace(
            $sourceCode,
            "/** $summary\n$description */",
            6,
            0);
    }
}
