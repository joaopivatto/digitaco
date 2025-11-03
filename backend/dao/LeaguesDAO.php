<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';;
require_once __DIR__ . '/../dto/leagues/LeaguesResponseDTO.php';
require_once __DIR__ . '/../dto/leagues/LeaguesListResponseDTO.php';
require_once __DIR__ . '/../dto/leagues/LeaguesSimpleListResponse.php';
require_once __DIR__ . '/../dto/ArrayResponseDTO.php';

use backend\config\Database;
use dto\ArrayResponseDTO;
use dto\leagues\LeaguesListResponseDTO;
use dto\leagues\LeaguesSimpleListResponse;
use dto\MessageResponseDTO;
use dto\leagues\LeaguesResponseDTO;
use dto\users\UsersPointsDTO;

class LeaguesDAO
{

    public static function create($name, $password, $creatorId): MessageResponseDTO
    {

        if (self::findByNameBoolean($name)) {
            return new MessageResponseDTO("Esta liga já existe!", 409);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO leagues (name, password, creator_id) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("ssi", $name, $hash, $creatorId);
        $sql->execute();

        return new MessageResponseDTO("Liga criada com sucesso!", 200);
    }

    public static function findAllByName(int $id, ?string $name = null): MessageResponseDTO
    {
        $conn = Database::connect();

        if ($name) {
            $sql = $conn->prepare("
                SELECT 
                    leagues.*, 
                    CASE 
                        WHEN league_user.user_id IS NOT NULL THEN TRUE 
                        ELSE FALSE 
                    END AS included
                FROM leagues
                LEFT JOIN league_user 
                    ON leagues.id = league_user.league_id 
                    AND league_user.user_id = ?
                WHERE leagues.name LIKE ?
            ");
            $param = "%$name%";
            $sql->bind_param("is", $id, $param);
        } else {
            $sql = $conn->prepare("
                SELECT 
                    leagues.*, 
                    CASE 
                        WHEN league_user.user_id IS NOT NULL THEN TRUE 
                        ELSE FALSE 
                    END AS included
                FROM leagues
                LEFT JOIN league_user 
                    ON leagues.id = league_user.league_id 
                    AND league_user.user_id = ?");
            $sql->bind_param("i", $id);
        }

        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $league = new LeaguesListResponseDTO($row['id'], $row['name'], $row['included']);
                $leagues[] = $league->jsonSerialize();
            }
        }

        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function findById($id): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $res = $sql->get_result();
        if ($res && $row=$res->fetch_assoc()) {
            return new LeaguesResponseDTO("Liga encontrada!", 200, $row['id'], $row['name']);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function findAllByCreatorId($creatorId): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE creator_id = ?");
        $sql->bind_param("i", $creatorId);
        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $league = new LeaguesSimpleListResponse($row['id'], $row['name']);
                $leagues[] = $league->jsonSerialize();
            }
        }

        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function findAllByIncludedId($includedId): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT * FROM leagues
            INNER JOIN league_user ON leagues.id = league_user.league_id
            WHERE league_user.user_id = ?
        ");
        $sql->bind_param("i", $includedId);
        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $league = new LeaguesSimpleListResponse($row['id'], $row['name']);
                $leagues[] = $league->jsonSerialize();
            }
        }

        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function delete($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            $conn = Database::connect();
            $sql = $conn->prepare("
                DELETE FROM leagues
                WHERE id = ?
            ");
            $sql->bind_param("i", $id);
            $sql->execute();

            return new MessageResponseDTO("Liga excluída com sucesso!", 200);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function getTablePoints($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            $conn = Database::connect();
            $sql = $conn->prepare("
                SELECT 
                    users.name AS user,
                    SUM(matches.points) AS points
                FROM matches
                INNER JOIN users ON users.id = matches.user_id
                WHERE matches.league_id = ?
                GROUP BY users.id, users.name
                ORDER BY points DESC;
            ");
            $sql->bind_param("i", $id);
            $sql->execute();
            $res = $sql->get_result();

            $points = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $points[] = new UsersPointsDTO($row['name'], $row['points']);
                }
            }

            return new ArrayResponseDTO("Pontuação Geral da Liga!", 200, $points);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function getWeekTablePoints($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            $conn = Database::connect();
            $sql = $conn->prepare("
                SELECT 
                    users.name AS user,
                    SUM(matches.points) AS points
                FROM matches
                INNER JOIN users ON users.id = matches.user_id
                WHERE 
                    matches.league_id = ?
                    AND matches.played_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY users.id, users.name
                ORDER BY points DESC;
            ");
            $sql->bind_param("i", $id);
            $sql->execute();
            $res = $sql->get_result();

            $points = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $points[] = new UsersPointsDTO($row['name'], $row['points']);
                }
            }

            return new ArrayResponseDTO("Pontuação Geral da Liga!", 200, $points);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function findByIdBoolean($id): bool {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $res = $sql->get_result();
        if ($res) {
            return true;
        }
        return false;
    }
    public static function findByNameBoolean($name): bool {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE name = ?");
        $sql->bind_param("s", $name);
        $sql->execute();
        $res = $sql->get_result();

        return $res && $res->num_rows > 0;
    }


}