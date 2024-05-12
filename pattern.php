<?php

require_once 'driver.php';

$unicodeProperties = [
    'L' => [
        'letter',
        'Lu' => 'uppercase letter',
        'Ll' => 'lowercase letter',
        'Lt' => 'title-case letter',
        'Lm' => 'modifier letter',
        'Lo' => 'other letter',
    ],
    'M' => [
        'mark',
        'Mn' => 'non-spacing mark',
        'Mc' => 'spacing mark',
        'Me' => 'enclosing mark'],
    'N' => [
        'number',
        'Nd' => 'decimal number',
        'Nl' => 'letter number',
        'No' => 'other number'],
    'P' => [
        'punctuation',
        'Pc' => 'connector punctuation',
        'Pd' => 'dash punctuation',
        'Ps' => 'open punctuation',
        'Pe' => 'close punctuation',
        'Pi' => 'initial punctuation',
        'Pf' => 'final punctuation',
        'Po' => 'other punctuation'],
    'S' => [
        'symbol',
        'Sm' => 'mathematical symbol',
        'Sc' => 'currency symbol',
        'Sk' => 'modifier symbol',
        'So' => 'other symbol'],
    'Z' => [
        'separator',
        'Zs' => 'space separator',
        'Zl' => 'line separator',
        'Zp' => 'paragraph separator'],
    'C' => [
        'other',
        'Cc' => 'control',
        'Cf' => 'format',
        'Cs' => 'surrogate',
        'Co' => 'private use',
        'Cn' => 'unassigned'],
];

$unicodeScriptNames = [
    'Adlam', 'Ahom', 'Anatolian_Hieroglyphs', 'Arabic', 'Armenian', 'Avestan', 'Balinese', 'Bamum', 'Bassa_Vah', 'Batak', 'Bengali,
    Bhaiksuki', 'Bopomofo', 'Brahmi', 'Braille', 'Buginese', 'Buhid', 'Canadian_Aboriginal', 'Carian', 'Caucasian_Albanian,
    Chakma', 'Cham', 'Cherokee', 'Chorasmian', 'Common', 'Coptic', 'Cuneiform', 'Cypriot', 'Cypro_Minoan', 'Cyrillic', 'Deseret,
    Devanagari', 'Dives_Akuru', 'Dogra', 'Duployan', 'Egyptian_Hieroglyphs', 'Elbasan', 'Elymaic', 'Ethiopic', 'Georgian,
    Glagolitic', 'Gothic', 'Grantha', 'Greek', 'Gujarati', 'Gunjala_Gondi', 'Gurmukhi', 'Han', 'Hangul', 'Hanifi_Rohingya,
    Hanunoo', 'Hatran', 'Hebrew', 'Hiragana', 'Imperial_Aramaic', 'Inherited', 'Inscriptional_Pahlavi,
    Inscriptional_Parthian', 'Javanese', 'Kaithi', 'Kannada', 'Katakana', 'Kayah_Li', 'Kharoshthi', 'Khitan_Small_Script,
    Khmer', 'Khojki', 'Khudawadi', 'Lao', 'Latin', 'Lepcha', 'Limbu', 'Linear_A', 'Linear_B', 'Lisu', 'Lycian', 'Lydian', 'Mahajani,
    Makasar', 'Malayalam', 'Mandaic', 'Manichaean', 'Marchen', 'Masaram_Gondi', 'Medefaidrin', 'Meetei_Mayek', 'Mende_Kikakui,
    Meroitic_Cursive', 'Meroitic_Hieroglyphs', 'Miao', 'Modi', 'Mongolian', 'Mro', 'Multani', 'Myanmar', 'Nabataean', 'Nandinagari,
    New_Tai_Lue', 'Newa', 'Nko', 'Nushu', 'Nyakeng_Puachue_Hmong', 'Ogham', 'Ol_Chiki', 'Old_Hungarian', 'Old_Italic,
    Old_North_Arabian', 'Old_Permic', 'Old_Persian', 'Old_Sogdian', 'Old_South_Arabian', 'Old_Turkic', 'Old_Uyghur', 'Oriya,
    Osage', 'Osmanya', 'Pahawh_Hmong', 'Palmyrene', 'Pau_Cin_Hau', 'Phags_Pa', 'Phoenician', 'Psalter_Pahlavi', 'Rejang', 'Runic,
    Samaritan', 'Saurashtra', 'Sharada', 'Shavian', 'Siddham', 'SignWriting', 'Sinhala', 'Sogdian', 'Sora_Sompeng', 'Soyombo,
    Sundanese', 'Syloti_Nagri', 'Syriac', 'Tagalog', 'Tagbanwa', 'Tai_Le', 'Tai_Tham', 'Tai_Viet', 'Takri', 'Tamil', 'Tangsa,
    Tangut', 'Telugu', 'Thaana', 'Thai', 'Tibetan', 'Tifinagh', 'Tirhuta', 'Toto', 'Ugaritic', 'Vai', 'Vithkuqi', 'Wancho', 'Warang_Citi,
    Yezidi', 'Yi', 'Zanabazar_Square',
];

$pattern = [
    line('Regular expression pattern, in compliance with PCRE2.'),
    '<br>', // only for PhpStorm
    section('Example usage:', [
        listItem([
            blockCodeTitle(
                'Regular expression matching multiple strings:',
                <<<'example'
                $link = new Pattern('https?://(www\.)?[a-z]+\.[a-z]+', 'i');
                $link->search($text);
                example,),
            line(
                'matches', str('http'), 'or', str('https'), ',',
                str('://'), ',', 'optional', str('www.'),
                ',', 'one or more letters, a period character', str('.'), ',',
                'one or more letters; all case-insensitive.'
            ),
            '<br>',// only for PhpStorm
        ]),
        listItem([
            blockCodeTitle('Predicate string format:', <<<'example'
                $check = new Pattern('\w+', Pattern::UNICODE);
                if ($check->test($string))
                example,),
            line('checks whether', inlineCode('$string'), 'is matched by regular expression.'),
            '<br>', // only for phpStorm
        ]),
    ]),
    section('Regular expression special characters (recognized outside character class):', [
        hyphen('\\', ['general-purpose escape']),
        hyphen('^', ['assertion start of subject; assertion start of line with ', mod('m')]),
        hyphen('$', ['assertion end of subject; assertion end of line with ', mod('m')]),
        hyphen('.', ['matches any non-newline character (similar to ', reg('\N'), ');', 'with ', mod('s'), 'matches any character including newline',]),
        hyphen('[', ['opening of character class']),
        hyphen('|', ['alternative branch, evaluated from left to right']),
        hyphen('(', ['opening group or opening control verb']),
        hyphen(')', ['closing group or closing control verb']),
        hyphen('{', ['absolute quantifier', ...eg(reg('{3,4}'))]),
        hyphen('?', ['"optional" quantifier', ...eq('{0,1}')]),
        hyphen('+', ['"at least one" quantifier', ...eq('{1,}')]),
        hyphen('*', ['"any amount" quantifier', ...eq('{0,}')]),
        hyphen('?', ['when follows a quantifier, the quantifier becomes reluctant', ...eg(regs('??', '+?', '*?', '{2,3}?'))]),
        hyphen('+', ['when follows a quantifier, the quantifier becomes possessive', ...eg(regs('?+', '++', '*+', '{2,3}+'))]),
    ]),
    section('Regular expression special characters (recognized inside character class):', [
        hyphen('\\', ['general-purpose escape (e.g. ', reg('[\^]'), 'matches', str('^'), ')']),
        hyphen('^', ['immediately after opened character class, negates the class ', ...eg(reg('[^123]'))]),
        hyphen(']', ['immediately after opened character class, matches character ', str(']')]),
        hyphen(']', ['closing character class']),
        hyphen('-', ['character range', ...eg(reg('[a-z]'), 'matches characters', str('a'), 'to', str('z'))]),
        hyphen('-', ['immediately after opening character class or immediately before closing character class matches', str('-'), ...eg(reg('[-]'))]),
        hyphen('[:#####:]', ['POSIX character class', ...eg(reg('[[:alpha:]]'))]),
    ]),
    section('Escaped special characters:', [
        listItem([
            line('Escaped non-alphanumeric character is matched', em('literally'), '(e.g. ', reg('\$'), 'matches', str('$'), ').'),
        ]),
        listItem([
            line('Escaped backslash', reg('\\\\'), 'matches character ', str('\\'), '.'),
        ]),
        listItem([
            section('Escaped numeric sequence matches either a backreference or a character represented by octal code:', [
                hyphen(reg('\23'), ['if the group is present in the pattern, then', reg('\23'), 'matches the group reference.']),
                hyphen(reg('\23'), ['if the group is not present, then', reg('\23'), ...eq('\023'), 'matches character of octal code ', inlineCode('23'), '.']),
            ]),
        ]),
    ]),
    section('Regular expression quantifiers:', [
        listItem([
            line(
                'Quantifiers are either', em('greedy'), 'or', em('reluctant'), '.',
                'Greedy quantifiers match as many occurrences as possible.',
                'Reluctant quantifiers match as little occurrences as possible.'),
        ]),
        listItem([
            line('Quantifiers are greedy by default, and become reluctant when followed by', reg('?'), '.'),
            line('With ', mod('U'), 'quantifiers are reluctant by default, and greedy when followed by', reg('?'), '.'),
        ]),
        listItem([
            line('Quantifiers followed by', reg('+'), 'are', em('possessive'), '.'),
            line('Possessive quantifiers prevent backtracking, thus being conceptually identical to an atomic group.'),
            line('Regular expression:', reg('a*+'), 'is identical to', reg('(?>a*)'), '.'),
        ]),
    ]),
    section('Regular expression quantifiers - greedy, unless' . mod('U') . ':', [
        hyphen(reg('?'), ['matches', var_('0'), 'or', var_('1'), 'times', ...eq('{,1}')]),
        hyphen(reg('*'), ['matches', em('any number'), 'of times', ...eq('{0,}')]),
        hyphen(reg('+'), ['matches', em('at least'), var_('1'), 'time', ...eq('{1,}')]),
        hyphen(reg('{a,b}'), ['matches', em('at least'), var_('a'), 'times,', em('at most'), var_('b'), 'times']),
        hyphen(reg('{n,}'), ['matches', em('at least'), var_('n'), 'times']),
        hyphen(reg('{n}'), ['matches', em('exactly'), var_('n'), 'times']),
    ]),
    section('Regular expression quantifiers - reluctant, unless' . mod('U') . ':', [
        hyphen(reg('??'), ['matches', var_('0'), 'or', var_('1'), 'times, reluctant', ...eq('{,1}?')]),
        hyphen(reg('*?'), ['matches', em('any number'), 'times, reluctant', ...eq('{0,}?')]),
        hyphen(reg('+?'), ['matches', em('at least'), var_('1'), 'time, reluctant', ...eq('{1,}?')]),
        hyphen(reg('{a,b}?'), ['matches', em('at least'), var_('a'), 'times,', em('at most'), var_('b'), 'times, reluctant']),
        hyphen(reg('{n,}?'), ['matches', em('at least'), var_('n'), 'times, reluctant']),
        hyphen(reg('{n}?'), ['matches', em('exactly'), var_('n'), 'times, reluctant']),
    ]),
    section('Regular expression quantifiers - possessive', [
        hyphen(reg('?+'), ['matches', var_('0'), 'or', var_('1'), 'times, possessive', ...eq('{,1}+')]),
        hyphen(reg('*+'), ['matches', em('any number'), 'times, possessive', ...eq('{0,}+')]),
        hyphen(reg('++'), ['matches', em('at least'), var_('1'), 'time, possessive', ...eq('{1,}+')]),
        hyphen(reg('{a,b}+'), ['matches', em('at least'), var_('a'), 'times,', em('at most'), var_('b'), 'times, possessive']),
        hyphen(reg('{n,}+'), ['matches', em('at least'), var_('n'), 'times, possessive']),
        hyphen(reg('{n}+'), ['matches', em('exactly'), var_('n'), 'times, possessive']),
    ]),
    section('Predefined characters:', [
        hyphenComplex([reg('\t'), ...eq('\x09')], ['tab, matches', chr_(9)]),
        hyphenComplex([reg('\n'), ...eq('\x0a')], ['line feed, matches', chr_(10)]),
        hyphenComplex([reg('\f'), ...eq('\x0c')], ['form feed, matches', chr_(12)]),
        hyphenComplex([reg('\r'), ...eq('\x0d')], ['carriage return, matches', chr_(13)]),
        hyphenComplex([reg('\e'), ...eq('\x1b')], ['escape, matches', chr_(27)]),
        hyphenComplex([reg('\c'), 'followed by a character'], ['control character', ...eg(reg('\ca'), 'matches', chr_(1))]),
    ]),
    section('Metacharacters:', [
        hyphenComplex(
            [reg('\\'), '###'],
            [
                'backreference to group or character of octal code',
                ...eg(reg('\23'), 'matches backreference to a group ', var_(23), 'or character of octal code', inlineCode(23)),
            ]),
        hyphenComplex(
            [reg('\0'), '## (zero)'],
            ['character of octal code', ...eg(reg('\023'), 'matches', chr_(19))]),
        hyphenComplex(
            [reg('\x'), '## (lowercase "x")'],
            ['character of hexadecimal code', ...eg(reg('\xab'), 'matches', chr_(171))]),
        hyphenComplex(
            [reg('\o{##}'), '(lowercase "o")'],
            ['character of octal code', ...eg(reg('\o{234}'), 'matches', chr_(156))]),
        hyphenComplex(
            [reg('\x{##}'), '(lowercase "x")'],
            ['character of hexadecimal code', ...eg(reg('\x{2ab}'), 'matches', str('\xCA\xAB', true))]),
        hyphen(
            reg('\x'),
            ['matches', chr_(0), ', equal to', reg('\0'), '(zero)']),
        hyphen(
            reg('\N{U+##}'),
            ['with', mod('u'), ', ', 'matches unicode code point, similar to', reg('\x{##}'), '.']),
        hyphenComplex(
            [reg('\N'), '(uppercase "N")'],
            [
                'matches any non-newline character, similar to ', reg('.'), '(period).',
                list_([
                    listItem([
                        line(mod('s'), 'makes', reg('.'), '(period)', 'match newline character,',
                            'but', reg('\N'), "doesn't match newline regardless of", mod('s'), '.',),
                    ]),
                    listItem([
                        line(
                            reg('\N'), 'is only equal to', reg('[^\n]'), ',',
                            'when newline convention is', var_('LF'), '.',
                            'If newline convention is changed, then', reg('\N'), 'negates newline of the convention',
                            ...array_merge(eg('with', reg('(*CR)'), ', the', reg('\N'), 'is equal to', reg('[^\r]')), ['.']),
                        ),
                    ]),
                ]),
            ]),
    ]),
    section('Character sets, in ASCII mode:', [
        hyphen('\d', ['digit', ...eq('[0-9]')]),
        hyphen('\s', ['whitespace', ...eq('[ \t\v]')]),
        hyphen('\v', ['whitespace vertical: line feed, vertical tab, form feed, carriage return', ...eq('[\n\xb\f\r]')]),
        hyphen('\h', ['whitespace horizontal: space, tab or non-breaking space', ...eq('[ \t\xa0]')]),
        hyphen('\w', ['word character', ...eq('[a-zA-Z0-9_]')]),
    ]),
    section('Character sets, in unicode mode:', [
        hyphen('\d', ['digit', ...eq('\p{Nd}')]),
        hyphen('\s', ['whitespace', ...eq('[\p{Z}\h\v]')]),
        hyphen('\v', ['whitespace vertical', ...eq('[\n\xb\f\r\x85\p{Zl}\p{Zp}]')]),
        hyphen('\h', ['whitespace horizontal', ...eq('[\t\P{Zs}\x{180e}]')]),
        hyphen('\w', ['word character', ...eq('[\p{L}\p{N}_]')]),
        hyphen('\p{##}', ['character with a unicode property', ...eg(reg('\p{Lu}'), 'matches a unicode letter')]),
    ]),
    section('Negated character sets:', [
        hyphen('\D', ['not a digit', ...eq('[^\d]')]),
        hyphen('\S', ['not a whitespace', ...eq('[^\s]')]),
        hyphen('\V', ['not a whitespace vertical', ...eq('[^\v]')]),
        hyphen('\H', ['not a whitespace horizontal', ...eq('[^\h]')]),
        hyphen('\W', ['not a word character', ...eq('[^\w]')]),
        hyphen('\P{##}', ['character without a unicode property', ...eg(reg('\P{Lu}'), 'is equal to', reg('[^\p{Lu}]'))]),
    ]),
    section('Other character sets:', [
        hyphen('\X', ['matches any number of unicode characters that form an extended sequence']),
        hyphen('\R', ['matches a newline sequence:', vars('CRLF', 'LF', 'VT', 'FF', 'CR', 'NEL'), ...eq('\r\n|\n|\x0b|\f|\r|\x85')]),
    ]),
    section('Regular expression for unicode property:', [
        hyphen('\p{##}', ['matches a character with unicode property', ...eg(reg('\p{Lu}'), 'matches', str('A'))]),
        '<br>',// only for phpstorm
        section('Unicode property categories (case-sensitive):',
            \array_map(
                function (string $regex, array $doc) {
                    $group = \array_shift($doc);
                    return hyphen("\p{{$regex}}", [
                        $group,
                        '(',
                        'additionally',
                        vars(...\array_keys($doc)),
                        ')',
                    ]);
                },
                \array_keys($unicodeProperties), $unicodeProperties
            )),
        section('Extended unicode categories (case-sensitive):', [
            hyphen('\p{Xan}', ['alphanumeric', ...eq('[\p{L}\p{N}]')]),
            hyphen('\p{Xwd}', ['word: alphanumeric, underscore', ...eq('[\p{L}\p{N}_]')]),
            hyphen('\p{Xsp}', ['extended space: space, tab, linefeed, vertical tab, form feed, carriage return, separator', ...eq('[ \t\n\xab\f\r\p{Z}]')]),
            hyphen('\p{Xps}', ['posix space', '(alias for ', var_('Xsp'), ')']),
            hyphen('\p{Xuc}', [
                'character that can be represented by a Universal Character Name, these include:',
                regs('$', '@', '`'),
                'and unicode code points greater than', var_('U+00A0'), ', excluding surrogates', var_('U+D800..U+DFFF'), '.',
            ]),
        ]),
        section('Unicode scripts (case-sensitive):', [
            hyphen('\p{#####}', [
                'character in unicode script', ...eg(reg('\p{Greek}'), 'matches', str('ζ')),
                line('Unicode script names:'),
                line(vars(...$unicodeScriptNames)),
            ]),
        ]),
        section('Other:', [
            hyphen('\p{Any}', ['matches any character']),
            hyphen('\p{L&}', ['cased letter: lowercase, uppercase or title-case', ...eq('[\p{Ll}\p{Lu}\p{Lt}]')]),
        ]),
    ]),
    section('POSIX character classes, in ASCII mode:', [
        hyphen('[[:alpha:]]', ['alphabetic character', ...eq('[a-zA-Z]')]),
        hyphen('[[:alnum:]]', ['alphanumeric character', ...eq('[a-zA-Z\d]')]),
        hyphen('[[:ascii:]]', ['byte between 0 and 127', ...eq('[\0-\x7f]')]),
        hyphen('[[:blank:]]', ['space or tab', ...eq('[ \t]')]),
        hyphen('[[:cntrl:]]', ['control character', ...eq('[\0-\x1f\x7f]')]),
        hyphen('[[:digit:]]', ['decimal digit', ...eq('\d')]),
        hyphen('[[:xdigit:]]', ['hexadecimal digit', ...eq('[0-9a-fA-F]')]),
        hyphen('[[:print:]]', ['printing character, including space', ...eq('[\x20-\x7e]')]),
        hyphen('[[:graph:]]', ['printing character, excluding space', ...eq('[\x21-\x7e]')]),
        hyphen('[[:punct:]]', ['printing character, excluding space, digits and letters', ...eq('[\x21-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]')]),
        hyphen('[[:lower:]]', ['lowercase letter', ...eq('[a-z]')]),
        hyphen('[[:upper:]]', ['uppercase case letter', ...eq('[A-Z]')]),
        hyphen('[[:word:]]', ['word character', ...eq('\w')]),
        hyphen('[[:space:]]', ['whitespace', ...eq('\s')]),
    ]),
    section('POSIX character classes, with ' . mod('u'), [
        hyphen('[[:alpha:]]', ['alphabetic character', ...eq('\p{L}')]),
        hyphen('[[:alnum:]]', ['alphanumeric character', ...eq('\p{Xan}')]),
        hyphen('[[:ascii:]]', ['unchanged']),
        hyphen('[[:blank:]]', ['horizontal whitespace', ...eq('[\h]')]),
        hyphen('[[:cntrl:]]', ['control character', ...eq('\p{Cc}')]),
        hyphen('[[:digit:]]', ['decimal digit', ...eq('\p{Nd}')]),
        hyphen('[[:xdigit:]]', ['unchanged']),
        hyphen('[[:graph:]]', ['character with unicode property: ', \implode(', ', \array_map('var_', ['L', 'M', 'N', 'P', 'S', 'Cf'])), '; excluding: U+061C,U+180E,U+2066..U+2069']),
        hyphen('[[:print:]]', ['same as', reg('[[:graph:]]'), 'plus unicode characters with property:', var_('Zs')]),
        hyphen('[[:punct:]]', ['printing character with unicode property', var_('P'), 'or character which code point is lower than 256 and is of unicode property', var_('S')]),
        hyphen('[[:lower:]]', ['lowercase letter', ...eq('\p{Ll}')]),
        hyphen('[[:upper:]]', ['uppercase letter', ...eq('\p{Lu}')]),
        hyphen('[[:word:]]', ['word character', ...eq('\p{Xwd}')]),
        hyphen('[[:space:]]', ['whitespace', ...eq('\p{Xsp}')]),
    ]),
    section('Negated POSIX character classes', [
        hyphen('[[:^#####:]]', [
            'characters not present in character class',
            ...eg(reg('[[:^digit:]]'), 'is equal to', reg('[^[:digit:]]')),
        ]),
    ]),
    section('Regular expression comments:', [
        hyphen('(?#....)', ['comment group', ...eg(reg('a(?#b)c'), 'matches', str('ac'))]),
        hyphen('#...', ['comment, starting with', reg('#'), 'and ending with a newline', '(only with', mod('x'), ')']),
    ]),
    section('Regular expression quote:', [
        hyphen('\Q...\E', ['characters matched', em('literally'), ...eg(reg('\Q$[\]\E'), 'matches', str('$[\]'))]),
    ]),
    section('Notes:', [
        listItem([
            line('In PHP string ', str('\v', true), 'is "vertical tab", while in regular expression', reg('\v'), 'describes a "vertical whitespace".'),
        ]),
        listItem([
            line([
                reg('\P{Any}'), '(inversion of', reg('\p{Any}'), ')', "doesn't match any character", ...eq('(*FAIL)'), '.',
            ]),
        ]),
        listItem([
            line(
                reg('[[:alnum:]]'), 'is not equal to', reg('[[:alpha:][:digit:]]'), '.',
                reg('[[:alnum:]]'), 'equals', reg('[\p{L}\p{N}]'), ', while', reg('[[:alpha:][:digit:]]'), 'is equal to', reg('[\p{L}\p{Nd}]'), '.'
            ),
        ]),
    ]),
];

/*
<!--

ANCHORS AND SIMPLE ASSERTIONS
\b          word boundary
\B          not a word boundary
^           start of subject
            - also after an internal newline in multiline mode
\A          start of subject
$           end of subject
            - also before newline at end of subject
            - also before internal newline in multiline mode
\Z          end of subject
            - also before newline at end of subject
\z          end of subject
\G          first matching position in subject

Inside a character class, \b has a different meaning; it matches the backspace character. 
If any other of these assertions appears in a character class, an "invalid escape sequence" error is generated.
-->

<!--
CAPTURING
(...)                capture group
(?&gt;name&lt;...)   named capture group (Perl)
(?'name'...)         named capture group (Perl)
(?P&gt;name&lt;...)  named capture group (Python)
(?:...)              non-capture group
(?|...)              non-capture group; reset group numbers for,  capture groups in each alternative

ATOMIC GROUPS
(?>...)         atomic non-capture group

REPORTED MATCH POINT SETTING
\K          set reported start of match

LOOKAHEAD AND LOOKBEHIND ASSERTIONS
(?=...)     ) positive lookahead
(?!...)     ) negative lookahead
(?&gt;=...)    ) positive lookbehind
(?&gt;!...)    ) negative lookbehind
(?*...)     ) non-atomic positive lookahead
(?&gt;*...)    ) non-atomic positive lookbehind

\n              reference by number (can be ambiguous)
\gn             reference by number
\g{n}           reference by number
\g+n            relative reference by number (PCRE2 extension)
\g-n            relative reference by number
\g{+n}          relative reference by number (PCRE2 extension)
\g{-n}          relative reference by number
\k&gt;name&lt;  reference by name (Perl)
\k'name'        reference by name (Perl)
\g{name}        reference by name (Perl)
\k{name}        reference by name (.NET)
(?P=name)       reference by name (Python)

SUBROUTINE REFERENCES (POSSIBLY RECURSIVE)
(?R)            recurse whole pattern
(?n)            call subroutine by absolute number
(?+n)           call subroutine by relative number
(?-n)           call subroutine by relative number
(?&amp;name)        call subroutine by name (Perl)
(?P>name)       call subroutine by name (Python)
\g&gt;name&lt;  call subroutine by name (Oniguruma)
\g'name'        call subroutine by name (Oniguruma)
\g&gt;n&lt;     call subroutine by absolute number (Oniguruma)
\g'n'           call subroutine by absolute number (Oniguruma)
\g&gt;+n&lt;          call subroutine by relative number (PCRE2 extension)
\g'+n'          call subroutine by relative number (PCRE2 extension)
\g&gt;-n&lt;          call subroutine by relative number (PCRE2 extension)
\g'-n'          call subroutine by relative number (PCRE2 extension)

CONDITIONAL PATTERNS
(?(condition)yes-pattern)
(?(condition)yes-pattern|no-pattern)

(?(n)               absolute reference condition
(?(+n)              relative reference condition
(?(-n)              relative reference condition
(?(&gt;name&lt;)          named reference condition (Perl)
(?('name')          named reference condition (Perl)
(?(name)            named reference condition (PCRE2, deprecated)
-->*/

function eg(...$items): array
{
    return ['(e.g. ', ...$items, ')'];
}

function eq(string $regex): array
{
    return ['(equal to ', reg($regex), ')'];
}

function chr_(int...$codePoints): string
{
    return inlineCode(\implode(' . ', \array_map(fn($codePoint) => "chr($codePoint)", $codePoints)));
}

function hyphen(string $key, array $items): string
{
    if ($key === ' - ') {
        return listItem([line(reg($key), ' (hyphen) - ', ...$items)]);
    }
    return listItem([line(reg($key), ' - ', ...$items)]);
}

function hyphenComplex(array $key, array $items): string
{
    return listItem([line(...array_merge($key, [' - '], $items))]);
}

return implode('', $pattern);
