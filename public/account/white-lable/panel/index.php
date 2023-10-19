<?php

include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

include "sendMail/sendMail.php";
$error = false;
$errorMessage = "";

//echo $security->decrypt('hKKnlnVmZmZm8523'); // Omra@1111
//echo $security->decrypt('gKqXnpipmpiddWdlZ2g=8523'); // Omra@1111


if (isset($_SESSION['email'])) {
    header('location:dashboard.php');
}

function generatePIN($digits = 6)
{
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while ($i < $digits) {
        $pin .= rand(0, 9);
        $i++;
    }
    return $pin;
}


/*$password_contact = 1234;*/
$random = generatePIN();

if (isset($_POST['btn_sign_in'])) {
    $pass = $_POST['txt_password'];

    if (isset($_POST['txt_emp']) && $_POST['txt_emp'] != "") {
        $email_id = mysqli_real_escape_string($con, $_POST['txt_emp']);
    } else {
        $error = true;
        $errorMessage .= "Please enter email id.<br>";
    }

    if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        $pass = mysqli_real_escape_string($con, $_POST['txt_password']);
    } else {
        $error = true;
        $errorMessage .= "Please enter password.<br>";
    }

    if (!$error) {
/*
echo $email_id;
echo $security->encrypt($pass) . "8523";
die();*/
        $result = $manage->adminLogin($email_id, $security->encrypt($pass) . "8523");
        if ($result != null) {
            $error = false;
            $name = $result["customer_name"];
            $login_type = $result["login_type"];
            $company_name = $result["company_name"];
            $customer_name = $result["customer_name"];
            $password_status = $result["pass_status"];
            if ($login_type == "admin") {
                $_SESSION["email_user"] = $email_id;
                $getUser = $manage->getAdminAuthentication($_SESSION["email_user"]);

                $name = $getUser["customer_name"];
                $login_type = $getUser["login_type"];
                $company_name = $getUser["company_name"];
                $email_id = $getUser["email_id"];
                $_SESSION["email"] = $email_id;
                $_SESSION["id"] = $getUser["id"];
                $_SESSION["name"] = $name;
                $_SESSION["type"] = $login_type;
                $_SESSION["company_name"] = $company_name;

                header('location:dashboard.php');
            } else {
                $_SESSION["email"] = $email_id;
                $_SESSION["id"] = $result["id"];
                $_SESSION["name"] = $name;
                $_SESSION["type"] = $login_type;
                $_SESSION["company_name"] = $company_name;
                header('location:about-us.php?edit_id=' . $security->encrypt($_SESSION["id"]));
            }
        } else {
            $error = true;
            $errorMessage .= "Invalid username and password";
        }
    }
}

/*if (isset($_POST['btn_otp_verify'])) {
    if (isset($_POST['txt_otp']) && $_POST['txt_otp'] != "") {
        $enter_otp = $_POST['txt_otp'];
        if ($enter_otp == $_SESSION["otp_verify"]) {

            $getUser = $manage->getAdminAuthentication($_SESSION["email_user"]);

            $name = $getUser["customer_name"];
            $login_type = $getUser["login_type"];
            $company_name = $getUser["company_name"];
            $email_id = $getUser["email_id"];
            $_SESSION["email"] = $email_id;
            $_SESSION["id"] = $getUser["id"];
            $_SESSION["name"] = $name;
            $_SESSION["type"] = $login_type;
            $_SESSION["company_name"] = $company_name;

            header('location:dashboard.php');


        } else {
            $error = true;
            $errorMessage .= "Please enter valid OTP for login. <br>";
        }
    } else {
        $error = true;
        $errorMessage .= "Please enter OTP <br>";
    }

}*/
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../assets/img/logo/imageedit_1_6188028366.png" type="image/x-icon"/>
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
        body {
            background: #f8f8f8 !important;
        }
    </style>
</head>
<body class="hold-transition login_background login-page">
<?php if (isset($_GET['send_otp']) == true) { ?>
    <div class="login-box">

        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                <p class="login-box-msg">Please verify OTP send to your email id.</p>

                <form action="" method="post">
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
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="txt_otp" placeholder="Enter OTP">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <button name="btn_otp_verify" type="submit"
                                    class="btn btn-primary btn-block">Verify OTP
                            </button>
                        </div>
                        <!-- /.col -->
                        <div class="col-8 text-right">
                            <a href="index.php">Back to login</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
<?php } else { ?>
    <div class="login-box">
        <div class="card">
            <!--<div class="login-logo">
                <a href="index.php">ADMIN</a>
            </div>-->
            <div class="card-body login-card-body">

                <p class="login-box-msg">Sign in to start your session</p>
                <input type="hidden" value="<?php echo $security->decrypt('gKqXnpipmpiddWdlZ2g=8523'); ?>">

                <form action="" method="post">
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
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="txt_emp" placeholder="Username">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="txt_password" placeholder="Password">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <button name="btn_sign_in" type="submit"
                                    class="btn btn-primary btn-block">Sign In
                            </button>
                        </div>
                        <div class="col-8 text-right">
                            <a href="forgot-password.php">I forgot my password</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

</body>
</html>
