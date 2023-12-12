<?php

class XMLVersion
{
    public function initBook(): static
    {
        $this->xm->openMemory();
        $this->xm->setIndent(1);
        $this->xm->startDocument(encoding: 'UTF-8');
        return $this;
    }

    public function setDescription(): static
    {
//        $this->attributes[] = 'description';
        return $this;
    }

    public function setAuthorFirstName(string $firstName): static
    {
        $this->attributes['author']['first-name'] = $firstName;
        return $this;
    }

    public function setAuthorLastName(string $lastName): static
    {
        $this->attributes['author']['last-name'] = $lastName;
        return $this;
    }

    public function setBookTitle(string $bookTitle): static
    {
        $this->attributes['book-title'] = $bookTitle;
        return $this;
    }

    public function setBookLang(string $lang): static
    {
        $this->attributes['lang'] = $lang;
        return $this;
    }

    public function endBook(): string
    {
        $this->xm->startElement('FictionBook');
        $this->xm->startElement('description');
        $this->xm->startElement('title-info');

        foreach ($this->attributes as $attribute => $value) {

            if (is_string($this->attributes[$attribute])) {
                $this->xm->startElement($attribute);
                $this->xm->text($attribute);
                $this->xm->endElement();
            }

            if (is_array($this->attributes[$attribute])) {
                foreach ($this->attributes[$attribute] as $item => $value) {
                    $this->xm->startElement($item);
                    $this->xm->text($value);
                    $this->xm->endElement();
                }
            }
        }

        foreach ($this->attributes as $attribute) {
            $this->xm->endElement();
        }

        $this->xm->endElement();
        $this->xm->endElement();
        $this->xm->endElement();


        return $this->xm->outputMemory();
    }

    protected function checkAuthorAttribute(): void
    {
        if (!in_array('author', $this->attributes)) {
            $this->attributes[] = 'author';
        }
    }
}