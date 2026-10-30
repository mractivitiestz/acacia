<?php

declare(strict_types=1);

namespace MrActivities\DesignSystem\Tests\Support;

/**
 * Reads the package's stylesheets into flat rules, so the conformance test can reason about selectors and declarations.
 */
final class StylesheetReader
{
    public const string LAYER_ORDER = 'vendor, tokens, base, layout, components, pages, utilities';

    public function __construct(private readonly string $assetsDir)
    {
    }

    /** @return list<string> paths relative to the assets directory, entry excluded */
    public function files(): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->assetsDir, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            $path = substr($file->getPathname(), \strlen($this->assetsDir) + 1);
            if (str_ends_with($path, '.css') && 'mractivities.css' !== $path) {
                $files[] = $path;
            }
        }
        sort($files);

        return $files;
    }

    public function source(string $file): string
    {
        return (string) file_get_contents($this->assetsDir.'/'.$file);
    }

    /** @return list<array{media: string, selector: string, body: string}> */
    public function rules(string $file): array
    {
        return $this->parse((string) preg_replace('#/\*.*?\*/#s', '', $this->source($file)), '');
    }

    /** @return list<array{media: string, selector: string, body: string}> */
    private function parse(string $css, string $media): array
    {
        $rules = [];
        $length = \strlen($css);
        $i = 0;
        while ($i < $length) {
            $open = strpos($css, '{', $i);
            if (false === $open) {
                break;
            }
            $head = trim(substr($css, $i, $open - $i));
            $semicolon = strrpos($head, ';');
            if (false !== $semicolon) {
                $head = trim(substr($head, $semicolon + 1));
            }
            $depth = 0;
            for ($j = $open; $j < $length; ++$j) {
                if ('{' === $css[$j]) {
                    ++$depth;
                } elseif ('}' === $css[$j] && 0 === --$depth) {
                    break;
                }
            }
            $body = substr($css, $open + 1, $j - $open - 1);
            if (str_starts_with($head, '@media') || str_starts_with($head, '@supports')) {
                array_push($rules, ...$this->parse($body, trim($media.' '.$head)));
            } elseif (!str_starts_with($head, '@')) {
                $rules[] = ['media' => $media, 'selector' => $head, 'body' => trim($body)];
            }
            $i = $j + 1;
        }

        return $rules;
    }

    /** @return list<string> */
    public static function splitTopLevel(string $selector): array
    {
        $parts = [];
        $depth = 0;
        $current = '';
        foreach (str_split($selector) as $char) {
            if ('(' === $char) {
                ++$depth;
            } elseif (')' === $char) {
                --$depth;
            }
            if (',' === $char && 0 === $depth) {
                $parts[] = trim($current);
                $current = '';
                continue;
            }
            $current .= $char;
        }
        $parts[] = trim($current);

        return $parts;
    }

    /**
     * Specificity of one complex selector as [ids, classes, types]: :where() counts nothing, :not()/:is()/:has() count their most specific argument.
     *
     * @return array{int, int, int}
     */
    public static function specificity(string $selector): array
    {
        $score = [0, 0, 0];
        $pattern = '/(#[\w-]+)|(\.[\w-]+)|(\[[^\]]*\])|(::[\w-]+(?:\([^)]*\))?)|(:[\w-]+)(\()?|(\*)|([a-zA-Z][\w-]*)/';
        $offset = 0;
        while (preg_match($pattern, $selector, $m, \PREG_OFFSET_CAPTURE, $offset)) {
            $offset = $m[0][1] + \strlen($m[0][0]);
            if ('' !== ($m[1][0] ?? '')) {
                ++$score[0];
            } elseif ('' !== ($m[2][0] ?? '') || '' !== ($m[3][0] ?? '')) {
                ++$score[1];
            } elseif ('' !== ($m[4][0] ?? '')) {
                ++$score[2];
            } elseif ('' !== ($m[5][0] ?? '')) {
                $name = $m[5][0];
                if ('(' === ($m[6][0] ?? '')) {
                    $depth = 1;
                    $start = $offset;
                    while ($depth > 0 && $offset < \strlen($selector)) {
                        $depth += match ($selector[$offset]) { '(' => 1, ')' => -1, default => 0 };
                        ++$offset;
                    }
                    $inner = substr($selector, $start, $offset - $start - 1);
                    if (\in_array($name, [':not', ':is', ':has'], true)) {
                        $best = [0, 0, 0];
                        foreach (self::splitTopLevel($inner) as $argument) {
                            $best = max($best, self::specificity(ltrim($argument, '>+~ ')));
                        }
                        $score = [$score[0] + $best[0], $score[1] + $best[1], $score[2] + $best[2]];
                    } elseif (':where' !== $name) {
                        ++$score[1];
                    }
                } else {
                    ++$score[1];
                }
            } elseif ('' !== ($m[8][0] ?? '')) {
                ++$score[2];
            }
        }

        return $score;
    }
}
