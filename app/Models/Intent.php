<?php
declare(strict_types=1);

namespace App\Models;

class Intent
{
	public function __construct(
		private string $name,
		private float $confidence,
		private array $entities,
		private string $text,
		private string $rawText
	) {
	}

	public function isConfident(): bool
	{
		return $this->confidence >= 0.8;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public static function fromRequest(array $requestData): self
	{
		return new self(
			$requestData['intent']['name'],
			$requestData['intent']['confidence'],
			$requestData['entities'],
			$requestData['text'],
			$requestData['raw_text']
		);
	}
}
