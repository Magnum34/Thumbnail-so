<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__,'.env');
$dotenv->load();


use ThumbnailSo\ThumbnailSo;


$img = new ThumbnailSo('./new.png');
$img->resizeToMaxSide(150);
$img->save('s3', 'tests', 'cat');

