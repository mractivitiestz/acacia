<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Unit;

use MrActivities\Acacia\Tests\Support\StylesheetReader;
use PHPUnit\Framework\TestCase;

/**
 * A place's mark and an activity's number keep their size, 24px in a list and 20px on a map, the list's mark centred on
 * the first line of its name; a two-figure number (data-wide) sets its figures a step smaller so 10 to 99 sit inside the
 * ring. A place is numbered, never lettered, so a stay's pin carries .staypin-number. The line of places under a
 * region's name is set in the body's face at 15px (16px on a phone) / 1.7, each name in the link colour at rest and
 * underlined on hover and focus, the middle dots muted, each link a 32px target on a phone; a stay's line keeps its look.
 */
final class PlaceMarkComponentTest extends TestCase
{
    private const string ASSETS = __DIR__.'/../../assets';

    private StylesheetReader $reader;

    protected function setUp(): void
    {
        $this->reader = new StylesheetReader(self::ASSETS);
    }

    public function testTheListsMarkKeepsItsSizeAndIsCentredOnTheNamesFirstLine(): void
    {
        self::assertSame('width: 24px;', $this->declaration('components/legend.css', '.legend-mark', 'width'));
        self::assertSame('font: 800 12px/1 var(--f);', $this->declaration('components/legend.css', '.legend-mark', 'font'));
        self::assertSame('margin-top: 2px;', $this->declaration('components/legend.css', '.legend-item > .legend-mark', 'margin-top'));
        self::assertSame('--pin-size: 20px;', $this->declaration('components/map-marker.css', '.pin[data-mark]', '--pin-size'));
    }

    public function testATwoFigureNumberSetsItsFiguresAStepSmaller(): void
    {
        self::assertSame('font-size: 11px;', $this->declaration('components/legend.css', '.legend-mark[data-wide]', 'font-size'));
        self::assertSame('font-size: 10px;', $this->declaration('components/legend.css', '.legend-mark[data-size="s"][data-wide]', 'font-size'));
        self::assertSame('font-size: 9.5px;', $this->declaration('components/legend.css', '.legend-mark[data-size="xs"][data-wide]', 'font-size'));
        self::assertSame('font-size: 9.5px;', $this->declaration('components/map-marker.css', '.pin-mark[data-wide]', 'font-size'));
    }

    public function testTheLineOfPlacesUnderARegionsNameIsBodyTextInTheLinkColour(): void
    {
        self::assertSame('font: 500 15px/1.7 var(--f);', $this->declaration('components/detail-tabs.css', '.headline-places', 'font'));
        self::assertSame('color: var(--brand-text);', $this->declaration('components/detail-tabs.css', '.headline-place', 'color'));
        self::assertSame('text-underline-offset: 3px;', $this->declaration('components/detail-tabs.css', '.headline-place:is(:hover, :focus-visible)', 'text-underline-offset'));
        self::assertSame('color: var(--muted);', $this->declaration('components/detail-tabs.css', '.headline-sep', 'color'));
        self::assertSame('margin: 0 .5em;', $this->declaration('components/detail-tabs.css', '.headline-sep', 'margin'));
        self::assertSame('font: 600 clamp(17px, 1.5vw, 20px)/1.35 var(--fh);', $this->declaration('components/detail-tabs.css', '.headline-places[data-variant="line"]', 'font'), 'a stay\'s one-sentence line keeps its look');
        $phone = array_values(array_filter($this->reader->rules('components/detail-tabs.css'), static fn (array $r): bool => str_contains($r['media'], 'max-width: 700px') && '.headline-place' === $r['selector']));
        self::assertCount(1, $phone);
        self::assertStringContainsString('padding-block: 7px;', $phone[0]['body']);
    }

    public function testAStaysPinCarriesItsPlacesNumber(): void
    {
        $stays = $this->reader->source('components/stays.css');
        self::assertStringContainsString('.staypin-number {', $stays);
        self::assertStringNotContainsString('staypin-letter', $stays);
    }

    private function declaration(string $file, string $selector, string $property): string
    {
        foreach ($this->reader->rules($file) as $rule) {
            if ('' !== $rule['media'] || $rule['selector'] !== $selector) {
                continue;
            }
            foreach (explode(';', $rule['body']) as $line) {
                if (str_starts_with(trim($line), $property.':')) {
                    return trim($line).';';
                }
            }
        }
        self::fail(\sprintf('%s has no %s in %s', $selector, $property, $file));
    }
}
