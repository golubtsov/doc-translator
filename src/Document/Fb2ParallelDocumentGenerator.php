<?php

namespace Nigo\Translator\Document;

use Nigo\Fb2Book\Fb2Book;
use Nigo\Translator\Translator\LibreTranslator;
use Nigo\Translator\Translator\TranslatorAbstract;

class Fb2ParallelDocumentGenerator
{
    private string $lang;

    private string $path;

    private string $text;

    private string $translatedText = '';

    private TranslatorAbstract $translator;

    private Fb2Book $book;

    public function __construct(string $lang, string $path)
    {
        $this->lang = $lang;
        $this->path = $path;
        $this->translator = new LibreTranslator(true);
        $this->book = new Fb2Book();
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

    public function setLogStateForTranslator(bool $state): void
    {
        $this->translator = new LibreTranslator($state);
    }

    public function setNewTranslator(TranslatorAbstract $translator): void
    {
        $this->translator = $translator;
    }

    private function splitByParagraphs(): void
    {
        $paragraphs = explode(PHP_EOL, $this->text);

        foreach ($paragraphs as $paragraph) {
            $this->translatedText .= "<p>{$this->splitBySentences($paragraph)}</p>";
        }
    }

    private function splitBySentences(string $paragraph): string
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

    private function save(string $filename): bool|int
    {
        return file_put_contents($this->path . '/' . $filename . '_Translate.fb2', $this->markup($filename));
    }

    private function clearText(string $text): string
    {
        return trim(preg_replace("/(\R\s*){2,}/", PHP_EOL, $text));
    }

    private function markup(string $filename): string
    {
        return $this->book->setTitle($filename)
            ->addSection($this->translatedText)
            ->create();
    }
}