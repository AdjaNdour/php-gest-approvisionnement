<?php
define('KEY_VEHICULES', 'vehicules');
define('KEY_CLIENTS', 'clients');
define('KEY_LOCATIONS', 'locations');
define('KEY_ROLES', 'roles');
define('KEY_MARQUES', 'marques');
define('KEY_MODELS', 'models');
define('KEY_FACTURES', 'factures');
define('KEY_CATALOGUES', 'catalogues');
define('KEY_USERS', 'utilisateurs');
define('KEY_USERCONNECT', 'userConnect');

function sessionStart(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function getData(string $key): mixed
{
    return $_SESSION[$key] ?? null;
}
function saveData(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function isConnect(): bool
{
    return isset($_SESSION[KEY_USERCONNECT]);
}

function isRole(string $role): bool
{
    return isConnect() && $_SESSION[KEY_USERCONNECT]['role']['nom'] === $role;
}

function removeData(string $key): void
{
    unset($_SESSION[$key]);
}

function destroySession(): void
{
    removeData(KEY_USERCONNECT);
}
