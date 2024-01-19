<?php

namespace Nigo\Translator\Translator;

class LibreTranslator extends TranslatorAbstract
{
    protected string $translatedTextKey = 'translatedText';

    protected string $uri = '';

    /**
     * @throws \Exception
     */
    public function translate(string $text, mixed $lang): string
    {
        $curl = curl_init();

        $fields = [
            'q' => $text,
            'source' => 'auto',
            'target' => $lang,
        ];

        curl_setopt($curl, CURLOPT_URL, $this->uri);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($status === 0) {
            throw new \Exception("API переводчика не отвечает\n");
        }

        if ($status != 200) {
            if ($this->logState) $this->writeError($response);
            return '';
        }

        return json_decode($response)->{$this->translatedTextKey};
    }
}