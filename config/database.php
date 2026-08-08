<?php

function connexionDB(): PDO
{
    $user = "postgres";
    $password = "kiki";
    static $db = null;

    if ($db == null) {

        $db = new PDO("pgsql:host=localhost;dbname=appro", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        if (!$db) {
            throw new Exception("Erreur de connexion à la base de données");
        }
    }

    return $db;
}

function query(PDO $pdo, string $sql, bool $single = true): array
{
    $query = $pdo->query($sql);
    return $single ? $query->fetch() : $query->fetchAll();
}

function prepare(PDO $pdo, string $sql, array $datas)
{
    $prepare = $pdo->prepare($sql);
    $prepare->execute($datas);
    return $prepare;
}

function executeQuery(PDO $pdo, string $sql, array $datas, bool $single = true): array
{
    $statement = prepare($pdo, $sql,  $datas);
    return $single ? $statement->fetch() : $statement->fetchAll();
}

function executeUpdate(PDO $pdo, string $sql, array $datas): int
{
    $statement = prepare($pdo, $sql,  $datas);
    return (str_starts_with(strtoupper($sql), 'INSERT'))  ? $pdo->lastInsertId() : $statement->rowCount();
}
