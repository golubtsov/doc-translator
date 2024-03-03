<?php

namespace Nigo\Translator\Test\Translator;

use PHPUnit\Framework\TestCase;
use Nigo\Translator\Translator\LibreTranslator;

class LibreTranslatorTest extends TestCase
{
    public function testTranslate(): void
    {
        $translator = new LibreTranslator();
        $translator->setUri('http://localhost:5000/translate');
        $result = $translator->translate('Hello', 'ru');
        self::assertEquals('Привет', $result);
    }
}
