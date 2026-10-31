<?php

declare(strict_types=1);

use MrActivities\Acacia\Service\PaletteService;

require __DIR__.'/../vendor/autoload.php';

const DEFAULT_PALETTE = 3;

$root = dirname(__DIR__);
$service = new PaletteService();
$palettes = $service->palettesFromJson((string) file_get_contents($root.'/palettes.json'));
file_put_contents($root.'/assets/tokens/palette.css', $service->stylesheet($palettes, DEFAULT_PALETTE));
echo 'assets/tokens/palette.css: '.count($palettes)." palettes\n";
