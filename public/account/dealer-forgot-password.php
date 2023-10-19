<?php
include 'whitelist.php';
error_reporting(1);
include "controller/ManageDealer.php";
$manage = new ManageDealer();
include "controller/validator.php";
$validate = new Validator();

require 'sendMail/sendMail.php';

$error = false;
$errorMessage = "";


$failed_status = false;


if (isset($_POST['btn_Send_contact'])) {
    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
        $email = $_POST['txt_contact'];
        if (!$error) {
            $result = $manage->validateRegisterEmail($email);
            if ($result) {
                $error = false;
                $password_contact = rand(100, 10000);
                $message = $password_contact . ' use this password for sign in your account';
                $send_mail = $manage->sendMail('Share digital Card',$email,'Forgot Password', $message);
//                $send_mail= true;
                if ($send_mail) {
                    $updatePassword = $manage->resetPasswordEmail($password_contact, $email);
                    if ($updatePassword) {
                        $errorMessage = "Temporary password has been send to your Email.<br>";
                    } else {

                    }
                } else {
                    $failed_status = true;
                }
            } else {
                $error = true;
                $errorMessage = "Invalid Email Id.<br>";
            }
        }
    } else {
        $error = true;
        $errorMessage = "Please enter your Email Id.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Forgot password | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Please enter your resgister email to get forgot pasword OTP for share digital card.">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, fogot password, password">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <!-- Animation Css -->
    <link href="user/assets/plugins/animate-css/animate.css" rel="stylesheet"/>
    <!-- Custom Css -->
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        [type="radio"]:not(:checked), [type="radio"]:checked {
            position: unset;
            left: 0;
            opacity: 1;
        }
    </style>
</head>

<body>
<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<!-- header area end -->
<section class="feature-area bg-gray padding_section margin_top_div" id="feature"
         style="background-image: url('assets/img/about/asdfaas.jpg');background-size: cover; background-position: bottom; height: 525px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">


            </div>
            <div class="col-md-3 hidden-sm margin_top_div col-xs-12">
                <div class="card_width card">
                    <div class="body">
                        <form id="forgot_password" method="POST" action="">
                            <?php if ($error) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                </div>
                            <?php
                            } else if (!$error && $errorMessage != "") {
                                ?>
                                <div class="alert alert-success">
                                    <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="msg">
                                <h5>Forgot password</h5>
                            </div>
                            <div id="contact_no" class="desc hide_div">
                                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                                    <div class="form-line">
                                        <input type="email" class="form-control" name="txt_contact"
                                               placeholder="Enter email">
                                    </div>
                                </div>

                                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit"
                                        name="btn_Send_contact">RESET PASSWORD
                                </button>

                                <div class="row m-t-20 m-b--5 ptb--10 text-center">
                                    <a href="dealer-register.php">Sign In!</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>

</html>