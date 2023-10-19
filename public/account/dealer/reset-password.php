<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}

$error = false;
$errorMessage = "";
$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];     $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
}



if (isset($_POST['btn_reset'])) {
    if (isset($_POST['txt_old_password']) && $_POST['txt_old_password'] != "" && isset($_POST['txt_new_passwoord']) && $_POST['txt_new_passwoord'] != "") {
        if(!$validate->lengthChecker($_POST['txt_new_passwoord'],5,10)){
            $error = true;
            $errorMessage = "Password should be between 5 to 10 character.<br>";
        }

        if (($_POST['txt_new_passwoord']) != ($_POST['txt_confirm_new_password'])) {
            $error = true;
            $errorMessage = "password is not same as above.<br>";
        }
        if (!$error) {
            $txt_new_passwoord = $_POST['txt_new_passwoord'];
            $status = $manage->resetDealerPassword($_POST["txt_old_password"], $_POST["txt_new_passwoord"]);
            if ($status) {
                $error = false;
                $errorMessage = "password updated successfully.<br>";
            } else {
                $error = true;
                $errorMessage = "Invalid Password.<br>";
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

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Basic Information</title>
    <?php include "assets/common-includes/header_includes.php" ?>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-9">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#change_password_settings" aria-controls="settings" role="tab" data-toggle="tab">Change Password</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="change_password_settings">
                                    <form class="form-horizontal" method="post" action="">
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
                                        <div class="form-group">
                                            <label for="OldPassword" class="col-sm-3 control-label">Old Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="OldPassword" name="txt_old_password" placeholder="Old Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NewPassword" class="col-sm-3 control-label">New Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="NewPassword" name="txt_new_passwoord" placeholder="New Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NewPasswordConfirm" class="col-sm-3 control-label">New Password (Confirm)</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="NewPasswordConfirm" name="txt_confirm_new_password" placeholder="New Password (Confirm)">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger" name="btn_reset">SUBMIT</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("assets/common-includes/footer_includes.php"); ?>
</body>

</html>
