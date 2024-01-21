<?php

namespace Nigo\Translator\Document;

use Nigo\Fb2Book\Fb2Book;

class SimpleFB2 extends DocumentGenerator
{
    private Fb2Book $book;

    private string $fb2Text = '';

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->book = new Fb2Book();
    }

    protected function splitByParagraphs(): void
    {
        $paragraphs = explode(PHP_EOL, $this->text);

        foreach ($paragraphs as $paragraph) {
            $this->fb2Text .= "<p>{$this->splitBySentences($paragraph)}</p>";
        }
    }

    protected function splitBySentences(string $paragraph): string
    {
        $fb2Sentences = '';

        $sentences = explode('. ', $paragraph);

        foreach ($sentences as $index => $sentence) {
            $fb2Sentences .= "$sentence";
            $fb2Sentences .= $index == count($sentences) - 1 ? ' ' : '. ';
        }

        return $fb2Sentences;
    }

    private function markup(string $filename): string
    {
        return $this->book->setTitle($filename)
            ->addSection($this->fb2Text)
            ->create();
    }

    protected function save(string $filename): bool|int
    {
        return file_put_contents($this->path . '/' . $filename . '.fb2', $this->markup($filename));
    }
}