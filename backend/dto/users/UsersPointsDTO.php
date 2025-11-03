<?php

namespace dto\users;

class UsersPointsDTO
{

    private string $name;

    private int $points;


    /**
     * @param string $name
     * @param int $points
     */
    public function __construct(string $name, int $points)
    {
        $this->name = $name;
        $this->points = $points;
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


}