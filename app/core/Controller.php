<?php

function renderView(string $file, array $data = [])
{
    $viewData = $data;
    // extract($data);
    require_once(PATHBASE . "/app/views/$file.html.php");
}

function redirectToRoute(string $uri): void
{
    header("Location:" . WEB_ROUTE . "$uri");
}

function renderViewLayout(string $file, string $layout, array $data = []): void
{
    $viewData = $data;
    ob_start();
    require_once(PATHBASE . "/app/views/$file.html.php");

    $contentView = ob_get_clean();

    require_once(PATHBASE . "/app/views/layout/$layout.layout.html.php");
}
