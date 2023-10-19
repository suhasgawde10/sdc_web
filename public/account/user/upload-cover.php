<?php

include "../controller/ManageUser.php";
$manage = new ManageUser();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (isset($_SESSION['id'])) {
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        $session_email = $_SESSION['create_user_email'];
    } else {
        $session_email = $_SESSION['email'];
    }
}

$form_data = $manage->getSpecificUserProfile();


if($_FILES["file"]["name"] != '')
{
    $test = explode('.', $_FILES["file"]["name"]);
    $ext = end($test);
    $name = rand(10000, 99999) . '.' . $ext;
    $coverImagePath = "uploads/" . $session_email . "/profile/" . $form_data['cover_pic'];
    $location = "uploads/" . $session_email . "/profile/" . $name;
    if (file_exists($coverImagePath) && $form_data['cover_pic'] !="") {
        unlink('uploads/' . $session_email . '/profile/' . $form_data['cover_pic'] . '');
        move_uploaded_file($_FILES["file"]["tmp_name"], $location);
    }else{
        move_uploaded_file($_FILES["file"]["tmp_name"], $location);
    }
    $update_cover_image = $manage->updateCoverPhoto($name);
    /*$location = './upload/' . $name;*/
    echo '<img src="'.$location.'"  style="width: 100%;
    height: 100px;
    position: absolute;"  />';
}




?>