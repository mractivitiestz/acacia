<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Unit;

use MrActivities\Acacia\Tests\Support\StylesheetReader;
use PHPUnit\Framework\TestCase;

/**
 * A place's mark and an activity's number, in a list (.legend-mark) and on a map (.pin-mark), sized to the text
 * beside them: a 20px disc in the list, centred on the first line of a 16px name, an 18px disc on the map, a place's
 * ring 2px; a two-figure number (data-wide) sets its figures a step smaller so 10 to 99 sit inside the ring. A place is
 * numbered, never lettered, so a stay's pin carries .staypin-number.
 */
final class PlaceMarkComponentTest extends TestCase
{
    private const string ASSETS = __DIR__.'/../../assets';

    private StylesheetReader $reader;

    protected function setUp(): void
    {
        $this->reader = new StylesheetReader(self::ASSETS);
    }

    public function testTheListsMarkIsATwentyPixelDiscCentredOnTheNamesFirstLine(): void
    {
        self::assertSame('width: 20px;', $this->declaration('components/legend.css', '.legend-mark', 'width'));
        self::assertSame('height: 20px;', $this->declaration('components/legend.css', '.legend-mark', 'height'));
        self::assertSame('font: 800 11px/1 var(--f);', $this->declaration('components/legend.css', '.legend-mark', 'font'));
        self::assertSame('grid-template-columns: 20px minmax(0, 1fr);', $this->declaration('components/legend.css', '.legend-item', 'grid-template-columns'));
        self::assertSame('margin-top: 4px;', $this->declaration('components/legend.css', '.legend-item > .legend-mark', 'margin-top'));
        self::assertSame('box-shadow: inset 0 0 0 2px var(--ink);', $this->declaration('components/legend.css', '.legend-mark[data-kind="place"]', 'box-shadow'));
    }

    public function testTheSmallMarkBesideSmallerTextIsEighteenPixelsAndTheOnlyOtherSize(): void
    {
        self::assertSame('width: 18px;', $this->declaration('components/legend.css', '.legend-mark[data-size="s"]', 'width'));
        self::assertSame('font-size: 10px;', $this->declaration('components/legend.css', '.legend-mark[data-size="s"]', 'font-size'));
        self::assertStringNotContainsString('data-size="xs"', $this->reader->source('components/legend.css'));
    }

    public function testTheMapsMarkIsAnEighteenPixelDisc(): void
    {
        self::assertSame('--pin-size: 18px;', $this->declaration('components/map-marker.css', '.pin[data-mark]', '--pin-size'));
        self::assertSame('font: 800 10px/1 var(--f);', $this->declaration('components/map-marker.css', '.pin-mark', 'font'));
        self::assertStringStartsWith('box-shadow: inset 0 0 0 2px var(--ink),', $this->declaration('components/map-marker.css', '.pin[data-kind="place"] .pin-mark', 'box-shadow'));
    }

    public function testATwoFigureNumberSetsItsFiguresAStepSmaller(): void
    {
        self::assertSame('font-size: 9.5px;', $this->declaration('components/legend.css', '.legend-mark[data-wide]', 'font-size'));
        self::assertSame('font-size: 9px;', $this->declaration('components/legend.css', '.legend-mark[data-size="s"][data-wide]', 'font-size'));
        self::assertSame('font-size: 9px;', $this->declaration('components/map-marker.css', '.pin-mark[data-wide]', 'font-size'));
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
