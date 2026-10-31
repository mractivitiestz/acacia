<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Unit;

use MrActivities\Acacia\Service\StylesheetConcatService;
use MrActivities\Acacia\Tests\Support\StylesheetReader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The framework's rules, checked on the source files: layers, C7 naming, tokens only, the three radii, no !important,
 * one owner per class, a fresh dist/.
 */
final class ConformanceTest extends TestCase
{
    private const string ASSETS = __DIR__.'/../../assets';
    private const string ENTRY = 'acacia.css';

    /** Elements a component may still select by tag: replaced elements, form controls and SVG internals, which carry no part class of ours; and any element of Leaflet's own markup (inside or on a leaflet- class). */
    private const array ALLOWED_ELEMENTS = ['img', 'svg', 'path', 'text', 'input', 'select', 'option', 'textarea'];

    /** Attributes besides data-* and aria-*: native states the browser itself sets or reads (details/dialog open, hidden, an empty option value). */
    private const array ALLOWED_ATTRIBUTES = ['open', 'hidden', 'value'];

    /** Leaflet writes the tile size and the popup width as inline styles; only !important outranks an inline style. */
    private const array ALLOWED_IMPORTANT = [
        'components/map.css .map-canvas img.leaflet-tile',
        'components/map.css .popup .leaflet-popup-content',
    ];

    /** Apple Pay and Google Pay are drawn in the providers' own published white and black. */
    private const string LITERAL_COLOUR_ALLOWED_SELECTOR = '/^\.wallet\b/';

    private const string RADIUS_TOKEN = '/^(var\(--r(-s|-pill)?\)|0|50%|inherit)$/';

    private const string C7_CLASS = '/^([a-z][a-z0-9]*(-[a-z0-9]+)?|leaflet-[a-z-]+)$/';

    private StylesheetReader $reader;

    protected function setUp(): void
    {
        $this->reader = new StylesheetReader(self::ASSETS);
    }

    public function testTheLayerOrderIsDeclaredOnceInTheEntry(): void
    {
        $entry = (string) @file_get_contents(self::ASSETS.'/'.self::ENTRY);
        self::assertSame(1, preg_match_all('/@layer\s+([^;{]+);/', $entry, $m), 'the entry declares the layer order exactly once');
        self::assertSame(StylesheetReader::LAYER_ORDER, trim($m[1][0]));
        foreach ($this->reader->files() as $file) {
            self::assertDoesNotMatchRegularExpression('/@layer\b/', $this->reader->source($file), $file.' declares a layer itself');
        }
    }

    public function testEveryFileIsImportedOnceIntoTheLayerOfItsDirectory(): void
    {
        $entry = (string) @file_get_contents(self::ASSETS.'/'.self::ENTRY);
        preg_match_all("/@import url\\('([^']+)'\\) layer\\((\\w+)\\);/", $entry, $m, \PREG_SET_ORDER);
        $imported = [];
        foreach ($m as [, $file, $layer]) {
            self::assertArrayNotHasKey($file, $imported, $file.' is imported twice');
            $imported[$file] = $layer;
            self::assertSame(explode('/', $file)[0], $layer, $file.' sits in the wrong layer');
        }
        $files = $this->reader->files();
        self::assertNotEmpty($files, 'the package ships stylesheets');
        self::assertEqualsCanonicalizing($files, array_keys($imported));
        $order = array_flip(explode(', ', StylesheetReader::LAYER_ORDER));
        $positions = array_map(static fn (string $layer): int => $order[$layer], array_values($imported));
        $sorted = $positions;
        sort($sorted);
        self::assertSame($sorted, $positions, 'files are imported in layer order');
    }

    /** @return iterable<string, array{string}> */
    public static function styledFiles(): iterable
    {
        $reader = new StylesheetReader(self::ASSETS);
        foreach ($reader->files() as $file) {
            if (preg_match('#^(layout|components|pages|utilities)/#', $file)) {
                yield $file => [$file];
            }
        }
    }

    #[DataProvider('styledFiles')]
    public function testSelectorsFollowC7(string $file): void
    {
        $violations = [];
        foreach ($this->reader->rules($file) as $rule) {
            foreach (StylesheetReader::splitTopLevel($rule['selector']) as $selector) {
                foreach ($this->violationsOf($selector) as $why) {
                    $violations[] = $selector.' → '.$why;
                }
            }
        }
        self::assertSame([], $violations);
    }

    public function testOnlyTokensHoldLiteralColoursAndOnlyTokensSetColourTokens(): void
    {
        $violations = [];
        foreach ($this->reader->files() as $file) {
            if (preg_match('#^(tokens|vendor)/#', $file)) {
                continue;
            }
            foreach ($this->reader->rules($file) as $rule) {
                if (preg_match(self::LITERAL_COLOUR_ALLOWED_SELECTOR, $rule['selector'])) {
                    continue;
                }
                if (preg_match('/#[0-9a-fA-F]{3,8}\b|\b(rgba?|hsla?)\(|(?<![\w-])(white|black)(?![\w-])/', $rule['body'])) {
                    $violations[] = $file.': '.$rule['selector'];
                }
                if (preg_match('/(?:^|;)\s*--('.implode('|', $this->colourTokens()).')\s*:/', $rule['body'], $m)) {
                    $violations[] = $file.': '.$rule['selector'].' redefines the colour token --'.$m[1];
                }
            }
        }
        self::assertSame([], $violations);
    }

    public function testRadiiUseTheThreeTokens(): void
    {
        $violations = [];
        foreach ($this->reader->files() as $file) {
            if (str_starts_with($file, 'vendor/')) {
                continue;
            }
            foreach ($this->reader->rules($file) as $rule) {
                preg_match_all('/border(?:-[a-z-]+)?-radius\s*:\s*([^;]+)/', $rule['body'], $m);
                foreach ($m[1] as $value) {
                    foreach (preg_split('/\s+/', trim($value)) ?: [] as $part) {
                        if (!preg_match(self::RADIUS_TOKEN, $part)) {
                            $violations[] = $file.': '.$rule['selector'].' '.$value;
                        }
                    }
                }
            }
        }
        self::assertSame([], $violations);
    }

    public function testNoImportantOutsideTheLeafletInlineOverrides(): void
    {
        $violations = [];
        $allowed = array_flip(self::ALLOWED_IMPORTANT);
        foreach ($this->reader->files() as $file) {
            if (str_starts_with($file, 'vendor/')) {
                continue;
            }
            foreach ($this->reader->rules($file) as $rule) {
                if (str_contains($rule['body'], '!important') && !isset($allowed[$file.' '.$rule['selector']])) {
                    $violations[] = $file.': '.$rule['selector'];
                }
            }
        }
        self::assertSame([], $violations);
    }

    public function testEveryClassHasOneOwningFile(): void
    {
        $owners = [];
        foreach ($this->reader->files() as $file) {
            if (str_starts_with($file, 'vendor/')) {
                continue;
            }
            foreach ($this->reader->rules($file) as $rule) {
                foreach (StylesheetReader::splitTopLevel($rule['selector']) as $selector) {
                    $class = $this->subjectClass($selector);
                    if (null !== $class) {
                        $owners[$class][$file] = true;
                    }
                }
            }
        }
        $shared = array_filter($owners, static fn (array $files): bool => \count($files) > 1);
        self::assertSame([], array_map(static fn (array $files): array => array_keys($files), $shared));
        self::assertNotEmpty($owners);
    }

    public function testEveryClassTheDocsUseIsDefined(): void
    {
        $docs = (string) @file_get_contents(__DIR__.'/../../docs/index.html');
        self::assertNotSame('', $docs, 'the docs page exists');
        $defined = [];
        foreach ($this->reader->files() as $file) {
            preg_match_all('/\.([a-z][\w-]*)/', (string) preg_replace('/\{[^}]*\}/', '{}', $this->reader->source($file)), $m);
            $defined += array_flip($m[1]);
        }
        preg_match_all('/<(?!pre|code)[a-z][^>]*\sclass="([^"]+)"/', (string) preg_replace('#<pre.*?</pre>#s', '', $docs), $m);
        $used = array_unique(array_merge(...array_map(static fn (string $list): array => preg_split('/\s+/', trim($list)) ?: [], $m[1])));
        $undefined = array_values(array_filter($used, static fn (string $class): bool => !isset($defined[$class]) && !str_starts_with($class, 'docs-')));
        self::assertSame([], $undefined);
    }

    public function testDistIsTheConcatenationOfTheSources(): void
    {
        $dist = (string) @file_get_contents(__DIR__.'/../../dist/acacia.css');
        self::assertSame((new StylesheetConcatService())->concat(self::ASSETS), $dist, 'dist/ is stale: run composer build');
    }

    /** @return list<string> the colour custom properties the tokens declare */
    private function colourTokens(): array
    {
        $names = [];
        foreach (['tokens/palette.css', 'tokens/fixed.css'] as $file) {
            preg_match_all('/--([\w-]+)\s*:/', $this->reader->source($file), $m);
            $names += array_flip($m[1]);
        }

        return array_keys($names);
    }

    /** @return list<string> */
    private function violationsOf(string $selector): array
    {
        $why = [];
        $specificity = StylesheetReader::specificity($selector);
        if ($specificity > [0, 3, 0]) {
            $why[] = 'specificity '.implode(',', $specificity);
        }
        $bare = (string) preg_replace('/\[[^\]]*\]/', '', $selector);
        if (preg_match('/#[\w-]+/', $bare)) {
            $why[] = 'id selector';
        }
        preg_match_all('/\.([\w-]+)/', $bare, $m);
        foreach ($m[1] as $class) {
            if (!preg_match(self::C7_CLASS, $class)) {
                $why[] = 'class .'.$class;
            }
        }
        preg_match_all('/\[([\w-]+)/', $selector, $m);
        foreach ($m[1] as $attribute) {
            if (!preg_match('/^(data|aria)-/', $attribute) && !\in_array($attribute, self::ALLOWED_ATTRIBUTES, true)) {
                $why[] = 'attribute '.$attribute;
            }
        }
        $compounds = preg_split('/\s*[>+~]\s*|\s+/', trim((string) preg_replace('/\((?:[^()]|\((?:[^()]|\([^()]*\))*\))*\)/', '', $bare))) ?: [];
        $vendor = false;
        foreach ($compounds as $position => $compound) {
            $vendor = $vendor || str_contains($compound, '.leaflet-');
            if (preg_match('/^([a-z][a-z0-9]*)/', $compound, $t)) {
                $tag = $t[1];
                $root = 0 === $position && \in_array($tag, ['html', 'body'], true);
                if (!$root && !$vendor && !\in_array($tag, self::ALLOWED_ELEMENTS, true)) {
                    $why[] = 'element '.$tag;
                }
            }
        }

        return $why;
    }

    private function subjectClass(string $selector): ?string
    {
        $unwrapped = (string) preg_replace('/:where\(((?:[^()]|\((?:[^()]|\([^()]*\))*\))*)\)/', '$1', $selector);
        $flat = (string) preg_replace('/\((?:[^()]|\((?:[^()]|\([^()]*\))*\))*\)/', '', (string) preg_replace('/\[[^\]]*\]/', '', $unwrapped));
        $compounds = array_reverse(preg_split('/\s*[>+~]\s*|\s+/', trim($flat)) ?: []);
        foreach ($compounds as $compound) {
            if (preg_match_all('/\.((?!leaflet-)[\w-]+)/', $compound, $m)) {
                return $m[1][0];
            }
        }

        return null;
    }
}
