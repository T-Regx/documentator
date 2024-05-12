<?php
namespace Test\Acceptance;

use Documentary\Project;
use Documentary\ProjectClass;
use PHPUnit\Framework\TestCase;
use Test\Fixture\File\File;
use Test\Fixture\PhpDocumentor\PhpDocumentor;

class ExampleProjectTest extends TestCase
{
    /**
     * @test
     * @large
     * @doesNotPerformAssertions
     */
    public function inspectHtmlView()
    {
        $this->documentProject();
        $this->phpDocumentorRenderHtml();
    }

    private function documentProject(): void
    {
        $project = new Project($this->projectLocation()->path);

        $project->addSummary(
            'Regex\Regex',
            'Represents a regular expression; can be used to predicate, count, extract, replace and split a string subject; filter a string array; obtain a Matcher object; perform a partial match; inspect capturing groups.');

        $this->addPatternMethods($project, 'Regex\Regex');

        $project->addSummary(
            'Regex\Pattern',
            'A standard/default implementation of Regex, accepting an undelimited regular expression and modifiers.',
            \implode("\n", [
                '<p>Instantiating {@see \Regex\Pattern} with a malformed regular expression, throws {@see \Regex\SyntaxException}.</p>',
                '<p>Instantiating {@see \Regex\Pattern} with an unexpected modifier, throws {@see \Regex\ModifierException}.</p>',
            ]));

        $pattern = $project->class('Regex\Pattern');
        $this->addModifiers($pattern);

        $project->addSummary(
            'Regex\PregPattern',
            "An implementation of Regex interface, accepting a delimited PCRE regular expression.",
            '<p>Instantiating {@see \Regex\PregPattern} with a malformed regular expression, throws {@see \Regex\SyntaxException}.</p>');

        $project->addSummary(
            'Regex\Detail',
            'Represents a matched occurrence of a regular expression in a subject; contains the matched text, the match position in the subject, the capturing groups and the ordinal number of the match.');

        $this->addMatcher($project);
        $this->addDetail($project);
        $this->addExceptions($project);

        $project->addSummary(
            'Regex\Internal\CompiledPattern',
            'This class should not be used directly, as it is part of internal implementation and is subject to change without warning (use Pattern or Regex instead).');

        foreach ($this->internalClasses() as $className) {
            $project->hide($className, type:'class');
        }
        foreach ($this->privateMethods() as $private) {
            $project->hide($private, type:'method');
        }
        foreach ($this->privateProperties() as $private) {
            $project->hide($private, type:'property');
        }
        $project->build();
    }

    private function projectLocation(): File
    {
        if (\DIRECTORY_SEPARATOR === '/') {
            return new File($_SERVER['HOME'] . '/Projects/pattern/src');
        }
        return new File('C:\Users\Daniel\PhpstormProjects\pattern\src');
    }

    private function phpDocumentorRenderHtml(): void
    {
        $documentor = new PhpDocumentor(File::temporaryDirectory());
        $documentor->renderHtml($this->projectLocation(), __DIR__ . '/resources/');
    }

    private function internalClasses(): array
    {
        $classNames = $this->classNames(
            $this->projectLocation()->join('Internal'),
            'Regex\Internal\\');
        return \array_diff($classNames, [
            'Regex\Internal\CompiledPattern',
        ]);
    }

    private function privateMethods(): array
    {
        return [
            'disqualified', '__construct',
            'existingGroup', 'matchedGroup',
        ];
    }

    private function privateProperties(): array
    {
        return [
            'groupKeys', 'matches', 'match',
            'delimited', 'groups', 'pcre',
            'capture', 'text', 'subject', 'offset', 'index',
        ];
    }

    private function classNames(File $folder, string $prefix): array
    {
        $classNames = [];
        foreach (\scanDir($folder->path) as $fileName) {
            if (\str_ends_with($fileName, '.php')) {
                $classNames[] = $prefix . \subStr($fileName, 0, \strLen($fileName) - 4);
            }
        }
        return $classNames;
    }

    private function addMatcher(Project $project): void
    {
        $matcher = $project->class('Regex\Matcher');
        $matcher->addSummary('Holds matched occurrences of a regular expression in a subject; can be used to obtain and count the matches.');
        $matcher->addMethodSummary('count', 'Get the number of matches; returns 0 if the subject is not matched.');
        $matcher->addMethodSummary('test', 'Check whether the matcher holds any matches; returns false if the subject is not matched.');
        $matcher->addMethodSummary('all', 'Get all matches; returns an empty array if the subject is not matched.'); // see NoMatchException
        $matcher->addMethodSummary('first', 'Get the first match; throws NoMatchException if the subject is not matched.'); // see NoMatchException
        $matcher->addMethodSummary('firstOrNull', 'Get the first match; returns null if the subject is not matched.'); // see NoMatchException
    }

    private function addDetail(Project $project): void
    {
        $detail = $project->class('Regex\Detail');
        $detail->addMethodSummary('text', 'Get the text of the occurrence matched by regular expression; alias for group(0).');
        $detail->addMethodSummary('subject', 'Get the subject in which the Detail was matched.');
        $detail->addMethodSummary('index', 'Get the ordinal number of the Detail; the index increments in order in which the occurrence was matched in the subject.');

        $detail->addMethodSummary('group', 'Get the capturing group by name or by index; throws GroupException if the group is not matched or not present in the pattern.');
        $detail->addMethodSummary('groupOrNull', 'Get the capturing group by name or by index; returns null if the group is not matched; throws GroupException if the group is not present in the pattern.');

        $detail->addMethodSummary('groupExists', 'Check whether a given capturing group is present in the pattern; index 0 is the whole match.');
        $detail->addMethodSummary('groupMatched', 'Check whether a given capturing group is matched; throws GroupException if the group is not present in the pattern.');

        $detail->addMethodSummary('offset', 'Get the position of the matched occurrence within the subject, in unicode characters.');
        $detail->addMethodSummary('byteOffset', 'Get the position of the matched occurrence within the subject, in bytes.');

        $detail->addMethodSummary('groupOffset', 'Get the position of a capturing group within the subject, in unicode characters; throws GroupException of the group is not matched.');
        $detail->addMethodSummary('groupByteOffset', 'Get the position of a capturing group within the subject, in bytes; throws GroupException of the group is not matched.');

        $detail->addMethodSummary('__toString', 'Casting Detail to string is the same as calling text().');
    }

    private function addPatternMethods(Project $project, string $className): void
    {
        $pattern = $project->class($className);
        $singleMatch = 'Execute a regular expression single match';
        $pattern->addMethodSummary('test', "$singleMatch; returns true if the subject is matched, false otherwise.");
        $pattern->addMethodSummary('first', "$singleMatch; returns a Detail object representing the match, throws NoMatchException if the subject is not matched.");
        $pattern->addMethodSummary('firstOrNull', "$singleMatch; returns a Detail object representing the match, returns null if the subject is not matched.");

        $globalMatch = 'Execute a regular expression global match';
        $pattern->addMethodSummary('count', "$globalMatch; returns number of matches, return 0 if the subject is not matched.");
        $pattern->addMethodSummary('search', "$globalMatch; returns matches as a string array, returns an empty array if the subject is not matched.");
        $pattern->addMethodSummary('searchGroup', "$globalMatch; returns a given capturing group of all matches as a string array, returns an empty array if the subject is not matched.");
        $pattern->addMethodSummary('match', "$globalMatch; returns a Matcher object holding the matches.");
        $pattern->addMethodSummary('matchPartial', "$globalMatch, up to the point of execution error; returns a Detail array.");

        $pattern->addMethodSummary('replace', "$globalMatch, substituting occurrences with a constant string; returns the subject unchanged if the subject is not matched.");
        $pattern->addMethodSummary('replaceCount', "$globalMatch, substituting occurrences with a constant string; returns the the result subject and number of replacements performed.");
        $pattern->addMethodSummary('replaceGroup', "$globalMatch, substituting occurrences with a capturing group; throws GroupException if the capturing group is not matched.");
        $pattern->addMethodSummary('replaceCallback', "$globalMatch, substituting occurrences using a closure that accepts Detail argument.");
        $pattern->addMethodSummary('split', "$globalMatch; returns the subject split by occurrences as string array, also including matched capturing groups in the array.");

        $pattern->addMethodSummary('filter', 'Execute regular expression for an array; returns a new array, containing only the items that match the pattern.');
        $pattern->addMethodSummary('reject', 'Execute regular expression for an array; returns a new array, containing only the items that do not match the pattern.');
        $pattern->addMethodSummary('groupNames', 'Get the names of the capturing groups present in the pattern; unnamed groups are returned as null.');
        $pattern->addMethodSummary('groupExists', 'Check whether a given capturing group is present in the pattern; index 0 is the whole match.');
        $pattern->addMethodSummary('groupCount', 'Get the number of capturing groups present in the pattern, excluding the whole match.');
        $pattern->addMethodSummary('delimited', 'Get the delimited regular expression, as is passed to PCRE execution.');
        $pattern->addMethodSummary('__toString', 'Casting the pattern to string is the same as calling delimited().');
    }

    private function addExceptions(Project $project): void
    {
        $project->addSummary('Regex\RegexException', 'Base class for all exceptions in the library.');

        $project->addSummary('Regex\MatchException', 'Base class for exceptions thrown during matching.');
        $project->addSummary('Regex\ExecutionException', 'Thrown during matching, when an undetermined failure of PCRE prevents a successful match.');
        $project->addSummary('Regex\BacktrackException', 'Thrown during matching, when number of backtracks exceeds system backtrack limit, to prevent catastrophic backtracking.');
        $project->addSummary('Regex\JitException', 'Thrown during matching, when stack memory size of just-in-time compiler is exhausted.');
        $project->addSummary('Regex\RecursionException', 'Thrown during matching, when number of recursed patterns exceeds system recursion limit, to prevent infinite recursion.');
        $project->addSummary('Regex\UnicodeException', 'Thrown during matching or pattern instantiation, when either a pattern or a subject contains malformed unicode characters; or extracting an improper unicode offset.');

        $project->addSummary('Regex\ModifierException', 'Thrown during pattern instantiation, when an unexpected modifier is used.');
        $project->addSummary('Regex\DelimiterException', 'Thrown during pattern instantiation, when a pattern cannot be delimited.');

        $project->addSummary('Regex\PatternException', 'Base class for exceptions thrown during pattern instantiation regarding PCRE pattern.');
        $project->addSummary('Regex\PcreException', 'Thrown during pattern instantiation, due to an undetermined failure of PCRE.');
        $this->addSyntaxException($project, 'Regex\SyntaxException');

        $project->addSummary('Regex\NoMatchException', 'Thrown after matching, when extracting the first match given that subject is not matched.');
        $project->addSummary('Regex\GroupException', 'Thrown after matching, when extracting a capturing group: missing, not matched or with an invalid name or index.');
    }

    private function addSyntaxException(Project $project, string $className): void
    {
        $exception = $project->class($className);
        $exception->addSummary('Thrown during pattern instantiation, when a regular expression contains malformed PCRE syntax.');
        $exception->addPropertySummary('syntaxErrorPattern', 'The regular expression containing the malformed syntax that caused the exception.');
        $exception->addMethodSummary('syntaxErrorStart', 'Get the position of the malformed expression within the pattern, in unicode characters.');
        $exception->addPropertySummary('syntaxErrorByteOffset', 'The position of the malformed expression within the pattern, in bytes.');
    }

    private function addModifiers(ProjectClass $pattern): void
    {
        $pattern->addConstantSummary('IGNORE_CASE', 'Modifier "case-insensitive"; matching is case-insensitive.');
        $pattern->addConstantSummary('MULTILINE', 'Modifier "multiline"; regular expression assertions ^ and $ petain to lines, instead of the subject.');
        $pattern->addConstantSummary('UNICODE', 'Modifier "unicode"; enable additional unicode features: unicode characters, \p{}, extended properties.');
        $pattern->addConstantSummary('COMMENTS_WHITESPACE', 'Modifier "extended"; permit redundant comments and whitespace to be added for readability.');
        $pattern->addConstantSummary('EXPLICIT_CAPTURE', 'Modifier "no auto-capture"; only explicitly named capturing groups are captured.');
        $pattern->addConstantSummary('ANCHORED', 'Modifier "anchored"; only match leading, consecutive occurrences.');
        $pattern->addConstantSummary('DUPLICATE_NAMES', 'Modifier "duplicate capturing group names"; multiple capturing groups with the same name are permitted.');
        $pattern->addConstantSummary('INVERTED_GREEDY', 'Modifier "greedyness inverted"; quantifiers are reluctant, quantifiers followed by regular expression special character ? are greedy.');
        $pattern->addConstantSummary('SINGLELINE', 'Modifier "single-line"; regular expression special character period also matches a newline.');
    }
}
