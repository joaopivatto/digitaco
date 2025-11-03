<?php

namespace dto\leagues;

use dto\MessageResponseDTO;

class LeaguesResponseDTO extends MessageResponseDTO
{

    private int $id;

    private string $name;

    public function __construct(string $message, int $statusCode, int $id, string $name)
    {
        parent::__construct($message, $statusCode);
        $this->id = $id;
        $this->name = $name;
    }

    public function jsonSerialize(): array
    {
        $parentData = parent::jsonSerialize();
        $childData = [
            'league' => [
                'id' => $this->id,
                'name' => $this->name
            ]
        ];
        return array_merge($parentData, $childData);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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