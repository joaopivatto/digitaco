<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../dto/matches/MatchesDTO.php';
require_once __DIR__ . '/../dto/users/UsersPointsDTO.php';
require_once __DIR__ . '/../dto/matches/UserMatchesHistoryDTO.php';
require_once __DIR__ . '/../dto/ArrayResponseDTO.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';;
require_once __DIR__ . '/UsersDAO.php';
require_once __DIR__ . '/LeaguesDAO.php';

use backend\config\Database;
use dto\matches\MatchesDTO;
use dto\matches\UserMatchesHistoryDTO;
use dto\ArrayResponseDTO;
use dto\MessageResponseDTO;
use dto\users\UsersPointsDTO;

class MatchesDAO
{

    public static function create(int $points, int $words, $leagueId, int $userId): MessageResponseDTO {

        if ($leagueId != null && !LeaguesDAO::findByIdBoolean($leagueId)) {
            return new MessageResponseDTO("Liga não encontrada!", 404);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO matches (points, words, league_id, user_id) VALUES (?,?,?, ?)");
        $sql->bind_param("iiii", $points, $words, $leagueId, $userId);
        $sql->execute();
        Database::close();

        return new MessageResponseDTO("Partida finalizada!", 201);
    }

    public static function getUserHistory(int $userId): MessageResponseDTO {

        if(!UsersDAO::validateExistentUser($userId)) {
            return new MessageResponseDTO("Usuário não encontrado!", 404);
        }

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
        $historic = new UserMatchesHistoryDTO();
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
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $match = new MatchesDto($row['points'], $row['words'], $row['played_at']);
                $matches[] = $match->jsonSerialize();
            }
        }
        $historic->setMatches($matches);

        Database::close();
        return $historic;
    }

    public static function getGlobalRating(): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT 
                users.name AS user,
                COUNT(matches.id) AS totalMatches,
                SUM(matches.points) AS totalPoints
            FROM matches
            INNER JOIN users ON users.id = matches.user_id
            GROUP BY users.id, users.name
            ORDER BY totalPoints DESC;
        ");
        $sql->execute();
        $res = $sql->get_result();
        $rating = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $placement = new UsersPointsDTO($row['user'], $row['totalPoints'], $row['totalMatches']);
                $rating[] = $placement->jsonSerialize();
            }
        }

        Database::close();
        return new ArrayResponseDTO("Ranking Geral!", 200, $rating);
    }

    public static function getGlobalRatingWeekly() : MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT 
                users.name AS user,
                COUNT(matches.id) AS totalMatches,
                SUM(matches.points) AS totalPoints
            FROM matches
            INNER JOIN users ON users.id = matches.user_id
            WHERE matches.played_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY users.id, users.name
            ORDER BY totalPoints DESC;
        ");
        $sql->execute();
        $res = $sql->get_result();
        $rating = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $placement = new UsersPointsDTO($row['user'], $row['totalPoints'], $row['totalMatches']);
                $rating[] = $placement->jsonSerialize();
            }
        }

        Database::close();
        return new ArrayResponseDTO("Ranking Geral Semanal!", 200, $rating);
    }

    public static function getLeagueRating(int $leagueId) {
        $conn = Database::connect();
        $sql = $conn->prepare("
                SELECT 
                    users.name AS user,
                    SUM(matches.points) AS points,
                    COUNT(matches.id) AS matches
                FROM matches
                INNER JOIN users ON users.id = matches.user_id
                WHERE matches.league_id = ?
                GROUP BY users.id, users.name
                ORDER BY points DESC;
            ");
        $sql->bind_param("i", $leagueId);
        $sql->execute();
        $res = $sql->get_result();

        $points = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $point = new UsersPointsDTO($row['user'], $row['points'], $row['matches']);
                $points[] = $point->jsonSerialize();
            }
        }

        Database::close();
        return new ArrayResponseDTO("Pontuação Geral da Liga!", 200, $points);
    }

    public static function getLeagueRatingWeekly(int $leagueId) {
        $conn = Database::connect();
        $sql = $conn->prepare("
                SELECT 
                    users.name AS user,
                    SUM(matches.points) AS points,
                    COUNT(matches.id) AS matches
                FROM matches
                INNER JOIN users ON users.id = matches.user_id
                WHERE 
                    matches.league_id = ?
                    AND matches.played_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY users.id, users.name
                ORDER BY points DESC;
            ");
        $sql->bind_param("i", $leagueId);
        $sql->execute();
        $res = $sql->get_result();

        $points = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $point = new UsersPointsDTO($row['user'], $row['points'], $row['matches']);
                $points[] = $point->jsonSerialize();
            }
        }

        Database::close();
        return new ArrayResponseDTO("Pontuação Semanal da Liga!", 200, $points);
    }

}