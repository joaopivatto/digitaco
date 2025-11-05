<?php

namespace dto\leagues;

require_once __DIR__ . "/../MessageResponseDTO.php";

use dto\MessageResponseDTO;

class LeaguesResponseDTO extends MessageResponseDTO
{

    private int $id;

    private string $name;

    private int $members;

    public function __construct(string $message, int $statusCode, int $id, string $name, int $members)
    {
        parent::__construct($message, $statusCode);
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    public function jsonSerialize(): array
    {
        $parentData = parent::jsonSerialize();
        $childData = [
            'league' => [
                'id' => $this->id,
                'name' => $this->name,
                'members' => $this->members,
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