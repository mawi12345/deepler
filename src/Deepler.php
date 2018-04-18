<?php

namespace Mawi12345\Deepler;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Deepler {

    private $client;

    private $authKey;

    private $endpoint;

    public function __construct(
        $authKey,
        ClientInterface $client = null,
        $endpoint = 'https://api.deepl.com'
    ) {
        $this->authKey = $authKey;
        $this->client = is_null($client) ? new Client() : $client;
        $this->endpoint = $endpoint;
    }

    private function encodeLanguageCode($code) {
        $code = trim(strtoupper($code));
        if (strlen($code) !== 2) {
            throw new DeeplerException('invalid language code: '.$code, 401);
        }
        return $code;
    }

    public function translate($text, $to, $from = null, array $options = []) {
        $params = [
            'text' => $text,
            'target_lang' => $this->encodeLanguageCode($to),
            'auth_key' => $this->authKey,
        ];

        if ($from) {
            $params['source_lang'] = $this->encodeLanguageCode($from);
        }

        try {
            $guzzleResponse = $this->client->request('POST', $this->endpoint.'/v1/translate', [
                'form_params' => array_merge($params, $options),
            ]);
        } catch (GuzzleException $exception) {
            throw new DeeplerException('Guzzle error during DeepLy API call: '.$exception->getMessage(), 502, $exception);
        }

        $code = $guzzleResponse->getStatusCode();
        if ($code !== 200) {
            throw new DeeplerException('Server side error during DeepLy API call: HTTP code '.$code, 503);
        }

        $body = (string) $guzzleResponse->getBody();

        try {
            $data = json_decode($body, true);
            $translations = array_map(function($translation) {
                return $translation['text'];
            }, $data['translations']);

            if (!is_array($text) && count($translations) === 1) {
                return $translations[0];
            }
            return $translations;
        } catch (\Exception $exception) {
            throw new DeeplerException('Error while reading the response JSON', 504, $exception);
        }
    }
}
