<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/enums/IdiomEnum.php';

use backend\config\Database;
use backend\model\enums\IdiomEnum;

class LeagueIdiomesDAO
{

    public static function insertIdiome(int $leagueId, int $idiomId) {
        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO league_idiomes (league_id, idiom) VALUES (?,?)");
        $sql->bind_param("ii", $leagueId, $idiomId);
        $sql->execute();
        Database::close();
    }
    public static function insertIdiomes(int $leagueId, array $idioms) {
        $placeholders = [];
        $params = [];
        $types = "";

        foreach ($idioms as $idiomId) {
            $placeholders[] = "(?, ?)";
            $types .= "ii";
            $params[] = $leagueId;
            $params[] = $idiomId;
        }

        $query = "INSERT INTO league_idiomes (league_id, idiom) VALUES " . implode(", ", $placeholders);

        $conn = Database::connect();
        $sql = $conn->prepare($query);
        $sql->bind_param($types, ...$params);
        $sql->execute();
        Database::close();
    }

    public static function getLeagueIdiomes(int $leagueId): array {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT idiom FROM league_idiomes WHERE league_id = ?");
        $sql->bind_param("i", $leagueId);
        $sql->execute();
        $res = $sql->get_result();
        $idiomes = [];

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $enum = IdiomEnum::from($row["idiom"]);
                $idiomes[] = $enum->code();
            }
        }

        Database::close();
        return $idiomes;
    }

    public static function getLeaguesIdiomes(): array
    {
        $conn = Database::connect();

        $sql = $conn->prepare("SELECT * FROM league_idiomes");
        $sql->execute();
        $result = $sql->get_result();

        $map = [];
        while ($row = $result->fetch_assoc()) {
            $enum = IdiomEnum::from($row["idiom"]);

            if (!isset($map[$row["league_id"]])) {
                $map[$row["league_id"]] = [];
            }

            $map[$row["league_id"]][] = $enum->code();
        }

        return $map;
    }

    public static function deleteIdiome(int $leagueId, int $idiomId) {
        $conn = Database::connect();
        $sql = $conn->prepare("DELETE FROM league_idiomes WHERE league_id = ? AND idiom = ?");
        $sql->bind_param("ii", $leagueId, $idiomId);
        $sql->execute();
        Database::close();
    }

    public static function deleteIdiomes(int $leagueId) {
        $conn = Database::connect();
        $sql = $conn->prepare("DELETE FROM league_idiomes WHERE league_id = ?");
        $sql->bind_param("i", $leagueId);
        $sql->execute();
        Database::close();
    }

}