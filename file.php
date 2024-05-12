<?php
require 'vendor/autoload.php';

use Documentary\Documentation;

[$mods, $modifiers, $summaries, $simpleExamples] = require 'modifiers.php';
$patterns = require 'pattern.php';

class Project
{
    private string $root;
    private Documentation $documentation;

    public function __construct(Documentation $documentation, string $root)
    {
        $this->documentation = $documentation;
        $this->root = $root . DIRECTORY_SEPARATOR;
    }

    public function document(string $filename): void
    {
        $path = $this->root . $filename;
        $sourceCode = \file_get_contents($path);
        \file_put_contents($path, $this->documentation->documented($sourceCode));
    }
}

$doc = new Documentary\Documentation();
$project = new Project($doc, 'C:\Users\Daniel\PhpstormProjects\pattern');

$patternConstructor = new Documentary\PhpDoc();
$patternConstructor->setDescription('Create pattern.');
$patternConstructor->setArgument('pattern', 'string',
    \str_replace(['{'], ['&#123;'], $patterns));

$patternConstructor->setArgument('modifiers', 'string', $modifiers);

$testMethod = new Documentary\PhpDoc();
$testMethod->setDescription('check if the subject matches the pattern.');
$testMethod->setArgument('subject', 'string', 'the subject to check');
$testMethod->setReturnValue('bool', 'true if checks, false if not.');

$pattern = $doc->forClass('Regex\Pattern');
$pattern->method('__construct', $patternConstructor->toString());
foreach ($mods as $modifier => $constant) {
    $pattern->constant(
        $constant,
        \strip_tags($summaries[$modifier]) .
        "\n" .
        blockCode($simpleExamples[$modifier])
    );
}

$regex = $doc->forClass('Regex\Regex');
$regex->description('Represents a regular expression.');
$regex->method('test', $testMethod->toString());

$project->document('src\Pattern.php');
$project->document('src\Regex.php');

// todo: encode opening curly brace to html entity
