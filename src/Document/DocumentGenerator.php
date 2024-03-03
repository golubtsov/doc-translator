<?php

namespace Nigo\Translator\Document;

use Nigo\Translator\Translator\TranslatorAbstract;

abstract class DocumentGenerator
{
    protected string $path;

    protected string $text;

    protected string $translatedText = '';

    protected TranslatorAbstract $translator;

    public function setUriForTranslator(string $uri): void
    {
        $this->translator->setUri($uri);
    }

    public function generateByFile(string $pathToFile, string $filename): bool|int
    {
        $this->text = $this->clearText(file_get_contents($pathToFile));

        $this->splitByParagraphs();

        return $this->save($filename);
    }

    public function generate(string $text, string $filename): bool|int
    {
        $this->text = $this->clearText($text);

        $this->splitByParagraphs();

        return $this->save($filename);
    }

    abstract protected function splitByParagraphs(): void;

    abstract protected function splitBySentences(string $paragraph): string;

    abstract protected function save(string $filename): bool|int;

    protected function clearText(string $text): string
    {
        return trim(preg_replace("/(\R\s*){2,}/", PHP_EOL, $text));
    }
}