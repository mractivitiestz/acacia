<?php

declare(strict_types=1);

use MrActivities\DesignSystem\Service\PaletteService;

require __DIR__.'/../vendor/autoload.php';

const DEFAULT_PALETTE = 3;

$root = dirname(__DIR__);
$palettes = json_decode((string) file_get_contents($root.'/palettes.json'), true, flags: \JSON_THROW_ON_ERROR);
file_put_contents($root.'/assets/tokens/palette.css', (new PaletteService())->stylesheet($palettes, DEFAULT_PALETTE));
echo 'assets/tokens/palette.css: '.count($palettes)." palettes\n";
