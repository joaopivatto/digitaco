<?php

namespace dto\matches;


class MatchesDTO
{
    private int $points;

    private int $words;
    private string $date;

    public function __construct(int $points, int $words, string $date) {
        $this->points = $points;
        $this->words = $words;
        $this->date = (new \DateTime($date))->format('d/m/Y H:i');
    }

    public function jsonSerialize(): array {
        return [
            "points"=>$this->points,
            "words"=>$this->words,
            "date"=>$this->date
        ];
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getWords(): int
    {
        return $this->words;
    }

    public function setWords(int $words): void
    {
        $this->words = $words;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }


}