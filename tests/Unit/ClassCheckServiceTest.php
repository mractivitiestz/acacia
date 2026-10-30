<?php

declare(strict_types=1);

namespace MrActivities\DesignSystem\Tests\Unit;

use MrActivities\DesignSystem\Service\ClassCheckService;
use PHPUnit\Framework\TestCase;

final class ClassCheckServiceTest extends TestCase
{
    private const string CSS = <<<'CSS'
        .card { border: 1px solid; }
        .card-body, .card[data-variant="flat"] .card-title { padding: 0; }
        .btn:where([aria-pressed="true"]) { color: red; }
        .chip[data-state="on"], [data-locked] .page, .card[data-n="1"] { overflow: hidden; }
        .map { background: url("img/x.png"); }
        CSS;

    private ClassCheckService $service;

    protected function setUp(): void
    {
        $this->service = new ClassCheckService(self::CSS);
    }

    public function testDefinedClassesAndAttributeValuesComeFromTheSelectors(): void
    {
        self::assertSame(['btn', 'card', 'card-body', 'card-title', 'chip', 'map', 'page'], $this->service->classes());
        self::assertSame(['aria-pressed' => ['true'], 'data-locked' => [''], 'data-n' => ['1'], 'data-state' => ['on'], 'data-variant' => ['flat']], $this->service->attributes());
    }

    public function testAnHtmlFileReportsEachUndefinedClassWithItsLine(): void
    {
        $html = "<div class=\"card\">\n  <p class=\"card-body lead\">x</p>\n</div>";

        self::assertSame([['file' => 'a.html', 'line' => 2, 'hook' => 'class', 'name' => 'lead']], $this->service->check('a.html', $html));
    }

    public function testInterpolatedClassAttributesReadTheStringsInsideTheExpression(): void
    {
        $js = 'const c = x => `<div class="card${x.big ? \' card-huge\' : \'\'} ${x.k}">`;';

        self::assertSame(['card-huge'], array_column($this->service->check('a.js', $js), 'name'));
    }

    public function testTwigExpressionsInsideAClassAttributeAreSkippedButTheirStringsRead(): void
    {
        $twig = '<a class="btn {{ on ? \'btn-on\' : \'\' }} {{ extra }}">';

        self::assertSame(['btn-on'], array_column($this->service->check('a.html.twig', $twig), 'name'));
    }

    public function testScriptSelectorsAndClassListCallsAreRead(): void
    {
        $js = "\$('.card .nope'); el.closest('.chip[data-state=\"on\"]'); el.classList.toggle('gone', on); L.divIcon({className: 'map pin'});";

        self::assertSame(['nope', 'gone', 'pin'], array_column($this->service->check('a.js', $js), 'name'));
    }

    public function testVariantAndStateAttributesMustUseAValueTheFrameworkDefines(): void
    {
        $html = '<span class="chip" data-state="off" data-variant="flat" data-n="5" data-act="go" aria-expanded="false"></span>';
        $js = "el.setAttribute('data-state', 'zero'); el.dataset.variant = 'flat';";

        self::assertSame([['file' => 'a.html', 'line' => 1, 'hook' => 'data-state', 'name' => 'off']], $this->service->check('a.html', $html));
        self::assertSame(['zero'], array_column($this->service->check('a.js', $js), 'name'));
    }

    public function testIgnoredPrefixesAreNotReported(): void
    {
        self::assertSame([], (new ClassCheckService(self::CSS, ['leaflet-']))->check('a.html', '<div class="leaflet-pane card">'));
    }
}
