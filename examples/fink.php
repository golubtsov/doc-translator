<?php


// Создаем XML-структуру для FB2
$xml = new SimpleXMLElement('<FictionBook/>');
$description = $xml->addChild('description');
$titleInfo = $description->addChild('title-info');
$titleInfo->addChild('book-title', 'Название книги');
$src = $titleInfo->addChild('coverpage')
    ->addChild('image');

$xml->addChild('book')->addChild('p', 'nfvjerkjvnjkejve');

// Сохраняем XML-файл
file_put_contents('book.fb2', $xml->asXML());

