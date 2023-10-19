<?php
include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include 'sendMail/sendMail.php';
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();


 if(isset($_POST['verify_otp']) && $_POST['verify_otp'] !=''){
     $verify_otp = implode('',$_POST['verify_otp']);
     $existing_otp = $security->decryptWebservice($_SESSION['random_sms']);
     if($verify_otp == $existing_otp){
         $_SESSION['verified_status'] = true;
         $returnData = array(
             'status' => 'ok',
             'msg' => '',
             'data' => ''
         );
         echo json_encode($returnData);
         exit();
     }else{

         $returnData = array(
             'status' => 'error',
             'msg' => 'Invalid OTP',
             'data' => ''
         );
         echo json_encode($returnData);
         exit();
     }

 }else {


     if ($_POST['type'] == "email") {
         if ($_POST['email_contact'] != "") {
             $result = $manage->validateRegisterEmail($_POST['email_contact']);
             if ($result) {
                 $returnData = array(
                     'status' => 'error',
                     'msg' => 'Email ID Already Exists!!',
                     'data' => ''
                 );
                 echo json_encode($returnData);
                 exit();
             } else {
                 $txt_email = $_POST['email_contact'];
                 $_SESSION['recaptcha'] = true;
                 $_SESSION['email_login'] = "true";
                 $sms_message = '<table style="width: 100%">
<tr>
<td colspan="2" style=' . $back_image . '>
<div style="' . $overlay . '">
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
                   <div class="about-content">
                       <p> Dear Customer,</p>
                    <p>Please check the below otp to verify your email id. Please do not share this otp with anyone for security reasons</p>

                </div>
                <div style="text-align: center;margin: 20px 0;">
                    <div class="otp-inner" style=" height: auto;
            background: #deddd9;
            text-align: center;
            padding: 10px 0;
            width: 30%;
            margin: 0 auto;
            border: 2px dotted #ccc;
            font-size: 18px;
            font-weight: 600;">
                        <label style="color: #666563;">Your OTP Is <br><span style="font-weight: bold;color: #666563;">' . substr_replace($random_sms,'-',3,0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
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
                 $subject = "OTP For Registration From - sharedigitalcard.com";
                 $send_sms = $manage->sendMail(MAIL_FROM_NAME, $txt_email, $subject, $sms_message);
         /*        $insert = $manage->insertContactNumberLog($txt_email);*/
                 $_SESSION['tmp_email'] = $txt_email;
                 $_SESSION['random_sms'] = $security->encryptWebservice($random_sms);
                 $returnData = array(
                     'status' => 'ok',
                     'msg' => 'OTP has been sent to your email id',
                     'data' => ''
                 );
                 echo json_encode($returnData);
                 exit();
             }
         } else {
             $returnData = array(
                 'status' => 'error',
                 'msg' => 'Please enter email id',
                 'data' => ''
             );
             echo json_encode($returnData);
             exit();
         }
     } elseif ($_POST['type'] == "contact") {

         if ($_POST['email_contact'] != "") {
             $_SESSION['recaptcha'] = true;

             $sms_contact = $_POST['email_contact'];
             $result = $manage->validateContact($sms_contact);
             if ($result) {
                 $returnData = array(
                     'status' => 'error',
                     'msg' => 'User with this contact number already registered!!',
                     'data' => ''
                 );
                 echo json_encode($returnData);
                 exit();
             } else {
                 //$sms_message = "Dear Customer,\n" . substr_replace($random_sms,'-',3,0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
                 //$sms_message = "Dear Customer, ". substr_replace($random_sms, '-', 3, 0) . " is your one time password - OTP. Please do not share this OTP with anyone for security reasons.";
                 //$send_sms = $manage->sendSMS($sms_contact, $sms_message);
                 $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
                 $send_sms = $manage->sendSMSWithTemplateId($sms_contact, $sms_message,TEMPLATE_REGISTRATION);
                 if($send_sms){
                     $insert = $manage->insertContactNumberLog($sms_contact);
                     $_SESSION['contact'] = $sms_contact;
                     $_SESSION['random_sms'] = $security->encryptWebservice($random_sms);
                     $returnData = array(
                         'status' => 'ok',
                         'msg' => 'OTP has been sent to your entered mobile number',
                         'data' => ''
                     );
                     echo json_encode($returnData);
                     exit();
                 }else{
                     $returnData = array(
                         'status' => 'error',
                         'msg' => 'Issue while sending sms please try after some time.',
                         'data' => ''
                     );
                     echo json_encode($returnData);
                     exit();
                 }

             }
         } else {
             $returnData = array(
                 'status' => 'error',
                 'msg' => 'Please enter contact number',
                 'data' => ''
             );

             echo json_encode($returnData);
             exit();
         }
     }
 }