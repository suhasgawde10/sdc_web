<?php

ob_start();
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate  = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (!isset($_SESSION['email'])) {
    header('location:index.php');
}


$error = false;
$errorMessage = "";

$id = 0;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $fetchId = $security->decrypt($id);
}


if (isset($_POST['btn_reset'])) {
    if (isset($_POST['txt_old_password']) && $_POST['txt_old_password'] != "" && isset($_POST['txt_new_passwoord']) && $_POST['txt_new_passwoord'] != "") {

        if (($_POST['txt_new_passwoord']) != ($_POST['txt_confirm_new_password'])) {
            $error = true;
            $errorMessage = "New password and confirm password doesn't match.<br>";
        }
        if (!$error) {
            $txt_new_passwoord = $_POST['txt_new_passwoord'];

            $status = $manage->resetUserPassword($security->encrypt($_POST["txt_old_password"])."8523", $security->encrypt($_POST["txt_new_passwoord"])."8523");
            if ($status) {
                $error = false;
                $errorMessage = "password updated successfully.<br>";
                if($_SESSION["type"] === "admin"){
                    header("location:manage-dealer.php");
                }else{
                    header("location:about-us.php?edit_id=".$security->encrypt($_SESSION['id']));
                }
            } else {
                $error = true;
                $errorMessage = "password mismatch.<br>";
            }
        }
    } else {
        $error = true;
        $errorMessage = "Please enter your password.<br>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password</title>
    <?php include 'assets/common-includes/header_includes.php' ?>
    <style>
        .error {
            color: red;
        }

        .valid {
            border: 2px solid #0ff;
        }
        #pswd_info {
            position: absolute;
            bottom: 0;
            left: 40px;
            padding: 10px;
            background: #fefefe;
            font-size: .875em;
            border-radius: 5px;
            box-shadow: 0 1px 3px #ccc;
            border: 1px solid #ddd;
            z-index: 9999;
        }

        #pswd_info h4 {
            font-size: 18px;
            margin: 0 0 10px 0;
            padding: 0;
            font-weight: normal;

        }

        #pswd_info::before {
            content: "\25B2";
            position: absolute;
            top: -12px;
            left: 45%;
            font-size: 14px;
            line-height: 14px;
            color: #ddd;
            text-shadow: none;
            display: block;

        }

        .invalid {
            background: url(../assets/img/close.png) no-repeat 0 50%;
            background-size: 12px;
            padding-left: 22px;
            line-height: 24px;
            color: #ec3f41;
        }

        .validp {
            background: url(../assets/img/right.png) no-repeat 0 50%;
            background-size: 20px;
            padding-left: 22px;
            line-height: 24px;
            color: #3a7d34;
        }

        #pswd_info {
            display: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php include 'assets/common-includes/header.php' ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include 'assets/common-includes/left_menu.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Password</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Reset Password</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-8">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Reset Password</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" action="">
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
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Enter Your Old Password</label>
                                        <input type="text" class="form-control" name="txt_old_password" id="txt_old" placeholder="Enter Your Old Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail2">Enter New Password</label>
                                        <input type="text" class="form-control" name="txt_new_passwoord" id="txt_new" placeholder="Enter New Password">
                                        <div id="pswd_info">
                                            <h4>Password must meet the following requirements:</h4>
                                            <ul style="list-style: none">
                                                <li id="letter" class="invalid">At least <strong>one letter</strong>
                                                </li>
                                                <li id="capital" class="invalid">At least <strong>one capital
                                                        letter</strong></li>
                                                <li id="number" class="invalid">At least <strong>one number</strong>
                                                </li>
                                                <li id="splcha" class="invalid">At least <strong>one special character</strong>
                                                </li>
                                                <li id="length" class="invalid">Be at least <strong>8 characters</strong></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail2">Repeat Password</label>
                                        <input type="text" class="form-control" name="txt_confirm_new_password" id="exampleInputEmail2" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" id="btn_submit" class="btn btn-primary" name="btn_reset">Submit</button>
                                    &nbsp;&nbsp;<a type="button" href="reset-password.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>


                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <?php include 'assets/common-includes/footer.php' ?>
</div>
<!-- ./wrapper -->
<?php include 'assets/common-includes/footer_includes.php' ?>
<script>
    $(document).ready(function () {
        $('#password_verify').hide();
        $('#txt_new').keyup(function () {
            var pswd = $(this).val();
            var strength = 1;

            if (pswd.length < 8) {
                $('#length').removeClass('validp').addClass('invalid');
                strength++;
            } else {
                $('#length').removeClass('invalid').addClass('validp');
            }
            if (pswd.match(/[A-z]/)) {
                $('#letter').removeClass('invalid').addClass('validp');
                strength++;
            } else {
                $('#letter').removeClass('validp').addClass('invalid');
            }

            //validate capital letter
            if (pswd.match(/[A-Z]/)) {
                $('#capital').removeClass('invalid').addClass('validp');
                strength++;
            } else {
                $('#capital').removeClass('validp').addClass('invalid');
            }

            //Special Character
            if (pswd.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                $('#splcha').removeClass('invalid').addClass('validp');
                strength++;
            } else {
                $('#splcha').removeClass('validp').addClass('invalid');
            }

            //validate number
            if (pswd.match(/\d/)) {
                $('#number').removeClass('invalid').addClass('validp');
                strength++;

            } else {
                $('#number').removeClass('validp').addClass('invalid');
                $('#btn-submit').attr('disabled', true);
            }
//            console.log(strength);
            if(strength != 5 && pswd  !=""){
//                    $('#btn-submit').removeAttr('disabled');
                $('#btn_submit').attr('disabled','disabled');
            }else{
                $('#btn_submit').removeAttr('disabled');
            }

        }).focus(function () {
            /*$('#btn-submit').attr('disabled','disabled');*/
            $('#pswd_info').show();
        }).blur(function () {
            /*$('#btn-submit').removeAttr('disabled');*/
            $('#pswd_info').hide();

        });
    });
</script>
</body>
</html>
