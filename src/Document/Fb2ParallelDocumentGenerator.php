<?php

namespace Nigo\Translator\Document;

use Nigo\Translator\Translator\LibreTranslator;
use Nigo\Translator\Translator\TranslatorAbstract;

class Fb2ParallelDocumentGenerator
{
    private string $lang;

    private string $path;

    private string $text;

    private string $translatedText = '';

    private TranslatorAbstract $translator;

    public function __construct(string $lang, string $path)
    {
        $this->lang = $lang;
        $this->path = $path;
        $this->translator = new LibreTranslator(true);
    }

    public function generateByFile(string $path): bool|int
    {
        $this->text = $this->getDataFromFile($path);

        $this->splitByParagraphs();

        return $this->save($path);
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

    private function save(string $path): bool|int
    {
        $explodePath = explode('/', $path);

        $filename = explode('.', $explodePath[count($explodePath) - 1])[0];

        return file_put_contents($this->path . '/' . $filename . '_Translate.fb2', $this->markup($filename));
    }

    private function getDataFromFile(string $filename): string
    {
        return trim(preg_replace("/(\R\s*){2,}/", PHP_EOL, file_get_contents($filename)));
    }

    private function markup(string $filename): string
    {
        return "<?xml version='1.0' encoding='utf-8'?>
       <FictionBook xmlns='http://www.gribuser.ru/xml/fictionbook/2.0' xmlns:l='http://www.w3.org/1999/xlink'>
            <description>
                <title-info>
                    <book-title>$filename</book-title>
                </title-info>
                <publish-info>
                    <book-name>$filename</book-name>
                </publish-info>   
            </description>
            <body>
                <section>{$this->translatedText}</section>
            </body>
       </FictionBook>";
    }
}