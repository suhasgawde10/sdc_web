<?php

// db connection
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";


if (isset($_POST['txt_domain'])) {
    $domain = $_POST['txt_domain'];
    if (!isset_Domain($domain)) {
        echo 'true';
    } else {
        echo 'false';
    }
}

function isset_Domain($domain)
{
    global $con;
    $username = trim($domain);
    $query = "SELECT COUNT(*) AS num FROM tb_dealer WHERE domain_name='" . mysqli_real_escape_string($con, $domain) . "'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);
    if ($row['num'] >= 1) {
        return TRUE; // true if user exists
    } else {
        return FALSE;
    }
}
/*

We complete AJAX request

*/


?>
