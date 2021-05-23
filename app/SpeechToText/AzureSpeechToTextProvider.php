<?php
declare( strict_types = 1 );

namespace App\SpeechToText;

use App\Exceptions\TranscriptionError;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;

class AzureSpeechToTextProvider extends SpeechToTextProvider {
    private const CACHE_TIMEOUT_SECS = 9.25 * 60;

    public function __construct(
        private string $subscriptionKey,
        private string $region
    ) {}

    public function transcribe(
        string $audioPath,
        string $audioFormat,
        string $language
    ): SpeechToTextResponse {
        $azureAccessToken = $this->getToken();

        $client = new Client();
        try {
            // TODO: Improve the way this is handled.
            $format = 'audio/wav; codecs=audio/pcm; samplerate=16000';
            if ($audioFormat === self::OGG_AUDIO_FORMAT) {
                $format = 'audio/ogg; codecs=opus';
            }

            $transcriptionURL = "https://{$this->region}.stt.speech.microsoft.com/" .
                "speech/recognition/conversation/cognitiveservices/v1";
            $response = $client->request('POST', $transcriptionURL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $azureAccessToken,
                    'Accept' => 'application/json',
                    'Content-type' => $format
                ],
                'body' =>file_get_contents($audioPath),
                'query' => [
                    'language' => $language
                ]
            ]);

            return $this->parseResponse($response);
        } catch (BadResponseException $e) {
            [$httpCode, $body, $message] = $this->parseBadRequestException($e);
            throw new TranscriptionError(
                "There was an error while transcribing the audio with Azure" .
                'Body: ' . $body .
                'Http Code:' . $httpCode .
                'Message: ' . $message
            );
        }
    }

    private function getToken(): string {
        $azureAccessKey = Cache::get('azure_access_key');
        if ($azureAccessKey) {
            return $azureAccessKey;
        }

        // Get the token from Azure API
        $azureAccessTokenURL = "https://{$this->region}.api.cognitive.microsoft.com/sts/v1.0/issueToken";
        $client = new Client();
        try {
            $response = $client->request('POST', $azureAccessTokenURL, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey
                ]
            ]);

            $azureAccessKey = $response->getBody()->getContents();
            Cache::put('azure_access_key', $azureAccessKey, self::CACHE_TIMEOUT_SECS);
            return $azureAccessKey;
        } catch (BadResponseException $e) {
            report($e);

            [$httpCode, $body, $message] = $this->parseBadRequestException($e);
            throw new TranscriptionError(
                'There was an error while fetching access token from Azure.' .
                'Body: ' . $body .
                'Http Code:' . $httpCode .
                'Message: ' . $message
            );
        }
    }

    private function parseResponse(ResponseInterface $response): SpeechToTextResponse {
        $json = json_decode($response->getBody()->getContents(), true);
        if ($json === false) {
            throw new TranscriptionError(
                "There was an error while parsing the response from Azure. " .
                "Response: " . $response->getBody()->getContents() .
                "Http Code: " . $response->getStatusCode()
            );
        }

        // https://docs.microsoft.com/en-us/azure/cognitive-services/speech-service/rest-speech-to-text#sample-responses
        if (strtolower($json['RecognitionStatus']) !== 'success') {
            throw new TranscriptionError(
                'Azure could not successfully transcribe the audio.' .
                'Response: ' . $response->getBody()->getContents()
            );
        }

        if (isset($json['NBest'])) {
            $words = [];
            $transcription = $json['NBest'][0];
            foreach ($transcription['Words'] as $word) {
                $words[] = $word['Word'];
            }

            return new SpeechToTextResponse(
                $transcription['Lexical'],
                $transcription['Confidence'],
                $words
            );
        } else {
            return new SpeechToTextResponse($json['DisplayText']);
        }

    }
}
