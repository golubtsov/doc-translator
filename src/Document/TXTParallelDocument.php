<?php

namespace Nigo\Translator\Document;

use Nigo\Translator\Document\Traits\LanguageForTranslateTrait;
use Nigo\Translator\Translator\LibreTranslator;

class TXTParallelDocument extends DocumentGenerator
{
    use LanguageForTranslateTrait;

    public function __construct(string $lang, string $path)
    {
        $this->lang = $lang;
        $this->path = $path;
        $this->translator = new LibreTranslator();
    }

    protected function splitByParagraphs(): void
    {
        $paragraphs = explode(PHP_EOL, $this->text);

        foreach ($paragraphs as $paragraph) {
            $this->translatedText .= "{$this->splitBySentences($paragraph)}" . PHP_EOL . PHP_EOL;
        }
    }

    protected function splitBySentences(string $paragraph): string
    {
        $translatedSentences = '';

        $sentences = explode('. ', $paragraph);

        foreach ($sentences as $index => $sentence) {
            try {
                $translatedSentences .= "{$this->translator->translate($sentence, $this->lang)}";
            } catch (\Exception $exception) {
                echo $exception->getMessage();
                die();
            }

            $translatedSentences .= $index == count($sentences) - 1 ? ' ' : '. ';
        }

        return $translatedSentences;
    }

    protected function save(string $filename): bool|int
    {
        return file_put_contents($this->path . '/' . $filename . '.txt', $this->translatedText);
    }
}