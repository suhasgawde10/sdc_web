<?php

header('Content-Type: application/json');
include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();
$response["success"] = false;

$date = date("Y-m-d");
function like_match($pattern, $subject)
{
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool)preg_match("/^{$pattern}$/i", $subject);
}

if (isset($_POST['user_id']) && $_POST['user_id'] != ''
&& isset($_POST['api_key']) && $_POST['api_key'] != '') {
    $user_id = $security->decryptWebservice($_POST['user_id']);
    $api_key = $security->decryptWebservice($_POST['api_key']);
    $user = $manage->validateUserIdAndAPIKey($user_id, $api_key);
    if ($user != null) {

        $response["success"] = true;
        $response["message"] = "Valid User";
        $response["email"] = $security->encryptWebservice($user["email"]);
        $response["name"] = $security->encryptWebservice($user["name"]);
        $response["img_name"] = $security->encryptWebservice($user["img_name"]);
        $response["expiry_date"] = $security->encryptWebservice($user["expiry_date"]);
        $response["user_id"] = $security->encryptWebservice($user["user_id"]);
        $response["custom_url"] = $security->encryptWebservice($user["custom_url"]);
        $response["contact_no"] = $security->encryptWebservice($user["contact_no"]);
        $response["user_referer_code"] = $security->encryptWebservice($user["user_referer_code"]);
        $response["designation"] = $security->encryptWebservice($user["designation"]);
        $response["api_key"] = $security->encryptWebservice($user["api_key"]);


        $userSpecificResult = $manage->displayUserSubscriptionDetails($user_id);

            if($userSpecificResult!=null){
             //   $expiry_date = $userSpecificResult['expiry_date'];
                $plan_name = $userSpecificResult['year'];
                $response['plan'] = $plan_name;
                $referral_by = $userSpecificResult['referer_code'];
                $response['referral_by'] = $referral_by;
                if (like_match('%dealer%', $referral_by) == 1) {
                    $response['user_type'] = "Dealer";
                }  else {
                    $response['user_type'] = "Self";
                }
            }

    } else {
        $response["message"] = "Invalid User";
    }
} else {
    $response["message"] = "Parameter are missing.";
}
echo json_encode($response);
