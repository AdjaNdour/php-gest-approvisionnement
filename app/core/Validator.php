<?php
function required(string $value, string $key, array &$errors, string $smsError = "Ce champ est obligatoir"): bool
{

    if (empty($value)) {
        $errors[$key] = $smsError;
        return false;
    }
    return true;
}


function unique(string $value, string $key, $datas, array &$errors, bool $required = true, string $smsError = "Ce champ est obligatoire")
{
    if (!$required) {
        foreach ($datas as $data) {
            if ($data[$key] == $value) {
                $errors[$key] = $smsError;
                break;
            }
        }
    }
}

function isPositive(string $value, string $key, array &$errors, string $smsError = "ce champs doit etre un entier positif")
{
    if (!is_numeric($value) || $value < 0) {
        $errors[$key] = $smsError;

        return false;
    }
    return true;
}
