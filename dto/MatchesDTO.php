<?php

namespace dto;

class MatchesDTO
{
    public int $id;
    public int $userId;
    public int $leagueId;
    public int $points;
    public string $date;

    public function __construct($match) {
        $this->id = $match->id;
        $this->userId = $match->userId;
        $this->leagueId = $match->leagueId;
        $this->points = $match->points;
        $this->date = $match->date;
    }

    public function toArray(): array {
        return [
            "id"=>$this->id,
            "userId"=>$this->userId,
            "leagueId"=>$this->leagueId,
            "points"=>$this->points,
            "date"=>$this->date
        ];
    }
}