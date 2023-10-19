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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

include "common-file.php";

if (isset($_POST['send'])) {

    if (isset($_POST['name']) && $_POST['name'] != "") {
        $name = $_POST['name'];
    } else {
        $error = true;
        $errorMessage .= "Enter Name<br>";
    }

    if (isset($_POST['phone']) && $_POST['phone'] != "") {
        $contact = $_POST['phone'];
    } else {
        $error = true;
        $errorMessage .= "Enter Mobile Number.<br>";
    }

    if (!$error) {

        $subject = "$name Contact For Franchise Details";
        $message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0;">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
	<style>
    /* Reset styles */
    body {
      font-family: Arial, sans-serif;
      height: 100% !important;
      margin: 0;
      min-width: 100%;
      padding: 0;
      width: 100% !important;
    }
    body, table, td, div, p, a {
      line-height: 100%;
      text-size-adjust: 100%;
      -webkit-font-smoothing: antialiased;
      -ms-text-size-adjust: 100%;
      -webkit-text-size-adjust: 100%;
    }
    table, td {
      border-collapse: collapse !important;
      border-spacing: 0;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }
    p {
        margin-block-start: .5em;
        margin-block-end: .5em;
    }
    img {
      border: 0;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }
    .action-item {
      border: 1px solid #005f7f;
      color: #005f7f;
      padding: 8px 20px;
    }
    .action-item:hover {
      background-color: #2a923d;
      border: 1px solid #2a923d;
      color: #fff;
    }
    #outlook a {padding: 0;}
    .ReadMsgBody {width: 100%;}
    .ExternalClass {width: 100%;}
    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {line-height: 100%;}

    /* Rounded corners for advanced mail clients only */
    @media all and (min-width: 560px) {
      .container {
        border-radius: 8px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        -khtml-border-radius: 8px;
      }
    }
    /* Set color for auto links (addresses, dates, etc.) */
    a, a:hover {color: #005f7f;}
    .footer a,
    .footer a:hover {
      color: #999999;
    }
 	</style>
	<!-- MESSAGE SUBJECT -->
	<title></title>
</head>
<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; background-color: #ececec; color: #333333;" bgcolor="#ececec" text="#333333">
<!-- WRAPPER TABLE -->
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;">
  <tr>
    <br>
    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;" bgcolor="#ececec">
      <!-- WRAPPER -->
      <table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit; max-width: 560px; margin: 30px 0 0 0;">
        <!-- PRIMARY IMAGE -->
        <tr>
          <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 5px;">';

        if ($logo != "") {
            $message .= '<img border="0" vspace="0" hspace="0" src="panel/uploads/logo/' . $logo . '" alt="' . $company_name . '" width="560" style="border: none; color: #333333; display: block; font-size: 13px; margin: 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 100%; -ms-interpolation-mode: bicubic;"/>';
        }

        $message .= '</td>
        </tr>
        <!-- CONTENT -->
        <tr>
          <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 90%;">
            <h4 style="color: #333;  font-size: 20px; font-weight: 800; line-height: 100%; margin: 20px 0 10px 0; padding: 0;">We have received franchise request</h4><br>
            <ul style="list-style: none;margin: 0 auto">
              <li style="font-size: 15px; font-weight: 400; line-height: 160%; color: #333333; font-family: Arial, sans-serif;"><strong>Name</strong>: ' . $name . '</li>
              <li style="font-size: 15px; font-weight: 400; line-height: 160%; color: #333333; font-family: Arial, sans-serif;"><strong>Contact number</strong>: ' . $contact . '</li>
            </ul>
            <br>

          </td>
        </tr>
        <tr>
          <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td style="padding-bottom: 0px;" align="center">

                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        <tr>
        <tr>
          <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
            <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">&nbsp;</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
        /*echo $message;
        exit;*/

        try {
//        $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $pawd;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $port;

            $mail->setFrom($email_id, $company_name);
            $mail->addAddress($email_id);

            $mail->isHTML(true);
            $mail->Subject = "Received franchise request";
            $mail->Body = $message;
            $mail->AltBody = 'Body in plain text for non-HTML mail clients';
            $mail->send();

            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Thank You!","Your Request received Contact you soon. ","success");';
            echo '}, 1000);</script>';

        } catch (Exception $e) {
//            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Oops!","Check your internet or try after some time. ","danger");';
            echo '}, 1000);</script>';
        }

    }
}

function isMobileDevice()
{
    return preg_match("/(android|iPod|iPhone|iPad|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
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
    <link href="white-lable/assets/css/fran.css" rel="stylesheet">

    <script type="text/javascript">
        screenWidth = window.screen.width;
        if (screenWidth >= 480) {
            console.log('here');
        } else {
            <?php $mobile_device = "true"; ?>

        }
    </script>

</head>
<body>
<?php include "white-lable/assets/common-includes/header.php"; ?>

<section style="padding: 10px 0 0 0">
    <div class="header-div">
        <div class="title">
            <h2 class="text-uppercase">BECOME A WHITE Label franchise PARTNER</h2>
            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit!</p>-->
            <div>
                <a href="#enroll" class="btn btn-primary btn-margin-bottom">Get Started <i
                        class="fa fa-chevron-circle-right"></i></a>
                <?php
                if (isMobileDevice()) {
                    ?>
                    <a href="tel:<?php echo $cotactnum ?>" class="btn btn-info btn-margin-bottom">Request to call <i
                            class="fa fa-phone"></i>
                    </a>
                <?php
                } else {
                    ?>
                    <a href="#open-modal" class="btn btn-info btn-margin-bottom">Request to call <i
                            class="fa fa-phone"></i>
                    </a>
                <?php
                }
                ?>
                <a target="_blank"
                   href="https://wa.me/91<?php echo $cotactnum ?>?text=I'm%20interested%20in%20Digital%20Card%20Franchise"
                   class="btn btn-success btn-margin-bottom">Chat on whatsapp <i class="fa fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="pricing-area ptb--30" id="enroll" style="padding-top: 60px;background-color: #efefef">
    <div class="container">
        <div class="section-title" style="text-align: center;position: relative">
            <h1 class="head-title icon-title" style="margin-bottom: 30px;position: relative">How we can Enroll for White
                Label Franchise Program?</h1>

            <p style="font-size: 25px">Go Paperless, Go Digital</p>
        </div>
        <!-- title-section -->
        <ul class="angle_icon_content">
            <li>
                <div class="process-item">
                    <div class="img_process">
                        <img src="white-lable/assets/img/icons/marriage.png" alt="image">
                        <span>01</span>
                    </div>
                    <div class="process_text">
                        <h4>Registration</h4>

                    </div>
                </div>
            </li>
            <li>
                <div class="process-item">
                    <div class="img_process">
                        <img src="white-lable/assets/img/icons/to-do-list.png" alt="image">
                        <span>02</span>
                    </div>
                    <div class="process_text">
                        <h4>Fill Out Information / Complete Your KYC</h4>

                    </div>
                </div>
            </li>
            <li>
                <div class="process-item">
                    <div class="img_process">
                        <img src="white-lable/assets/img/icons/stopwatch.png" alt="image">
                        <span>03</span>
                    </div>
                    <div class="process_text">
                        <h4>Wait for Approval</h4>


                    </div>
                </div>
            </li>
            <li>
                <div class="process-item">
                    <div class="img_process">
                        <img src="white-lable/assets/img/icons/money.png" alt="image">
                        <span>04</span>
                    </div>
                    <div class="process_text">
                        <h4>Once Approved Pay Enrollment Fees.</h4>

                    </div>
                </div>
            </li>
            <li>
                <div class="process-item">
                    <div class="img_process" style="">
                        <img src="white-lable/assets/img/icons/credit-card.png" alt="image" class="img_process_custom"
                             style="width: 61%">
                        <span style="">05</span>
                    </div>
                    <div class="process_text">
                        <h4>Create Digital Cards</h4>

                    </div>
                </div>
            </li>
            <li>
                <div class="process-item">
                    <div class="img_process">
                        <img src="white-lable/assets/img/icons/salary.png" alt="image">
                        <span>06</span>
                    </div>
                    <div class="process_text">
                        <h4>Sell and Start Earning.</h4>
                    </div>
                </div>
            </li>
        </ul>

        <!--<div class="row">
            <div class="angle_icon"></div>
            <div class="col-md-2 col-sm-12">


            </div>
            <div class="col-md-2 col-sm-12">



            </div>
            <div class="col-md-2 col-sm-12">



            </div>
            <div class="col-md-2 col-sm-12">



            </div>
            <div class="col-md-2 col-sm-12">



            </div>
            <div class="col-md-2 col-sm-12">


            </div>
        </div>-->
    </div>
    <!-- .container -->
</section>
<section class="feature-area about_back_color ptb--30" id="feature" style="padding: 40px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="head-title program" style="position: relative;">What is White Label Franchise Program?</h2>

                <div class="dearle-para">
                    <p>
                        White label franchise Program allows any Businesses or Individual Person to sell Digital Card
                        and Start
                        Earning Extra Money out of it. Digital Card is special type of service which represent as
                        Digital Visiting card and can be used to showcase and promote anyone’s business.
                    </p>

                    <p>
                        The Main Motive of White label franchise program is to spread the concept of Digital Card
                        Concept in order
                        to replace traditional paper visiting card to save tree and represent your business in
                        Digital Way.
                    </p>
                </div>
                <div>
                    <a href="#enroll" class="btn btn-success cust-btn-dealer">Get Started <i
                            class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="white-lable/assets/img/banner/1.svg" style="width: 100%">
            </div>
        </div>
    </div>
</section>
<section class="pricing-area ptb--40" id="pricing">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="white-lable/assets/img/banner/2.jpg" style="width: 100%">
            </div>
            <div class="col-md-6">
                <h2 class="head-title benefits">What is benefits of White Label Franchise Program to Dealers?</h2>

                <div class="dearle-para dealer_icon mb-3">
                    <div class="row">
                        <div class="col-md-6 dearle-para dealer_icon">
                            <p><i class="fa fa-angle-right"></i> Unlimited Website Creation</p>

                            <p><i class="fa fa-angle-right"></i> Unlimited Card Creation</p>

                            <p><i class="fa fa-angle-right"></i> Get your own Brand Website</p>

                            <p><i class="fa fa-angle-right"></i> Get free Marketing Materials</p>
                        </div>
                        <div class="col-md-6 dearle-para dealer_icon">
                            <p><i class="fa fa-angle-right"></i> Sell Franchise</p>

                            <p><i class="fa fa-angle-right"></i> Card Renewal Benifits</p>

                            <p><i class="fa fa-angle-right"></i> Expert Guidance </p>

                            <p><i class="fa fa-angle-right"></i> Technical Support</p>
                        </div>
                    </div>
                </div>
                <div>
                    <a class="btn btn-success cust-btn-dealer" href="#enroll">Get Started <i
                            class="fa fa-chevron-circle-right"></i>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>
<section class="pricing-area steps_to_create_sec" id="feature">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="head-title register" style="position: relative;">Who can register for White Label Franchise
                    program?</h2>

                <div class="dearle-para dealer_icon">
                    <p>White label franchise program can be joined by any Individual as well as businesses. Just before
                        joining
                        white label franchise program it is necessary to Complete your KYC to Verify your identity.</p>

                    <p><i class="fa fa-angle-right"></i> Individual</p>

                    <p><i class="fa fa-angle-right"></i> Freelancer</p>

                    <p><i class="fa fa-angle-right"></i> Unregister Company</p>

                    <p><i class="fa fa-angle-right"></i> Register Company</p>

                    <div style="margin-top: 30px">
                        <a href="#enroll" class="btn btn-success">Get Started <i class="fa fa-chevron-circle-right"></i>
                        </a>
                    </div>
                </div>

            </div>
            <div class="col-md-6 text-center">
                <img src="white-lable/assets/img/banner/3.png" style="width: 100%">

            </div>
        </div>
    </div>
</section>

<section class="pricing-area" id="" style="padding-top: 0px">
    <div class="container">
        <div class="section-title" style="text-align: center;position: relative">
            <h1 class="head-title icon-title" style="margin-bottom: 30px;position: relative">Franchise Plan</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="main">
                    <table class="price-table table" style="margin-bottom: 0px">
                        <tbody>
                        <tr>
                            <td class="price-blank"></td>

                            <td class="price-blank"></td>
                            <td class="price-table-popular">Most popular</td>
                            <td class="price-blank"></td>
                        </tr>
                        <tr class="price-table-head">
                            <td></td>

                            <td>
                                Silver
                                <br>

                            </td>
                            <td>
                                Golden
                                <br>

                            </td>
                            <td>
                                Diamond
                                <br>

                            </td>
                        </tr>
                        <tr class="info-table-cell">
                            <td></td>
                            <td>&nbsp;50% plan &nbsp;</td>
                            <td>80% plan</td>
                            <td>&nbsp;100% plan&nbsp;</td>
                        </tr>
                        <tr class="info-table-cell">
                            <td> Discount Per Card
                            </td>
                            <td>50% Off On Every plan</td>

                            <td>80% Off On Every plan</td>
                            <td>100% Off On Every plan</td>
                        </tr>
                        <tr class="">
                            <td> Unlimited Website Selling
                            </td>
                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="info-table-cell">
                            <td> Unlimited Card Selling
                            </td>

                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="">
                            <td> Get Your Own Brand Website
                            </td>
                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="">
                            <td> Sell franchise
                            </td>

                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="info-table-cell">
                            <td> Card Renewal Benifits
                            </td>
                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="">
                            <td> Expert Guidance
                            </td>
                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="info-table-cell">
                            <td>Technical Support
                            </td>
                            <td><i class="fa fa-check"></i></td>

                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>
                        <tr class="info-table-cell">
                            <td>Estimated Delivery Time
                            </td>
                            <td>03-04 Days</td>

                            <td>03-04 Days</td>
                            <td>07-08 Days</td>
                        </tr>
                        <tr class="price-tr">
                            <td>
                            </td>
                            <?php
                            if ($getFranchisePlan != "") {
                                while ($price = mysqli_fetch_array($getFranchisePlan)) {
                                    ?>
                                    <td>₹ <?php echo $price['plan_price'] ?></td>
                                <?php
                                }
                            } else {
                                ?>
                                <td>₹ 12,000</td>
                                <td>₹ 18,000</td>
                                <td>₹ 20,000</td>
                            <?php
                            }
                            ?>
                        </tr>
                        <tr class="contact-tr">
                            <td>
                            </td>
                            <td style="width: 10px  ">
                                <a href="https://wa.me/91<?php echo $cotactnum ?>?text=I%20am%20interested%20for%2050%%20Digital%20franchise%20Plan"
                                   class="btn btn-success">Contact <i class="fa fa-whatsapp"></i></a>
                            </td>
                            <td>
                                <a href="https://wa.me/91<?php echo $cotactnum ?>?text=I%20am%20interested%20for%2080%%20Digital%20franchise%20Plan"
                                   class="btn btn-success">Contact <i class="fa fa-whatsapp"></i></a>
                            </td>
                            <td>
                                <a href="https://wa.me/91<?php echo $cotactnum ?>?text=I%20am%20interested%20for%20100%%20Digital%20franchise%20Plan"
                                   class="btn btn-success">Contact <i class="fa fa-whatsapp"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="" style="margin-top: 10px">
                    <small style="color: red"><span style="font-weight: 500">NOTE:</span> Franchise plan validity will
                        be for 5 year
                    </small>
                </div>


            </div>
        </div>

    </div>
</section>


<section style="padding:0">
    <div class="header-div">
        <div class="title-foot">
            <h1 class="text-uppercase mb-4">Register Now <br>as White Label Franchise Partner</h1>
            <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit!</p>-->
            <div>
                <a href="#open-modal" class="btn btn-primary"><i class="fa fa-phone"></i> Request to call
                </a>
                <a href="https://wa.me/91<?php echo $cotactnum ?>?text=I'm%20interested%20in%20Digital%20Card%20Franchise"
                   class="btn btn-success"><i class="fa fa-whatsapp"></i> Chat on whatsapp</a>
            </div>
        </div>
    </div>
</section>

<div id="open-modal" class="modal-window">
    <div>
        <a href="#" title="Close" class="modal-close">Close</a>

        <h1>Request to call</h1>

        <form action="" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Full Name" required="">
            </div>
            <div class="form-group">
                <label for="number">Mobile No</label>
                <input type="number" name="phone" class="form-control" id="number" placeholder="Mobile Number"
                       required="">
            </div>
            <button type="submit" class="btn btn-primary" name="send">Send</button>
            <button type="submit" class="btn btn-danger">Cancel</button>
        </form>
    </div>
</div>

<?php include "white-lable/assets/common-includes/footer.php"; ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<div id="preloader">
    <div class="loder-img">
        <!--        <img src="panel/uploads/logo/--><?php //echo $logo ?><!--">-->
    </div>
</div>

<?php
include "white-lable/assets/common-includes/footer-includes.php";
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-simple-captcha@1.0.0/src/jquery.simpleCaptcha.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZG5Y9ZEJ2V"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-ZG5Y9ZEJ2V');
</script>
</body>
</html>
