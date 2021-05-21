<?php
declare( strict_types = 1 );

namespace App\SearchQuery\Results;

class WikipediaQueryResult implements QueryResult {
    private const MAX_CHARS = 150;

    private const MAX_SENTENCES = 2;

    private ?string $parsedText;

    public function __construct(
        private ?string $title,
        private ?string $description,
        private ?string $originalImage,
        private ?string $desktopURL,
        private ?string $mobileURL,
        private ?string $extract
    ) {
        $this->parsedText = '';
    }

    public function getResult(): ?string {
        if ($this->parsedText) {
            return $this->parsedText;
        }

        if ($this->extract) {
            $sentences = preg_split('/(?<=[.?!;:])\s+/', $this->extract, -1, PREG_SPLIT_NO_EMPTY);
            $sentencesToDisplay = array_slice($sentences, 0, self::MAX_SENTENCES);
            $this->parsedText = implode('', $sentencesToDisplay);

            // since MAX_SENTENCES are greater than MAX_CHARS, try using 1 less sentence.
            if (strlen($this->parsedText) > self::MAX_CHARS) {
                $sentences = array_slice( $sentences, 0, self::MAX_SENTENCES - 1);
                $this->parsedText = implode( '', $sentences);

                // still greater than MAX_CHARS, just use the words.
                if (strlen($this->parsedText) > self::MAX_CHARS) {
                    $this->parsedText = substr($this->parsedText, 0, self::MAX_CHARS);
                    $words = explode(' ', $this->parsedText);
                    array_pop($words);
                    $this->parsedText = implode(' ', $words);
                }
            }
        }

        return $this->parsedText;
    }

    public static function fromJson(array $jsonData): self {
        $title = $jsonData['title'] ?? null;
        $img = $jsonData['originalimage']['source'] ?? '';
        $description = $jsonData['description'] ?? null;
        $desktopURL = $jsonData['content_urls']['desktop']['page'] ?? null;
        $mobileURL = $jsonData['content_urls']['mobile']['page'] ?? null;
        $extract = $jsonData['extract'] ?? null;

        return new WikipediaQueryResult($title, $description, $img, $desktopURL, $mobileURL, $extract);
    }
}
