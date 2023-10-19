<?php
require_once "controller/ManageApp.php";
$manage = new ManageApp();
include 'sendMail/sendMailContact.php';

$error = false;
$errorMessage = "";

if (isset($_POST['send_detail'])) {
    $message_form = $_POST['msg'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $contact_no = $_POST['contact_no'];

    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        // Google reCAPTCHA API secret key
        $secretKey = '6LeSbAEVAAAAAH0X1C5mpHlSZVDhH4tsJ9atoGx1';

        // Verify the reCAPTCHA response
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);

        // Decode json data
        $responseData = json_decode($verifyResponse);

        // If reCAPTCHA response is valid
        if ($responseData->success) {

            $toName = "SHARE DIGITAL CARD";
            $toEmail = $global_email;
            /*$subject = "ENQUIRY MAIL -<?php echo $_SERVER['HTTP_HOST']; ?>";*/
            $subject = "ENQUIRY MAIL";
            $message = '<html><body>';
            $message .= '<table style="border-collapse: collapse;" cellpadding="10">';
            $message .= '<tr style="background: #eee; text-align: center;" ><td colspan="2">';
            $message .= '<b>Enquiry Form</b>';
            $message .= '</td></tr>';
            $message .= "<tr style='background: #eee;'><td>Name:- </td>";
            $message .= "<td>" . $name . "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>Contact Number:- </td>";
            $message .= "<td>" . $contact_no . "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>Email:- </td>";
            $message .= "<td>" . $email . "</td></tr>";
            $message .= "<tr style='background: #eee;'><td>Message:- </td>";
            $message .= "<td>" . $message_form . "</td></tr>";
            $message .= "</table>";
            $message .= "</body></html>";
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
            //$sendMail = true;
            if ($sendMail) {
                $error = false;
                $errorMessage = "Thanks for contacting us! We will be in touch with you shortly.";
            }
        } else {
            $error = true;
            $errorMessage = 'Robot verification failed, please try again.';
        }
    } else {
        $error = true;
        $errorMessage = "Please check on the reCAPTCHA box.";
    }

}
?>
<div class="container contact-area ptb--70">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="contact-form panel panel-success">
            <div class="custom_panel_heading">
                We will glad to hear from you!
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <?php if ($error) {
                        ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?php if (isset($errorMessage)) echo $errorMessage; ?>
                        </div>
                        <?php
                    } else if (!$error && $errorMessage != "") {
                        ?>
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?php if (isset($errorMessage)) echo $errorMessage; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <span id="body_lbl_name" class="form-control-names"><i class="fa fa-user"></i>&nbsp;Full Name</span>
                    <input type="text" name="name" placeholder="Full Name" required="required">
                    <span id="body_lbl_email_id" class="form-control-names"><i class="fa fa-envelope"></i>&nbsp;Email ID</span>
                    <input type="text" name="email" placeholder="Email Id" required="required">
                    <span id="body_lbl_mobile" class="form-control-names"><i class="fa fa-phone"></i>&nbsp;Contact Number</span>
                    <input type="text" name="contact_no" placeholder="Contact Number" required="required">
                    <span id="body_lbl_msg" class="form-control-names"><i class="fa fa-comment"></i>&nbsp;Message</span>
                    <textarea name="msg" id="msg" placeholder="Your Message" required="required"></textarea>
                    <!-- Google reCAPTCHA box -->
                    <div class="g-recaptcha" data-sitekey="6LeSbAEVAAAAAD7x5O1HkY9NtBkEThRTBK1lfHDI"></div>
                    <input type="submit" value="Send" name="send_detail" id="send">
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="contact_info">
            <div class="s-info">
                <i class="fa fa-map-marker"></i>

                <div class="meta-content">
                    <span>	709 / 7th floor, Lotus Business Park, Ram Bagh Road, Opp Dal Mill Compound, Near HP petrol pump, S. V. Road, Malad West, Mumbai, Maharashtra 400064.</span>
                </div>
            </div>

            <div class="s-info">
                <i class="fa fa-mobile"></i>

                <div class="meta-content">
                    <span>+91 99677 83583 / +91 97689 04980.</span>
                </div>
            </div>
            <!--<div class="s-info">
                <i class="fa fa-headphones" aria-hidden="true"></i>

                <div class="meta-content">
                    <span><a href="tel:9321894076">+91 9321894076</a></span>

                </div>
            </div>-->
            <div class="s-info">
                <i class="fa fa-clock-o" aria-hidden="true"></i>

                <div class="meta-content">
                    <span>Support Call Time: 11:00 to 6:00 (Monday to Friday)</span>

                </div>
            </div>

            <div class="s-info">
                <i class="fa fa-paper-plane"></i>

                <div class="meta-content">
                    <span>support@sharedigitalcard.com</span>

                </div>
            </div>
            <div class="c-social">
                <div class="social_icon">
                    <a href="https://www.facebook.com/sharedigitalcard/" target="_blank" class="fa fa-facebook"></a>
                    <a href="https://www.youtube.com/channel/UCQ4o_M5CqMUA9vnZZVfyvQw" target="_blank"
                       class="fa fa-youtube"></a>
                    <a href="https://www.instagram.com/sharedigitalcard/" target="_blank" class="fa fa-instagram"></a>
                </div>
            </div>
        </div>
    </div>
</div>