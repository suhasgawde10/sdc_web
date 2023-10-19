<?php
error_reporting(1);
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate  = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}


$error = false;
$errorMessage = "";
include("session_includes.php");
/*include "validate-page.php";*/

/*echo $session_email;
exit;*/

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
            $status = $manage->resetUserPassword($security->encrypt($_POST["txt_old_password"])."8523", $security->encrypt($_POST["txt_new_passwoord"])."8523");
            if ($status) {
                $action = "Changed";
                if(isset($_SESSION['dealer_login_type'])){
                    $by = "by dealer.";
                }else{
                    $by = "by user.";
                }
                $remark = "You have changed your password to ".$txt_new_passwoord ." ". $by;
                $insertLog = $manage->insertUserLogData($page_name,$action,$remark);

                $checkDealerCode = $manage->getUserData();
                if($checkDealerCode != ""){
                    $DealerReferCode = $checkDealerCode['referer_code'];
                }else{
                    $DealerReferCode = "";
                }

                if($DealerReferCode != ""){
                    $message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' .$back_image. '>
<div style="' . $overlay. '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                    <p>Dear <span style="color:blue;">' . ucwords($session_name) . '</span>,</p>
                    <p>You have successfully reset your password, Please keep your New password safe and secure. </p>
                    <p>To do any changes in your "Share Digital Card " click on to below button to login to our web portal or you can change your details from mobile application.</p>
                </div>
</td>
</tr>
</table>';
                }else{
                    $message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' .$back_image. '>
<div style="' . $overlay. '">
<div style=" margin: 0 auto">
 <div class="user-name-logo" style="padding-top: 10px;">
                        <div style="text-align: center;color: #c4a758;width: 80px;margin: 1px auto;background: white;border-radius: 50%;height: 80px;text-align: center;padding: 5px;">
                            <img src="https://sharedigitalcard.com/assets/img/logo/logo.png" style="padding-top: 15px;width:100%">
                        </div>
                    </div>
                    <div style="text-align: center;color: white;font-weight: 700;padding-bottom: 10px;">
                        <h1 style="font-size: 24px;margin: 0;">Share Digital Card</h1>
                    </div>
</div>
</div>
</td>
</tr>
<tr>
<td colspan="2">
 <div style=" font-size: 18px;">
                    <p>Dear <span style="color:blue;">' . ucwords($session_name) . '</span>,</p>
                    <p>You have successfully reset your password, Please keep your New password safe and secure. </p>
                 <a href="' . SHARED_URL.$session_custom_url_is . '" style="' . $btn . ';background: #db5ea5 !important;width: 100%;color: #ffffff;border-radius: 4px;font-size: 16px;padding: 10px 0;">Open Your Digital Card</a>
                    <p>To do any changes in your "Share Digital Card " click on to below button to login to our web portal or you can change your details from mobile application.</p>
                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn. ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
                   <a target="_blank" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px;display: inline-block;vertical-align: middle;"><img src="https://sharedigitalcard.com/assets/img/playstore.png"
                                                                                          style="width: 135px" alt="digital card app"></a>
</td></tr>
<tr>
<td colspan="2" style=" font-size: 18px;">
<p> For any query email us on <a href="mailto:support@sharedigitalcard.com" class="payment">support@sharedigitalcard.com</a></p>
</td>
</tr>
<tr><td colspan="2" style="padding: 10px;background: #e6e6e6;height: 115px;">
 <div style="width: 85%;margin: 0 auto;">
                <div style=" width: 100%;margin: 0 auto;">
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
<a href="https://www.instagram.com/sharedigitalcard/"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
<a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                    </div>
                </div>

            </div>
</td></tr>
</table>';
                }
                 $toEmail = "" . $session_email . "";
                $subject = "You have Successfully changed password.";
                $sendMail = $manage->sendMail(MAIL_FROM_NAME, $toEmail, $subject, $message);
//                var_dump($sendMail);
                $error = false;
                $errorMessage = "password updated successfully.<br>";
            } else {
                $error = true;
                $errorMessage = "Invalid password.<br>";
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
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Reset Password</title>
    <?php include "assets/common-includes/header_includes.php" ?>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <?php
    if(isset($_SESSION['create_user_status']) && $_SESSION['create_user_status']==true){
        include "assets/common-includes/session_button_includes.php" ;
    }
    ?>
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
