<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/enums/LanguageEnum.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';;
require_once __DIR__ . '/../dto/leagues/LeaguesResponseDTO.php';
require_once __DIR__ . '/../dto/leagues/LeaguesListResponseDTO.php';
require_once __DIR__ . '/../dto/leagues/LeaguesSimpleListResponse.php';
require_once __DIR__ . '/../dto/ArrayResponseDTO.php';
require_once __DIR__ . '/../dto/users/UsersPointsDTO.php';
require_once __DIR__ . '/UsersDAO.php';
require_once __DIR__ . '/MatchesDAO.php';
require_once __DIR__ . '/LeagueUserDAO.php';
require_once __DIR__ . '/LeagueLanguagesDAO.php';

use backend\config\Database;
use backend\model\enums\LanguageEnum;
use dto\ArrayResponseDTO;
use dto\leagues\LeaguesListResponseDTO;
use dto\leagues\LeaguesSimpleListResponse;
use dto\MessageResponseDTO;
use dto\leagues\LeaguesResponseDTO;

class LeaguesDAO
{

    public static function create($name, $password, $creatorId, $languages): MessageResponseDTO
    {
        if(!UsersDAO::validateExistentUser($creatorId)) {
            return new MessageResponseDTO("Usuário não encontrado!", 404);
        }

        if (self::findByNameBoolean($name)) {
            return new MessageResponseDTO("Esta liga já existe!", 409);
        }

        // validate and create enum array
        $languageEnums = [];
        foreach ($languages as $language) {
            $enum = LanguageEnum::fromCode($language);
            if ($enum === null) {
                return new MessageResponseDTO("Idioma inválido: $language", 400);
            }
            $languageEnums[] = $enum->value;
        }


        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO leagues (name, password, creator_id) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("ssi", $name, $hash, $creatorId);
        $sql->execute();

        $leagueId = $conn->insert_id;
        Database::close();

        LeagueUserDAO::insertCreator($leagueId, $creatorId);;

        LeagueLanguagesDAO::insertLanguages($leagueId, $languageEnums);

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
        $languages = LeagueLanguagesDAO::getLeaguesLanguages();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $leagueLanguages = $languages[$row['id']] ?? [];
                $league = new LeaguesListResponseDTO($row['id'], $row['name'], $row['members'], $row['included'], $leagueLanguages);
                $leagues[] = $league->jsonSerialize();
            }
        }

        Database::close();
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
        Database::close();
        if ($res && $row=$res->fetch_assoc()) {
            return new LeaguesResponseDTO("Liga encontrada!", 200,
                $row['id'], $row['name'], $row['members'], LeagueLanguagesDAO::getLeagueLanguages($id));
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
        $languages = LeagueLanguagesDAO::getLeaguesLanguages();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $leagueLanguages = $languages[$row['id']] ?? [];
                $league = new LeaguesSimpleListResponse($row['id'], $row['name'], $row['members'], $leagueLanguages);
                $leagues[] = $league->jsonSerialize();
            }
        }

        Database::close();
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

        $languages = LeagueLanguagesDAO::getLeaguesLanguages();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $leagueLanguages = $languages[$row['id']] ?? [];
                $league = new LeaguesSimpleListResponse($row['id'], $row['name'], $row['members'], $leagueLanguages);
                $leagues[] = $league->jsonSerialize();
            }
        }

        Database::close();
        return new ArrayResponseDTO("Ligas encontradas!", 200, $leagues);
    }

    public static function delete($id): MessageResponseDTO {
        if (self::findByIdBoolean($id)) {
            LeagueUserDAO::deleteAllLeagueUser($id);
            LeagueLanguagesDAO::deleteLanguages($id);

            $conn = Database::connect();
            $sql = $conn->prepare("
                DELETE FROM leagues
                WHERE id = ?
            ");
            $sql->bind_param("i", $id);
            $sql->execute();

            Database::close();
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
        $result = $res && $res->num_rows > 0;
        Database::close();

        return $result;
    }

    public static function findByNameBoolean($name): bool {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE name = ?");
        $sql->bind_param("s", $name);
        $sql->execute();
        $res = $sql->get_result();

        $result = $res && $res->num_rows > 0;
        Database::close();

        return $result;
    }

    public static function validateExistentLeague($id) :?string
    {
        $conn = Database::connect();

        $validate = $conn->prepare("SELECT name FROM leagues WHERE id = ?");
        $validate->bind_param("i", $id);
        $validate->execute();
        $resultValidation = $validate->get_result();

        if ($resultValidation->num_rows === 0)
        {
            Database::close();
            return false;
        }

        $row = $resultValidation->fetch_assoc();
        Database::close();
        return $row['name'];
    }

    public static function validatePasswordLeague($id, $password) :?string
    {
        $conn = Database::connect();

        $validate = $conn->prepare("SELECT password FROM leagues WHERE id = ?");
        $validate->bind_param("i", $id);
        $validate->execute();
        $resultPassword = $validate->get_result();

        if ($resultPassword && $row = $resultPassword->fetch_assoc()) {
            if (password_verify($password,$row['password'])) {
                Database::close();
                return true;
            }
        }

        Database::close();
        return false;
    }
}