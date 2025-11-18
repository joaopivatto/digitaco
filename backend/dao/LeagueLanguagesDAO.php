<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/enum/LanguageEnum.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';;
require_once __DIR__ . '/LeaguesDAO.php';

use backend\config\Database;
use backend\model\enum\LanguageEnum;
use dto\MessageResponseDTO;

class LeagueLanguagesDAO
{

    public static function insertLanguage(int $leagueId, string $language): MessageResponseDTO {
        $languageId = LanguageEnum::fromCode($language)->value;
        if ($languageId == null) {
            return new MessageResponseDTO("Idioma inválido!", 400);
        }

        if(self::exists($leagueId, $languageId)) {
            return new MessageResponseDTO("Idioma já adicionado!", 409);
        }
        if (LeaguesDAO::findByIdBoolean($leagueId)) {
            $conn = Database::connect();
            $sql = $conn->prepare("INSERT INTO league_languages (league_id, language) VALUES (?,?)");
            $sql->bind_param("ii", $leagueId, $languageId);
            $sql->execute();
            Database::close();
            return new MessageResponseDTO("Idioma adicionado com sucesso!", 201);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }
    public static function insertLanguages(int $leagueId, array $languages) {
        $placeholders = [];
        $params = [];
        $types = "";

        foreach ($languages as $languageId) {
            $placeholders[] = "(?, ?)";
            $types .= "ii";
            $params[] = $leagueId;
            $params[] = $languageId;
        }

        $query = "INSERT INTO league_languages (league_id, language) VALUES " . implode(", ", $placeholders);

        $conn = Database::connect();
        $sql = $conn->prepare($query);
        $sql->bind_param($types, ...$params);
        $sql->execute();
        Database::close();
    }

    public static function getLeagueLanguages(int $leagueId): array {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT language FROM league_languages WHERE league_id = ?");
        $sql->bind_param("i", $leagueId);
        $sql->execute();
        $res = $sql->get_result();
        $languages = [];

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $enum = LanguageEnum::from($row["language"]);
                $languages[] = $enum->code();
            }
        }

        Database::close();
        return $languages;
    }

    public static function getLeaguesLanguages(): array
    {
        $conn = Database::connect();

        $sql = $conn->prepare("SELECT * FROM league_languages");
        $sql->execute();
        $result = $sql->get_result();

        $map = [];
        while ($row = $result->fetch_assoc()) {
            $enum = LanguageEnum::from($row["language"]);

            if (!isset($map[$row["league_id"]])) {
                $map[$row["league_id"]] = [];
            }

            $map[$row["league_id"]][] = $enum->code();
        }

        return $map;
    }

    public static function deleteLanguage(int $leagueId, string $language): MessageResponseDTO {
        $languageId = LanguageEnum::fromCode($language)->value;
        if ($languageId == null) {
            return new MessageResponseDTO("Idioma inválido!", 400);
        }
        if(!self::exists($leagueId, $languageId)) {
            return new MessageResponseDTO("Idioma não adicionado!", 409);
        }
        if (LeaguesDAO::findByIdBoolean($leagueId)) {
            $conn = Database::connect();
            $sql = $conn->prepare("DELETE FROM league_languages WHERE league_id = ? AND language = ?");
            $sql->bind_param("ii", $leagueId, $languageId);
            $sql->execute();
            Database::close();

            return new MessageResponseDTO("Idioma removido da liga!", 200);
        }
        return new MessageResponseDTO("Liga não encontrada!", 404);
    }

    public static function deleteLanguages(int $leagueId) {
        $conn = Database::connect();
        $sql = $conn->prepare("DELETE FROM league_languages WHERE league_id = ?");
        $sql->bind_param("i", $leagueId);
        $sql->execute();
        Database::close();
    }

    private static function exists(int $leagueId, int $languageId): bool {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM league_languages WHERE league_id = ? AND language = ?");
        $sql->bind_param("ii", $leagueId, $languageId);
        $sql->execute();
        $result = $sql->get_result();
        Database::close();
        return $result->num_rows > 0;
    }

}