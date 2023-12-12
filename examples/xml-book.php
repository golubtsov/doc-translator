<?php

require_once 'vendor/autoload.php';

use Nigo\Translator\XML\XMLBook;

$book = new XMLBook();

$text = $book->setTitle('My FB2 book')
    ->setAuthor('First Name', 'Last Name')
    ->setAnnotation('My book annotation')
    ->setBookLang('en')
    ->create();
