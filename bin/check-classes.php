<?php

declare(strict_types=1);

use MrActivities\DesignSystem\Service\ClassCheckService;

require __DIR__.'/../vendor/autoload.php';

/*
 * Reports every class, and every value of a data-* attribute the framework styles, that a page, template or script
 * uses and dist/mractivities.css does not define. Exits 1 when it finds any.
 *
 *   php bin/check-classes.php [--engine=<design engine root>] [file ...]
 *
 * With no file the package's docs/index.html is checked. --engine (or the MRACTIVITIES_ENGINE environment variable)
 * adds the engine's site/assets/js/app.js and expedition.js and every page it writes under site/.
 */
$root = dirname(__DIR__);
$files = [];
$engine = getenv('MRACTIVITIES_ENGINE') ?: null;
/** @var list<string> $argv */
$argv = $_SERVER['argv'] ?? [];
foreach (array_slice($argv, 1) as $arg) {
    if (str_starts_with($arg, '--engine=')) {
        $engine = substr($arg, 9);
    } else {
        $files[] = $arg;
    }
}
if ([] === $files) {
    $files[] = $root.'/docs/index.html';
}
if (null !== $engine) {
    $site = rtrim($engine, '/').'/site';
    if (!is_dir($site)) {
        fwrite(\STDERR, "No site/ under {$engine}.\n");
        exit(2);
    }
    $files[] = $site.'/assets/js/app.js';
    $files[] = $site.'/assets/js/expedition.js';
    $pages = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($site, FilesystemIterator::SKIP_DOTS));
    foreach ($pages as $page) {
        if ($page instanceof SplFileInfo && 'index.html' === $page->getFilename()) {
            $files[] = $page->getPathname();
        }
    }
}

$service = new ClassCheckService((string) file_get_contents($root.'/dist/mractivities.css'), ['leaflet-', 'docs-']);
$count = 0;
foreach ($files as $file) {
    $source = @file_get_contents($file);
    if (false === $source) {
        fwrite(\STDERR, "Cannot read {$file}.\n");
        exit(2);
    }
    foreach ($service->check($file, $source) as $finding) {
        echo "{$finding['file']}:{$finding['line']}  {$finding['hook']}  {$finding['name']}\n";
        ++$count;
    }
}
echo 0 === $count ? sprintf("%d file(s): every hook is defined.\n", count($files)) : "{$count} undefined hook(s).\n";
exit(0 === $count ? 0 : 1);
