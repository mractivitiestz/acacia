<?php

declare(strict_types=1);

namespace MrActivities\DesignSystem\Tests\Unit;

use MrActivities\DesignSystem\Service\StylesheetConcatService;
use PHPUnit\Framework\TestCase;

final class StylesheetConcatServiceTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir().'/mra-concat-'.bin2hex(random_bytes(4));
        mkdir($this->dir.'/assets/components', 0o777, true);
        file_put_contents($this->dir.'/assets/mractivities.css', "@layer tokens, components;\n@import url('tokens/a.css') layer(tokens);\n@import url('components/logo.css') layer(components);\n");
        mkdir($this->dir.'/assets/tokens');
        file_put_contents($this->dir.'/assets/tokens/a.css', ':root { --x: 1px; }');
        file_put_contents($this->dir.'/assets/components/logo.css', ".logo { mask: url(../img/mark.svg) center/contain; background: url('data:image/png;base64,AA'); }");
    }

    public function testWrapsEachFileInItsLayerInImportOrder(): void
    {
        $css = (new StylesheetConcatService())->concat($this->dir.'/assets');

        self::assertStringContainsString("@layer tokens, components;\n", $css);
        self::assertLessThan(strpos($css, '@layer components {'), strpos($css, '@layer tokens {'));
    }

    public function testRewritesRelativeUrlsSoTheyResolveFromDist(): void
    {
        $css = (new StylesheetConcatService())->concat($this->dir.'/assets');

        self::assertStringContainsString('url(../assets/img/mark.svg)', $css);
        self::assertStringContainsString("url('data:image/png;base64,AA')", $css);
    }
}
