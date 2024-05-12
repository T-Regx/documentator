<?php
require_once 'driver.php';

$anchored = [
    line('With ', mod('A'), ', the regular expression is constrained to only match at the end of the previous match, starting from the beginning the subject.'),
    section('Examples:', [
        listItem([
            line('Consecutive matches:'),
            line(pattern('foo|bar', 'A'), 'in', str('foobarfoo'), 'matches', tuple(['foo', 'bar', 'foo']), '.'),
            line('Each occurrence immediately follows the previous.'),
        ]),
        listItem([
            line('Non-consecutive occurrences:'),
            line(pattern('foo', 'A'), 'in', str('foo foo'), 'matches', tuple(['foo']), '.'),
            line("The second potential occurrence doesn't immediately follow the first."),
        ]),
    ]),
    section('Counter-examples:', [
        listItem([
            line('Example - ', reg('^'), 'vs', mod('A'), ':'),
            line(pattern('^foo'), 'in', str('foofoo'), 'matches', tuple(['foo']), '.'),
            line(pattern('foo', 'A'), 'in', str('foofoo'), 'matches', tuple(['foo', 'foo']), '.'),
        ]),
        listItem([
            line('Example - ', reg('.*'), 'with', mod('A'), ':'),
            line(pattern('.*foo', 'A'), 'in', str('1foo'), 'matches', str('1foo')),
            line(pattern('foo'), 'in', str('1foo'), 'matches', str('foo'), '.'),
        ]),
    ]),
];

$caseInsensitive = [
    line('By default, regular expression matching is', em('case-sensitive'), '.'),
    line('With', mod('i'), ' matching is', em('case-insensitive'), '.'),
    section('Examples:', [
        listItem([
            line(pattern('[a-z]', 'i'), 'is equal to', pattern('[a-zA-Z]'), '.'),
            line('Ranges', reg('[a-z]'), 'and', reg('[A-Z]'), 'both match lowercase and uppercase letters.'),
        ]),
        listItem([
            line(pattern('abc', 'i'), 'is equal to', pattern('[aA][bB][cC]'), '.'),
            line('Characters', reg('abc'), 'match both lowercase and uppercase letters.'),
        ]),
    ]),
    section('Notes:', [
        listItem([
            line('Without', mod('u'), ', case-insensitivity only applies to range', reg('[a-z]'), '.',
                'For example:' . pattern('ä', 'i'), em('does not'), 'match', str('Ä'), '.'),
        ]),
        listItem([
            line('With', mod('u'), ', case-insensitivity applies to all unicode letters.',
                'For example:', pattern('ä', 'i'), em('does'), 'match', str('Ä'), '.'),
        ]),
    ]),
];

$multiline = [
    line('By default, the subject is matched as consisting of a single sequence of characters,',
        'despite it actually containing potential newline characters (e.g. ', str("\n"), 'or', str("\r"), ').',
        'Assertion', reg('^'), 'matches only at the start of the string, while assertion ', reg('$'), ' matches only at the end of the string.'),

    line('With', mod('m'), ', assertions', reg('^'), 'and', reg('$'), 'match at the boundaries of ', em('each line'), '.'),

    list_([
        listItem([
            line('Assertion', reg('^'), 'matches immediately after a newline character or at the start of the subject.'),
        ]),
        listItem([
            line('Assertion', reg('$'), 'matches immediately before a newline character or at the end of the subject.'),
        ]),
    ]),

    section('Notes:', [
        listItem([
            line(mod('m'), "doesn't affect assertion", reg('\Z'), ', which always matches at the end of the subject.'),
        ]),
    ]),
];

$unicode = [
    line(
        'By default, the regular expression pattern and the subject are considered ASCII character',
        'strings. The regular expression and the subject may contain unicode characters, such as',
        str('€'), 'or', str('ä'), ', but without', mod('u'),
        'the characters are matched as bytes.'
    ),
    line('With ', mod('u'), 'both the regular expression and the subject are interpreted as unicode strings.'),
    section('Examples - quantifiers:', [
        listItem([
            line(
                pattern('€{2}', 'u'), 'matches', str('€€'),
                ', because quantifier', reg('{2}'), 'applies to the unicode character ', str('€'), ', matching it twice.'),
            line(
                'In contrast,', pattern('€{2}'), "doesn't match", str('€€'),
                ', but does match ', inlineCode('chr(226).chr(130).chr(172).chr(172)'), '. ',
                'Quantifier ', reg('{2}'), ' applies only to the very last byte, matching it twice,',
                'leaving the first two characters not quantified. To mimic unicode matching, use', pattern('(?>€){2}'), '.'
            ),
        ]),
        listItem([
            line(
                pattern('.'), 'in', str('€'), 'matches a single byte', inlineCode('226'), '.',
                'In contrast, ', pattern('.', 'u'), 'in', str('€'), 'matches unicode character', str('€'), '.'
            ),
        ]),
    ]),
    line('Without', mod('u'), 'each byte is treated as a character.',
        'With', mod('u'), ', each code point is treated as a single character.'),
    section('Examples - allowed subjects:', [
        listItem([
            line('By default, a subject which contains malformed unicode sequences is executed correctly,',
                'since the subject is interpreted as unrelated bytes.'),
        ]),
        listItem([
            line('With', mod('u'), ', subject containing malformed unicode sequence throws', inlineCode('UnicodeException'), '.'),
        ]),
    ]),
];

$commentsAndWhitespace = [
    line('By default, whitespaces in regular expression match their respective characters.',
        'Comments are permitted in a comment group:', pattern('foo(?#comment)bar'), '.'),
    line(
        'With', mod('x'), ", whitespace can be added in certain constructs for 
           readability, but is ignored unless explicitly escaped. Additionally, 
           comments can be added to each line for explanation. Comments resemble 
           bash-style/Perl-style comments, starting with", reg('#'), 'and ending with a newline:',
        pattern("foo #comment\nfoo", 'x'),
        '.'
    ),
    section('Examples - ignored whitespace:', [
        listItem([line(
            pattern('a b', 'x'),
            ' - whitespace at top level is', em('insignificant'), '.',
        )]),
        listItem([line(
            pattern('(a | b)', 'x'),
            ' - whitespace inside capturing groups is', em('insignificant'), '.',
        )]),
        listItem([line(
            pattern('(?= )', 'x'),
            ' - whitespace in look-aheads is', em('insignificant'), '.',
        )]),
        listItem([line(
            pattern('F {3}', 'x'),
            ' - between characters and quantifiers is', em('insignificant'), '.',
        )]),
    ]),

    section('Examples - significant whitespace:', [
        listItem([line(
            pattern('a\ b', 'x'),
            '- escaped whitespace is', em('significant'), '.'
        )]),
        listItem([line(
            pattern('a[ ]b', 'x'),
            '- whitespace in character class is', em('significant'), '.'
        )]),
        listItem([line(
            pattern('(?<a b>)', 'x'),
            '- whitespace in named group name is', em('invalid'), '.'
        )]),
        listItem([line(
            pattern('(?(con dit)', 'x'),
            '- whitespace in conditional assertion is', em('invalid'), '.'
        )]),
    ]),

    section('Examples - extended comment:', [
        listItem([line(
            pattern("[a-z]+#comment\n$", 'x', true),
            '- comment is', em('insignificant'), '.'
        )]),
        listItem([
            blockCodeTitle('Multiline pattern:', <<<multiline
                new Pattern('
                    [a-z]+     # any character
                    (foo|bar)  # "foo" or "bar"
                    $          # end of subject
                ', 'x');
                multiline,),
        ]),
    ]),
];

$explicitCapture = [
    line('By default, named and unnamed groups are capturing.'),
    list_([
        listItem([line(reg('(foo)'), '- indexed capturing group')]),
        listItem([line(reg('(?<name>foo)'), '- named capturing group')]),
    ]),
    line('With', mod('n'), ', the named capturing groups remain capturing,',
        'but unnamed groups are not capturing.'),
    section('Examples:', [
        listItem([
            line(pattern('(foo)', 'n'), 'is equal to', pattern('(?:foo)'), '.'),
        ]),
    ]),
    section('Notes:', [
        listItem([
            line('In absence of', mod('n'), ', non-capturing group can be used:', reg('(?:foo)'), '.'),
        ]),
        listItem([
            line(
                "We're aware that PCRE modifier ", reg('/n'), ' is only available in PHP 8.2, ',
                'but', mod('n'), 'can be used in any PHP version, due to backporting.',
            ),
        ]),
    ]),
];

$singleLine = [
    line('By default, special character', reg('.'), 'matches every character except for newline.'),
    line('With', mod('s'), ', special character', reg('.'), 'does match newline'),
    section('Examples:', [
        listItem([
            line(pattern('a.b'), "doesn't match", str("a\nb")),
        ]),
        listItem([
            line(pattern('a.b', 's'), 'does match', str("a\nb")),
        ]),
    ]),
    section('Notes:', [
        listItem([
            line('Without', mod('s'), ',', pattern('.'), 'is equal to', pattern('\N'), '.'),
        ]),
    ]),
];

$invertedGreedy = [
    line('By default, all quantifiers are', em('greedy'),
        ' - match as many times as possible (e.g. quantifier ',
        reg('{3,9}'), ' matches 9 times, if possible).'),

    line(
        'When quantifier is followed by ', reg('?'), ','
        , 'it is ', em('reluctant'), ' ("ungreedy").',
        'Reluctant quantifiers: ', regs('??', '*?', '+?', '{2,3}?'), ' match as few times as possible.'
    ),

    section('Examples:', [
        listItem([
            line(pattern('fo+'), 'in', str('foooo'), 'matches', tuple(['foooo']), '.'),
        ]),
        listItem([
            line(pattern('fo+?'), 'in', str('foooo'), 'matches', tuple(['fo']), '.'),
        ]),
        listItem([
            line(pattern('fo+', 'U'), 'in', str('foooo'), 'matches', tuple(['fo']), '.'),
        ]),
        listItem([
            line(pattern('fo+?', 'U'), 'in', str('foooo'), 'matches', tuple(['foooo']), '.'),
        ]),
    ]),
    section('Notes:', [
        listItem([
            line('Setting', mod('U'), "doesn't have effect on possessive quantifiers: ", regs('?+', '++', '*+'), '.'),
        ]),
    ]),
];

$duplicateNames = [
    line(
        'By default, duplicated subpattern names are considered a pattern error.',
        'In that case,', inlineCode('SyntaxException'), 'is thrown.'
    ),
    line('With', mod('J'), ', multiple groups may have identical names.'),
    section('Examples:', [
        listItem([line(pattern('(?<g>)(?<g>)'), 'throws', inlineCode('SyntaxException'), '.')]),
        listItem([line(pattern('(?<g>)(?<g>)', 'J'), 'executes without exception.')]),
    ]),
];

return [
    'A' => $anchored,
    'i' => $caseInsensitive,
    'm' => $multiline,
    'u' => $unicode,
    'x' => $commentsAndWhitespace,
    'n' => $explicitCapture,
    's' => $singleLine,
    'U' => $invertedGreedy,
    'J' => $duplicateNames,
];
