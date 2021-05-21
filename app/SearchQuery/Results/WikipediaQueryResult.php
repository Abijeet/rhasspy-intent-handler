<?php
declare( strict_types = 1 );

namespace App\SearchQuery\Results;

class WikipediaQueryResult implements QueryResult {
    private function __construct(
        private ?string $title,
        private ?string $description,
        private ?string $originalImage,
        private ?string $desktopURL,
        private ?string $mobileURL,
        private ?string $extract
    ) {}

    public function getResult(): ?string {
        return $this->extract;
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
