<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Unit;

use MrActivities\Acacia\Tests\Support\StylesheetReader;
use PHPUnit\Framework\TestCase;

/**
 * The promise card: its title is the title (.promise-bold) whatever it holds, so a title written in language spans
 * (<b class="promise-bold"><span class="en">…</span><span class="sw">…</span></b>) stays the title; the line below
 * it is the muted 17 px line, whether it is .promise-label or the card's own language spans.
 */
final class PromiseComponentTest extends TestCase
{
    public function testTheLineBelowIsStyledOnlyWhereItIsAChildOfTheCardsText(): void
    {
        $css = new StylesheetReader(__DIR__.'/../../assets')->source('components/promise.css');

        self::assertDoesNotMatchRegularExpression('/\.promise-text :is\(/', $css, 'a language span inside the title is not the line below');
        self::assertSame(2, substr_count($css, '.promise-text > :is(.promise-label, .en, .sw) {'));
        self::assertMatchesRegularExpression('/\.promise-text > :is\(\.promise-label, \.en, \.sw\) \{\s*display: block;\s*font-size: 17px;/', $css);
        self::assertMatchesRegularExpression('/\.promise-text > :is\(\.promise-label, \.en, \.sw\) \{\s*color: var\(--muted\);/', $css);
    }
}
