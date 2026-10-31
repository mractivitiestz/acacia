<?php

declare(strict_types=1);

use MrActivities\Acacia\Service\StylesheetConcatService;

require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
@mkdir($root.'/dist');
$css = (new StylesheetConcatService())->concat($root.'/assets');
file_put_contents($root.'/dist/acacia.css', $css);
echo 'dist/acacia.css: '.strlen($css)." bytes\n";
