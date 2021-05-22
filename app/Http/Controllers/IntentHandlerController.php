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

        // TODO: On a Raspberry Pi this might take a while, so return "Please wait"
        // and then run a background job to fetch the query.
        $speechText = $intentHandlerService->handle($intent);
        return $this->sendSpeech($speechText);
    }

    protected function sendSpeech(string $speechText): JsonResponse {
        return response()->json(
            [
                'speech' => [ 'text' => $speechText ]
            ]
        );
    }
}
