<?php

header('Content-Type: application/json');
include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();
$response["success"] = false;



if (isset($_POST['token']) && $_POST['token'] != ''
&& isset($_POST['old_password']) && $_POST['old_password'] != ''
&& isset($_POST['new_password']) && $_POST['new_password'] != '') {
    $token = $security->decryptWebservice($_POST['token']);
    $seperate_token = explode('+',$token);
   $user_id = $seperate_token[0];
   $api_key = $seperate_token[1];

    $old_password = $security->decryptWebservice($_POST['old_password']);
    $new_password = $security->decryptWebservice($_POST['new_password']);
    $user = $manage->validateUserIdAndAPIKey($user_id, $api_key);
    if ($user != null) {
        $status = $manage->resetUserPassword($user_id,$security->encrypt($old_password)."8523", $security->encrypt($new_password)."8523");
        if ($status) {
            $response["success"] = true;
            $response["message"] = "Password Reset Successfully";
        }else{
            $response["success"] = false;
            $response["message"] = "Please Enter Valid Old Password";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "User Authentication Failed.";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
