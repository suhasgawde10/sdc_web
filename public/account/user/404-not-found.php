<?php


include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

$error = false;
$errorMessage = "";
include("session_includes.php");

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>404 Not FOUND</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body bgcolor="white">
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>
    <div class="col-md-12 text-center">
        <br>
        <img class="not-found-image" src="assets/images/404.png">
    </div>

    <?php
    if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
        ?>
        <div class="col-md-12 text-center">
            <br>
            <button class="btn btn-primary" onclick="window.location.href='dashboard.php'">Go to dashboard</button>
        </div>
    <?php
    }
    ?>

</section>
<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>