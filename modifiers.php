<?php

$descriptions = require 'descriptions.php';

function lCase(string $letter): string
{
    if (\cType_lower($letter)) {
        return 'lowercase';
    }
    return 'uppercase';
}

$_modifiers = [
    'i' => 'IGNORE_CASE',
    'm' => 'MULTILINE',
    'u' => 'UNICODE',
    'x' => 'COMMENTS_WHITESPACE',
    'n' => 'EXPLICIT_CAPTURE',
    'A' => 'ANCHORED',
    's' => 'SINGLELINE',
    'U' => 'INVERTED_GREEDY',
    'J' => 'DUPLICATE_NAMES',
];

$summaries = [
    'i' => 'Ignore letter case.',
    'm' => 'Enable multiline matching.',
    'u' => 'Enable unicode mode (unicode characters, <b>\p{}</b>, extended properties).',
    'x' => 'Permit additional comments and whitespace.',
    'n' => 'Only capture named groups, not indexed groups.',
    'A' => 'Only match leading, consecutive occurrences.',
    's' => 'Special character <b>.</b> (period) additionally matches newline.',
    'U' => 'Quantifiers are reluctant, unless followed by ? (question mark).',
    'J' => 'Permit capturing groups with the same name.',
];

$simpleExamples = [
    'i' => <<<'ignoreCase'
           $word = new Pattern('abc', Pattern::IGNORE_CASE); // or 'i'
           $word->test('ABC'); // (bool) true
           ignoreCase,
    'm' => <<<'multiline'
           $word = new Pattern('^\w+$', Pattern::MULTILINE); // or 'm'
           $word->search("abc\ndef\nghi\n"); // (array) ["abc", "def", "ghi"]
           multiline,
    'u' => <<<'unicode'
           $euro = new Pattern('\p{S}', Pattern::UNICODE); // or 'u'
           $euro->test('€'); // (bool) true
           unicode,
    'x' => <<<'extended'
           $url = new Pattern('
               https?:// # http or https
               (www\.)?  # optional www.
               [a-z.-]+  # host
           ', Pattern::COMMENTS_WHITESPACE); // or 'x'
           extended,
    'n' => <<<'explicitCapture'
           $pattern = new Pattern('(foo)(?<match>bar)', Pattern::EXPLICIT_CAPTURE); // or 'n'
           $pattern->first('foobar')->group(1); // (string) "bar"
           explicitCapture,
    's' => <<<'singleLine'
           $line = new Pattern('abc.def', Pattern::SINGLELINE); // or 's'
           $line->test("abc\ndef"); // (bool) true
           singleLine,
    'A' => <<<'anchored'
           $words = new Pattern('Foo|Bar|Car', Pattern::ANCHORED); // or 'A'
           $words->search("FooBar Car"); // (array) ['Foo', 'Bar']
           anchored,
    'U' => <<<'invertedGreedy'
           $chars = new Pattern('fo+', Pattern::INVERTED_GREEDY); // or 'U'
           $chars->search('foooo'); // (array) ['fo']
           invertedGreedy,

    'J' => <<<'duplicateNames'
           $pattern = new Pattern('(?<group>foo) (?<group>foo)', Pattern::DUPLICATE_NAMES); // or 'J'
           $pattern->groupNames(); // (array) ['group', null];
           duplicateNames,
];

function mods(callable $mapper): array
{
    global $_modifiers;
    return \array_map($mapper, \array_keys($_modifiers), \array_values($_modifiers));
}

$modifiers = \implode('', [
    line('Additional modifiers that extend/restrict the syntax of', inlineCode('Pattern'), 'and/or alter the execution of the regular expression.'),
    line('Multiple modifiers should be concatenated:', pattern('', 'miU'), '.'),
    line("Passing an empty string", str(''), "doesn't have any effect, although is allowed for completeness."),

    '<br>', // only because of PhpStorm

    section('Available modifiers:', mods(fn(string $modifier, string $name) => listItem([
        line(str($modifier), '(', lCase($modifier), ')', '-', '<strong>', $summaries[$modifier], '</strong>'),
        blockCode($simpleExamples[$modifier]),
        '<br>', // only because of PhpStorm
    ]))),

    section('Detailed modifier documentation:', mods(fn(string $modifier, string $name) => listItem([
        line(str($modifier), '(', lCase($modifier), ')', ' - ', '<strong>', $summaries[$modifier], '</strong>'),
        '<br>', // only because of PhpStorm
        ...$descriptions[$modifier],
        '<br>', // only because of PhpStorm
    ]))),
]);

return [$_modifiers, $modifiers, $summaries, $simpleExamples];
