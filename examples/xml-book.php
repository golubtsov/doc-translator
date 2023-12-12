<?php

require_once 'vendor/autoload.php';

use Nigo\Translator\XML\FB2BookStr;

$book = new FB2BookStr();

$xml = $text = $book->setTitle('My FB2 book')
    ->setAuthor('First Name', 'Last Name')
    ->setAnnotation('My book annotation')
    ->setBookLang('en')
    ->addSection('First section for book', 'Chapter one')
    ->addSection('Second section for book', 'Chapter two')
    ->create();

file_put_contents(__DIR__ . '/../storage/translations/my-book.fb2', $text);