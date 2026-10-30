<?php

declare(strict_types=1);

namespace MrActivities\DesignSystem\Service;

/**
 * Finds the hooks a template, page or script uses that the framework does not define: every class (in class
 * attributes, script selectors, classList calls and className values) must appear in a selector of the framework,
 * and a variant or state attribute (the C7 data-variant, data-size, …) must carry a value one of its selectors names.
 * Other data-* attributes are behaviour, or style some values and leave the rest to the default, so their values
 * are not checked. Run by bin/check-classes.php.
 *
 * @phpstan-type Finding array{file: string, line: int, hook: string, name: string}
 */
final class ClassCheckService
{
    private const string CLASS_NAME = '/^-?[_a-zA-Z][\w-]*$/';
    private const string HOLE = "\u{a7}";
    public const array VARIANT_ATTRIBUTES = ['data-variant', 'data-size', 'data-width', 'data-as', 'data-state', 'data-columns'];

    /** @var array<string, true> */
    private array $classes = [];

    /** @var array<string, array<string, true>> */
    private array $attributes = [];

    /**
     * @param list<string> $ignoredPrefixes classes owned by another library (Leaflet's leaflet-)
     */
    public function __construct(string $css, private readonly array $ignoredPrefixes = [])
    {
        $css = (string) preg_replace(['#/\*.*?\*/#s', '/url\([^)]*\)/'], ['', 'url()'], $css);
        preg_match_all('/([^{};]+)\{/', $css, $heads);
        foreach ($heads[1] as $head) {
            $selector = trim($head);
            if ('' === $selector || str_starts_with($selector, '@')) {
                continue;
            }
            preg_match_all('/\.(-?[_a-zA-Z][\w-]*)/', (string) preg_replace('/\[[^\]]*\]/', '', $selector), $m);
            foreach ($m[1] as $class) {
                $this->classes[$class] = true;
            }
            preg_match_all('/\[\s*((?:data|aria)-[\w-]+)\s*(?:([~|^$*]?=)\s*(?:"([^"]*)"|\'([^\']*)\'|([^\]\s]+)))?\s*\]/', $selector, $a, \PREG_SET_ORDER);
            foreach ($a as $match) {
                $operator = $match[2] ?? '';
                $value = '' === $operator ? '' : ('=' === $operator ? ($match[3] ?? '').($match[4] ?? '').($match[5] ?? '') : '*');
                $this->attributes[$match[1]][$value] = true;
            }
        }
    }

    /** @return list<string> */
    public function classes(): array
    {
        $classes = array_keys($this->classes);
        sort($classes);

        return array_map('strval', $classes);
    }

    /** @return array<string, list<string>> */
    public function attributes(): array
    {
        $attributes = [];
        foreach ($this->attributes as $name => $values) {
            $list = array_map('strval', array_keys($values));
            sort($list);
            $attributes[$name] = $list;
        }
        ksort($attributes);

        return $attributes;
    }

    /** @return list<Finding> */
    public function check(string $file, string $source): array
    {
        $found = [];
        foreach ($this->classAttributes($source) as [$offset, $class]) {
            $found[] = [$offset, 'class', $class];
        }
        $script = !str_ends_with($file, '.html') || str_ends_with($file, '.twig');
        if ($script) {
            preg_match_all('/(?:\$\$?|querySelector(?:All)?|closest|matches)\(\s*([\'"`])((?:(?!\1).)*)\1/', $source, $m, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
            foreach ($m as $match) {
                $selector = $this->holes($match[2][0]);
                preg_match_all('/\.(-?[_a-zA-Z][\w-]*'.self::HOLE.'?)/u', (string) preg_replace('/\[[^\]]*\]/', '', $selector), $c, \PREG_OFFSET_CAPTURE);
                foreach ($c[1] as [$class]) {
                    $found[] = [$match[2][1], 'class', $class];
                }
                preg_match_all('/\[\s*(data-[\w-]+)\s*=\s*"?([^"\]]*)"?\s*\]/', $selector, $at, \PREG_SET_ORDER);
                foreach ($at as $attribute) {
                    $found[] = [$match[2][1], $attribute[1], $attribute[2]];
                }
            }
            preg_match_all('/classList\.(?:add|remove|toggle|contains|replace)\(([^)]*)\)/', $source, $m, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
            foreach ($m as $match) {
                preg_match_all('/([\'"])([^\'"]*)\1/', $match[1][0], $s);
                foreach ($s[2] as $class) {
                    $found[] = [$match[1][1], 'class', $class];
                }
            }
            preg_match_all('/className\s*[:=]\s*([\'"`])((?:(?!\1).)*)\1/', $source, $m, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
            foreach ($m as $match) {
                foreach (preg_split('/\s+/', $this->holes($match[2][0])) ?: [] as $class) {
                    $found[] = [$match[2][1], 'class', $class];
                }
            }
            preg_match_all('/setAttribute\(\s*\'(data-[\w-]+)\'\s*,\s*\'([^\']*)\'\s*\)|dataset\.(\w+)\s*=\s*\'([^\']*)\'/', $source, $m, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
            foreach ($m as $match) {
                $name = isset($match[3]) ? 'data-'.strtolower((string) preg_replace('/[A-Z]/', '-$0', $match[3][0])) : ($match[1][0] ?? '');
                $found[] = [$match[0][1], $name, isset($match[4]) ? $match[4][0] : ($match[2][0] ?? '')];
            }
        }
        preg_match_all('/\s(data-[\w-]+)=(["\'])((?:(?!\2).)*)\2/', $source, $m, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER);
        foreach ($m as $match) {
            $found[] = [$match[1][1], $match[1][0], $match[3][0]];
        }
        usort($found, static fn (array $a, array $b): int => $a[0] <=> $b[0]);

        $findings = [];
        foreach ($found as [$offset, $hook, $name]) {
            if (!$this->undefined($hook, $name)) {
                continue;
            }
            $finding = ['file' => $file, 'line' => substr_count($source, "\n", 0, $offset) + 1, 'hook' => $hook, 'name' => $name];
            if (!\in_array($finding, $findings, true)) {
                $findings[] = $finding;
            }
        }

        return $findings;
    }

    private function undefined(string $hook, string $name): bool
    {
        if ('class' !== $hook) {
            $values = \in_array($hook, self::VARIANT_ATTRIBUTES, true) ? $this->attributes[$hook] ?? null : null;

            return null !== $values && !str_contains($name, self::HOLE) && !preg_match('/\$\{|\{\{|\{%/', $name)
                && !isset($values['']) && !isset($values['*']) && !isset($values[$name]);
        }
        if (!preg_match(self::CLASS_NAME, $name) || isset($this->classes[$name])) {
            return false;
        }
        foreach ($this->ignoredPrefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return false;
            }
        }

        return true;
    }

    /** @return list<array{int, string}> each class of every class attribute, with its offset; parts built by an expression are skipped */
    private function classAttributes(string $source): array
    {
        $out = [];
        preg_match_all('/\sclass\s*=\s*(["\'])/', $source, $m, \PREG_OFFSET_CAPTURE);
        foreach ($m[1] as [$quote, $start]) {
            $i = $start + 1;
            $text = '';
            $strings = [];
            $length = \strlen($source);
            while ($i < $length && $source[$i] !== $quote) {
                if (str_starts_with(substr($source, $i, 2), '${') || str_starts_with(substr($source, $i, 2), '{{') || str_starts_with(substr($source, $i, 2), '{%')) {
                    $i = $this->skipExpression($source, $i, $strings);
                    $text .= self::HOLE;
                    continue;
                }
                $text .= $source[$i++];
            }
            foreach ((preg_split('/\s+/', trim($text)) ?: []) as $class) {
                $out[] = [$start, $class];
            }
            foreach ($strings as $string) {
                if (!preg_match('/[<=">]/', $string)) {
                    foreach ((preg_split('/\s+/', trim($string)) ?: []) as $class) {
                        $out[] = [$start, $class];
                    }
                }
            }
        }

        return $out;
    }

    /**
     * @param list<string> $strings collects the string literals met inside the expression
     *
     * @return int the offset after the expression
     */
    private function skipExpression(string $source, int $i, array &$strings): int
    {
        $twig = '{' === $source[$i];
        $close = $twig ? ('{' === $source[$i + 1] ? '}}' : '%}') : '}';
        $i += 2;
        $depth = 0;
        $length = \strlen($source);
        while ($i < $length) {
            $c = $source[$i];
            if ('\'' === $c || '"' === $c || '`' === $c) {
                $end = $this->skipString($source, $i);
                $strings[] = substr($source, $i + 1, $end - $i - 2);
                $i = $end;
                continue;
            }
            if (!$twig && '{' === $c) {
                ++$depth;
            } elseif (str_starts_with(substr($source, $i, \strlen($close)), $close) && 0 === $depth) {
                return $i + \strlen($close);
            } elseif (!$twig && '}' === $c) {
                --$depth;
            }
            ++$i;
        }

        return $i;
    }

    /** @return int the offset after the closing quote */
    private function skipString(string $source, int $i): int
    {
        $quote = $source[$i++];
        $length = \strlen($source);
        while ($i < $length && $source[$i] !== $quote) {
            if ('\\' === $source[$i]) {
                $i += 2;
                continue;
            }
            if ('`' === $quote && str_starts_with(substr($source, $i, 2), '${')) {
                $ignored = [];
                $i = $this->skipExpression($source, $i, $ignored);
                continue;
            }
            ++$i;
        }

        return $i + 1;
    }

    private function holes(string $text): string
    {
        return (string) preg_replace('/\$\{[^}]*\}/', self::HOLE, $text);
    }
}
