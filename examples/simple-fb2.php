<?php

require_once 'vendor/autoload.php';

use Nigo\Translator\Document\SimpleFB2;
use Symfony\Component\Dotenv\Dotenv;

$env = new Dotenv();
$env->load(__DIR__ . '/../.env');

$document = new SimpleFB2(__DIR__ . '/../storage/translations');

if ($document->generateByFile(__DIR__ . '/../storage/test_doc/text.txt', 'simple-fb2') !== false) {
    echo 'Файл создан' . PHP_EOL;
} else {
    echo 'Произошла ошибка' . PHP_EOL;
}