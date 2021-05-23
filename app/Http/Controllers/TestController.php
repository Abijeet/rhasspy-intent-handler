<?php
declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\SpeechToText\AzureSpeechToTextProvider;
use App\SpeechToText\SpeechToTextProvider;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function azureTranscribeAudio(Request $request, AzureSpeechToTextProvider $speechToText) {
        $this->validate($request, [
            'audio' => 'required|file',
        ]);

        $audio = $request->file('audio');
        $audioPath = $audio->getPath() . '/'  . $audio->getFilename();

        return $speechToText->transcribe($audioPath, SpeechToTextProvider::WAV_AUDIO_FORMAT, 'en-IN');
    }
}
