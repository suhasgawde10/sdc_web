<?php

include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "sendMail/sendMail.php";
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";


function generatePIN($digits = 6)
{
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while ($i < $digits) {
        //generate a random number between 0 and 9.
        $pin .= rand(0, 9);
        $i++;
    }
    return $pin;
}


/*$password_contact = 1234;*/
$random = generatePIN();

if (isset($_POST['btn_send_contact'])) {
    if (isset($_POST['email']) && $_POST['email'] != "") {
        $email = $_POST['email'];
        if (!$error) {
            $result = $manage->validateUserEmail($email);
            if ($result) {
                $error = false;
                $message = "Dear Customer,\n" . $random . " is your temporary Password. Please do not share this Password with anyone for security reasons.";
                $send_sms = $manage->sendMail("Dealer", $email, "Forgot Password", $message);
                if ($send_sms) {
                    $updatePassword = $manage->updatePasswordForForget($security->encrypt($random) . "8523", $email);
                    $error = false;
                    $errorMessage .= "Your temporary password send to <b> $email </b> this email id";
                } else {
                    $error = true;
                    $errorMessage .= "Issue while sending email Please try after some time.<br>";
                }
            } else {
                $error = true;
                $errorMessage .= "Invalid email id.<br>";
            }
        }
    } else {
        $error = true;
        $errorMessage .= "Please enter your Email.<br>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Forgot Password</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../assets/img/logo/imageedit_1_6188028366.png" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body{
            background: #1f133e !important
        }
    </style>
</head>
<body class="hold-transition login_background login-page">
<div class="login-box">
    <div class="login-logo">
        <!--<a href="index.php"><img src="../assets/img/logo-1.png"></a>-->

    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

            <form action="" method="post">
                <?php if ($error && $errorMessage != "") {
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
                <div class="input-group mb-3">
                    <input id="email-2b" type="email" name="email" class="form-control"
                           placeholder="Enter Email Id."
                           value="">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                    <!--<div class="form-group form-primary">
                                    <input type="text" name="send_otp" class="form-control"
                                           value="<?php /*if (isset($_POST['send_otp'])) echo $_POST['send_otp']; */ ?>"
                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                           maxlength="4">
                                    <span class="form-bar"></span>
                                    <label class="float-label">Enter Your OTP</label>
                                </div>-->

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" name="btn_send_contact"
                                class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">
                            Request new password
                        </button>
                    </div>
                </div>


            </form>

            <p class="mt-3 mb-1">
                <a href="index.php">Login</a>
            </p>
            <!--<p class="mb-0">
              <a href="pages/examples/register.html" class="text-center">Register a new membership</a>
            </p>-->
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

</body>
</html>
