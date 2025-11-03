<?php

namespace dto\leagues;

use backend\dto\MessageResponseDTO;

class LeaguesResponseDTO extends MessageResponseDTO
{
    private string $name;

    public function __construct(string $message, int $statusCode, string $name)
    {
        parent::__construct($message, $statusCode);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}