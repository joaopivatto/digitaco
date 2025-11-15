<?php

use backend\config\Database;

require_once __DIR__ . '/../config/Database.php';

const NAME_DATABASE = 'digitacosssss';

// Create connection
$conn = mysqli_connect(Database::HOST, Database::USERNAME, Database::PASSWORD);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS ".NAME_DATABASE;
if (mysqli_query($conn, $sql)) {
    echo "<br>Banco de dados criado com sucesso ou já existente!<br>";
} else {
    echo "<br>Erro ao criar banco de dados: " . mysqli_error($conn);
}

$conn->close();

try {
    $conn = Database::connect(NAME_DATABASE);
    $conn->query("use ".NAME_DATABASE);
    echo "<br>Banco ". NAME_DATABASE . " sendo utilizado!<br>";
    Database::close();
} catch (\Throwable $erro)
{
    Database::close();
    echo "<br>".$erro->getMessage();
}

try {
    $conn = Database::connect(NAME_DATABASE);
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
    echo "<br>Tabela users criada!<br>";
    Database::close();
} catch (\Throwable $erro)
{
    Database::close();
    echo "<br>".$erro->getMessage();
}

try {
    $conn = Database::connect(NAME_DATABASE);
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
    echo "<br>Tabela leagues criada com sucesso!<br>";
    Database::close();
} catch (\Throwable $erro)
{
    Database::close();
    echo "<br>".$erro->getMessage();
}

try {
    $conn = Database::connect(NAME_DATABASE);
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
    echo "<br>Tabela matches criada com sucesso!<br>";
    Database::close();
} catch (\Throwable $erro)
{
    Database::close();
    echo "<br>".$erro->getMessage();
}

try {
    $conn = Database::connect(NAME_DATABASE);
    $conn->query("
                CREATE TABLE `league_languages` (
                    `league_id` int(11) NOT NULL,
                    `language` int(11) NOT NULL,
                    KEY `fk_idioms_league_id` (`league_id`),
                    CONSTRAINT `fk_idioms_league_id` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "<br>Tabela league_languages criada com sucesso!<br>";
    Database::close();
} catch (\Throwable $erro)
{
    Database::close();
    echo "<br>".$erro->getMessage();
}

try {
    $conn = Database::connect(NAME_DATABASE);
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
    echo "<br>Tabela league_user criada com sucesso!<br>";
    Database::close();
} catch (\Throwable $erro)
{
    Database::close();
    echo "<br>".$erro->getMessage();
}