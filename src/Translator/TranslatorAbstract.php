<?php

namespace Nigo\Translator\Translator;

abstract class TranslatorAbstract
{
    protected bool $logState;

    /** Ключ, по которому можно получить перевод от ответа API */
    protected string $translatedTextKey = '';

    /** API */
    protected string $uri = '';

    abstract public function translate(string $text, mixed $lang);

    public function __construct(bool $logState = false)
    {
        $this->logState = $logState;
        $this->uri = $_ENV['API_TRANSLATOR'] ?? '';
    }

    public function setLogState(bool $state): void
    {
        $this->logState = $state;
    }

    protected function writeError(string $response): void
    {
        $files = scandir('storage/logs');

        $todayFile = date('d_m_Y') . '.txt';

        if (!in_array($todayFile, $files)) {
            file_put_contents('storage/logs/' . $todayFile, '');

            for ($i = 2; $i < count($files); $i++) {
                unlink('storage/logs/' . $files[$i]);
            }
        }

        $file = fopen('storage/logs/' . $todayFile, "a");

        fwrite($file, date('d.m.Y h:i:s') . "\r" . $response . PHP_EOL);

        fclose($file);
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }
}