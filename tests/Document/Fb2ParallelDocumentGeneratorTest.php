<?php

namespace Document;

use PHPUnit\Framework\TestCase;
use Nigo\Translator\Document\Fb2ParallelDocumentGenerator;

class Fb2ParallelDocumentGeneratorTest extends TestCase
{
    public function testCreateFb2(): void
    {
        $filename = 'hello';
        $savePath = __DIR__ . '/../../storage/logs';
        $pathToFile = __DIR__ . '/../../storage/logs/' . $filename . '.fb2';

        $fb2 = new Fb2ParallelDocumentGenerator('ru', $savePath);
        $fb2->setUriForTranslator('http://localhost:5000/translate');
        $result = $fb2->generate('World.', $filename);

        self::assertNotFalse($result);
        self::assertFileExists($pathToFile);

        $xml = simplexml_load_string(file_get_contents($pathToFile));

        $word = json_decode(json_encode($xml->body->section->p->p->strong))->{0};
        $translate = json_decode(json_encode($xml->body->section->p->p->emphasis))->{0};

        self::assertEquals('World.', $word);
        self::assertEquals('(Мир.)', $translate);
    }
}
