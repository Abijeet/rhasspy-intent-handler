<?php
declare(strict_types=1);

namespace App\IntentHandlers;

use App\Exceptions\IntentActionReceiverError;
use App\IntentActionReceivers\IntentActionReceiver;
use App\Models\Intent;
use App\NaturalLanguageProcessors\NaturalLanguageProcessor;

class ReminderIntentHandler implements IntentHandler
{
	private const NAME = 'Reminder';

	public function __construct(
		private IntentActionReceiver $intentActionReceiver,
		private NaturalLanguageProcessor $nlpProcessor
	) {
	}

	public function is(Intent $intent): bool
	{
		return $intent->getName() === self::NAME;
	}

	public function getName(): string
	{
		return self::NAME;
	}

	public function handle(Intent $intent): string
	{
		try {
			$reminderText = $this->intentActionReceiver->get();
			if (!$reminderText) {
				return __('rhasspy_empty_reminder_action');
			}

			// 1. Query OpenNLP to get the time
            // $eventDateTimes = $this->nlpProcessor->getDateTime($reminderText);

			// TODO: 2. Create the event on a calendar via Caldav

			// 3. Respond to Rhasspy.
            return __('rhasspy_reminder_set', [ 'reminder' => $reminderText ]);
		} catch (IntentActionReceiverError $e) {
			report($e);
			return __('rhasspy_audio_query_builder_error');
		}
	}
}
