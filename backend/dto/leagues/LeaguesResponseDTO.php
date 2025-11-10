<?php

namespace dto\leagues;

require_once __DIR__ . "/../MessageResponseDTO.php";

use dto\MessageResponseDTO;

class LeaguesResponseDTO extends MessageResponseDTO
{

    private int $id;

    private string $name;

    private int $members;

    private array $idiomes;

    public function __construct(string $message, int $statusCode, int $id, string $name, int $members, array $idiomes)
    {
        parent::__construct($message, $statusCode);
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
        $this->idiomes = $idiomes;
    }

    public function jsonSerialize(): array
    {
        $parentData = parent::jsonSerialize();
        $childData = [
            'league' => [
                'id' => $this->id,
                'name' => $this->name,
                'members' => $this->members,
                'idiomes' => $this->idiomes
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

    public function getMembers(): int
    {
        return $this->members;
    }

    public function setMembers(int $members): void
    {
        $this->members = $members;
    }

    public function getIdiomes(): array
    {
        return $this->idiomes;
    }

    public function setIdiomes(array $idiomes): void
    {
        $this->idiomes = $idiomes;
    }

}