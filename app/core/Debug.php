<?php
function dump(mixed $data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}
function dd(mixed $data)
{
    dump($data);
    die;
}
