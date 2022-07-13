<?php
declare(strict_types=1);

namespace App\ResponseReporters;

use App\Models\Intent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class TelegramResponseReporter implements ResponseReporter
{
	public function __construct(
		private Client $client,
		private string $botToken,
		private string $channelId
	) {
	}

	public function report(string $message, Intent $intent): bool
	{
		// https://api.telegram.org/bot{token}/sendMessage
		$telegramApiURL = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
		try {
			$response = $this->client->request('POST', $telegramApiURL, [
				'headers' => [
					'Content-type' => 'application/json'
				],
				'body' => json_encode([
					'chat_id' => $this->channelId,
					'text' => $message
				]),
				'timeout' => 15
			]);

			return $this->isOK($response);
		} catch (BadResponseException $e) {
			report($e);
			return false;
		}
	}

	private function isOK(ResponseInterface $response): bool
	{
		if ($response->getStatusCode() !== Response::HTTP_OK) {
			return false;
		}

		$jsonResponse = json_decode($response->getBody()->getContents(), true);
		if ($jsonResponse) {
			return $jsonResponse[ 'ok' ] ?? false;
		}

		return false;
	}
}
