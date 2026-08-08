<?php

function getAllArticlesEnRupture(): array
{
    $pdo = connexionDB();
    $sql="SELECT a.libelle, a.qteStock,f.nomEntrprise ,
        CASE WHEN a.qteStock > 2 THEN 'warning'
        ELSE 'danger' END AS couleur
        FROM articles a INNER JOIN fournisseurs f ON a.fournisseur_id = f.id WHERE a.qteStock <= a.seuil ORDER BY a.qteStock DESC";
            
    $data = query($pdo, $sql, false);
    return $data;
}
