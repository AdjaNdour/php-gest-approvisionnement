<?php

function getAllArticlesEnRupture(): array
{
    $pdo = connexionDB();
    $sql="SELECT a.libelle, a.qteStock,f.nomEntreprise ,
        CASE WHEN a.qteStock > 2 THEN 'warning'
        ELSE 'danger' END AS couleur
        FROM articles a INNER JOIN fournisseurs f ON a.fournisseur_id = f.id WHERE a.qteStock <= a.seuil ORDER BY a.qteStock DESC";
            
    $data = query($pdo, $sql, false);
    return $data;
}

function getAllArticlesByFournisseurId(int $four_id): array
{
    $pdo = connexionDB();
    $sql="SELECT a.id, a.prixAchat, a.libelle, a.qteStock
        FROM articles a
        INNER JOIN fournisseurs f ON a.fournisseur_id = f.id
        WHERE a.qteStock > 0 AND a.fournisseur_id = :fournisseur_id";
    $data = executeQuery($pdo, $sql,["fournisseur_id"=>$four_id], false);
    return $data;
}
