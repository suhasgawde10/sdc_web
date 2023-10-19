<?php

$root = "";
if (strpos($_SERVER['DOCUMENT_ROOT'],  '.com') !== false) {
    $root = $_SERVER['DOCUMENT_ROOT'];
} else {
    $root = $_SERVER['DOCUMENT_ROOT'] . '/sdc_aws_improved';
}


require_once   $root . '/vendor/autoload.php';


//require "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = JWT_SECRET_KEY;
$jwt = null;
$headers = getallheaders();
$authHeader = "";

if (isset($headers["authorization"])) {
    $authHeader = substr($headers["authorization"], 7, strlen($headers["authorization"]));
} else if (isset($headers["Authorization"])) {
    $authHeader = substr($headers["Authorization"], 7, strlen($headers["Authorization"]));
}
if ($authHeader != "") {
    $arr = explode(" ", $authHeader);
} else {
    http_response_code(401);
    echo json_encode(array(
        "code" => 110,
        "status" => "false",
        "message" => "Authorization header not found."
    ));
    die();
}

$jwt = $arr[0];

if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    } catch (Exception $e) {

        http_response_code(401);

        echo json_encode(array(
            "code" => 100,
            "status" => "false",
            "message" => "Access denied." . $e->getMessage(),
            "error" => $e->getMessage()
        ));
        die();
    }
}