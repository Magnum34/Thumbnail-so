<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__ . '/vendor/autoload.php';


use ThumbnailSo\ThumbnailSo;

$img = new ThumbnailSo('./new.png');
$img->resizeToMaxSide(150);
$img->save('local', './tests', 'cat');

echo 'test';