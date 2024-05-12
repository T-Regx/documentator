<?php

function mod(string $modifier): string
{
    return "<code>\$modifier='$modifier'</code>";
}

function line(string|array ...$words): string
{
    $words = call_user_func_array(
        '\array_merge',
        \array_map(function (array|string $value): array {
            if (\is_array($value)) {
                return $value;
            }
            return [$value];
        }, $words));

    $array = \array_map('trim', $words);
    for ($i = 0; $i < \count($array) - 1; ++$i) {
        $prev = $array[$i];
        $next = $array[$i + 1];
        if (_hasSpace($prev, $next)) {
            $array[$i] = $array[$i] . ' ';
        }
    }
    return '<p>' . \implode('', $array) . '</p>';
}

function var_(string $var): string
{
    return "<samp>$var</samp>";
}

function vars(string...$vars): string
{
    return \implode(', ', \array_map('var_', $vars));
}

function _hasSpace(string $previous, string $next): bool
{
    $first = \subStr($previous, -1);
    $last = $next[0];

    if ($first === '(' || $last === ')') {
        return false;
    }
    if ($first === '-' || $last === '-') {
        return true;
    }
    if (\in_array($first, ['.', ',', ':', ';'])) {
        return true;
    }
    if ($last === '.') {
        return false;
    }
    if ($last === '(') {
        return true;
    }

    if (\ctype_alpha($first) && $last === '<') {
        return true;
    }
    if ($first === '>' && $last === ',') {
        return false;
    }
    if ($first === '>' && \cType_alpha($last)) {
        return true;
    }
    if ($first === '>' && $last === ':') {
        return false;
    }
    if ($first === '>' && $last === ';') {
        return false;
    }
    if ($first === '>' && $last === '<') {
        return true;
    }
    if ($first === '>' && $last === '#') {
        return false;
    }
    if ($first === '#' && $last === '<') {
        return false;
    }
    if ($first === ')' && $last === '<') {
        return true;
    }
    if ($first === ')' && \cType_alpha($last)) {
        return true;
    }
    if (\cType_alpha($first) && \cType_alpha($last)) {
        return true;
    }
    throw new Exception('Unexpected joining');
}

function section(string $title, array $children): string
{
    return "<p>$title</p>" . list_($children);
}

/**
 * @deprecated
 */
function list_(array $children): string
{
    return "<ul>" . \implode('', $children) . "</ul>";
}

function listItem(array $children): string
{
    return '<li>' . \implode('', $children) . '</li>';
}

function reg(string $regex): string
{
    if ($regex === '-') {
        return "<b>$regex</b> (hyphen)";
    }
    return "<b>$regex</b>";
}

function regs(string ...$regex): string
{
    return implode(', ', \array_map(function (string $reg): string {
        if ($reg === '`') {
            return "<b>$reg</b> (grave accent)";
        }
        return "<b>$reg</b>";
    }, $regex));
}

function pattern(string $regex, string $modifier = null, bool $constantModifier = false): string
{
    $pattern = phpString($regex);
    if ($modifier === null) {
        return "<code>new Pattern($pattern)</code>";
    }
    if ($constantModifier) {
        $constant = ['x' => 'Pattern::COMMENTS_WHITESPACE'][$modifier];
        return "<code>new Pattern($pattern, $constant)</code>";
    }
    return "<code>new Pattern($pattern, '$modifier')</code>";
}

function phpString(string $regex, bool $double = false): string
{
    if (\strPos($regex, "\n") === false &&
        \strPos($regex, "\r") === false
    ) {
        if ($double) {
            $pattern = '"' . $regex . '"';
        } else {
            $pattern = "'$regex'";
        }
    } else {
        $strReplace = \str_replace(["\n", "\r"], ['\n', '\r'], $regex);
        $pattern = '"' . $strReplace . '"';
    }
    return $pattern;
}

function inlineCode(string $code): string
{
    return "<code>$code</code>";
}

function blockCodeTitle(string $title, string $code): string
{
    return $title . blockCode($code);
}

function blockCode(string $code): string
{
    return "\n<pre>\n$code\n</pre>"; // that's for phpStorm
//    return "<pre>$code</pre>";
}

function str(string $string, bool $double = false): string
{
    if ($string === '\\') {
        if ($double) {
            throw new Exception('unexpected');
        }
        return "<samp>'\'</samp> (backslash)";
    }
    $pattern = phpString($string, $double);
    return "<code>$pattern</code>";
}

function tuple(array $array): string
{
    return '<code>["' . implode('","', $array) . '"]</code>';
}

function em(string $string): string
{
    return "<i>$string</i>";
}
