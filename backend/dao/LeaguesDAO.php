<?php

namespace backend\dao;

use config\Database;
use dto\leagues\LeaguesResponseDTO;

class LeaguesDAO
{

    public static function create($name, $password, $creatorId): bool
    {
        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO leagues (name, password, creator_id) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("ssi", $name, $hash, $creatorId);
        return $sql->execute();
    }

    public static function findById($id): ?LeaguesResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $res = $sql->get_result();
        if ($res && $row=$res->fetch_assoc()) {
            return new LeaguesResponseDTO($row['name']);
        }
        return null;
    }

    public static function findByCreatorId($creatorId): array {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM leagues WHERE creator_id = ?");
        $sql->bind_param("i", $creatorId);
        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $leagues[] = new LeaguesResponseDTO($row['name']);
            }
        }

        return $leagues;
    }

    public static function findByUserId($userId): array {
        $conn = Database::connect();
        $sql = $conn->prepare("
            SELECT * FROM leagues
            INNER JOIN league_user ON leagues.id = league_user.league_id
            WHERE league_user.user_id = ?
        ");
        $sql->bind_param("i", $userId);
        $sql->execute();
        $res = $sql->get_result();

        $leagues = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $leagues[] = new LeaguesResponseDTO($row['name']);
            }
        }

        return $leagues;
    }

}