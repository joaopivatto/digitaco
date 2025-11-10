<?php

namespace backend\config;

trait TraitQueryExecution
{
    public static function createDatabase()
    {
        try {
            $conn = Database::connect();
            $conn->query("CREATE DATABASE IF NOT EXISTS digitaco;");
            echo "Banco digitaco criado (ou já existente)!.<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }

    public static function useDatabase()
    {
        try {
            $conn = Database::connect();
            $conn->query("use digitaco");
            echo "Banco digitaco sendo utilizado!.<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }

    public static function createTableLeagueLanguages()
    {
        try {
            $conn = Database::connect();
            $conn->query("
                CREATE TABLE `league_languages` (
                    `league_id` int(11) NOT NULL,
                    `language` int(11) NOT NULL,
                    KEY `fk_idioms_league_id` (`league_id`),
                    CONSTRAINT `fk_idioms_league_id` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            echo "Tabela league_languages criada com sucesso!.<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }

    public static function createTableLeagueUser()
    {
        try {
            $conn = Database::connect();
            $conn->query("
                CREATE TABLE `league_user` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `league_id` int(11) NOT NULL,
                    `user_id` int(11) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `league_id` (`league_id`),
                    KEY `user_id` (`user_id`),
                    CONSTRAINT `league_user_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`),
                    CONSTRAINT `league_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            echo "Tabela league_user criada com sucesso!.<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }

    public static function createTableLeagues()
    {
        try {
            $conn = Database::connect();
            $conn->query("
                CREATE TABLE `leagues` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(150) NOT NULL,
                    `password` varchar(255) NOT NULL,
                    `creator_id` int(11) NOT NULL,
                    `created_at` timestamp NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `unique_league_name` (`name`),
                    KEY `creator_id` (`creator_id`),
                    CONSTRAINT `leagues_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            echo "Tabela leagues criada com sucesso!<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }

    public static function createTableMatches()
    {
        try {
            $conn = Database::connect();
            $conn->query("
                CREATE TABLE `matches` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `points` int(11) NOT NULL,
                    `league_id` int(11) DEFAULT NULL,
                    `user_id` int(11) NOT NULL,
                    `played_at` timestamp NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (`id`),
                    KEY `user_id` (`user_id`),
                    KEY `fk_league_id` (`league_id`),
                    CONSTRAINT `fk_league_id` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`),
                    CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            echo "Tabela matches criada com sucesso!.<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }

    public static function createTableUsers()
    {
        try {
            $conn = Database::connect();
            $conn->query("
                CREATE TABLE `users` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(150) NOT NULL,
                    `email` varchar(150) NOT NULL,
                    `password` varchar(255) NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `unique_email` (`email`)
)                ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            echo "Tabela users criada!<br>";
            Database::close();
        } catch (\Throwable $erro)
        {
            Database::close();
            echo $erro->getMessage();
        }
    }
}