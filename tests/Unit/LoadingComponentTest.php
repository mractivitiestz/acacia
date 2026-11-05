<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Unit;

use MrActivities\Acacia\Tests\Support\StylesheetReader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Loading C with A's pulse: a photo on its way sits on the placeholder's ground with its glyph, breathing as the
 * dropdown's loading rows do, and fades in as the ground fades out; a photo that failed is the placeholder at rest with
 * its words; a list that waits on its answer turns its words into soft pills; a map breathes on the ground until its
 * first tile; nothing breathes under reduced motion.
 */
final class LoadingComponentTest extends TestCase
{
    private const string ASSETS = __DIR__.'/../../assets';

    private StylesheetReader $reader;

    protected function setUp(): void
    {
        $this->reader = new StylesheetReader(self::ASSETS);
    }

    /** @return iterable<string, array{string}> */
    public static function photoStates(): iterable
    {
        foreach (['hold', 'wait', 'done', 'failed'] as $state) {
            yield $state => [$state];
        }
    }

    #[DataProvider('photoStates')]
    public function testThePlaceholderDrawsEveryStateOfAPhotoOnItsWay(string $state): void
    {
        $css = $this->reader->source('components/photo-placeholder.css');
        self::assertStringContainsString('.placeholder[data-state="'.$state.'"]', $css);
        self::assertStringContainsString('.placeholder-thumb[data-state="'.$state.'"]', $css);
    }

    public function testAWaitingGroundBreathesOnTheDropdownsPulseAndTheOnlyCopyOfIt(): void
    {
        $placeholder = $this->reader->source('components/photo-placeholder.css');
        self::assertMatchesRegularExpression('/\.placeholder\[data-state="wait"\][^{]*\{[^}]*animation: dropdown-wait 1\.2s ease-in-out infinite alternate;/', $placeholder);
        $keyframes = 0;
        foreach ($this->reader->files() as $file) {
            $keyframes += substr_count($this->reader->source($file), '@keyframes dropdown-wait');
        }
        self::assertSame(1, $keyframes, 'the pulse is declared once, in the dropdown');
        self::assertStringNotContainsString('@keyframes', $this->reader->source('components/loading.css'));
    }

    public function testThePhotoFadesInAndAFailedPhotoKeepsItsBox(): void
    {
        $css = $this->reader->source('components/photo-placeholder.css');
        self::assertMatchesRegularExpression('/img \{[^}]*transition: opacity var\(--d-slow\) var\(--ease\);/', $css);
        self::assertMatchesRegularExpression('/\[data-state="failed"\]\)+ img \{\s*opacity: 0;/', $css, 'a failed photo is hidden, not removed, so its box keeps its size');
        self::assertMatchesRegularExpression('/\.placeholder:is\(\[data-state="hold"\],\[data-state="wait"\],\[data-state="done"\]\) \.placeholder-note \{\s*display: none;/', $css, 'the words only when it failed');
        self::assertDoesNotMatchRegularExpression('/\[data-state="failed"\] \.placeholder-note \{\s*display: block;/', $css, 'a tile that carries its own label (the hero mosaic) keeps its failed photo without words');
    }

    public function testAPhotoWithAHoverTransitionStillFadesIn(): void
    {
        $missing = [];
        foreach ($this->reader->files() as $file) {
            foreach ($this->reader->rules($file) as $rule) {
                if (str_ends_with($rule['selector'], ' img') && preg_match('/(?:^|;)\s*transition:([^;]*)/', $rule['body'], $m) && !str_contains($m[1], 'opacity')) {
                    $missing[] = $file.': '.$rule['selector'];
                }
            }
        }
        self::assertSame([], $missing, 'a photo\'s own transition list carries the fade, or its own opacity transition (a slideshow\'s cross-fade), or it would replace it');
    }

    public function testAWaitingListTurnsItsWordsIntoPillsAndHidesItsIcons(): void
    {
        $css = $this->reader->source('components/loading.css');
        self::assertMatchesRegularExpression('/\.loading\[data-waiting\] \.loading-text \{[^}]*color: transparent;[^}]*background: var\(--soft\);[^}]*border-radius: var\(--r-pill\);[^}]*box-decoration-break: clone;[^}]*animation: dropdown-wait 1\.2s ease-in-out infinite alternate;/', $css);
        self::assertMatchesRegularExpression('/\.loading\[data-waiting\] \.loading-icon[^{]*\{\s*visibility: hidden;/', $css);
        self::assertMatchesRegularExpression('/\.loading\[data-waiting\] \{[^}]*pointer-events: none;/', $css);
        self::assertMatchesRegularExpression('/:where\(\.loading\[data-waiting\]\) :is\(\.placeholder,\.placeholder-thumb\)\[data-state\] \{[^}]*opacity: 1;[^}]*animation: dropdown-wait/', $css, 'its photos, come or not, back on the breathing ground');
    }

    public function testASuggestionsPhotoHasABoxOfItsOwn(): void
    {
        self::assertMatchesRegularExpression('/\.suggest-thumb \{[^}]*position: relative;[^}]*width: 44px;[^}]*height: 44px;[^}]*overflow: hidden;/', $this->reader->source('components/search.css'));
    }

    public function testACreditsPhotoHasABoxOfItsOwn(): void
    {
        self::assertMatchesRegularExpression('/\.credits-photo \{[^}]*position: relative;[^}]*width: 64px;[^}]*height: 64px;[^}]*overflow: hidden;[^}]*border-radius: var\(--r-s\);/', $this->reader->source('pages/credits.css'));
    }

    public function testAMapBreathesOnTheGroundUntilItsFirstTile(): void
    {
        $css = $this->reader->source('components/loading.css');
        self::assertStringContainsString('.leaflet-container:not(:has(.leaflet-tile-loaded))::before', $css);
    }

    public function testNothingBreathesUnderReducedMotion(): void
    {
        foreach (['components/photo-placeholder.css', 'components/loading.css'] as $file) {
            self::assertMatchesRegularExpression('/@media \(prefers-reduced-motion:reduce\) \{[^@]*animation: none;/', $this->reader->source($file), $file);
        }
    }

    public function testTheDocsShowEveryMoment(): void
    {
        $docs = (string) file_get_contents(__DIR__.'/../../docs/index.html');
        foreach (['data-state="hold"', 'data-state="wait"', 'data-state="done"', 'data-state="failed"', 'class="rows loading" data-waiting', 'loading-text', 'loading-icon'] as $hook) {
            self::assertStringContainsString($hook, $docs);
        }
    }
}
