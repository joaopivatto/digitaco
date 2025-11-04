<?php

namespace backend\dto\matches;

class MatchesRating
{

    private string $user;

    private int $totalMatches;

    private int $totalPoints;

    private float $average;

    public function __construct(string $user, int $totalMatches, int $totalPoints)
    {
        $this->user = $user;
        $this->totalMatches = $totalMatches;
        $this->totalPoints = $totalPoints;
        $this->average = $totalPoints / $totalMatches;
    }

    public function jsonSerialize(): array
    {
        return [
            "user" => $this->user,
            "totalMatches" => $this->totalMatches,
            "totalPoints" => $this->totalPoints,
            "average" => $this->average
        ];
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function getTotalMatches(): int
    {
        return $this->totalMatches;
    }

    public function setTotalMatches(int $totalMatches): void
    {
        $this->totalMatches = $totalMatches;
    }

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function setTotalPoints(int $totalPoints): void
    {
        $this->totalPoints = $totalPoints;
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