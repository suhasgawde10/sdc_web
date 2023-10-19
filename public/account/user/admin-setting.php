<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include '../sendMail/sendMail.php';

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
} elseif (isset($_SESSION['email']) && $_SESSION['email'] != "admin@sharedigitalcard.com" && (isset($_SESSION['type']) && $_SESSION['type'] != 'Admin')) {
    header('location:../login.php');
}


$error = false;
$errorMessage = "";


if (isset($_POST['btn_update'])) {
    $reg_email_contact = trim($_POST['reg_email_contact']);
    $file_handle = fopen('../controller/config/reg_email_contact.txt', 'w');
    fwrite($file_handle, $reg_email_contact);
    fclose($file_handle);
}

if (isset($_POST['btn_service'])) {
    $rd_service = trim($_POST['rd_service']);
    $rd_reviews = trim($_POST['rd_reviews']);
    $file_handle = fopen('../controller/config/email_services.txt', 'w');
    fwrite($file_handle, $rd_service . "," . $rd_reviews);
    fclose($file_handle);
}

if (isset($_POST['btn_razor'])) {
    $razor_api = trim($_POST['razor_api']);
    $razor_secret = trim($_POST['razor_secret']);
    $file_handle = fopen('../controller/config/razor_api.txt', 'w');
    fwrite($file_handle, $razor_api . "," . $razor_secret);
    fclose($file_handle);
}
if (isset($_POST['btn_sms_config'])) {
    $sms_url = trim($_POST['sms_url']);
    $auth_key = trim($_POST['auth_key']);
    $sms_username = trim($_POST['sms_username']);
    $sms_apikey = trim($_POST['sms_apikey']);
    $sms_sender= trim($_POST['sms_sender']);
    $file_handle = fopen('../controller/config/sms_config.txt', 'w');
    fwrite($file_handle, $sms_url . "," . $auth_key . "," . $sms_username . "," . $sms_apikey . "," . $sms_sender);
    fclose($file_handle);
}

if (isset($_POST['btn_email_config'])) {

    $mail_host= trim($_POST['mail_host']);
    $mail_username= trim($_POST['mail_username']);
    $mail_password= trim($_POST['mail_password']);
    $mail_port= trim($_POST['mail_port']);
    $file_handle = fopen('../controller/config/email_config.txt', 'w');
    fwrite($file_handle, $mail_host . "," . $mail_username . "," . $mail_password . "," . $mail_port);
    fclose($file_handle);
}

// Registration Email Contact
$file_handle = fopen('../controller/config/reg_email_contact.txt', 'r');
$get_reg_email_contact = fread($file_handle, filesize('../controller/config/reg_email_contact.txt'));
fclose($get_reg_email_contact);

//SMS On Service & Reviews
$file_handle = fopen('../controller/config/email_services.txt', 'r');
$get_serv_reviews = fread($file_handle, filesize('../controller/config/email_services.txt'));
fclose($get_serv_reviews);
$get_serv_reviews = explode(',', $get_serv_reviews);

//Razor Pay
$file_handle = fopen('../controller/config/razor_api.txt', 'r');
$get_razor = fread($file_handle, filesize('../controller/config/razor_api.txt'));
fclose($get_razor);
$get_razor = explode(',', $get_razor);

//SMS Config
$file_handle = fopen('../controller/config/sms_config.txt', 'r');
$get_sms_config = fread($file_handle, filesize('../controller/config/sms_config.txt'));
fclose($get_sms_config);
$get_sms_config = explode(',', $get_sms_config);

//Email Config
$file_handle = fopen('../controller/config/email_config.txt', 'r');
$get_mail_config = fread($file_handle, filesize('../controller/config/email_config.txt'));
fclose($get_mail_config);
$get_mail_config = explode(',', $get_mail_config);

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Invoice Report</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }

        @media print {
            footer {
                page-break-after: always;
            }
        }

        @page {
            size: auto;   /* auto is the initial value */
            margin: 5mm 10mm;  /* this affects the margin in the printer settings */
        }
    </style>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="container-fluid">


        <div class="row clearfix">

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <form method="post" action="">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>
                                            Registration Basis Of Email / Contact
                                        </h2>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="body table-responsive table_scroll">
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
                            <div>
                                <label class="form-label">Select One</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <input type="radio" class="radio_prop" name="reg_email_contact"
                                           value="email" <?php if (isset($get_reg_email_contact) && $get_reg_email_contact == "email") echo "checked"; ?>>
                                    Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="radio_prop" type="radio" name="reg_email_contact"
                                           value="contact" <?php if (isset($get_reg_email_contact) && $get_reg_email_contact == 'contact') echo "checked"; ?>>
                                    Contact

                                </div>
                            </div>
                            <div class="form-group form_inline">


                                <input value="Update" type="submit" name="btn_update"
                                       class="btn btn-primary waves-effect form-control">
                            </div>


                        </div>

                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <form method="post" action="">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>
                                            Manage SMS ON Service / Reviews
                                        </h2>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="body table-responsive table_scroll">
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
                            <div>
                                <label class="form-label">For Service </label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <input type="radio" class="radio_prop" name="rd_service"
                                           value="1" <?php if (isset($get_serv_reviews[0]) && $get_serv_reviews[0] == "1") echo "checked"; ?>>
                                    On &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="radio_prop" type="radio" name="rd_service"
                                           value="0" <?php if (isset($get_serv_reviews[0]) && $get_serv_reviews[0] == '0') echo "checked"; ?>>
                                    OFF
                                </div>

                            </div>
                            <div>
                                <label class="form-label">For Reviews</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <input type="radio" class="radio_prop" name="rd_reviews"
                                           value="1" <?php if (isset($get_serv_reviews[1]) && $get_serv_reviews[1] == "1") echo "checked"; ?>>
                                    On &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="radio_prop" type="radio" name="rd_reviews"
                                           value="0" <?php if (isset($get_serv_reviews[1]) && $get_serv_reviews[1] == '0') echo "checked"; ?>>
                                    OFF
                                </div>
                            </div>
                            <div class="form-group form_inline">


                                <input value="Update" type="submit" name="btn_service"
                                       class="btn btn-primary waves-effect form-control">
                            </div>


                        </div>

                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <form method="post" action="">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>
                                            Manage Razor Pay Config
                                        </h2>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="body table-responsive table_scroll">
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
                            <div>
                                <label class="form-label">RAZOR LIVE API</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="razor_api" class="form-control"
                                               placeholder="Enter RAZOR LIVE API"
                                               value="<?php if (isset($get_razor[0])) echo $get_razor[0]; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">RAZOR LIVE SECRET</label> <span
                                    class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="razor_secret" class="form-control"
                                               placeholder="Enter RAZOR LIVE SECRET"
                                               value="<?php if (isset($get_razor[1])) echo $get_razor[1]; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group form_inline">


                                <input value="Update" type="submit" name="btn_razor"
                                       class="btn btn-primary waves-effect form-control">
                            </div>


                        </div>

                    </div>
                </form>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <form method="post" action="">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>
                                            Manage SMS Configuration
                                        </h2>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="body table-responsive table_scroll">
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
                            <div>
                                <label class="form-label">SMS URL</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="sms_url" class="form-control"
                                               placeholder="Enter SMS URL"
                                               value="<?php if (isset($get_sms_config[0])) echo $get_sms_config[0]; ?>">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">AUTH KEY</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="auth_key" class="form-control"
                                               placeholder="Enter AUTH KEY"
                                               value="<?php if (isset($get_sms_config[1])) echo $get_sms_config[1]; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">SMS USERNAME</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="sms_username" class="form-control"
                                               placeholder="Enter SMS USERNAME"
                                               value="<?php if (isset($get_sms_config[2])) echo $get_sms_config[2]; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">SMS APIKEY</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="sms_apikey" class="form-control"
                                               placeholder="Enter SMS APIKEY"
                                               value="<?php if (isset($get_sms_config[3])) echo $get_sms_config[3]; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">SMS SENDER</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="sms_sender" class="form-control"
                                               placeholder="Enter SMS SENDER"
                                               value="<?php if (isset($get_sms_config[4])) echo $get_sms_config[4]; ?>" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form_inline">


                                <input value="Update" type="submit" name="btn_sms_config"
                                       class="btn btn-primary waves-effect form-control">
                            </div>


                        </div>

                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <form method="post" action="">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2>
                                            Manage Email Configuration
                                        </h2>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="body table-responsive table_scroll">
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
                            <div>
                                <label class="form-label">MAIL HOST</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="mail_host" class="form-control"
                                               placeholder="Enter MAIL HOST"
                                               value="<?php if (isset($get_mail_config[0])) echo $get_mail_config[0]; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">MAIL USERNAME</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="mail_username" class="form-control"
                                               placeholder="Enter MAIL USERNAME"
                                               value="<?php if (isset($get_mail_config[1])) echo $get_mail_config[1]; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">MAIL PASSWORD</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="mail_password" class="form-control"
                                               placeholder="Enter MAIL PASSWORD"
                                               value="<?php if (isset($get_mail_config[2])) echo $get_mail_config[2]; ?>" >
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">MAIL PORT</label> <span class="required_field">*</span>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input name="mail_port" class="form-control"
                                               placeholder="Enter MAIL PORT"
                                               value="<?php if (isset($get_mail_config[3])) echo $get_mail_config[3]; ?>" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form_inline">


                                <input value="Update" type="submit" name="btn_email_config"
                                       class="btn btn-primary waves-effect form-control">
                            </div>


                        </div>

                    </div>
                </form>
            </div>


            <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="image-slider.php">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="far fa-image"></i>
                        </div>
                        <div class="content">
                            <div class="text">Image Slider</div>
                            <div class="number count-to"><?php /*if (isset($sliderCount)) echo $sliderCount; */ ?></div>
                        </div>
                    </div>
                </a>
            </div>-->
        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
</body>
</html>