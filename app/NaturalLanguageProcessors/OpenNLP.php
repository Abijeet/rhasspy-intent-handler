<?php
declare(strict_types=1);

namespace App\NaturalLanguageProcessors;

use App\Exceptions\NLPException;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

class OpenNLP implements NaturalLanguageProcessor
{
	public function __construct(
		private Client $client,
		private string $serverURL,
		private string $langaugeCode,
		private string $timezone
	) {
	}

	/** @inheritDoc */
	public function getDateTime(string $text): array
	{
		$timezone = new DateTimeZone($this->timezone);
		$nlpResponse = $this->makeRequest(
			$text,
			[
				'properties' => [
					'annotators' => 'tokenize,ssplit,ner',
					'date' => (new DateTime('now', $timezone))->format(DateTime::ATOM)
				]
			]
		);

		$entityMentions = $nlpResponse['sentences'][0]['entitymentions'] ?? [];
		$dateTimes = [];
		foreach ($entityMentions as $mention) {
			if ($mention['ner'] !== 'TIME') {
				continue;
			}

			$timex = $mention['timex'];
			$dateTimes[$timex['tid']] = new DateTime($timex['value'], $timezone);
		}

		return $dateTimes;
	}

	private function makeRequest(string $text, array $props): array
	{
		$props['properties']['outputFormat'] = 'json';
		$props['pipelineLanguage'] = $this->langaugeCode;
		$props['properties'] = json_encode($props['properties']);

		$response = $this->client->post(
			$this->serverURL,
			[
				'query' => $props,
				'body' => $text
			]
		);

		$responseBody = $response->getBody()->getContents();
		$nlpResponse = json_decode($responseBody, true);
		if ($response->getStatusCode() !== Response::HTTP_OK || $nlpResponse === false) {
			throw new NLPException(__('rhasspy_nlp_error', [ 'response' => $responseBody ]));
		}

		return $nlpResponse;
	}
}
