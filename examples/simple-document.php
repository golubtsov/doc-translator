<?php

require_once 'vendor/autoload.php';

use Nigo\Translator\Document\SimpleDocument;
use Symfony\Component\Dotenv\Dotenv;

$env = new Dotenv();
$env->load(__DIR__ . '/../.env');

$document = new SimpleDocument('ru', __DIR__ . '/../storage/translations');

if ($document->generateByFile(__DIR__ . '/../storage/test_doc/text.txt', 'simple-doc') !== false) {
    echo 'Перевод завершен' . PHP_EOL;
} else {
    echo 'Произошла ошибка' . PHP_EOL;
}