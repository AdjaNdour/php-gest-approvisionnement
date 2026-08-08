<?php

function getModelById(array $datas,int $id, string $key = 'id'):array|null{
    foreach ($datas as  $data) {
        if($data[$key]===$id){
            return $data;
        }
    }
    return null;
}