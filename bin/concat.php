<?php

declare(strict_types=1);

use MrActivities\DesignSystem\Service\StylesheetConcatService;

require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
@mkdir($root.'/dist');
$css = (new StylesheetConcatService())->concat($root.'/assets');
file_put_contents($root.'/dist/mractivities.css', $css);
echo 'dist/mractivities.css: '.strlen($css)." bytes\n";
