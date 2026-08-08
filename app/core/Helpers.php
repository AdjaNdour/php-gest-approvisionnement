<?php

function asset(string $path): void{
     echo WEB_ROUTE."/assets/$path";
}

function showProfil(): void{
    $userConnect = getData(KEY_USERCONNECT);
    echo $userConnect["prenom"]." ".$userConnect["nom"];
}

function showUrlProfilPhoto(): void{
    $userConnect = getData(KEY_USERCONNECT);
    echo $userConnect["photo"];
}

function pathUrl(string $uri = ""){
    echo WEB_ROUTE."/$uri";
}