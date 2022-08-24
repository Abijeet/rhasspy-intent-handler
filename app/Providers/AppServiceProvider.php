<?php
declare(strict_types=1);

namespace App\Providers;

use App\IntentActionReceivers\AudioIntentActionReceiver;
use App\IntentActionReceivers\IntentActionReceiver;
use App\IntentHandlers\IntentHandlerFactory;
use App\IntentHandlers\IntentHandlerService;
use App\IntentHandlers\WikipediaIntentHandler;
use App\NaturalLanguageProcessors\NaturalLanguageProcessor;
use App\NaturalLanguageProcessors\OpenNLP;
use App\ResponseReporters\ResponseReporterFactory;
use App\ResponseReporters\TelegramResponseReporter;
use App\SearchQuery\Handlers\WikipediaQueryHandler;
use App\SpeechToText\AzureSpeechToTextProvider;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->singleton(
			IntentHandlerService::class,
			function (): IntentHandlerService {
				return new IntentHandlerService(
					new IntentHandlerFactory(),
					$this->app->make(ResponseReporterFactory::class)
				);
			}
		);

		$this->app->singleton(
			IntentActionReceiver::class,
			function (): IntentActionReceiver {
				return new AudioIntentActionReceiver(
					config('audio.recorder'),
					config('audio.args'),
					config('audio.fileFormat'),
					config('audio.timeoutSecs'),
					$this->app->make(AzureSpeechToTextProvider::class)
				);
			}
		);

		$this->app->singleton(
			WikipediaIntentHandler::class,
			function ($app): WikipediaIntentHandler {
				return new WikipediaIntentHandler(
					$app->make(IntentActionReceiver::class),
					$app->make(WikipediaQueryHandler::class)
				);
			}
		);

		$this->app->singleton(
			AzureSpeechToTextProvider::class,
			function (): AzureSpeechToTextProvider {
				return new AzureSpeechToTextProvider(
					env('AZURE_SUBSCRIPTION_KEY'),
					env('AZURE_REGION')
				);
			}
		);

		$this->app->singleton(
			ResponseReporterFactory::class,
			function (): ResponseReporterFactory {
				return new ResponseReporterFactory(
					$this->app,
					config('response-reporters.available')
				);
			}
		);

		$this->app->singleton(
			TelegramResponseReporter::class,
			function (): TelegramResponseReporter {
				return new TelegramResponseReporter(
					new Client(),
					env('TELEGRAM_BOT_TOKEN'),
					env('TELEGRAM_CHANNEL_ID')
				);
			}
		);

		$this->app->singleton(
			NaturalLanguageProcessor::class,
			function (): OpenNLP {
				return new OpenNLP(
					new Client(),
					env('OPEN_NLP_URL'),
					env('APP_LOCALE'),
					env('NLP_TIMEZONE')
				);
			}
		);
	}
}
