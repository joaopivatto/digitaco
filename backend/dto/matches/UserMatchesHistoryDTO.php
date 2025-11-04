<?php

namespace dto\matches;

require_once __DIR__ . "/../MessageResponseDTO.php";

use dto\MessageResponseDTO;

class UserMatchesHistoryDTO extends MessageResponseDTO
{

    private int $totalMatches;

    private int $totalWords;

    private int $totalPoints;

    private int $bestScore;

    private array $matches;

    public function __construct(){
        parent::__construct("Histórico de Partidas!", 200);
    }

    public function jsonSerialize(): array
    {
        $parentData = parent::jsonSerialize();
        $childData = [
            "userPerformance" => [
                "totalMatches" => $this->totalMatches,
                "totalWords" => $this->totalWords,
                "totalPoints" => $this->totalPoints,
                "bestScore" => $this->bestScore,
                "matches" => $this->matches
            ]
        ];

        return array_merge($parentData, $childData);
    }

    public function getTotalMatches(): int
    {
        return $this->totalMatches;
    }

    public function setTotalMatches(int $totalMatches): void
    {
        $this->totalMatches = $totalMatches;
    }

    public function getTotalWords(): int
    {
        return $this->totalWords;
    }

    public function setTotalWords(int $totalWords): void
    {
        $this->totalWords = $totalWords;
    }

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function setTotalPoints(int $totalPoints): void
    {
        $this->totalPoints = $totalPoints;
    }

    public function getBestScore(): int
    {
        return $this->bestScore;
    }

    public function setBestScore(int $bestScore): void
    {
        $this->bestScore = $bestScore;
    }

    public function getMatches(): array
    {
        return $this->matches;
    }

    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

}