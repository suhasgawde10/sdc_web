<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../controller/ManageApi.php';
$manage = new ManageAPI();
include '../../controller/EncryptDecrypt.php';
$security = new EncryptDecrypt;
date_default_timezone_set('Asia/Calcutta');
require "protected.php";



$newcomersUser = $manage->newcomersUser();
// dd($displayUser);exit;
if ($newcomersUser != null) {
    $count = mysqli_num_rows($newcomersUser);
} else {
    $count = 0;
    $response = [
        'code'  => 404,
        'status'  => false,
        'message'  => 'Record not found.',
    ];
       
}

$data = [];
if($newcomersUser != null){
    while ($get_result = mysqli_fetch_array($newcomersUser)) {
         array_push($data,$get_result);
    }
    $response = [
        'code'  => 200,
        'status'  => true,
        'count' => $count,
        'users' => $data,
        'message'  => 'Record found.',
    ];
}else{
    $response = [
        'code'  => 404,
        'status'  => false,
        'message'  => 'Record not found.',
    ];
}

echo json_encode($response);
exit; 

?>