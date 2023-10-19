<?php
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";

include "common-file.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$mail = new PHPMailer(true);

if (isset($_POST['send_msg'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = $_POST['txt_name'];

    } else {
        $error = true;
        $errorMessage .= "Enter Name<br>";
    }
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $emails = $_POST['txt_email'];

    } else {
        $error = true;
        $errorMessage .= "Enter Email Id.<br>";
    }
    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
        $contact = $_POST['txt_contact'];

    } else {
        $error = true;
        $errorMessage .= "Enter Mobile Number.<br>";
    }
    if (isset($_POST['txt_subject']) && $_POST['txt_subject'] != "") {
        $sub = $_POST['txt_subject'];
    } else {
        $error = true;
        $errorMessage .= "Enter Subject<br>";
    }
    if (isset($_POST['txt_message']) && $_POST['txt_message'] != "") {
        $msg = $_POST['txt_message'];
    } else {
        $error = true;
        $errorMessage .= "Enter Message<br>";
    }
    if (!$error) {
        $subject = "$name Contact for digital Card";
        $message = '<!DOCTYPE html>
<html>
<head>
    <title>' . $company_name . '</title>
    <style>
        body{
            background: #f1f1f1;
        }
        @media only screen and (max-width: 600px) {
            .main {
                width: 320px !important;
            }

            .top-image {
                width: 30% !important;
            }

            .inside-footer {
                width: 320px !important;
            }

            table[class="contenttable"] {
                width: 320px !important;
                text-align: left !important;
            }

            td[class="force-col"] {
                display: block !important;
            }

            td[class="rm-col"] {
                display: none !important;
            }

            .mt {
                margin-top: 15px !important;
            }

            *[class].width300 {
                width: 255px !important;
            }

            *[class].block {
                display: block !important;
            }

            *[class].blockcol {
                display: none !important;
            }

            .emailButton {
                width: 100% !important;
            }

            .emailButton a {
                display: block !important;
                font-size: 18px !important;
            }
        }
    </style>
</head>
<body link="#00a5b5" vlink="#00a5b5" alink="#00a5b5">
<table class=" main contenttable" align="center"
       style="font-weight: normal;border-collapse: collapse;border: 0;margin-left: auto;margin-right: auto;padding: 0;font-family: Arial, sans-serif;color: #555559;background-color: white;font-size: 16px;line-height: 26px;width: 600px;">
    <tr>
        <td class="border"
            style="border-collapse: collapse;border: 1px solid #eeeff0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
            <table
                style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                <tr>
                    <td colspan="4" valign="top" class="image-section"
                        style="text-align: center;border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust:
                        none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #fff;border-bottom: 4px solid #4233ff">';

        if ($logo != "") {
            $message .= '<img border="0" vspace="0" hspace="0" src="panel/uploads/logo/' . $logo . '" alt="' . $company_name . '" width="560" style="border: none; color: #333333; display: block; font-size: 13px; margin: 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 100%; -ms-interpolation-mode: bicubic;"/>';
        }

        $message .= '</td>
                </tr>
                <tr bgcolor="#fff" style="border-top: 4px solid #00a5b5;">
                    <td valign="top" class="footer"
                        style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                        <table
                            style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    Full Name
                                </td>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">' . $name . '</td>
                            </tr>
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    Email
                                </td>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">' . $emails . '</td>
                            </tr>
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    Contact
                                </td>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">' . $contact . '</td>
                            </tr>
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    Subject
                                </td>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">' . $sub . '</td>
                            </tr>
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    Message
                                </td>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">' . $msg . '</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#fff" style="border-top: 4px solid #00a5b5;">
                    <td valign="top" class="footer"
                        style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                        <table
                            style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                            <tr>
                                <td class="inside-footer" align="center" valign="middle"
                                    style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                    <div id="address" class="mktEditable">
                                        <b>' . $company_name . '</b><br>
                                        ' . $Addrs . '
                                        <br>
                                        <p style="color: #00a5b5;">' . $company_name . '</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

        try {
//            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $hosts;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $pawd;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $port;

            $mail->setFrom('atulkumar@kubictechnology.in', $company_name);
//            $mail->addAddress('atulkumar@kubictechnology.in');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = 'Body in plain text for non-HTML mail clients';
            $mail->send();

            $error = false;
            $errorMessage = "Your email send successfully";

        } catch (Exception $e) {
            $error = true;
            $errorMessage = "Issue while sending mail.";
        }
    } else {
        $error = true;
        $errorMessage = "Issue while sending mail.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create digital card - <?php echo strtoupper($company_name); ?></title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="DGINDIA" name="keywords">
    <!-- Favicons -->
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">


    <!-- Vendor CSS Files -->
    <?php include "white-lable/assets/common-includes/header-includes.php"; ?>

</head>
<body>
<?php include "white-lable/assets/common-includes/header.php"; ?>
<section class="breadcrumbs" style="">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Contact Us</h2>
            <ol>
                <li><a href="index.php">Home</a></li>
                <li>Contact us</li>
            </ol>
        </div>

    </div>
</section>
<section id="contact" class="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6 info">
                        <i class="bx bx-map"></i>
                        <h4>Address</h4>

                        <?php
                        if (isset($Addrs) && $Addrs != "") {
                            echo '<a href="' . $Addrs . '" target="_blank">' . $Addrs . '</a>';
                        } else { ?>
                            <p>
                                Not Specified
                            </p>
                        <?php }
                        ?>

                    </div>
                    <div class="col-lg-6 info">
                        <i class="bx bx-phone"></i>
                        <h4>Call Us</h4>
                        <?php
                        if (isset($call) && $call != "") { ?>
                            <a href="tel:<?php echo $call ?>"><?php echo $call ?></a><br/>
                        <?php } else { ?>
                            <p>Not Specified</p><br/>
                        <?php }
                        ?>
                    </div>
                    <div class="col-lg-6 info">
                        <i class="bx bx-envelope"></i>
                        <h4>Email Us</h4>
                        <?php
                        if (isset($email_cont) && $email_cont != "") { ?>
                            <a href="mailto:<?php echo strip_tags($email_cont) ?>">
                                <?php echo $email_cont ?>
                            </a>
                        <?php } else { ?>
                            <a href="mailto:mydigitalcardz@gmail.com">
                                <p>Not Specified</p>
                            </a>
                        <?php }
                        ?>
                    </div>
                    <div class="col-lg-6 info">
                        <i class="bx bx-time-five"></i>
                        <h4>Working Hours</h4>

                        <?php
                        if (isset($hours) && $hours != "") { ?>
                            <?php echo $hours ?>
                        <?php } else { ?>
                            <p>
                                Mon - Fri:9.30 am to 6.30 pm<br>
                                Saturday: 9.30 am to 2.30 pm
                            </p>
                        <?php }
                        ?>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact_form">

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
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_name" placeholder="Your Name"
                                   required=""/>
                        </div>
                        <div class="form-group mt-3">

                            <input type="email" class="form-control" name="txt_email" placeholder="Your Email"
                                   required=""/>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="txt_contact"
                                   placeholder="Your Contact No." required=""/>

                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="txt_subject" placeholder="Subject"
                                   required=""/>

                        </div>

                        <div class="form-group mt-3">
                                <textarea name="txt_message" placeholder="Write Something..." class="form-control"
                                          Rows="5" required=""></textarea>

                        </div>

                        <div class="text-center btn_msg">
                            <button type="submit" name="send_msg" class="send_msg">Send Message</button>

                        </div>

                    </form>
                </div>

            </div>

        </div>

    </div>
</section>

<?php include "white-lable/assets/common-includes/footer.php"; ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<div id="preloader">
    <div class="loder-img">
    </div>
</div>

<?php
include "white-lable/assets/common-includes/footer-includes.php";
?>
</body>
</html>

