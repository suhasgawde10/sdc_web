<?php
ob_start();
error_reporting(0);
ini_set('memory_limit', '-1');
date_default_timezone_set("Asia/Kolkata");
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$controller = new Controller();
$con = $controller->connect();
header('Content-Type: text/html; charset=utf-8');

$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include("android-login.php");
$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');
$error = false;
$errorMessage = "";
$errorFile = false;
$errorMessageFile = "";

$sql = "SELECT cover_pic,id FROM tb_user_profile WHERE cover_pic !=''";

$result = $con->query($sql);

while ($row = mysqli_fetch_array($result)){
    $user_id = $row['id'];
    $cover_name = explode(',',$row['cover_pic']);
    foreach ($cover_name as $key){
        $status = " INSERT INTO tb_cover_profile(user_id,position_order,cover_pic,created_by,created_date) VALUES('$user_id',1,'$key','$user_id','" . date('Y-m-d') . "')";
        $con->query($status);
    }
}