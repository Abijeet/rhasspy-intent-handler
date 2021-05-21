<?php
declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\IntentHandlers\IntentHandlerService;
use App\Models\Intent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class IntentHandlerController extends Controller
{
    public function handle(Request $request, IntentHandlerService $intentHandlerService): JsonResponse {
        $this->validate($request, [
            'intent.name' => 'required',
            'intent.confidence' => 'required',
            'text' => 'required'
        ]);

        $requestData = $request->all();
        try {
            $intent = Intent::fromRequest( $requestData );
        } catch (Throwable $t) {
            return $this->error(500, 1001, $requestData, 'There was an error while parsing the Intent', $t);
        }

        if (!$intentHandlerService->isIntentValid($intent)) {
            return $this->sendSpeech(__('rhasspy_error_unknown'));
        }

        if (!$intent->isConfident()) {
            return $this->sendSpeech(__('rhasspy_error_confused'));
        }

        $intentHandlerService->handle($intent);
        return $this->sendSpeech(__('rhasspy_success_please_wait'));
    }

    protected function sendSpeech(string $speechText): JsonResponse {
        return response()->json(
            [
                'speech' => [ 'text' => $speechText ]
            ]
        );
    }
}
