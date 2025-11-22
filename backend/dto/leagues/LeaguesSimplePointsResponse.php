<?php

namespace dto\leagues;

class LeaguesSimplePointsResponse
{
    private int $id;

    private string $name;

    private int $members;

    private int $points;

    private array $languages;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name, int $members, int $points, array $languages)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
        $this->points = $points;
        $this->languages = $languages;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "members" => $this->members,
            "points" => $this->points,
            "languages" => $this->languages,
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

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function setLanguages(array $languages): void
    {
        $this->languages = $languages;
    }


}