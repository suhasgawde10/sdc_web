<?php

include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (isset($_GET['user_id'])) {
    $user_id = $security->decryptWebservice($_GET['user_id']);
    $validateId = $manage->displayAllUserByID($user_id);
    if ($validateId != null) {
        $referral_code = $validateId['user_referer_code'];
    }
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>REFER AND EARN</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body style="background-color: #eee">

<div class="container invite text-center">

    <div class="col-xs-12 invite_img">
        <img src="assets/img/teammate.png">
    </div>
    <div class="col-xs-12">
        <h4>REFER AND EARN</h4>

        <P class="referral-text">For every successful referral, users and referral can get +2 month extra benefits on
            your digital card.</P>
        <h4 class="referral-text">Your Referral Code</h4>

        <div class="col-xs-8 col-xs-offset-2">
            <div class="referral_code">
                <span><?php
                    echo strtoupper($referral_code);
                    ?></span>
            </div>
        </div>
        <br>
    </div>
</div>
<div class="invite_btn">
    <a class="btn btn-primary" href="invite-now-popup.php">INVITE YOUR FRIENDS</a>
</div>


</body>
</html>