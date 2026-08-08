<?php

function getAllAppros(): array
{
    $pdo = connexionDB();

    $sql = "SELECT a.id, a.refBL, f.nomentrprise, a.etatAppro_id, ea.nomEtat,
    SUM(la.quantiteRecu *la.prixAppro) as valeurFacture,
    SUM(la.quantiteAppro *la.prixAppro) as valeurReceptionne,
    CASE
        WHEN ea.nomEtat = 'En Cours' THEN ea.nomEtat 
        WHEN SUM(la.quantiteAppro * la.prixAppro) > SUM(la.quantiteRecu * la.prixAppro) THEN 'ECART-' || (SUM(la.quantiteAppro * la.prixAppro) - SUM(la.quantiteRecu * la.prixAppro)) || ' FCFA'
        ELSE 'Concorde'
    END AS typee,

    CASE
        WHEN ea.nomEtat = 'En Cours' THEN 'warning'
        WHEN SUM(la.quantiteAppro * la.prixAppro) > SUM(la.quantiteRecu * la.prixAppro)THEN 'danger'
        ELSE 'success'
    END AS couleur
    FROM approvisionnements a
    INNER JOIN fournisseurs f ON a.fournisseur_id = f.id
    INNER JOIN ligneAppro la ON la.appro_id = a.id
    INNER JOIN etatAppro ea ON a.etatAppro_id = ea.id
    GROUP BY a.id, a.refBL, f.nomentrprise, a.etatAppro_id, ea.nomEtat";

    $appros = query($pdo, $sql, false);

    return $appros;
}

function getApprosValide(): array
{
    $pdo = connexionDB();

    $sql = "SELECT a.id, a.dateAppro, a.refBL, f.nomEntrprise, a.etatAppro_id,
            SUM(la.quantiteRecu *la.prixAppro) as valeurFacture,
            SUM(la.quantiteAppro *la.prixAppro) as prixDemande,
            a.etatAppro_id , ea.nomEtat
            FROM approvisionnements a
            INNER JOIN fournisseurs f ON a.fournisseur_id = f.id
            INNER JOIN ligneAppro la ON la.appro_id = a.id
            INNER JOIN etatAppro ea ON ea.id = a.etatappro_id
            GROUP BY a.id, a.dateappro, a.refBL, f.nomentrprise, a.etatAppro_id, ea.nomEtat;";

    $appros = query($pdo, $sql, false);

    $sql1 = "SELECT ar.libelle, la.article_id, la.quantiteRecu ,la.prixAppro, la.quantiteAppro
            FROM ligneAppro la 
            INNER JOIN articles ar ON la.article_id = ar.id
            INNER JOIN approvisionnements a ON la.appro_id = a.id
            WHERE  a.id = :appro_id";

    foreach ($appros as &$appro) {
        $appro['articles'] = executeQuery($pdo, $sql1, ["appro_id" => $appro['id']], false);
    }

    return $appros;
}
function getApproAValide(): array
{

    $pdo = connexionDB();

    $sql = "SELECT a.id, a.dateAppro, a.refBL, f.nomEntrprise, a.etatAppro_id,
            SUM(la.quantiteRecu *la.prixAppro) as valeurFacture,
            SUM(la.quantiteAppro *la.prixAppro) as prixDemande,
            a.etatAppro_id , ea.nomEtat
            FROM approvisionnements a
            INNER JOIN fournisseurs f ON a.fournisseur_id = f.id
            INNER JOIN ligneAppro la ON la.appro_id = a.id
            INNER JOIN etatAppro ea ON ea.id = a.etatappro_id
            GROUP BY a.id, a.dateappro, a.refBL, f.nomentrprise, a.etatAppro_id, ea.nomEtat;";

    $appros = query($pdo, $sql, false);

    $sql1 = "SELECT ar.libelle, la.article_id, la.quantiteRecu ,la.prixAppro, la.quantiteAppro
            FROM ligneAppro la 
            INNER JOIN articles ar ON la.article_id = ar.id
            INNER JOIN approvisionnements a ON la.appro_id = a.id
            WHERE  a.id = :appro_id";

    foreach ($appros as &$appro) {
        $appro['articles'] = executeQuery($pdo, $sql1, ["appro_id" => $appro['id']], false);
    }

    return $appros;
}

function confirmerReceptionAppro(array $newAppro): int
{
    $pdo = connexionDB();
    $pdo->beginTransaction();
    $sqlAppro = "INSERT INTO approvisionnements (refBL, etatAppro_id, fournisseur_id)
                 VALUES(:refBL, :etatAppro_id, :fournisseur_id)
                 RETURNING id";

    $rows = executeQuery($pdo, $sqlAppro, ["refBL" => $newAppro["refBL"], "etatAppro_id" => $newAppro["etatAppro_id"], "fournisseur_id" => $newAppro["fournisseur_id"]]);

    $approId = $rows["id"];

    foreach ($newAppro["articles"] as $ligne) {

        $sqlLigneAppro = "INSERT INTO ligneAppro( quantiteAppro, quantiteRecu, prixAppro, appro_id, article_id)
                          VALUES( :quantiteAppro, :quantiteRecu, :prixAppro, :appro_id, :article_id)";

        executeQuery($pdo,$sqlLigneAppro,["quantiteAppro" => $ligne["quantiteAppro"], 
                                            "quantiteRecu" => $ligne["quantiteRecu"], 
                                            "prixAppro" => $ligne["prixAppro"], 
                                            "appro_id" => $approId, 
                                            "article_id" => $ligne["article_id"]]
                                            );
        $sqlUpdateArticle = "UPDATE articles SET qteStock = qteStock + :qteStock WHERE id = :article_id";
        executeQuery($pdo,$sqlUpdateArticle,["qteStock" => $ligne["quantiteRecu"],"article_id" => $ligne["article_id"]]);
    }

    $pdo->commit();
    return $approId;
}
