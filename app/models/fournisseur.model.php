<?php

function getAllFournisseurs(): array
{
    $pdo = connexionDB();
    $sql = "";
    $data = query($pdo, $sql, false);
    return $data;
}
