<?php
error_reporting(1);
header('Content-Type: application/json');

include "../controller/ManageService.php";
include "../controller/EncryptDecrypt.php";
include '../sendMail/sendMail.php';
$manage = new ManageService();
$security = new EncryptDecrypt();

$response = array();

$response["success"] = false;
$plan_expired = false;
$response['message'] = "";
$date = date("Y-m-d");


/*$sevenDaysDate = date('Y-m-d', strtotime('+ 7 days'));
$sixDaysDate = date('Y-m-d', strtotime('+ 6 days'));
$fiveDaysDate = date('Y-m-d', strtotime('+ 5 days'));
$fourDaysDate = date('Y-m-d', strtotime('+ 4 days'));
$threeDaysDate = date('Y-m-d', strtotime('+ 3 days'));
$twoDaysDate = date('Y-m-d', strtotime('+ 2 days'));
$oneDaysDate = date('Y-m-d', strtotime('+ 1 days'));*/
/*
$sevenDaysRemaining = $manage->getdaysRemaining("7");
$sixDaysRemaining = $manage->getdaysRemaining("6");
$fiveDaysRemaining = $manage->getdaysRemaining("5");
$fourDaysRemaining = $manage->getdaysRemaining("4");*/
$threeDaysRemaining = $manage->getdaysRemaining("3");
$twoDaysRemaining = $manage->getdaysRemaining("2");
$oneDayRemaining = $manage->getdaysRemaining("1");
$zeroDayRemaining = $manage->getdaysRemaining("0");

/*var_dump($threeDaysRemaining);
exit;*/

if ($threeDaysRemaining != null) {
    $plan_expired = true;
    /*$inner_array1 = array();*/
    $days = "3";

    while ($row_data3 = mysqli_fetch_array($threeDaysRemaining)) {
        /*print_r($row_data4);
        exit;*/
        $name = $row_data3['name'];
        $contact_no = $row_data3['contact_no'];
        $email = $row_data3['email'];
        $expiry_date = $row_data3['expiry_date'];
        $user_start_date = $row_data3['user_start_date'];
        $user_id = $row_data3['user_id'];
        $verified_email_status = $row_data3['verified_email_status'];
        /* array_push($inner_array1, $row_data1['email']);*/
        $subject = "Dear " . $name . " Expiration of digital card";
        $email_message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <style>
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
    <title>Share Digital card</title>
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
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0;">
                        <img border="0" vspace="0" hspace="0" src="https://sharedigitalcard.com/assets/img/logo/logo.png" alt="Share digital card" width="200" style="border: none; color: #333333; display: block; font-size: 13px; margin:10px 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 20%; -ms-interpolation-mode: bicubic;"/>
                        <hr>
                    </td>
                </tr>
                <!-- CONTENT -->
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <h3 style="color: #d1272e;  font-size: 18px; font-weight: 600; line-height: 140%; margin: 20px 0 -5px 0; padding: 0;text-align: center;margin: 0 auto">Share Digital Card</h3>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">Dear <strong>' . ucwords($name) . '</strong>.</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">This email regarding the expiry of share digital card. Your ending date is <strong>' . $expiry_date . '</strong>. </p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">You have only ' . $days . ' days remaining. To renew your digital card click on following link <a href="https://sharedigitalcard.com/payment.php?token=' . $security->encryptWebservice($user_id) . '">Renew now</a></p><br><br>

                    </td>
                </tr>
                <tr><td colspan="2" style="text-align:center">
                    <a href="http://sharedigitalcard.com/login.php" style="padding: 6px 0 1px 0px;
            border-radius: 3px;
            margin: 0;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 700;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
            color: white;;color:white; border-radius: 4px;">
                        <img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="' . $btn . 'color: white;">Click To Login</a>
                </td></tr>
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <p style="font-size: 10px; font-weight: 400; line-height: 100%; color: #333333; font-family: Arial, sans-serif;">&nbsp;</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">For any query email us on <a href="support@sharedigitalcard.com" target="_blank">support@sharedigitalcard.com</a></p>
                    </td>
                </tr>
                <tr><td colspan="2" style="padding: 10px;background: #fff;height: 100px;">
                    <div style="width: 85%;margin: 0 auto;">
                        <div style=" width: 100%;margin: 0 auto;">
                            <div style="text-align:center">
                                <a href="https://www.facebook.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
                                <a href="https://www.instagram.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
                                <a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                            </div>
                        </div>
                    </div>
                </td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

        $toEmail = $email;
        $toName = $name;
        $message = "You have only " . $days . " days remaining of your digital card. Click here to renew now. sharedigitalcard.com/payment.php?token=" . $security->encryptWebservice($user_id);
        if ($verified_email_status == 1) {
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $email_message);
        } else {
            $sendMail = true;
        }
        if ($sendMail) {
            $response["success"] = false;
            $response['message'] = "Mail has been sent";
            $send_sms = $manage->sendSMS($contact_no, $message);
            if (!$send_sms) {
                $response["success"] = true;
                $response['message'] = "Issue while sending sms";
            } else {
                $response["success"] = false;
                $response['message'] .= "sms has been sent";
            }
        } else {
            $response["success"] = true;
            $response['message'] = "Issue while sending email";
        }
    }

} else {
    $response["success"] = true;
    $response['message'] = "No data found";
}

if ($twoDaysRemaining != null) {
    $plan_expired = true;
    /*$inner_array1 = array();*/
    $days = "2";
    while ($row_data2 = mysqli_fetch_array($twoDaysRemaining)) {
        $name = $row_data2['name'];
        $contact_no = $row_data2['contact_no'];
        $email = $row_data2['email'];
        $expiry_date = $row_data2['expiry_date'];
        $user_start_date = $row_data2['user_start_date'];
        $user_id = $row_data2['user_id'];
        $verified_email_status = $row_data2['verified_email_status'];
        /* array_push($inner_array1, $row_data1['email']);*/
        $subject = "Dear " . $name . " Expiration of digital card";
        $email_message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <style>
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
    <title>Share Digital card</title>
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
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0;">
                        <img border="0" vspace="0" hspace="0" src="https://sharedigitalcard.com/assets/img/logo/logo.png" alt="Share digital card" width="200" style="border: none; color: #333333; display: block; font-size: 13px; margin:10px 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 20%; -ms-interpolation-mode: bicubic;"/>
                        <hr>
                    </td>
                </tr>
                <!-- CONTENT -->
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <h3 style="color: #d1272e;  font-size: 18px; font-weight: 600; line-height: 140%; margin: 20px 0 -5px 0; padding: 0;text-align: center;margin: 0 auto">Share Digital Card</h3>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">Dear <strong>' . ucwords($name) . '</strong>.</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">This email regarding the expiry of share digital card. Your ending date is <strong>' . $expiry_date . '</strong>. </p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">You have only ' . $days . ' days remaining. To check our subscription plans click on below link <a href="https://sharedigitalcard.com/payment.php?token=' . $security->encryptWebservice($user_id) . '">https://sharedigitalcard.com/payment.php?token= ' . $security->encryptWebservice($user_id) . '</a></p><br><br>

                    </td>
                </tr>
                <tr><td colspan="2" style="text-align:center">
                    <a href="http://sharedigitalcard.com/login.php" style="padding: 6px 0 1px 0px;
            border-radius: 3px;
            margin: 0;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 700;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
            color: white;;color:white; border-radius: 4px;">
                        <img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="' . $btn . 'color: white;">Click To Login</a>
                </td></tr>
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <p style="font-size: 10px; font-weight: 400; line-height: 100%; color: #333333; font-family: Arial, sans-serif;">&nbsp;</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">For any query email us on <a href="support@sharedigitalcard.com" target="_blank">support@sharedigitalcard.com</a></p>
                    </td>
                </tr>
                <tr><td colspan="2" style="padding: 10px;background: #fff;height: 100px;">
                    <div style="width: 85%;margin: 0 auto;">
                        <div style=" width: 100%;margin: 0 auto;">
                            <div style="text-align:center">
                                <a href="https://www.facebook.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
                                <a href="https://www.instagram.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
                                <a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                            </div>
                        </div>
                    </div>
                </td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';
        echo $email_message;
        exit;

        $toEmail = $email;
        $toName = $name;
        $message = "You have only " . $days . " days remaining of your digital card. Click here to renew now. sharedigitalcard.com/payment.php?token=" . $security->encryptWebservice($user_id);

        if ($verified_email_status == 1) {
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $email_message);
        } else {
            $sendMail = true;
        }
        if ($sendMail) {
            $response["success"] = false;
            $response['message'] = "Mail has been sent";
            $send_sms = $manage->sendSMS($contact_no, $message);
            if (!$send_sms) {
                $response["success"] = true;
                $response['message'] = "Issue while sending sms";
            } else {
                $response["success"] = false;
                $response['message'] .= "sms has been sent";
            }
        } else {
            $response["success"] = true;
            $response['message'] = "Issue while sending email";
        }
    }
} else {
    $response["success"] = true;
    $response['message'] = "No data found";
}

if ($oneDayRemaining != null) {
    $plan_expired = true;
    /*    $inner_array2 = array();*/
    $days = "1";
    while ($row_data1 = mysqli_fetch_array($oneDayRemaining)) {
        $name = $row_data1['name'];
        $contact_no = $row_data1['contact_no'];
        $email = $row_data1['email'];
        $expiry_date = $row_data1['expiry_date'];
        $user_start_date = $row_data1['user_start_date'];
        $user_id = $row_data1['user_id'];
        $verified_email_status = $row_data1['verified_email_status'];
        /* array_push($inner_array2, $row_data2['email']);*/
        $email_message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <style>
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
    <title>Share Digital card</title>
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
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0;">
                        <img border="0" vspace="0" hspace="0" src="https://sharedigitalcard.com/assets/img/logo/logo.png" alt="Share digital card" width="200" style="border: none; color: #333333; display: block; font-size: 13px; margin:10px 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 20%; -ms-interpolation-mode: bicubic;"/>
                        <hr>
                    </td>
                </tr>
                <!-- CONTENT -->
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <h3 style="color: #d1272e;  font-size: 18px; font-weight: 600; line-height: 140%; margin: 20px 0 -5px 0; padding: 0;text-align: center;margin: 0 auto">Share Digital Card</h3>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">Dear <strong>' . ucwords($name) . '</strong>.</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">This email regarding the expiry of share digital card. Your ending date is <strong>' . $expiry_date . '</strong>. </p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">You have only ' . $days . ' days remaining. To renew your digital card click on following link <a href="https://sharedigitalcard.com/payment.php?token=' . $security->encryptWebservice($user_id) . '">Renew now</a></p><br><br>

                    </td>
                </tr>
                <tr><td colspan="2" style="text-align:center">
                    <a href="http://sharedigitalcard.com/login.php" style="padding: 6px 0 1px 0px;
            border-radius: 3px;
            margin: 0;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 700;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
            color: white;;color:white; border-radius: 4px;">
                        <img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="' . $btn . 'color: white;">Click To Login</a>
                </td></tr>
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <p style="font-size: 10px; font-weight: 400; line-height: 100%; color: #333333; font-family: Arial, sans-serif;">&nbsp;</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">For any query email us on <a href="support@sharedigitalcard.com" target="_blank">support@sharedigitalcard.com</a></p>
                    </td>
                </tr>
                <tr><td colspan="2" style="padding: 10px;background: #fff;height: 100px;">
                    <div style="width: 85%;margin: 0 auto;">
                        <div style=" width: 100%;margin: 0 auto;">
                            <div style="text-align:center">
                                <a href="https://www.facebook.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
                                <a href="https://www.instagram.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
                                <a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                            </div>
                        </div>
                    </div>
                </td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

        $subject = "Dear " . $name . " Expiration of digital card";
        $toEmail = $email;
        $toName = $name;
        /* $subject = "Expiration of digital card";*/
        // $message = $days . " days of your digital card";
        // $message = " You have only " . $days . " days remaining for your digital card please click here to renew it.\n https://sharedigitalcard.com/login.php";
        $message = "You have only " . $days . " days remaining of your digital card. Click here to renew now. sharedigitalcard.com/payment.php?token=" . $security->encryptWebservice($user_id);
        if ($verified_email_status == 1) {
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $email_message);
        } else {
            $sendMail = true;
        }
        if ($sendMail) {
            $response["success"] = false;
            $response['message'] .= "Mail has been sent";
            $send_sms = $manage->sendSMS($contact_no, $message);
            if (!$send_sms) {
                $response["success"] = true;
                $response['message'] = "Issue while sending sms";
            } else {
                $response["success"] = false;
                $response['message'] .= "sms has been sent";
            }
        } else {
            $response["success"] = true;
            $response['message'] = "Issue while sending email";
        }
    }

} else {
    $response["success"] = true;
    $response['message'] = "No data found";
}


if ($zeroDayRemaining != null) {
    $plan_expired = true;
    /*    $inner_array2 = array();*/
    $days = "0";
    while ($row_data1 = mysqli_fetch_array($zeroDayRemaining)) {
        $name = $row_data1['name'];
        $contact_no = $row_data1['contact_no'];
        $email = $row_data1['email'];
        $expiry_date = $row_data1['expiry_date'];
        $user_start_date = $row_data1['user_start_date'];
        $user_id = $row_data1['user_id'];
        $verified_email_status = $row_data1['verified_email_status'];
        /* array_push($inner_array2, $row_data2['email']);*/
        $email_message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <style>
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
    <title>Share Digital card</title>
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
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 0;">
                        <img border="0" vspace="0" hspace="0" src="https://sharedigitalcard.com/assets/img/logo/logo.png" alt="Share digital card" width="200" style="border: none; color: #333333; display: block; font-size: 13px; margin:10px 0; max-width: 560px; padding: 0; outline: none; text-decoration: none; width: 20%; -ms-interpolation-mode: bicubic;"/>
                        <hr>
                    </td>
                </tr>
                <!-- CONTENT -->
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <h3 style="color: #d1272e;  font-size: 18px; font-weight: 600; line-height: 140%; margin: 20px 0 -5px 0; padding: 0;text-align: center;margin: 0 auto">Share Digital Card</h3>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">Dear <strong>' . ucwords($name) . '</strong>.</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">This email regarding the expiry of share digital card. Your ending date is <strong>' . $expiry_date . '</strong>. </p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">You have only ' . $days . ' days remaining. To renew your digital card click on following link <a href="https://sharedigitalcard.com/payment.php?token=' . $security->encryptWebservice($user_id) . '">Renew now</a></p><br><br>

                    </td>
                </tr>
                <tr><td colspan="2" style="text-align:center">
                    <a href="http://sharedigitalcard.com/login.php" style="padding: 6px 0 1px 0px;
            border-radius: 3px;
            margin: 0;
            color: #fbdedb;
            background-color: #fbdedb;
            display: inline-block;
            background: #e74c3c;
            -webkit-transition: 0.3s;
            -moz-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            font-family: sans-serif;
            font-weight: 700;
            font-size: .85em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            -webkit-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0em -0.3rem 0em rgba(0, 0, 0, 0.1) inset;
            position: relative;
            color: white;;color:white; border-radius: 4px;">
                        <img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="' . $btn . 'color: white;">Click To Login</a>
                </td></tr>
                <tr>
                    <td valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;">
                        <p style="font-size: 10px; font-weight: 400; line-height: 100%; color: #333333; font-family: Arial, sans-serif;">&nbsp;</p>
                        <p style="color: #333333; font-size: 15px; font-weight: 400; font-family: Arial, sans-serif; line-height: 160%;">For any query email us on <a href="support@sharedigitalcard.com" target="_blank">support@sharedigitalcard.com</a></p>
                    </td>
                </tr>
                <tr><td colspan="2" style="padding: 10px;background: #fff;height: 100px;">
                    <div style="width: 85%;margin: 0 auto;">
                        <div style=" width: 100%;margin: 0 auto;">
                            <div style="text-align:center">
                                <a href="https://www.facebook.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/fb.png"></a>
                                <a href="https://www.instagram.com/sharedigitalcard/" style="margin-right: 15px;"><img src="http://sharedigitalcard.com/user/assets/images/insta.png"></a>
                                <a href="https://www.youtube.com/watch?v=6T9Ia_2rsig&list=PLg1QyEHQ9MYYBRDxWqLrWLCyvJlSheqTh"><img src="http://sharedigitalcard.com/user/assets/images/yt.png"></a>
                            </div>
                        </div>
                    </div>
                </td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

        $subject = "Dear " . $name . " Expiration of digital card";
        $toEmail = $email;
        $toName = $name;
        /* $subject = "Expiration of digital card";*/
        // $message = $days . " days of your digital card";
        // $message = " You have only " . $days . " days remaining for your digital card please click here to renew it.\n https://sharedigitalcard.com/login.php";
        $message = "You have only " . $days . " days remaining of your digital card. Click here to renew now. sharedigitalcard.com/payment.php?token=" . $security->encryptWebservice($user_id);
        if ($verified_email_status == 1) {
            $sendMail = $manage->sendMail($toName, $toEmail, $subject, $email_message);
        } else {
            $sendMail = true;
        }
        if ($sendMail) {
            $response["success"] = false;
            $response['message'] .= "Mail has been sent";
            $send_sms = $manage->sendSMS($contact_no, $message);
            if (!$send_sms) {
                $response["success"] = true;
                $response['message'] = "Issue while sending sms";
            } else {
                $response["success"] = false;
                $response['message'] .= "sms has been sent";
            }
        } else {
            $response["success"] = true;
            $response['message'] = "Issue while sending email";
        }
    }

} else {
    $response["success"] = true;
    $response['message'] = "No data found";
}

echo json_encode($response);
?>