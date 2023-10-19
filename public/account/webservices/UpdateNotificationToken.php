<?php

header('Content-Type: application/json');
include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();
$response["success"] = false;



if (isset($_POST['user_id']) && $_POST['user_id'] != ''
&& isset($_POST['api_key']) && $_POST['api_key'] != ''
    && isset($_POST['notification_token']) && $_POST['notification_token'] != '') {
    $user_id = $security->decryptWebservice($_POST['user_id']);
    $api_key = $security->decryptWebservice($_POST['api_key']);
    $notification_token = $security->decryptWebservice($_POST['notification_token']);
    $user = $manage->validateUserIdAndAPIKey($user_id, $api_key);
    if ($user != null) {
        $response["success"] = true;
        $response["message"] = "Valid User";
        $update = $manage->updateUserNotification($notification_token,$user_id);
    } else {
        $response["message"] = "Invalid User";
    }
} else {
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
