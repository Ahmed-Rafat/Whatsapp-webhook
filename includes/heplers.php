<?php


function createBase($path = BASE_STRORAGE, $createPath = true): void
{
    if (!file_exists(BASE_STRORAGE)) {
        mkdir(BASE_STRORAGE, 0755);
    } 
}

function createJsonFile($content, $fileName = null): void
{
    createBase();
    
    $fileName =  $fileName ?? time();
    
    file_put_contents(BASE_STRORAGE . $fileName . '.json', json_encode($content, JSON_PRETTY_PRINT));
}

function getJsonRequest()
{
    return json_decode(file_get_contents("php://input"), true) ?? [];
}

function getAllRequestData()
{
    return array_merge(getJsonRequest(), $_REQUEST, $_POST, $_GET);
}

function getNestedVar($array, $key)
{
    $keys = explode('.', $key);

    foreach ($keys as $key) {
        if (is_array($array) && array_key_exists($key, $array)) {
            $array = $array[$key];
        } else {
            return null;
        }
    }

    return $array;
}

