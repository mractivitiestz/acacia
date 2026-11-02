<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Unit;

use MrActivities\Acacia\Tests\Support\StylesheetReader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The house dropdown (D4): its stylesheet draws every part and state the application's controller renders, the dark
 * theme has an error red of its own, and the docs show every member of the family, open and closed.
 */
final class DropdownComponentTest extends TestCase
{
    private const string ASSETS = __DIR__.'/../../assets';

    private const array PARTS = ['dropdown', 'dropdown-trigger', 'dropdown-value', 'dropdown-chev', 'dropdown-panel', 'dropdown-handle', 'dropdown-head', 'dropdown-title', 'dropdown-clear', 'dropdown-search', 'dropdown-list', 'dropdown-group', 'dropdown-heading', 'dropdown-option', 'dropdown-mark', 'dropdown-text', 'dropdown-count', 'dropdown-empty', 'dropdown-loading', 'dropdown-skeleton', 'dropdown-foot', 'dropdown-done', 'dropdown-scrim'];

    public function testTheStylesheetDrawsEveryPart(): void
    {
        $css = (new StylesheetReader(self::ASSETS))->source('components/dropdown.css');
        foreach (self::PARTS as $part) {
            self::assertMatchesRegularExpression('/\.'.$part.'\b/', $css, '.'.$part.' is drawn');
        }
        foreach (['data-size="form"', 'data-multiple', 'data-state="sheet"', 'data-placement="top"', 'data-align="end"', 'aria-selected="true"', 'aria-disabled="true"', 'data-state="active"', 'data-count="0"', 'aria-invalid="true"', 'aria-busy="true"', 'aria-expanded="true"'] as $state) {
            self::assertStringContainsString($state, $css, $state.' is drawn');
        }
    }

    public function testTheKeyboardRingIsTheBrandUnderFocusOnly(): void
    {
        $css = (new StylesheetReader(self::ASSETS))->source('components/dropdown.css');
        self::assertMatchesRegularExpression('/:where\(\.dropdown-list:focus-visible\) \.dropdown-option\[data-state="active"\],\s*:where\(\.dropdown-search:focus-within ~ \.dropdown-list\) \.dropdown-option\[data-state="active"\] \{\s*box-shadow: inset 0 0 0 1\.5px var\(--brand\);/', $css);
        self::assertStringNotContainsString('var(--ink)', (string) preg_replace('/\.dropdown-(trigger|chev|title|option|search input|panel)[^{]*\{[^}]*\}/', '', $css), 'no dark glow');
    }

    public function testTheDarkThemeHasItsOwnErrorRed(): void
    {
        $theme = (new StylesheetReader(self::ASSETS))->source('tokens/theme.css');
        self::assertMatchesRegularExpression('/html\[data-theme="dark"\] \{[^}]*--error: #[0-9a-f]{6};[^}]*--error-bg: #[0-9a-f]{6};/', $theme);
    }

    /** @return iterable<string, array{string}> */
    public static function members(): iterable
    {
        foreach (['single choice', 'with counts', 'multiple choice', 'a long list', 'grouped', 'in a form', 'with an error', 'the language menu', 'disabled', 'loading', 'the phone sheet'] as $member) {
            yield $member => [$member];
        }
    }

    #[DataProvider('members')]
    public function testTheDocsShowEveryMember(string $member): void
    {
        $docs = (string) file_get_contents(__DIR__.'/../../docs/index.html');
        preg_match('#<section class="docs-sec" id="c-components-dropdown">.*?</section>#s', $docs, $m);
        self::assertMatchesRegularExpression('#<span class="docs-sig">'.preg_quote($member, '#').'\b#', $m[0] ?? '', 'the docs\' dropdown section shows '.$member);
        self::assertStringContainsString('href="#c-components-dropdown"', $docs, 'the docs nav links it');
    }
}
