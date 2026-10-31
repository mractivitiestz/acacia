<?php

declare(strict_types=1);

namespace MrActivities\Acacia\Tests\Functional;

use PHPUnit\Framework\TestCase;

final class CheckClassesScriptTest extends TestCase
{
    private const string SCRIPT = __DIR__.'/../../bin/check-classes.php';

    public function testTheDocsUseOnlyDefinedHooks(): void
    {
        [$code, $output] = $this->runScript();

        self::assertSame(0, $code, $output);
        self::assertStringContainsString('every hook is defined', $output);
    }

    public function testAnUndefinedClassIsReportedWithItsFileAndLineAndFails(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'mra').'.html';
        file_put_contents($file, "<div class=\"card\">\n<p class=\"card-nope\"></p></div>");

        [$code, $output] = $this->runScript($file);
        unlink($file);

        self::assertSame(1, $code);
        self::assertStringContainsString($file.':2  class  card-nope', $output);
    }

    public function testAnEngineWithoutASiteIsRefused(): void
    {
        [$code] = $this->runScript('--engine='.sys_get_temp_dir().'/no-engine-here');

        self::assertSame(2, $code);
    }

    /** @return array{int, string} */
    private function runScript(string ...$args): array
    {
        exec(implode(' ', array_map('escapeshellarg', [\PHP_BINARY, self::SCRIPT, ...$args])).' 2>&1', $lines, $code);

        return [$code, implode("\n", $lines)];
    }
}
