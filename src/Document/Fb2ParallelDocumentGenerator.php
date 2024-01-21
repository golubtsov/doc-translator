<?php

namespace Nigo\Translator\Document;

use Nigo\Fb2Book\Fb2Book;
use Nigo\Translator\Translator\LibreTranslator;
use Nigo\Translator\Translator\TranslatorAbstract;

class Fb2ParallelDocumentGenerator extends DocumentGenerator
{
    private Fb2Book $book;

    public function __construct(string $lang, string $path)
    {
        $this->lang = $lang;
        $this->path = $path;
        $this->translator = new LibreTranslator();
        $this->book = new Fb2Book();
    }

    public function setLogStateForTranslator(bool $state): void
    {
        $this->translator = new LibreTranslator($state);
    }

    public function setNewTranslator(TranslatorAbstract $translator): void
    {
        $this->translator = $translator;
    }

    protected function splitByParagraphs(): void
    {
        $paragraphs = explode(PHP_EOL, $this->text);

        foreach ($paragraphs as $paragraph) {
            $this->translatedText .= "<p>{$this->splitBySentences($paragraph)}</p>";
        }
    }

    protected function splitBySentences(string $paragraph): string
    {
        $translatedSentences = '';

        $sentences = explode('. ', $paragraph);

        foreach ($sentences as $index => $sentence) {
            try {
                $translatedSentences .= "<strong>$sentence</strong> ({$this->translator->translate($sentence, $this->lang)})";
            } catch (\Exception $exception) {
                echo $exception->getMessage();
                die();
            }

            $translatedSentences .= $index == count($sentences) - 1 ? ' ' : '. ';
        }

        return $translatedSentences;
    }

    private function markup(string $filename): string
    {
        return $this->book->setTitle($filename)
            ->addSection($this->translatedText)
            ->create();
    }

    protected function save(string $filename): bool|int
    {
        return file_put_contents($this->path . '/' . $filename . ' Translate.fb2', $this->markup($filename));
    }
}