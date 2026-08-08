<?php

function getAllFournisseurs(): array
{
    $pdo = connexionDB();
    $sql = "SELECT nomEntreprise FROM fournisseurs;";
    $data = query($pdo, $sql, false);
    return $data;
}
