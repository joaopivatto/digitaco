<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';;
require_once __DIR__ . '/../dto/leagues/LeaguesResponseDTO.php';
require_once __DIR__ . '/../dto/leagues/LeaguesListResponseDTO.php';
require_once __DIR__ . '/../dto/leagues/LeaguesSimpleListResponse.php';
require_once __DIR__ . '/../dto/ArrayResponseDTO.php';
require_once __DIR__ . '/../dto/users/UsersPointsDTO.php';
require_once __DIR__ . '/UsersDAO.php';
require_once __DIR__ . '/MatchesDAO.php';

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

        if(!UsersDAO::validateExistentUser($creatorId)) {
            return new MessageResponseDTO("Usuário não encontrado!", 404);
        }

        if (self::findByNameBoolean($name)) {
            return new MessageResponseDTO("Esta liga já existe!", 409);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO leagues (name, password, creator_id) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("ssi", $name, $hash, $creatorId);
        $sql->execute();

        $leagueId = $conn->insert_id;
        $sql = $conn->prepare("INSERT INTO league_user (league_id, user_id) VALUES (?,?)");
        $sql->bind_param("ii", $leagueId, $creatorId);
        $sql->execute();

        return new MessageResponseDTO("Liga criada com sucesso!", 201);
    }

    public static function findAllByName(int $id, ?string $name = null): MessageResponseDTO
    {
        $conn = Database::connect();

        if ($name) {
            $sql = $conn->prepare("
                SELECT 
                    leagues.*, 
                    COUNT(league_user_members.user_id) AS members,
                    CASE 
                        WHEN league_user_included.user_id IS NOT NULL THEN TRUE 
                        ELSE FALSE 
                    END AS included
                FROM leagues
                LEFT JOIN league_user league_user_members 
                    ON leagues.id = league_user_members.league_id
                LEFT JOIN league_user league_user_included 
                    ON leagues.id = league_user_included.league_id AND league_user_included.user_id = ?
                WHERE leagues.name LIKE ?
                GROUP BY leagues.id
                ORDER BY leagues.id;
            ");
            $param = "%$name%";
            $sql->bind_param("is", $id, $param);
        } else {
            $sql = $conn->prepare("
                SELECT 
                    leagues.*, 
                    COUNT(league_user_members.user_id) AS members,
                    CASE 
                        WHEN league_user_included.user_id IS NOT NULL THEN TRUE 
                        ELSE FALSE 
                    END AS included
                FROM leagues
                LEFT JOIN league_user league_user_members 
                    ON leagues.id = league_user_members.league_id
                LEFT JOIN league_user league_user_included 
                    ON leagues.id = league_user_included.league_id AND league_user_included.user_id = ?
                GROUP BY leagues.id
                ORDER BY leagues.id;
            ");
            $sql->bind_param("i", $id);
        }

        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $league = new LeaguesListResponseDTO($row['id'], $row['name'], $row['members'], $row['included']);
                $leagues[] = $league->jsonSerialize();
            }
        }

        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function findById($id): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT 
                leagues.id, leagues.name, COUNT(league_user.id) AS members 
            FROM leagues 
            INNER JOIN league_user ON leagues.id = league_user.league_id
            WHERE leagues.id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $res = $sql->get_result();
        if ($res && $row=$res->fetch_assoc()) {
            return new LeaguesResponseDTO("Liga encontrada!", 200, $row['id'], $row['name'], $row['members']);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function findAllByCreatorId($creatorId): MessageResponseDTO {

        if(!UsersDAO::validateExistentUser($creatorId)) {
            return new MessageResponseDTO("Usuário não encontrado!", 404);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT 
                leagues.id, leagues.name, COUNT(league_user.id) AS members
            FROM leagues 
            INNER JOIN league_user ON leagues.id = league_user.league_id
            WHERE creator_id = ?
            GROUP BY leagues.id
        ");
        $sql->bind_param("i", $creatorId);
        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $league = new LeaguesSimpleListResponse($row['id'], $row['name'], $row['members']);
                $leagues[] = $league->jsonSerialize();
            }
        }

        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function findAllByIncludedId($includedId): MessageResponseDTO {

        if(!UsersDAO::validateExistentUser($includedId)) {
            return new MessageResponseDTO("Usuário não encontrado!", 404);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT 
                leagues.*, COUNT(league_user.id) AS members  
            FROM leagues
            INNER JOIN league_user ON leagues.id = league_user.league_id
            WHERE league_user.user_id = ?
            GROUP BY leagues.id;
        ");
        $sql->bind_param("i", $includedId);
        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $league = new LeaguesSimpleListResponse($row['id'], $row['name'], $row['members']);
                $leagues[] = $league->jsonSerialize();
            }
        }

        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function delete($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            $conn = Database::connect();
            $sql = $conn->prepare("
                DELETE FROM league_user
                WHERE league_id = ?
            ");
            $sql->bind_param("i", $id);
            $sql->execute();

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

    public static function getRating($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            return MatchesDAO::getLeagueRating($id);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function getWeekRating($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            return MatchesDAO::getLeagueRatingWeekly($id);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function findByIdBoolean($id): bool {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $res = $sql->get_result();

        return $res && $res->num_rows > 0;
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