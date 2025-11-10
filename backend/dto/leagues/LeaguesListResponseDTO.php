<?php

namespace dto\leagues;

class LeaguesListResponseDTO
{

    private int $id;

    private string $name;

    private int $members;

    private bool $included;

    private array $idiomes;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name, int $members, bool $included, array $idiomes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
        $this->included = $included;
        $this->idiomes = $idiomes;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "members" => $this->members,
            "included" => $this->included,
            "idiomes" => $this->idiomes,
        ];
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

    public function isIncluded(): bool
    {
        return $this->included;
    }

    public function setIncluded(bool $included): void
    {
        $this->included = $included;
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