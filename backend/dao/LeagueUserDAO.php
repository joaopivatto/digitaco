<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';;
require_once __DIR__ . '/LeaguesDAO.php';

use backend\config\Database;
use dto\MessageResponseDTO;

class LeagueUserDAO
{

    public static function insertCreator(int $leagueId, int $creatorId) {
        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO league_user (league_id, user_id) VALUES (?,?)");
        $sql->bind_param("ii", $leagueId, $creatorId);
        $sql->execute();
        Database::close();
    }

    public static function validateExistentLeagueUser($idLeague)
    {
        $conn = Database::connect();

        $validate = $conn->prepare("SELECT id FROM league_user WHERE user_id = ? AND league_id = ?");
        $validate->bind_param("ii", $_SESSION['userId'], $idLeague);
        $validate->execute();
        $resultValidation = $validate->get_result();

        Database::close();
        return $resultValidation->num_rows !== 0;
    }

    public static function insertLeagueUser($idLeague, $password): MessageResponseDTO
    {
        $leagueName = LeaguesDAO::validateExistentLeague($idLeague);
        if (!$leagueName)
        {
            Database::close();
            return new MessageResponseDTO("Liga não encontrada!", 404);
        }

        if (!LeaguesDAO::validatePasswordLeague($idLeague, $password))
        {
            return new MessageResponseDTO("Senha incorreta!", 401);
        }

        if (self::validateExistentLeagueUser($idLeague))
        {
            return new MessageResponseDTO("Usuário já está na liga!", 409);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO league_user (league_id, user_id) VALUES (?, ?)");
        $sql->bind_param("ii", $idLeague, $_SESSION['userId']);
        $sql->execute();

        Database::close();
        return new MessageResponseDTO("Bem-vindo a liga: " . $leagueName . "!", 200);
    }

    public static function deleteLeagueUser($idLeague)
    {
        $leagueName = LeaguesDAO::validateExistentLeague($idLeague);
        if (!$leagueName)
        {
            return new MessageResponseDTO("Liga não encontrada!", 404);
        }

        if (!self::validateExistentLeagueUser($idLeague))
        {
            return new MessageResponseDTO("Usuário não está na liga!", 409);
        }


        $conn = Database::connect();
        $sql = $conn->prepare("DELETE FROM league_user WHERE league_id = ? AND user_id = ?");
        $sql->bind_param("ii", $idLeague, $_SESSION['userId']);
        $sql->execute();

        Database::close();
        return new MessageResponseDTO("Usuário removido da liga: " .$leagueName . "!" , 200);
    }

    public static function deleteAllLeagueUser($idLeague) {
        $conn = Database::connect();
        $sql = $conn->prepare("DELETE FROM league_user WHERE league_id = ?");
        $sql->bind_param("i", $idLeague);
        $sql->execute();
        Database::close();
    }

}