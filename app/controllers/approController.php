<?php

require_once PATHBASE . "/app/models/article.model.php";
require_once PATHBASE . "/app/models/fournisseur.model.php";
require_once PATHBASE . "/app/models/appro.model.php";

function dashboard()
{
    $appros = getAllAppros();
    $articlesEnRupture = getAllArticlesEnRupture();

    $approsValide = getApprosValide();

    $fournisseurs = getAllFournisseurs();

    $fournisseur_id = (int)$_GET["fournisseur_id"];
    $articlesParFournisseur= getAllArticlesByFournisseurId($fournisseur_id);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $approId = $_POST["appro_id"] ?? -1;
        $newAppros = getData("newAppro");

        foreach ($newAppros as $indexAppro => &$appro) {
            if ($appro["id"] == $approId) {

                $articlesRecus = $_POST["article"] ?? [];
                foreach ($appro["articles"] as $index => &$article) {
                    $article["quantiteRecu"] = $articlesRecus[$index]["quantiteRecu"];
                    $article["prixAppro"] = $articlesRecus[$index]["prixAppro"];
                }
                unset($article);
                $appro["etatAppro_id"] = 2;
                confirmerReceptionAppro($appro);
                unset($newAppros[$indexAppro]);
                $newAppros = array_values($newAppros);
                saveData("newAppro", $newAppros);
                break;
            }
        }
        unset($appro);
        redirectToRoute("appro.html.php");
        exit;
    }
    // $approsAValide = getData("newAppro") ?? [];

    renderView('appro', ['appros' => $appros,
    'articlesEnRupture' => $articlesEnRupture,
    'approsValide' => $approsValide,
    // 'approsAValide' => $approsAValide,
    'fournisseurs'=> $fournisseurs,
    'articlesParFournisseur'=>$articlesParFournisseur
    ]);
}
