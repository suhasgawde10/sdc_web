<?php
include 'whitelist.php';
include "controller/ManageUser.php";
$manage = new ManageUser();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include 'sendMail/sendMail.php';
$error = false;
$errorMessage = "";
$controller = new Controller();
$con = $controller->connect();
$adminOTPMessage = "";
$adminErrorMessage = "";

// echo $security->decrypt("ZW5lamZr8523");
// 090516
//090516
// die();
// Dear Customer, 
// For login into the website or mobile application, Your One-Time Password (OTP) is {#var#}. Please do not share this OTP with anyone. Message ID: {#var#}
// Best Regards 
// DGCARD 

// https://www.alots.in/sms-panel/api/http/index.php?username=Kubic&apikey=FEAB9-F45CF&apirequest=Text&sender=DGCARD&mobile=9768904980&TemplateID=1207168182827606525&route=TRANS&format=JSON&message=Dear%20Customer%2C%20%0AFor%20login%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%201544555.%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$admin_otp_sms = generatePIN();
$user_otp_sms = generatePIN();
if (isset($_POST['send_otp'])) {
    $contact_no = $_POST['sms_contact'];

    $ContactNumberChecker = $manage->ContactNumberChecker($contact_no);/**/
    if($ContactNumberChecker){
        //  $sms_message = "Dear Customer, For login into the website or mobile application, Your One-Time Password (OTP) is.".$user_otp_sms.". Please do not share this OTP with anyone. Message ID: asdasd545454 Best Regards DGCARD";
        // $otp = 903904;
         $sms_message = "Dear%20Customer%2C%20%0AFor%20login%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".$user_otp_sms.".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
         $send_sms = $manage->sendSMSWithTemplateId($contact_no, $sms_message, TEMPLATE_LOGIN);
         //dd($send_sms);exit;
         $_SESSION['user_otp'] = $user_otp_sms;
         $adminErrorMessage = "OTP has been sent to your contact number please verify to login.<br>";
       $_SESSION['contact_no'] = $contact_no;  
    }
    else{
        $error = true;
        $errorMessage .= "Contact No. does not exist.<br>";

    }
    
    // $contact
    // dd($_POST);
}

$_SESSION['contact_login'] = "true";
if (isset($_POST['change_session_contact'])) {
    $_SESSION['contact_login'] = "true";   
}

if (isset($_POST['change_session_password'])) {
    unset($_SESSION['contact_login']);
    unset($_SESSION['user_otp']);
}

if (isset($_SESSION['email'])) {
    if ($_SESSION['type'] == "User") {
        header('location:user/basic-user-info.php');
    } elseif ($_SESSION['type'] == "Editor") {
        header('location:user/admin_dashboard.php');
    } elseif ($_SESSION['type'] == "Admin") {
        header('location:user/admin_dashboard.php');
    }
}


$date = date("Y-m-d");



//$admin_otp_sms = 123456;

$adminErrorMessage2 = "";


if (isset($_POST['cancel_login'])) {
    session_destroy();
    header('location:login.php');
}

if (isset($_POST['btn_send_otp'])) {
    if (isset($_SESSION['admin_email']) && is_numeric($_SESSION['admin_email'])) {
        //$sms_message = "Dear Customer,\n" . substr_replace($admin_otp_sms, '-', 3, 0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
        $sms_message = "Dear Customer, " . substr_replace($admin_otp_sms, '-', 3, 0) . " is your one time password - OTP. Please do not share this OTP with anyone for security reasons.";
        
        //$send_sms = $manage->sendSMS($_SESSION['admin_email'], $sms_message);
        $send_sms = $manage->sendSMSWithTemplateId($_SESSION['admin_email'], $sms_message, TEMPLATE_REGISTRATION);
        $_SESSION['admin_otp'] = $admin_otp_sms;
        $adminErrorMessage = "OTP has been sent to your contact number please verify to activate account.<br>";
    } else {
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
                        <label style="color: #646464;">Your OTP Is <br><span style="font-weight: bold;color: #646464;">' . substr_replace($admin_otp_sms, '-', 3, 0) . '</span></label>
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
        $subject = "OTP For Login From - sharedigitalcard.com";


        if(!$main_site){
            $subject = "OTP For Login";

            $sms_message = '<table style="width: 100%">
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
                        <label style="color: #646464;">Your OTP Is <br><span style="font-weight: bold;color: #646464;">' . substr_replace($admin_otp_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
</table>';

        }

        $send_sms = $manage->sendMail(MAIL_FROM_NAME, $_SESSION['admin_email'], $subject, $sms_message);
        $_SESSION['admin_otp'] = $admin_otp_sms;
        $adminErrorMessage = "OTP has been sent to your register email address please verify to activate account.<br>";
    }


}

if(isset($_POST['login_with_otp'])){
    // if($_SESS)
    $explode_otp = implode('', $_POST['sms_otp']);
    $sms_otp = trim($explode_otp);
    if ($sms_otp == $_SESSION['user_otp']) {
        $userSpecificResult = $manage->getUserProfileForOTPLogin($_SESSION['contact_no']);
        if ($userSpecificResult != null) {

            /*
             * while ($form_data = mysqli_fetch_array($result)) {
                   $email = $form_data["email"];
                   $user_id = $form_data["user_id"];
            }*/
            unset($_SESSION['contact_no']);
            unset($_SESSION['user_otp']);
            $name = $userSpecificResult["name"];
            $custom_url = $userSpecificResult["custom_url"];
            $contact = $userSpecificResult['contact_no'];
            $type = $userSpecificResult['type'];
            $status = $userSpecificResult['status'];
            $expiry_date = $userSpecificResult['expiry_date'];
            $email = $userSpecificResult['email'];
            $user_id = $userSpecificResult['user_id'];
            if ($status == 0) {
                $error = true;
                $errorMessage .= "You have been blocked.<br>";
            } elseif ($status == 2) {
                $_SESSION['user_deactivate'] = "true";
                $_SESSION['admin_email'] = $username;
                $_SESSION['admin_password'] = $pass;
                $error = true;
                $errorMessage .= "Your account was deactivated from your side. Please click below to reactive it,<br><a href='javascript:void(0)' onclick='sendThroughLinkOTP()' style='color: #2793e6;text-decoration: underline'>Activate Now</a>";
            } elseif ($status == 3) {
                $error = true;
                $errorMessage .= "Your account has been Deleted.<br>";
            } else {
                unset($_SESSION['dealer_login_type']); // dealer login
                $_SESSION['type'] = $type;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $security->encrypt($user_id);
                $_SESSION['name'] = $name;
                $_SESSION['contact'] = $contact;
                $_SESSION['custom_url'] = $custom_url;


                if ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor") {
                    /* $_SESSION['admin_contact'] = $contact;
                     $_SESSION['admin_email'] = $email;
                     $_SESSION['admin_password'] = $pass;
                     $array = explode(',', $contact);
                     $sms_message = "Dear Customer,\n" . $admin_otp_sms . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
                     foreach ($array as $key) {
                           $send_sms = $manage->sendSMS($key, $sms_message);
                     }
                     $_SESSION['admin_otp'] = $admin_otp_sms;
                     $adminOTPMessage .= "OTP has been sent.<br>";*/
                    unset($_SESSION["admin_otp"]);
                    header('location:user/admin_dashboard.php');
                } else {
                    $get_section = $manage->getSectionName();
                    if ($get_section != null) {
                        $_SESSION['menu'] = array('s_profile' => $get_section['profile'],
                            's_services' => $get_section['services'],
                            's_our_service' => $get_section['our_service'],
                            's_products' => $get_section['products'],
                            's_our_products' => $get_section['our_product'],
                            's_gallery' => $get_section['gallery'],
                            's_images' => $get_section['images'],
                            's_videos' => $get_section['videos'],
                            's_clients' => $get_section['clients'],
                            's_client_name' => $get_section['client_name'],
                            's_client_review_tab' => $get_section['client_review'],
                            's_team' => $get_section['team'],
                            's_our_team' => $get_section['our_team'],
                            's_bank' => $get_section['bank'],
                            's_payment' => $get_section['payment'],
                            's_basic_info' => $get_section['basic_info'],
                            's_company_info' => $get_section['company_info']);
                    } else {
                        $_SESSION['menu'] = array(
                            's_profile' => "Profile",
                            's_services' => "Services",
                            's_our_service' => "Our Services",
                            's_products' => "Products",
                            's_our_products' => "Our Products",
                            's_gallery' => "Gallery",
                            's_images' => "Images",
                            's_videos' => "Videos",
                            's_clients' => "Clients",
                            's_client_name' => "Clients",
                            's_client_review_tab' => "Client's Reviews",
                            's_team' => "Team",
                            's_our_team' => "Our Team",
                            's_bank' => "Bank",
                            's_payment' => "Payment",
                            's_basic_info' => "Basic Info",
                            's_company_info' => "Company Info");
                    }
                    /*if($user_id !=86){*/
                    $_SESSION['total_percent'] = 10;
                    $pending_dot = $manage->getPendingFormCount();
                    $_SESSION['red_dot'] = array();
                    if ($pending_dot['company_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => false));
                    }
                    if ($pending_dot['service_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => false));
                    }
                    if ($pending_dot['image_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => false));
                    }
                    if ($pending_dot['video_link'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => false));
                    }
                    if ($pending_dot['client_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => false));
                    }
                    if ($pending_dot['client_review'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => false));
                    }
                    if ($pending_dot['our_team'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => false));
                    }
                    if ($pending_dot['bank_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => false));
                    }
                    if ($pending_dot['upi_id'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => false));
                    }
                    /*     }else{
                             $_SESSION['total_percent'] = 100;
                             $_SESSION['red_dot'] =  array('company_name' =>false, 'service_name' =>false, 'image_name' =>false, 'video_link' =>false, 'client_name' =>false, 'client_review' =>false, 'our_team' =>false, 'bank_name' => false, 'upi_id' =>false);
                         }*/

                    // $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                    $three_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 2 days'));
                    $userSubs = $manage->displaySubscriptionDetailsByIdAlreadyIteate($user_id);
                    if ($userSubs != "") {
                        $plan_name = $userSubs['year'];
                    } else {
                        $plan_name = "plan";
                    }

                    if ($plan_name != 'Life Time') {
                        if (isset($_GET['view_invoice']) && $_GET['view_invoice'] != "") {
                            header('location:user/user-invoice.php?user_invoice_id=' . $_GET['view_invoice']);
                        }/* elseif ($expiry_date <= $three_day) {
                                header('location:user/plan-selection.php');
                            } elseif ($expiry_date <= $date) {
                                header('location:user/plan-selection.php');
                            }*/ else {
                            header('location:user/basic-user-info.php');
                        }
                    } else {
                        header('location:user/basic-user-info.php');
                    }

                }
            }
        } else {
            $error = true;
            $errorMessage .= "Invalid entered OTP";
        }
    }else{
        $error = true;
        $errorMessage .= "Invalid entered OTP";
    }
}


if (isset($_POST['btn_sign_in'])) {

    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $username = trim(mysqli_real_escape_string($con, $_POST['txt_email']));
    } else {
        $error = true;
        $errorMessage .= "Please enter Email.<br>";
    }

    if (isset($_POST['txt_password']) && $_POST['txt_password'] != "") {
        $pass = mysqli_real_escape_string($con, $_POST['txt_password']);
    } else {
        $error = true;
        $errorMessage .= "Please enter password.<br>";
    }

    // echo $security->decrypt("aG5taWVt8523");
    // die();

    if (!$error) {

        $userSpecificResult = $manage->getUserProfileForLogin($username, $security->encrypt($pass) . "8523");/**/
        if ($userSpecificResult != null) {

            /*
             * while ($form_data = mysqli_fetch_array($result)) {
                   $email = $form_data["email"];
                   $user_id = $form_data["user_id"];
            }*/

            $name = $userSpecificResult["name"];
            $custom_url = $userSpecificResult["custom_url"];
            $contact = $userSpecificResult['contact_no'];
            $type = $userSpecificResult['type'];
            $status = $userSpecificResult['status'];
            $expiry_date = $userSpecificResult['expiry_date'];
            $email = $userSpecificResult['email'];
            $user_id = $userSpecificResult['user_id'];
            if ($status == 0) {
                $error = true;
                $errorMessage .= "You have been blocked.<br>";
            } elseif ($status == 2) {
                $_SESSION['user_deactivate'] = "true";
                $_SESSION['admin_email'] = $username;
                $_SESSION['admin_password'] = $pass;
                $error = true;
                $errorMessage .= "Your account was deactivated from your side. Please click below to reactive it,<br><a href='javascript:void(0)' onclick='sendThroughLinkOTP()' style='color: #2793e6;text-decoration: underline'>Activate Now</a>";
            } elseif ($status == 3) {
                $error = true;
                $errorMessage .= "Your account has been Deleted.<br>";
            } else {
                unset($_SESSION['dealer_login_type']); // dealer login
                $_SESSION['type'] = $type;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $security->encrypt($user_id);
                $_SESSION['name'] = $name;
                $_SESSION['contact'] = $contact;
                $_SESSION['custom_url'] = $custom_url;


                if ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor") {
                    /* $_SESSION['admin_contact'] = $contact;
                     $_SESSION['admin_email'] = $email;
                     $_SESSION['admin_password'] = $pass;
                     $array = explode(',', $contact);
                     $sms_message = "Dear Customer,\n" . $admin_otp_sms . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
                     foreach ($array as $key) {
                           $send_sms = $manage->sendSMS($key, $sms_message);
                     }
                     $_SESSION['admin_otp'] = $admin_otp_sms;
                     $adminOTPMessage .= "OTP has been sent.<br>";*/
                    unset($_SESSION["admin_otp"]);
                    header('location:user/admin_dashboard.php');
                } else {
                    $get_section = $manage->getSectionName();
                    if ($get_section != null) {
                        $_SESSION['menu'] = array('s_profile' => $get_section['profile'],
                            's_services' => $get_section['services'],
                            's_our_service' => $get_section['our_service'],
                            's_products' => $get_section['products'],
                            's_our_products' => $get_section['our_product'],
                            's_gallery' => $get_section['gallery'],
                            's_images' => $get_section['images'],
                            's_videos' => $get_section['videos'],
                            's_clients' => $get_section['clients'],
                            's_client_name' => $get_section['client_name'],
                            's_client_review_tab' => $get_section['client_review'],
                            's_team' => $get_section['team'],
                            's_our_team' => $get_section['our_team'],
                            's_bank' => $get_section['bank'],
                            's_payment' => $get_section['payment'],
                            's_basic_info' => $get_section['basic_info'],
                            's_company_info' => $get_section['company_info']);
                    } else {
                        $_SESSION['menu'] = array(
                            's_profile' => "Profile",
                            's_services' => "Services",
                            's_our_service' => "Our Services",
                            's_products' => "Products",
                            's_our_products' => "Our Products",
                            's_gallery' => "Gallery",
                            's_images' => "Images",
                            's_videos' => "Videos",
                            's_clients' => "Clients",
                            's_client_name' => "Clients",
                            's_client_review_tab' => "Client's Reviews",
                            's_team' => "Team",
                            's_our_team' => "Our Team",
                            's_bank' => "Bank",
                            's_payment' => "Payment",
                            's_basic_info' => "Basic Info",
                            's_company_info' => "Company Info");
                    }
                    /*if($user_id !=86){*/
                    $_SESSION['total_percent'] = 10;
                    $pending_dot = $manage->getPendingFormCount();
                    $_SESSION['red_dot'] = array();
                    if ($pending_dot['company_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => false));
                    }
                    if ($pending_dot['service_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => false));
                    }
                    if ($pending_dot['image_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => false));
                    }
                    if ($pending_dot['video_link'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => false));
                    }
                    if ($pending_dot['client_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => false));
                    }
                    if ($pending_dot['client_review'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => false));
                    }
                    if ($pending_dot['our_team'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => false));
                    }
                    if ($pending_dot['bank_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => false));
                    }
                    if ($pending_dot['upi_id'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => true));
                    } else {
                        $_SESSION['total_percent'] = $_SESSION['total_percent'] + 10;
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => false));
                    }
                    /*     }else{
                             $_SESSION['total_percent'] = 100;
                             $_SESSION['red_dot'] =  array('company_name' =>false, 'service_name' =>false, 'image_name' =>false, 'video_link' =>false, 'client_name' =>false, 'client_review' =>false, 'our_team' =>false, 'bank_name' => false, 'upi_id' =>false);
                         }*/

                    // $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                    $three_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 2 days'));
                    $userSubs = $manage->displaySubscriptionDetailsByIdAlreadyIteate($user_id);
                    if ($userSubs != "") {
                        $plan_name = $userSubs['year'];
                    } else {
                        $plan_name = "plan";
                    }

                    if ($plan_name != 'Life Time') {
                        if (isset($_GET['view_invoice']) && $_GET['view_invoice'] != "") {
                            header('location:user/user-invoice.php?user_invoice_id=' . $_GET['view_invoice']);
                        }/* elseif ($expiry_date <= $three_day) {
                                header('location:user/plan-selection.php');
                            } elseif ($expiry_date <= $date) {
                                header('location:user/plan-selection.php');
                            }*/ else {
                            header('location:user/basic-user-info.php');
                        }
                    } else {
                        header('location:user/basic-user-info.php');
                    }

                }
            }
        } else {
            $error = true;
            $errorMessage .= "Invalid username and password";
        }
    }
}


if (isset($_POST['resend_otp'])) {
    if (isset($_SESSION['admin_email']) && is_numeric($_SESSION['admin_email'])) {
        $sms_message = "Dear Customer,\n" . substr_replace($admin_otp_sms, '-', 3, 0) . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
        $send_sms = $manage->sendSMS($_SESSION['admin_email'], $sms_message);
        $_SESSION['admin_otp'] = $admin_otp_sms;
        $adminErrorMessage .= "OTP has been re-sent to your contact number please verify to activate account.<br>";
    } else {
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
                        <label style="color: #646464;">Your OTP Is <br><span style="font-weight: bold;color: #646464;">' . substr_replace($admin_otp_sms, '-', 3, 0) . '</span></label>
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
        $subject = "OTP For Login From - sharedigitalcard.com";

        if(!$main_site){
            $subject = "OTP For Login";
            $sms_message = '<table style="width: 100%">
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
                        <label style="color: #646464;">Your OTP Is <br><span style="font-weight: bold;color: #646464;">' . substr_replace($admin_otp_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
</table>';
        }

        $send_sms = $manage->sendMail(MAIL_FROM_NAME, $_SESSION['admin_email'], $subject, $sms_message);
        $_SESSION['admin_otp'] = $admin_otp_sms;
        $adminErrorMessage .= "OTP has been re-sent to your email address please verify to activate account.<br>";
    }
}

if (isset($_POST['verify_otp'])) {
    $explode_otp = implode('', $_POST['sms_otp']);
    $sms_otp = trim($explode_otp);
    if ($sms_otp == $_SESSION['admin_otp']) {
        $userSpecificResult = $manage->getUserProfileForLogin($_SESSION['admin_email'], $security->encrypt($_SESSION['admin_password']) . "8523");/**/
        if ($userSpecificResult) {
            $name = $userSpecificResult["name"];
            $custom_url = $userSpecificResult["custom_url"];
            $contact = $userSpecificResult['contact_no'];
            $type = $userSpecificResult['type'];
            $status = $userSpecificResult['status'];
            $expiry_date = $userSpecificResult['expiry_date'];
            $email = $userSpecificResult['email'];
            $user_id = $userSpecificResult['user_id'];
            $_SESSION['id'] = $security->encrypt($user_id);
            $update_status = $manage->deactivateUserAccount("", "", "activated", 1);
            if ($update_status) {
                $_SESSION['type'] = $type;
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name;
                $_SESSION['contact'] = $contact;
                $_SESSION['custom_url'] = $custom_url;
                unset($_SESSION["admin_otp"]);
                unset($_SESSION['user_deactivate']);
                unset($_SESSION['admin_password']);
                unset($_SESSION['admin_email']);
                if ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor") {
                    /* $_SESSION['admin_contact'] = $contact;
                     $_SESSION['admin_email'] = $email;
                     $_SESSION['admin_password'] = $pass;
                     $array = explode(',', $contact);
                     $sms_message = "Dear Customer,\n" . $admin_otp_sms . " is your one time password (OTP). Please do not share this OTP with anyone for security reasons.";
                     foreach ($array as $key) {
                           $send_sms = $manage->sendSMS($key, $sms_message);
                     }
                     $_SESSION['admin_otp'] = $admin_otp_sms;
                     $adminOTPMessage .= "OTP has been sent.<br>";*/

                    header('location:user/admin_dashboard.php');
                } else {
                    $get_section = $manage->getSectionName();
                    if ($get_section != null) {
                        $_SESSION['menu'] = array('s_profile' => $get_section['profile'],
                            's_services' => $get_section['services'],
                            's_our_service' => $get_section['our_service'],
                            's_gallery' => $get_section['gallery'],
                            's_images' => $get_section['images'],
                            's_videos' => $get_section['videos'],
                            's_clients' => $get_section['clients'],
                            's_client_name' => $get_section['client_name'],
                            's_client_review_tab' => $get_section['client_review'],
                            's_team' => $get_section['team'],
                            's_our_team' => $get_section['our_team'],
                            's_bank' => $get_section['bank'],
                            's_payment' => $get_section['payment'],
                            's_basic_info' => $get_section['basic_info'],
                            's_company_info' => $get_section['company_info']);
                    } else {
                        $_SESSION['menu'] = array(
                            's_profile' => "Profile",
                            's_services' => "Services",
                            's_our_service' => "Our Services",
                            's_gallery' => "Gallery",
                            's_images' => "Images",
                            's_videos' => "Videos",
                            's_clients' => "Clients",
                            's_client_name' => "Clients",
                            's_client_review_tab' => "Client's Reviews",
                            's_team' => "Team",
                            's_our_team' => "Our Team",
                            's_bank' => "Bank",
                            's_payment' => "Payment",
                            's_basic_info' => "Basic Info",
                            's_company_info' => "Company Info");
                    }
                    $_SESSION['total_percent'] = $manage->getUserProfilePercent();
                    $pending_dot = $manage->getPendingFormCount();
                    $_SESSION['red_dot'] = array();
                    if ($pending_dot['company_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => false));
                    }
                    if ($pending_dot['service_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => false));
                    }
                    if ($pending_dot['image_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => false));
                    }
                    if ($pending_dot['video_link'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => false));
                    }
                    if ($pending_dot['client_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => false));
                    }
                    if ($pending_dot['client_review'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => false));
                    }
                    if ($pending_dot['our_team'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team' => false));
                    }
                    if ($pending_dot['bank_name'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => false));
                    }
                    if ($pending_dot['upi_id'] == "") {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => true));
                    } else {
                        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => false));
                    }

                    // $five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));
                    $three_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 2 days'));
                    $userSubs = $manage->displaySubscriptionDetailsByIdAlreadyIteate($user_id);
                    if ($userSubs != "") {
                        $plan_name = $userSubs['year'];
                    } else {
                        $plan_name = "plan";
                    }
                    if ($plan_name != 'Life Time') {
                        if (isset($_GET['view_invoice']) && $_GET['view_invoice'] != "") {
                            header('location:user/user-invoice.php?user_invoice_id=' . $_GET['view_invoice']);
                        } elseif ($expiry_date <= $three_day) {
                            header('location:user/plan-selection.php');
                        } elseif ($expiry_date <= $date) {
                            header('location:user/plan-selection.php');
                        } else {
                            header('location:user/basic-user-info.php');
                        }
                    } else {
                        header('location:user/basic-user-info.php');
                    }

                }
            } else {
                $adminErrorMessage2 .= "Issue while updating status please try after some time.<br>";
            }
        } else {
            $adminErrorMessage2 .= "Invalid Username and password.<br>";
        }
    } else {
        $adminErrorMessage2 .= "OTP Mismatched<br>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Login | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Sign in to digital card,log in to digital business card,Digital card is online digital representation of your profile, includes your personal information, bank details, and many more">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, login, sign in">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Mobile Specific Meta  -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <!-- Animation Css -->
    <link href="user/assets/plugins/animate-css/animate.css" rel="stylesheet"/>
    <!-- Custom Css -->
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .margin_top_div {
            margin-top: 30px;
        }
    </style>
</head>

<body>
<!-- preloader area start -->

<!-- preloader area end -->
<!-- header area start -->



<?php
// if ($main_site) {
    include "assets/common-includes/header.php";
// }

?>
<!-- header area end -->
<section class="feature-area  padding_section <?php if ($main_site) {
    echo "bg-gray margin_top_div background_login";
} else {
    echo "login-center";
} ?> " id="feature">
<div class="container-fluid">
<div class="row">
<?php
if ($main_site) {
    ?>
    <div class="col-md-8 col-sm-6 col-xs-12">
        <div class="hidden-sm hidden-xs col-md-10 col-md-offset-1">
            <div style="position: relative; margin-top: 5%">

                <a href="https://www.youtube.com/embed/ieG23aRsrl8" target="_blank"><img
                        src="assets/img/loginpage.png"></a>
                <iframe id="sign-in-video"
                        src="https://www.youtube.com/embed/ieG23aRsrl8">
                </iframe>

            </div>
        </div>
    </div>
<?php
}
?>
<div class="<?php if ($main_site) {
    echo "custom-reg-width";
} ?> hidden-sm margin_top_div col-xs-12">
    <div class="card_width card">
        <div class="body">
            <form id="sign_in" method="POST">
                <?php if ($error) {
                    ?>
                    <div class="alert alert-danger">
                        <?php if (isset($errorMessage)) echo $errorMessage; ?>
                    </div>
                <?php
                } else if ($error && $errorMessage != "") {
                    ?>
                    <div class="alert alert-success">
                        <?php if (isset($errorMessage)) {
                            echo $errorMessage;
                        } ?>
                    </div>
                <?php
                }
                if (isset($adminOTPMessage) && $adminOTPMessage != "") {
                    ?>
                    <div class="alert alert-success">
                        <?php if (isset($adminOTPMessage)) {
                            echo $adminOTPMessage;
                        } ?>
                    </div>
                <?php
                }
                if (isset($adminErrorMessage) && $adminErrorMessage != "") {
                    ?>
                    <div class="alert alert-success">
                        <?php
                        if (isset($adminErrorMessage)) echo $adminErrorMessage;
                        ?>
                    </div>
                <?php
                } elseif (isset($adminErrorMessage2) && $adminErrorMessage2 != "") {
                    ?>
                    <div class="alert alert-danger">
                        <?php
                        if (isset($adminErrorMessage2)) echo $adminErrorMessage2;
                        ?>
                    </div>
                <?php
                }
                if (isset($_GET['resetpassword']) && $_GET['resetpassword'] == "true") {
                    ?>
                    <div class="alert alert-success fade-out">
                        Temporary password has been sent.
                    </div>
                    <!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> -->
                <?php
                }
                if (isset($_SESSION['contact_login']) && $_SESSION['contact_login'] == "true") {
                    if(isset($_SESSION['user_otp']) && $_SESSION['user_otp'] != ''){
                        ?>

                        <div class="sms_otp_box">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="otp_section">
                                    <div class="digit-group">
                                        <input class="send_textbox" type="number" id="digit-1" name="sms_otp[]" data-next="digit-2" onkeypress="return isNumberKey(event)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1">
                                        <input class="send_textbox" type="number" id="digit-2" name="sms_otp[]" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1">
                                        <input class="send_textbox" type="number" id="digit-3" name="sms_otp[]" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1">
                                        <span class="splitter">–</span>
                                        <input class="send_textbox" type="number" id="digit-4" name="sms_otp[]" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1">
                                        <input class="send_textbox" type="number" id="digit-5" name="sms_otp[]" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1">
                                        <input class="send_textbox" type="number" id="digit-6" name="sms_otp[]" data-previous="digit-5" onkeypress="return isNumberKey(event)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="1">
                                    </div>
                                </div>
                                <!-- <div class="form-line">
                                     <input type="number" name="sms_otp" class="form-control" placeholder="OTP"
                                            autofocus onkeypress="return isNumberKey(event)"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="4">

                                </div>-->
                            </div>
                        </div>
                        <?php

                    }else{
                ?>
                <h5>Login with OTP</h5>
                    <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">phone</i>
                                </span>
                                <div class="form-line">
                                    <input type="number" id="contact_no" name="sms_contact" class="form-control" value="" placeholder="Contact Number" autofocus  autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10">
                                </div>
                            </div>
                <?php
                    }
                }else{
                ?>
                <div class="msg p-0">
                    <h6 class="text-center">SIGN IN</h6>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input type="text" class="form-control" placeholder="Email / Contact no" name="txt_email" autofocus autocomplete="off" value="<?php if (isset($_POST['txt_email'])) { echo $_POST['txt_email']; } elseif (isset($_SESSION['admin_email'])) echo $_SESSION['admin_email']; ?>">
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input type="password" class="form-control" placeholder="Password" name="txt_password" autofocus value="<?php if (isset($_SESSION['admin_password'])) echo $_SESSION['admin_password']; ?>">
                    </div>
                </div>
                <?php
                }
                if (isset($_SESSION['admin_otp'])) {
                    ?>
                    <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                        <div class="otp_section">
                            <div class="digit-group">
                                <input class="send_textbox" type="number" id="digit-1" name="sms_otp[]"
                                       data-next="digit-2" onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-2" name="sms_otp[]"
                                       data-next="digit-3" data-previous="digit-1"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-3" name="sms_otp[]"
                                       data-next="digit-4" data-previous="digit-2"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <span class="splitter">&ndash;</span>
                                <input class="send_textbox" type="number" id="digit-4" name="sms_otp[]"
                                       data-next="digit-5" data-previous="digit-3"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-5" name="sms_otp[]"
                                       data-next="digit-6" data-previous="digit-4"
                                       onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                                <input class="send_textbox" type="number" id="digit-6" name="sms_otp[]"
                                       data-previous="digit-5" onkeypress="return isNumberKey(event)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                       maxlength="1"/>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row">
                    <?php
                    if(!isset($_SESSION['contact_login'])){
                        if (!isset($_SESSION['admin_otp'])) {
                            ?>
                            <?php
                            ?>
                            <div class="col-xs-12">
                                <button class="btn btn-block bg-pink waves-effect"
                                    <?php
                                    if (isset($_SESSION['user_deactivate']) && $_SESSION['user_deactivate'] == "true") {
                                        echo 'name="btn_send_otp"';
                                    } else {
                                        echo 'name="btn_sign_in"';
                                    }
                                    ?>>SIGN IN
                                </button>

                            </div>
                            <!-- <?php
                            //     }
                            ?> -->
                        <?php
                        }
                    }else{
                        if(isset($_SESSION['user_otp']) && $_SESSION['user_otp'] != ''){
                            ?>
                                <button type="submit" class="btn btn-block bg-pink waves-effect" name="login_with_otp" style="margin-bottom: 15px">Login</button>
                            <?php
                        }else{

                        ?>

                        <button type="submit" class="btn btn-block bg-pink waves-effect" name="send_otp" style="margin-bottom: 15px">Send OTP</button>
                        <?php
                        }
                    }

                    if (isset($_SESSION['admin_otp'])) {
                        ?>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block bg-pink waves-effect" name="verify_otp" style="margin-bottom: 15px">Verify OTP</button>
                            <button type="submit" class="resend_otp" name="resend_otp">Resend OTP</button>
                            <button type="submit" class="resend_otp" name="cancel_login"
                                    style="float: left; text-decoration: underline">Cancel
                            </button>
                            <br>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                
            </form>
            <form method="post" action="">
                            <?php
                            if (isset($_SESSION['contact_login']) && $_SESSION['contact_login'] == "true") {
                                    ?>
                                    <div style="float: left;">
                                        <button class="resend_otp" type="submit" name="change_session_password" style="color: #2793e6;font-size: 13px;float: left;padding-left: 0">Login with Password </button>
                                    </div>
                                    <?php 
                            }else{
                                ?>
                                <div style="float: left;">
                                    <button class="resend_otp" type="submit" name="change_session_contact" style="color: #2793e6;font-size: 13px;float: left;padding-left: 0">Login with OTP </button>
                                </div>
                             <?php
                            }
                            ?>
                </form>
                <div style="float: right;">
                    <a href="forgot-password<?php echo $extension; ?>">Forgot Password?</a>
                </div>
        </div>
    </div>
    <?php
    if ($main_site) {
        ?>
        <div class="card_width card_bottom card">
            <div class="body">
                <h5>Create An Account</h5>

                <p>In order to create your own personalise card for your business. <b>"Go Paperless, Go
                        Digital"</b></p>
                <a class="btn btn-block bg-pink waves-effect" href="register.php">Create an account</a>
            </div>
        </div>
    <?php
    }
    ?>
</div>
</div>
</div>

</section>


<!-- footer area start -->
<?php
if ($main_site) {
    include "assets/common-includes/footer.php";
}
?>
<!-- footer area end -->

<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script src="user/assets/plugins/node-waves/waves.js"></script>
<script src="user/assets/js/admin.js"></script>
<script>

    $('.digit-group').find('input').each(function () {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function (e) {
            var parent = $($(this).parent());

            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if (next.length) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
</script>
<script type="text/javascript">
    function sendThroughLinkOTP() {
        $('button[name=btn_send_otp]')[0].click();
    }
    $(document).ready(function() {
        // Automatically fade out the alert after 3 seconds
        setTimeout(function() {
            $(".fade-out").fadeOut("slow");
        }, 5000);
    });
</script>
</body>

</html>