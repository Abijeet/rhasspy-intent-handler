<?php
declare( strict_types = 1 );

namespace App\SpeechToText;

use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class SpeechToTextProvider {
    const WAV_AUDIO_FORMAT = 'wav';
    const OGG_AUDIO_FORMAT = 'ogg';

    abstract public function transcribe(
        string $audioPath,
        string $audioFormat,
        string $language
    ): SpeechToTextResponse;

    protected function parseBadRequestException(BadResponseException $e) {
        $httpCode = 'N/A';
        $body = 'N/A';

        if ($e->hasResponse()) {
            $httpCode = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();
        }

        return [$httpCode, $body, $e->getMessage()];
    }
}
