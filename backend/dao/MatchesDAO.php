<?php

namespace backend\dao;

require_once "/../../config/Database.php";
require_once "/../../dto/MessageResponseDTO.php";

use backend\config\Database;
use backend\dto\matches\MatchesDTO;
use backend\dto\matches\MatchesHistoryDTO;
use dto\MessageResponseDTO;

class MatchesDAO
{

    public static function create(int $points, int $words, int $leagueId, int $userId): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO matches (points, words, league_id, user_id) VALUES (?,?,?, ?)");
        $sql->bind_param("iiii", $points, $words, $leagueId, $userId);
        $sql->execute();

        return new MessageResponseDTO("Partida finalizada!", 201);
    }

    public static function getHistoric(int $userId): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT 
              COUNT(*) AS totalMatches,
              SUM(words) AS totalWords,
              SUM(points) AS totalPoints,
              MAX(points) AS bestScore
            FROM matches
            WHERE user_id = ?;
        ");
        $sql->bind_param("i", $userId);
        $sql->execute();
        $res = $sql->get_result();
        $historic = new MatchesHistoryDTO();
        if ($res && $row=$res->fetch_assoc()) {
            $historic->setTotalMatches($row['totalMatches']);
            $historic->setTotalWords($row['totalWords']);
            $historic->setTotalPoints($row['totalPoints']);
            $historic->setBestScore($row['bestScore']);
        }

        $sql = $conn->prepare("
            SELECT points, words, played_at
            FROM matches
            WHERE user_id = ?
            ORDER BY played_at DESC;
        ");
        $sql->bind_param("i", $userId);
        $sql->execute();
        $res = $sql->get_result();
        $matches = [];
        if ($res && $row=$res->fetch_assoc()) {
            $match = new MatchesDto($row['points'], $row['words'], $row['played_at']);
            $matches[] = $match->jsonSerialize();
        }
        $historic->setMatches($matches);

        return $historic;
    }

}