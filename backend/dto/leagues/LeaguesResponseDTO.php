<?php

namespace dto\leagues;

use backend\dto\MessageResponse;

class LeaguesResponseDTO extends MessageResponse
{
    private string $name;

    public function __construct($message, $name)
    {
        parent::__construct($message);
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