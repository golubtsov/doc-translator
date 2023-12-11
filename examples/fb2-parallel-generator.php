<?php

require_once 'vendor/autoload.php';

use Nigo\Translator\Document\Fb2ParallelDocumentGenerator;

$generator = new Fb2ParallelDocumentGenerator('ru', __DIR__ . '/../storage/translations');

if ($generator->generateByFile(__DIR__ . '/../storage/test_doc/text.txt') !== false) {
    echo 'Перевод завершен' . PHP_EOL;
} else {
    echo 'Произошла ошибка' . PHP_EOL;
}
