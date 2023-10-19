<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$error = false;
$errorMessage = "";
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}elseif(isset($_SESSION['email']) && $_SESSION['email'] != "admin@sharedigitalcard.com" && (isset($_SESSION['type']) && $_SESSION['type'] != 'Admin')){
    header('location:../login.php');
}

if (isset($_GET['user_id'])) {
    $user_id = $security->decrypt($_GET['user_id']);
    $displayUser = $manage->displayAllUserByID($user_id);
    if ($displayUser != null) {
        $email = $displayUser['email'];
        $contact_no = $displayUser['contact_no'];
        $name = $displayUser['name'];
    }
    if (isset($_POST['send'])) {
        $subject = $_POST['subject'];
        $txt_email = $_POST['email'];
        $txt_contact = $_POST['contact_no'];
        $email_body = $_POST['email_body'];
        $sms_body = $_POST['sms_body'];
        if ($email_body != "" && $sms_body != "" && $subject != "") {
            $sendMail = $manage->sendMail($name, $txt_email, $subject, $email_body);
            $send_sms = $manage->sendSMS($txt_contact, $sms_body);
            if ($send_sms) {
                $error = false;
                $errorMessage = "Email and SMS has been sent to " . $txt_email;
            }
        } else {
            $error = true;
            $errorMessage = "Please enter keyword in body";
        }
    }
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>User Management</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-md-6">
                <div class="row margin_div_web">
                    <div class="card">
                        <div class="header">
                            <h2>Email & SMS</h2>
                        </div>
                        <div class="body">
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
                            } ?>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="width-prf">
                                    <label class="form-label">to</label> <span>*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="email" class="form-control"
                                                   placeholder="Email"
                                                   value="<?php if (isset($email)) echo $email; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="width-prf">
                                    <label class="form-label">Contact No</label> <span>*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="contact_no" class="form-control"
                                                   placeholder="contact_no"
                                                   value="<?php if (isset($contact_no)) echo $contact_no; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="width-prf">
                                    <label class="form-label">Subject</label> <span>*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="subject" class="form-control"
                                                   placeholder="Subject"
                                                   value="" required="required">
                                        </div>
                                    </div>
                                </div>
                                <div class="width-prf">
                                    <label class="form-label">Email Body</label> <span>*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea rows="5" class="form-control" name="email_body"
                                                      placeholder="Email..." required="required"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="width-prf">
                                    <label class="form-label">SMS Body</label> <span>*</span>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea rows="5" class="form-control" name="sms_body"
                                                      placeholder="SMS..." required="required"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button name="send" type="submit"
                                            class="btn btn-primary waves-effect"><i class="fas fa-paper-plane"></i> SEND
                                    </button>
                                    &nbsp;&nbsp;<a href="user-management.php"
                                                   class="btn btn-danger waves-effect">Cancel
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>