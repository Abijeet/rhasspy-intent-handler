<?php
declare( strict_types = 1 );

namespace App\SpeechToText;

use JsonSerializable;

class SpeechToTextResponse implements JsonSerializable {
    public function __construct(
        private string $transcript,
        private ?float $confidence = null,
        private ?array $words = null
    ) {}

    public function getTranscript(): string {
        return $this->transcript;
    }

    public function getConfidence(): ?float {
        return $this->confidence;
    }

    public function getWords(): ?array {
        return $this->words;
    }

    public function jsonSerialize(): array {
        return [
            'transcript' => $this->transcript,
            'confidence' => $this->confidence,
            'words' => $this->words
        ];
    }
}
