<?php

declare(strict_types=1);

namespace MrActivities\DesignSystem\Tests\Unit;

use MrActivities\DesignSystem\Service\PaletteService;
use PHPUnit\Framework\TestCase;

/**
 * The expected tones were computed once by running the design engine's palette formula (brand.js) and are pinned here.
 */
final class PaletteServiceTest extends TestCase
{
    private const array PALETTE_3 = ['name' => '3 · Highland green and sun', 'ink' => '#24573a', 'accent' => '#d9a405', 'paper' => '#fbfaf2', 'night' => '#0d1710'];
    private const array PALETTE_22 = ['name' => '22 · Bottle green and cream', 'ink' => '#0e3b2e', 'accent' => '#c79a3e', 'paper' => '#fffdf6', 'night' => '#06180f'];

    public function testPaletteThreeTonesEqualTheEngines(): void
    {
        self::assertSame([
            'bg' => '#f1f4f2', 'panel' => '#fbfaf2', 'ink' => '#18201b', 'muted' => '#53645b', 'line' => '#dae1dd', 'soft' => '#eaf0ed',
            'tint' => '#dfece5', 'brand' => '#24573a', 'on-brand' => '#ffffff', 'brand-text' => '#24573a', 'accent' => '#d9a405',
            'on-accent' => '#141414', 'accent-text' => '#18201b', 'night' => '#0d1710', 'on-night' => '#ffffff',
        ], (new PaletteService())->tones(self::PALETTE_3));
    }

    public function testPaletteTwentyTwoTonesEqualTheEngines(): void
    {
        self::assertSame([
            'bg' => '#f0f5f3', 'panel' => '#fffdf6', 'ink' => '#16221f', 'muted' => '#4f6861', 'line' => '#d9e3e0', 'soft' => '#e8f2ef',
            'tint' => '#dcefe9', 'brand' => '#0e3b2e', 'on-brand' => '#ffffff', 'brand-text' => '#0e3b2e', 'accent' => '#c79a3e',
            'on-accent' => '#141414', 'accent-text' => '#16221f', 'night' => '#06180f', 'on-night' => '#ffffff',
        ], (new PaletteService())->tones(self::PALETTE_22));
    }

    public function testTheStylesheetPutsTheDefaultOnRootAndEveryPaletteUnderItsAttribute(): void
    {
        $css = (new PaletteService())->stylesheet([self::PALETTE_3, self::PALETTE_22], 3);

        self::assertStringContainsString(":root,\n[data-palette=\"3\"] {\n  --bg: #f1f4f2;", $css);
        self::assertStringContainsString("[data-palette=\"22\"] {\n  --bg: #f0f5f3;", $css);
        self::assertSame(2, substr_count($css, '--on-night:'));
    }

    public function testTheShippedPaletteFileIsGeneratedFromPalettesJson(): void
    {
        $service = new PaletteService();
        $palettes = $service->palettesFromJson((string) file_get_contents(__DIR__.'/../../palettes.json'));

        self::assertCount(24, $palettes);
        self::assertSame($service->stylesheet($palettes, 3), file_get_contents(__DIR__.'/../../assets/tokens/palette.css'), 'tokens/palette.css is stale: run composer build');
    }
}
