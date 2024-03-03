<?php

namespace Nigo\Translator\Test\Document;

use PHPUnit\Framework\TestCase;
use Nigo\Translator\Document\TXTParallelDocument;

class SimpleDocumentTest extends TestCase
{
    public function testCreateTxtFile(): void
    {
        $filename = 'hello-world';
        $txtDoc = new TXTParallelDocument('ru', __DIR__ . '/../../storage/logs');
        $txtDoc->setUriForTranslator('http://localhost:5000/translate');
        $result = $txtDoc->generate('Hello, World!', $filename);
        self::assertNotFalse($result);
        self::assertFileExists(__DIR__ . '/../../storage/logs/' . $filename . '.txt');
    }

    public function testCreateTxtFileUseMethodGenerateByFile(): void
    {
        $filename = 'hello-world';
        $txtDoc = new TXTParallelDocument('ru',__DIR__ . '/../../storage/logs');
        $txtDoc->setUriForTranslator('http://localhost:5000/translate');
        $result = $txtDoc->generateByFile(__DIR__ . '/../../storage/test_doc/text.txt', $filename);
        self::assertNotFalse($result);
        self::assertFileExists(__DIR__ . '/../../storage/logs/' . $filename . '.txt');
    }
}
