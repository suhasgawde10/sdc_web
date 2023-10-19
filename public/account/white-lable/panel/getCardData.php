<?php

ini_set('memory_limit', '-1');
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";

$id = $_POST['id'];
$deleteData = $manage->deleteData($manage->demoCardTable, $id);
if ($deleteData) {
    echo "Data deleted Successfully";
} else {
    echo "Data not deleted! try after some time";
}
?>