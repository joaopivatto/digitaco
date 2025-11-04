<?php

namespace backend\dao;

use backend\config\Database;
use backend\model\League;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/League.php';

class LeagueDAO
{
    public static function validateExistentLeague($id) :?string
    {
        $conn = Database::connect();

        $validate = $conn->prepare("SELECT name FROM leagues WHERE id = ?");
        $validate->bind_param("i", $id);
        $validate->execute();
        $resultValidation = $validate->get_result();

        if ($resultValidation->num_rows === 0)
        {
            return false;
        }

        $row = $resultValidation->fetch_assoc();
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
                return true;
            }
        }

        return false;
    }

    public static function validateExistentUserLeague($idLeague)
    {
        $conn = Database::connect();

        $validate = $conn->prepare("SELECT id FROM league_user WHERE user_id = ? AND league_id = ?");
        $validate->bind_param("ii", $_SESSION['userId'], $idLeague);
        $validate->execute();
        $resultValidation = $validate->get_result();

        if ($resultValidation->num_rows === 0)
        {
            return false;
        }

        return true;
    }

    public static function insertUserLeague($idLeague, $password)
    {
        $conn = Database::connect();
        $nameLeague = self::validateExistentLeague($idLeague);
        if (!$nameLeague)
        {
            return [
                "success" => false,
                "reason" => "league_not_found"
            ];
        }

        if (!self::validatePasswordLeague($idLeague, $password))
        {
            return [
                "success" => false,
                "reason" => "league_password_incorrect"
            ];
        }

        if (self::validateExistentUserLeague($idLeague))
        {
            return [
                "success" => false,
                "reason" => "user_already_in_league"
            ];
        }

        $sql = $conn->prepare("INSERT INTO league_user (league_id, user_id) VALUES (?, ?)");
        $sql->bind_param("ii", $idLeague, $_SESSION['userId']);
        $sql->execute();

        return [
            "success" => true,
            "leagueName" => $nameLeague
        ];
    }

    public static function deleteUserLeague($idLeague)
    {
        $conn = Database::connect();
        $nameLeague = self::validateExistentLeague($idLeague);
        if (!$nameLeague)
        {
            return [
                "success" => false,
                "reason" => "league_not_found"
            ];
        }

        if (!self::validateExistentUserLeague($idLeague))
        {
            return [
                "success" => false,
                "reason" => "user_not_already_in_league"
            ];
        }

        $sql = $conn->prepare("DELETE FROM league_user WHERE league_id = ? AND user_id = ?");
        $sql->bind_param("ii", $idLeague, $_SESSION['userId']);
        $sql->execute();

        return [
            "success" => true,
            "leagueName" => $nameLeague
        ];
    }
}