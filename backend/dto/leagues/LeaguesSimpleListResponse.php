<?php

namespace dto\leagues;

class LeaguesSimpleListResponse
{

    private int $id;

    private string $name;

    private int $members;

    private array $idiomes;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name, int $members, array $idiomes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
        $this->idiomes = $idiomes;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "members" => $this->members,
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

    public function getIdiomes(): array
    {
        return $this->idiomes;
    }

    public function setIdiomes(array $idiomes): void
    {
        $this->idiomes = $idiomes;
    }



}