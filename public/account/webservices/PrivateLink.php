<?php

header('Content-Type: application/json');
include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();
$response["success"] = false;

if(isset($_POST['custom_url']) && isset($_POST['user_id']) && isset($_POST['api_key'])){
    $custom_url = $security->decryptWebservice($_POST['custom_url']);
    $user_id = $security->decryptWebservice($_POST['user_id']);
    $token = $security->encryptWebservice($random_sms);
    $api_key = $security->decryptWebservice($_POST['api_key']);
    $user = $manage->validateUserIdAndAPIKey($user_id, $api_key);
    if ($user != null) {
        $date = date_create(date("Y-m-d"));
        date_add($date, date_interval_create_from_date_string("7 days"));
        $update = $manage->mu_insertPrivateLinkToken($user_id, $token, date_format($date, "Y-m-d"));
        $url =  'https://sharedigitalcard.com/payment/'.$custom_url.'&token='.$token;;
        $response["success"] = true;
        $response["message"] = "Private Link generated successfully";
        $response["private_url"] = $url;
    }else{
        $response["success"] = false;
        $response["message"] = "Invalid User";
    }
}else{
    $response["success"] = false;
    $response["message"] = "Parameter are missing.";
}


echo json_encode($response);
?>