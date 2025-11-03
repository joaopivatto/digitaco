<?php

namespace dto\users;

class UsersPointsDTO
{

    private string $name;

    private int $points;

    private int $matches;

    private float $average;

    /**
     * @param string $name
     * @param int $points
     */
    public function __construct(string $name, int $points, int $matches)
    {
        $this->name = $name;
        $this->points = $points;
        $this->matches = $matches;
        $this->average = $points / $matches;
    }

    public function jsonSerialize(): array
    {
        return [
            "name" => $this->name,
            "points" => $this->points,
            "matches" => $this->matches,
            "average" => $this->average,
        ];
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getMatches(): int
    {
        return $this->matches;
    }

    public function setMatches(int $matches): void
    {
        $this->matches = $matches;
    }

    public function getAverage(): float
    {
        return $this->average;
    }

    public function setAverage(float $average): void
    {
        $this->average = $average;
    }

}