<?php
ob_start();
/*error_reporting(0);*/
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';

include "../controller/ManageUser.php";
$manage = new ManageUser();
$error = false;
$errorMessage = "";
$id = 0;
include("session_includes.php");

$get_user_expiry_count = $manage->selectTheme();
if ($get_user_expiry_count != null) {
    $update_user_count = $get_user_expiry_count['update_user_count'];
    $get_email_count = $get_user_expiry_count['email_count'];
}
$date1 = date("Y-m-d");
$date = date_create("$date1");
date_add($date, date_interval_create_from_date_string("5 days"));
$final_date = date_format($date, "Y-m-d");
if ($update_user_count == 0) {
    $year = "Free Trail (5 days)";
    $amount = "0";
    $status = "success";
    $referal_by = "";
    $refrenced_by = "";
    $active_plan = 1;
    $invoice_no = "";
    $discount = 0;
    $paymentMode = "";
    $paymentBrand = "";
    $custBankId = "";
    $timestamp = date('Y-m-d H:i:s');
    $tax = 0;
    $payment_type = "Free trial";
    $gstn_no_status = 0;
    $insertUserSubscription = $manage->insertUserData($year, $amount,$amount, $date1, $final_date, $status, $referal_by,
        $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $amount, $paymentBrand, $paymentMode, $custBankId,
        $timestamp,$payment_type,$session_name,$session_email,"","",FROM_BILL,FROM_GSTNO,FROM_PAN,SAC_CODE);
    if ($insertUserSubscription) {
        $updateUserExpiry = $manage->updateUserExpiryDate($final_date);
        if ($updateUserExpiry) {
            if ($get_email_count == "0") {
                $update_email_count = $manage->update_email_count();
                $toName = $session_name;
                $toEmail = $session_email;
                $subject = "Profile Updated Successfully";
                $email_message = '    <table style="width: 100%">
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
                   <p> Dear <span class="cust-name">' . ucwords($session_name) . '</span>,</p>

                    <p>
                        Your digital card is ready.
                    </p>

                    <p>
                        Staring Date From <span class="se-date">' . $date1 . '</span> To Ending date <span
                        class="se-date">' . $final_date . '</span>.</p>
                     <a href="' .SHARED_URL . $session_custom_url_is . '" style="' . $btn . ';background: #db5ea5 !important;width: 100%;color: #ffffff;border-radius: 4px;font-size: 16px;padding: 10px 0;">Open Your Digital Card</a>
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
                $sms_mail_message = "Dear " . $session_name . ",\n";
                $sms_mail_message .= "Your Digital Card is ready\n";
                $sms_mail_message .= SHARED_URL.$session_custom_url_is;
                $sendMail = $manage->sendMail($toName, $toEmail, $subject, $email_message);
                $send_sms = $manage->sendSMS($session_contact_no, $sms_mail_message);
            }
        }
        if ($android_url != "") {
            header('location:basic-user-info.php?company_info_tab=true&' . $android_url);
        } else {
            header('location:basic-user-info.php?company_info_tab=true');
        }
    }
} else {
    header('location:plan-selection.php');
}
?>