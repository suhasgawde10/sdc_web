<?php

if (isset($_GET['android_user_id'])  && isset($_GET['api_key']) && isset($_GET['type']) && $_GET['type'] == "android") {

    $android_url = "android_user_id=" . $_GET['android_user_id'] . "&type=" . $_GET['type'] . "&api_key=" . $_GET['api_key'];
    $user_id1 = $_GET['android_user_id'];
    // dd($user_id1);exit;
    $api_key = $_GET['api_key'];
    $validateUserId = $manage->validAPIKEYId($user_id1,$api_key);
    if ($validateUserId) {
        if(!isset($_SESSION['id']) && !isset($_SESSION['email'])) {
            $userSpecificResult = $manage->getUserProfile($user_id1);
            if ($userSpecificResult != null) {
                $android_name = $userSpecificResult["name"];
                $android_email = $userSpecificResult["email"];
                $android_custom_url = $userSpecificResult["custom_url"];
                $android_contact = $userSpecificResult['contact_no'];
                $android_type = $userSpecificResult['type'];
            }
            $_SESSION['type'] = $android_type;
            $_SESSION['email'] = $android_email;
            $_SESSION['name'] = $android_name;
            $_SESSION['contact'] = $android_contact;
            $_SESSION['custom_url'] = $android_custom_url;
            $_SESSION['id'] = $security->encrypt($user_id1);
        }
    } else {
        header('location:404-not-found.php?'.$android_url);
    }
} elseif (!isset($_SESSION['email'])) {
     header('location:../login.php');
} else {
    $android_url = "";
}

?>