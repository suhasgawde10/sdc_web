<?php
ob_start();
error_reporting(0);
ini_set('memory_limit', '-1');
date_default_timezone_set("Asia/Kolkata");
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$controller = new Controller();
$con = $controller->connect();
header('Content-Type: text/html; charset=utf-8');
/*if(isset($_SESSION['tmp_email']) && $_SESSION['tmp_email'] == "true"){
    unset($_SESSION['tmp_email']);
}*/
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include("android-login.php");
$maxsize = 4194304;
include_once('lib/ImgCompressor.class.php');
$error = false;
$errorMessage = "";
$errorCompany = false;
$errorCMessage = "";
$errorFile = false;
$errorMessageFile = "";
$emailError = false;
$emailErrorMessage = "";
$contactError = false;
$contactErrorMessage = "";
include("session_includes.php");
$number_verified = false;
/*@session_start() ;
session_destroy() ;*/

$drp_other = false;
/*echo $_SESSION['user_code'];
die();*/

$date = date('Y-m-d');
$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$ip_aadr = (string)$xml->geoplugin_request;

$current_city = (string)$xml->geoplugin_city;
$current_region = (string)$xml->geoplugin_region;
$countryName = (string)$xml->geoplugin_countryName;


$userSpecificResult = $manage->selectTheme();
if ($userSpecificResult != null) {
    $expiry_date = $userSpecificResult['expiry_date'];
    $get_email_count = $userSpecificResult['email_count'];
    $country = $userSpecificResult['country'];
}

if (isset($_POST['cancel_button'])) {
    header('location:' . $page);
}
if ($expiry_date != "") {
    $notification_token = $userSpecificResult['user_notification'];
} else {
    $notification_token = "";
}


$active_tab = false;

if (isset($_GET['display_data'])) {
    $display_data = $security->decryptWebservice($_GET['display_data']);
    $form_data = $manage->getSpecificUserChildProfile($display_data);
    if ($form_data != null) {
        $name = $form_data['name'];
        $user_id = $form_data['user_id'];
        $email = $form_data['email'];
        $contact_no = $form_data['contact_no'];
        $designation = $form_data['designation'];
        $gender = $form_data['gender'];
        $date_of_birth = $form_data['date_of_birth'];
        $alter_contact_no = $form_data['altr_contact_no'];
        $website = $form_data['website_url'];
        $linked_in = $form_data['linked_in'];
        $youtube = $form_data['youtube'];
        $facebook = $form_data['facebook'];
        $twitter = $form_data['twitter'];
        $instagram = $form_data['instagram'];
        $map = $form_data['map_link'];
        $address = $form_data['address'];
        $custom_url = $form_data['custom_url'];
        $img_name = $form_data['img_name'];
        $keyword = $form_data['user_keyword'];
        $expiry_date = $form_data['expiry_date'];
        $company_name = $form_data['company_name'];
        $gst_no = $form_data['gst_no'];
        $pan_no = $form_data['pan_no'];
        $about_us = $form_data['about_company'];
        $our_mission = $form_data['our_mission'];
        $playstore = $form_data['playstore_url'];
        $whatsapp_no = $form_data['whatsapp_no'];
        $saved_email = $form_data['saved_email'];
        $business_category = $form_data['business_category'];

        if ($form_data['cover_pic'] != "") {
            $key_data = explode(',', $form_data['cover_pic']);
        } else {
            $key_data = 0;
        }


       /* if ($form_data['company_profile'] != "") {
            $companyPath = "uploads/" . $email . "/profile/" . $form_data['company_profile'];
        } else {
            $companyPath = $form_data['company_profile'];
        }*/
        $verified_number = $form_data['verify_number'];

        $state = $form_data['state'];
        $city = $form_data['city'];
        $locality = $form_data['locality'];
        $optional_status = $form_data['optional_status'];
    }
}else {
    $form_data = $manage->getSpecificUserProfile();
    if ($form_data != null) {
       /* $name = $form_data['name'];
        $designation = $form_data['designation'];
        $gender = $form_data['gender'];
        $date_of_birth = $form_data['date_of_birth'];
        $saved_email = $form_data['saved_email'];
       */
        $alter_contact_no = $form_data['altr_contact_no'];
        $website = $form_data['website_url'];
        $linked_in = $form_data['linked_in'];
        $youtube = $form_data['youtube'];
        $facebook = $form_data['facebook'];
        $twitter = $form_data['twitter'];
        $instagram = $form_data['instagram'];
        $map = $form_data['map_link'];
        $address = $form_data['address'];
        $custom_url = $form_data['custom_url'];
        $img_name = $form_data['img_name'];
        $keyword = $form_data['user_keyword'];
        $expiry_date = $form_data['expiry_date'];
        $company_name = $form_data['company_name'];
        $gst_no = $form_data['gst_no'];
        $pan_no = $form_data['pan_no'];
        $about_us = $form_data['about_company'];
        $our_mission = $form_data['our_mission'];
        $playstore = $form_data['playstore_url'];
        $whatsapp_no = $form_data['whatsapp_no'];
        $business_category = $form_data['business_category'];
        if ($form_data['cover_pic'] != "") {
            $key_data = explode(',', $form_data['cover_pic']);
        } else {
            $key_data = 0;
        }


        if ($form_data['company_profile'] != "") {
            $companyPath = "uploads/" . $email . "/profile/" . $form_data['company_profile'];
        } else {
            $companyPath = $form_data['company_profile'];
        }
        $verified_number = $form_data['verify_number'];

        $state = $form_data['state'];
        $city = $form_data['city'];
        $locality = $form_data['locality'];
        $optional_status = $form_data['optional_status'];
    }
}
$parentProfilePath = "uploads/" . $session_email . "/" . $email . "/profile/" . $form_data['img_name'];


if (isset($_POST['submit'])) {
    if ($_POST['custom_url_preview'] === $custom_url) {
        header('location:' . $_SERVER['PHP_SELF'].'?display_data='.$_GET['display_data'].'&action=edit');
    } else {
        $result = $manage->validateCustomUrl(trim($_POST['custom_url_preview']));
        if ($result) {
            $errorPreview = true;
            echo "<script>alert('custom url already exist')</script>";
            /* $errorPreviewMessage .="custom url already exist"; */
        }
        $removeCustomSpace = str_replace(' ', '-', $_POST['custom_url_preview']);
        if (!$errorPreview) {
            $update_custom_url = $manage->updateCustomUrlOfChild($user_id, $removeCustomSpace);
            $addLogFile = $manage->addCustomUrlLog($removeCustomSpace);
            if ($addLogFile) {
                /*header('location:basic-user-info.php');*/
                $toEmail = "" . $email . "";
                $subject = "Successfully changed the custom URL.";
                $sms_message = "Dear " . $name . ",\n";
                $sms_message .= "Your new digital card link is ready.\n";
                $sms_message .= SHARED_URL . $removeCustomSpace;
                $message = '<table style="width: 100%">
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
                    <p>Dear <span style="color:blue;">' . ucwords($name) . '</span>,<p>
                    <p> This mail is regarding successful updations of custom url link.</p>
                 <a href="sharedigitalcard.com/m/index.php?custom_url=' . $removeCustomSpace . '" style="' . $btn . ';background: #db5ea5 !important;width: 100%;color: #ffffff;border-radius: 4px;font-size: 16px;padding: 10px 0;">Open Your Digital Card</a>
                    <p>To do any changes in your "Share Digital Card " click on to below button to login to our web portal or you can change your details from mobile application.</p>
                </div>
</td>
</tr>
<tr><td colspan="2" style="text-align:center">
<a href="http://sharedigitalcard.com/login.php" style="' . $btn . ';color:white; border-radius: 4px;"><img src="http://sharedigitalcard.com/user/assets/images/laptop.png" style="width: 19%;display: inline-block;vertical-align: middle;padding-right: 5px;color: white;">Click To Login</a>
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
                // $sendMail = $manage->sendMail(MAIL_FROM_NAME, $toEmail, $subject, $message);
                //  $send_sms = $manage->sendSMS($contact_no, $sms_message);
                header('location:' . $_SERVER['PHP_SELF'].'?display_data='.$_GET['display_data'].'&action=edit');
            }
        }
    }
}



$five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));


$imgUploadStatus = false;
$fileUploadStatus = false;

/*This method used for update the Branch data*/
function GenerateAPIKey()
{
    $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
    return $key;
}
$api_key = GenerateAPIKey();
$page = end(explode('/', $_SERVER["REQUEST_URI"]));
if (isset($_POST['btn_update'])) {
    /*  if (isset($_POST['dob']) && $_POST['dob'] != "") {
          $validateDob = $validate->validDateChecker($_POST['dob'], "dd/mm/yyyy");
          if ($validateDob) {
              $dob = $_POST['dob'];
          }
      } else {
          $error = true;
          $errorMessage = 'Please enter date of birth.';
      }*/
    if (isset($_POST['basic-desiganation']) && $_POST['basic-desiganation'] != "") {
        $txt_designation = $_POST['basic-desiganation'];
    } else {
        $txt_designation = "";
    }
    if (isset($_POST['txt_state']) && $_POST['txt_state'] != "") {
        $state = $_POST['txt_state'];
    } else {
        $state = "";
    }
    if (isset($_POST['txt_city']) && $_POST['txt_city'] != "") {
        $city = $_POST['txt_city'];
    } else {
        $city = "";
    }
    if (isset($_POST['txt_locality']) && $_POST['txt_locality'] != "") {
        $locality = $_POST['txt_locality'];
    } else {
        $locality = "";
    }
    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
        $contact_no = $_POST['txt_contact'];
        $result = $manage->validateChildContactByID($contact_no,$user_id);
        if ($result) {
            $error = true;
            $errorMessage .= "Contact Number Already Exists!!";
        }
    } else {
        $error = true;
        $errorMessage .= "Please Enter Contact Number.<br>";
    }
    if (isset($_POST['saved_email']) && $_POST['saved_email'] != "") {
        /*if (!filter_var($_POST['saved_email'], FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $errorMessage .= "Invalid email format.<br>";
        }*/
        $saved_email = explode(',',$_POST['saved_email']);
        $result = $manage->validateChildEmailByID($saved_email[0],$user_id);
        if ($result) {
            $error = true;
            $errorMessage .= "Email ID Already Exists!!<br>";
        }
        $txt_email = $saved_email[0];
    } else {
        $error = true;
        $errorMessage .= "Please Enter Email.<br>";
    }
    if (isset($_POST['drp_business_category']) && $_POST['drp_business_category'] != "" && $_POST['drp_business_category'] != "other") {
        $drp_business_category = mysqli_real_escape_string($con, $_POST['drp_business_category']);
        $other_optional_status = 0;
    } elseif (isset($_POST['other_business']) && $_POST['other_business'] != "") {
        $other_optional_status = 1;
        $drp_business_category = mysqli_real_escape_string($con, $_POST['other_business']);
    } else {
        $error = true;
        $errorMessage .= "Please Select Business category.<br>";
    }

    if(isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4){
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/" . $txt_email . "/profile/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');
        $maxsize = 2097152;
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage .= "Please select valid file extension";
            }
        }
    }

    if (!$error) {
        if($txt_email !=$email){
            $oldname = 'uploads/' . $session_email . '/' . $email;
            $newname = 'uploads/' . $session_email . '/' . $txt_email;
            rename($oldname, $newname);
        }
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newimgname = "";
        if ($imgUploadStatus) {
            /*    $filename = '/path/to/foo.txt';
                if (file_exists($filename)) {
                    echo "The file $filename exists";
                } else {
                    echo "The file $filename does not exist";
                }*/
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $removeImgSpace = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $removeImgSpace;
                if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                    $success = compress_image($tmpFilePath, $tmpFilePath, null, null, 90);
                    if($success) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }
                    }
                }else{
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $error = true;
                        $errorMessage .= "Failed to upload file";
                    }
                }

            }
        }

        $date1 = date("Y-m-d");
        $date = date_create("$date1");
        date_add($date, date_interval_create_from_date_string("5 days"));
        $final_date = date_format($date, "Y-m-d");
        /*echo "here";
        die();*/
        $get_user_expiry_count = $manage->selectTheme();
        if ($get_user_expiry_count != null) {
            $update_user_count = $get_user_expiry_count['update_user_count'];
            $get_email_count = $get_user_expiry_count['email_count'];
        }
        $status = $manage->updateUserChildProfile($_POST['txt_name'], $txt_designation, $_POST["gender"], $_POST['dob'], $_POST['txt_alt_contact'], $_POST['basic_website'], $_POST['txt_linked'], $_POST['basic_youtube'], $_POST['basic_facebook'], $_POST['basic_twitter'], $_POST['txt_instagram'], $_POST['txt_map'], $_POST['basic-address'], $_POST['txt_keyword'], $_POST['txt_playstore'], $_POST['whatsapp_no'], $_POST['saved_email'], $state, $city, $locality, $drp_business_category, $other_optional_status,$removeImgSpace,$display_data);
        if ($status) {
            $update_login = $manage->updateChildLoginDetails($txt_email,$contact_no,$display_data);
            if (isset($_GET['display_data'])) {
                $display_data = $security->decryptWebservice($_GET['display_data']);
                $form_data = $manage->getSpecificUserChildProfile($display_data);
                if ($form_data != null) {
                    $name = $form_data['name'];
                    $user_id = $form_data['user_id'];
                    $email = $form_data['email'];
                    $contact_no = $form_data['contact_no'];
                    $designation = $form_data['designation'];
                    $gender = $form_data['gender'];
                    $date_of_birth = $form_data['date_of_birth'];
                    $alter_contact_no = $form_data['altr_contact_no'];
                    $website = $form_data['website_url'];
                    $linked_in = $form_data['linked_in'];
                    $youtube = $form_data['youtube'];
                    $facebook = $form_data['facebook'];
                    $twitter = $form_data['twitter'];
                    $instagram = $form_data['instagram'];
                    $map = $form_data['map_link'];
                    $address = $form_data['address'];
                    $custom_url = $form_data['custom_url'];
                    $img_name = $form_data['img_name'];
                    $keyword = $form_data['user_keyword'];
                    $expiry_date = $form_data['expiry_date'];
                    $company_name = $form_data['company_name'];
                    $gst_no = $form_data['gst_no'];
                    $pan_no = $form_data['pan_no'];
                    $about_us = $form_data['about_company'];
                    $our_mission = $form_data['our_mission'];
                    $playstore = $form_data['playstore_url'];
                    $whatsapp_no = $form_data['whatsapp_no'];
                    $saved_email = $form_data['saved_email'];
                    $business_category = $form_data['business_category'];
                    $parentProfilePath = "uploads/" . $session_email . "/" . $txt_email . "/profile/" . $form_data['img_name'];
                    if ($form_data['cover_pic'] != "") {
                        $key_data = explode(',', $form_data['cover_pic']);
                    } else {
                        $key_data = 0;
                    }


                    /* if ($form_data['company_profile'] != "") {
                         $companyPath = "uploads/" . $email . "/profile/" . $form_data['company_profile'];
                     } else {
                         $companyPath = $form_data['company_profile'];
                     }*/
                    $verified_number = $form_data['verify_number'];

                    $state = $form_data['state'];
                    $city = $form_data['city'];
                    $locality = $form_data['locality'];
                    $optional_status = $form_data['optional_status'];
                }
            }
                $error = false;
                $errorMessage = "Profile updated successfully";
        } else {
            $error = true;
            $errorMessage = "Issue while updating details please try again.";
        }
    }
}


if (isset($_POST['btn_insert'])) {
    /*  if (isset($_POST['dob']) && $_POST['dob'] != "") {
          $validateDob = $validate->validDateChecker($_POST['dob'], "dd/mm/yyyy");
          if ($validateDob) {
              $dob = $_POST['dob'];
          }
      } else {
          $error = true;
          $errorMessage = 'Please enter date of birth.';
      }*/
    if (isset($_POST['basic-desiganation']) && $_POST['basic-desiganation'] != "") {
        $txt_designation = $_POST['basic-desiganation'];
    } else {
        $txt_designation = "";
    }
    if (isset($_POST['txt_state']) && $_POST['txt_state'] != "") {
        $state = $_POST['txt_state'];
    } else {
        $state = "";
    }
    if (isset($_POST['txt_city']) && $_POST['txt_city'] != "") {
        $city = $_POST['txt_city'];
    } else {
        $city = "";
    }
    if (isset($_POST['txt_locality']) && $_POST['txt_locality'] != "") {
        $locality = $_POST['txt_locality'];
    } else {
        $locality = "";
    }
    if (isset($_POST['txt_contact']) && $_POST['txt_contact'] != "") {
        $contact_no = $_POST['txt_contact'];
        $result = $manage->validateChildContact($contact_no);
        if ($result) {
            $error = true;
            $errorMessage .= "Contact Number Already Exists!!<br>";
        }
    } else {
        $error = true;
        $errorMessage .= "Please Enter Contact Number.<br>";
    }
    if (isset($_POST['saved_email']) && $_POST['saved_email'] != "") {
     /*   if (!filter_var($_POST['saved_email'], FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $errorMessage .= "Invalid email format.<br>";
        }*/
        $saved_email = explode(',',$_POST['saved_email']);
        $result = $manage->validateChildEmail($saved_email[0]);
        if ($result) {
            $error = true;
            $errorMessage .= "Email ID Already Exists!!<br>";
        }
        $txt_email = $saved_email[0];
    } else {
        $error = true;
        $errorMessage .= "Please Enter Email.<br>";
    }
    if (isset($_POST['drp_business_category']) && $_POST['drp_business_category'] != "" && $_POST['drp_business_category'] != "other") {
        $drp_business_category = mysqli_real_escape_string($con, $_POST['drp_business_category']);
        $other_optional_status = 0;
    } elseif (isset($_POST['other_business']) && $_POST['other_business'] != "") {
        $other_optional_status = 1;
        $drp_business_category = mysqli_real_escape_string($con, $_POST['other_business']);
    } else {
        $error = true;
        $errorMessage .= "Please Select Business category.<br>";
    }

        if(isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4){
            $imgUploadStatus = true;
            $directory_name = "uploads/" . $session_email . "/" . $txt_email . "/profile/";
            $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG','.pdf','.doc','.docx','.PDF','.DOC','.DOCX');
            $maxsize = 2097152;
            $total = count($_FILES['upload']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension.<br>";
                }
            }
        }/*else{
                $error = true;
                $errorMessage .= 'Please upload file<br>';
        }*/
    if (!$error) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newimgname = "";
        if ($imgUploadStatus) {
            if(!file_exists("uploads/" . $session_email . "/" . $txt_email . "/profile/")) {
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/profile/", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/image-slider/", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/about-us/", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/service/", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/images/", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/testimonials/clients", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/testimonials/client_review", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/our-team/", 0777, true);
                mkdir("uploads/" . $session_email . "/" . $txt_email . "/logo/", 0777, true);
            }
            /*    $filename = '/path/to/foo.txt';
                if (file_exists($filename)) {
                    echo "The file $filename exists";
                } else {
                    echo "The file $filename does not exist";
                }*/
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $file_original_name . "$" . $randomNum . '.' . $file_extension;
                $removeImgSpace = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $removeImgSpace;

                if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                    $success = compress_image($tmpFilePath, $newPath, 60);
                    if(!$success) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                    }
                }else{
                    if (!move_uploaded_file($tmpFilePath, $newPath)) {
                        $error = true;
                        $errorMessage .= "Failed to upload file";
                    }
                }

            }
        }
        $date1 = date("Y-m-d");
        $date = date_create("$date1");
        date_add($date, date_interval_create_from_date_string("5 days"));
        $final_date = date_format($date, "Y-m-d");
        /*echo "here";
        die();*/
        $txt_custom_url = str_replace(' ', '-', trim($_POST['txt_name']));
        $result = $manage->validateCustomUrl(trim($txt_custom_url));
        if ($result) {
            $custom_url = $txt_custom_url . rand(1000, 100000);
        } else {
            $custom_url = $txt_custom_url;
        }
        if(!$error){
            if(isset($_GET['add']) && $_GET['add'] !='') {
                $year = $security->decryptWebservice($_GET['add']);
                $total_credit = $manage->getUserCreditByYear($year);
                if($total_credit !=null && $total_credit['credit_qty'] > 0){
                    if ($year == "1 year") {
                        $month = 12;
                    } else if ($year == "3 year") {
                        $month = 36;
                    } else if ($year== "5 year") {
                        $month = 60;
                    }else{
                        $month = "";
                    }
                    $user_expiry_date = null;

                    if($user_expiry_date == null && $month !="") {
                        $expiry_date_in_time = strtotime("+" . $month . " months", strtotime(date('Y-m-d')));
                        $expiry_date = date("Y-m-d", $expiry_date_in_time);
                    }else{
                        $expiry_date = "";
                    }

                    $getUserId = $manage->insertParentUserProfile($_POST['txt_name'], $custom_url, $txt_designation,
                        $_POST["gender"], $_POST['dob'], $_POST['txt_alt_contact'], $_POST['basic_website'],
                        $_POST['txt_linked'], $_POST['basic_youtube'], $_POST['basic_facebook'], $_POST['basic_twitter'],
                        $_POST['txt_instagram'], $_POST['txt_map'], $_POST['basic-address'], $removeImgSpace, $_POST['txt_keyword'],
                        $_POST['txt_playstore'], $_POST['whatsapp_no'], $_POST['saved_email'],$country, $state, $city, $locality,
                        $drp_business_category, $other_optional_status,$expiry_date);
                    if ($getUserId) {
                        if(!file_exists("uploads/" . $session_email . "/" . $txt_email . "/profile/")) {
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/profile/", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/image-slider/", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/about-us/", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/service/", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/images/", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/testimonials/clients", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/testimonials/client_review", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/our-team/", 0777, true);
                            mkdir("uploads/" . $session_email . "/" . $txt_email . "/logo/", 0777, true);
                        }
                        $remain_credit = $total_credit['credit_qty'] - 1;
                        $update_credit = $manage->updateUserCredit($remain_credit,$total_credit['id']);
                        $txt_password = "12345678";
                        $type = "Child";
                        $_SESSION['user_code'] = "ref100" . $getUserId;
                        $updateDealer = $manage->updateUserCode($getUserId);
                        $insertUser = $manage->addUserLogin($getUserId, $type, $txt_email, $contact_no, $security->encrypt($txt_password) . "8523", $api_key);/**/
                        if ($insertUser) {
                            $insertCustomUrl = $manage->addCustomUrl($getUserId, $custom_url);
                            $insertMenuBar = $manage->addMenuBar($getUserId);
                            $getSectionDetails = $manage->getSectionDetails();
                            if ($getSectionDetails != null) {
                                while ($result_data = mysqli_fetch_array($getSectionDetails)) {
                                    $sectionId = $result_data["id"];
                                    if($sectionId == 7){
                                        $p_dg_status = 0;
                                    }else{
                                        $p_dg_status = 1;
                                    }
                                    $insertUserSectionEntry = $manage->insertDefaultUserSectionEntry($getUserId, $sectionId,$p_dg_status);
                                }
                            }
                            $error = false;
                            $errorMessage = "Team Member Created successfully";
                        } else {
                            $error = true;
                            $errorMessage .= "Something went wrong!! Please try again later.";
                        }

                    } else {
                        $error = true;
                        $errorMessage = "Issue while inserting details please try again.";
                    }
                }else{
                    $error = true;
                    $errorMessage = "Please upgrade plan.";
                }
            }else{
                $error = true;
                $errorMessage = "Please select plan.";
            }
        }


    }
}


function addUrlParam($array)
{

    $url = $_SERVER['REQUEST_URI'];
    $val = "";
    if ($array != "") {
        foreach ($array as $name => $value) {
            if ($val != "") {
                $val .= "&" . $name . '=' . urlencode($value);
            } else {
                $val .= $name . '=' . urlencode($value);
            }
        }
    }
    if (strpos($url, '?') !== false) {
        $url .= '&' . $val;
    } else {
        $url .= '?' . $val;
    }
    return $url;
}

$get_business = $manage->getBusniessCategory();
$get_state = $manage->getStateCategory($country);
$errorPreview = false;
$errorPreviewMessage = "";


?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Basic Information</title>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">

    <link rel="stylesheet" href="assets/croppie/croppie.css">
    <?php include "assets/common-includes/header_includes.php" ?>

    <link rel="stylesheet" type="text/css" href="assets/css/component.css"/>
    <!-- CSS -->
    <!--<link href='assets/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>-->
    <style>
        #upload_image {
            display: none;
        }

        input[type="file"] {
            display: none;
        }

        #overlay {
            position: fixed; /* Sit on top of the page content */
            display: block; /* Hidden by default */
            width: 100%; /* Full width (cover the whole page) */
            height: 100%; /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
            z-index: 999; /* Specify a stack order in case you're using a different order for other elements */
        }

        section {
            position: relative;
        }
    </style>
    <script>
        import LiteEditor from 'lite-editor';

        const editor = new LiteEditor('.js-editor');
    </script>
</head>
<body>
<?php
if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>

        <div class="clearfix padding_bottom_46">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero_both">
                <div class="row margin_div_web">
                    <?php
                    if (isset($_GET['display_data'])) {

                        ?>
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-10 col-sm-9 custom_input padding_zero padding_zero_both">
                                    <div class="form-group form-float">
                                        <div class="form-line preview_holder">
                                            <form action="" method="post" id="custom_url">
                                                <div class="info_circle help">
                                                    <div class="info-box-url" style="display: none;">
                                                        <a href="#" class="close-button">×</a>
                                                        <img src="assets/images/preview.png">
                                                    </div>
                                                    <a class="help-button" href="#" title="Click to know more"><i
                                                                class="fas info_circle_color fa-info-circle"></i></a>
                                                </div>
                                                <input type="text" id="myInput" onkeypress="return RestrictSpace()"
                                                       name="custom_url_preview" class="form-control preview_padding"
                                                       placeholder="<?php echo SHARED_URL.$custom_url; ?>"
                                                       value="<?php if (isset($_GET['custom_url_id'])) {
                                                           echo $custom_url;
                                                       } else {
                                                           echo SHARED_URL. $custom_url;
                                                       } ?>" <?php if (!isset($_GET['custom_url_id'])) echo 'style="background: white"'; ?>>

                                                <div class="edit_icon">
                                                    <?php if (isset($_GET['custom_url_id'])) { ?>
                                                        <!--<button class="right_button" name="cancel_button"></button>-->
                                                        <a href="add_team_card.php?display_data=<?php echo $_GET['display_data']; ?>&action=edit"><i
                                                                    class="fas wrong_button1 fa-times"></i></a>
                                                        <button class="right_button" type="submit" name="submit"><i
                                                                    class="fas right_check1 fa-check"></i></button>
                                                        <?php
                                                    } else { ?>
                                                        <a class="fas edit_color fa-pencil-alt"
                                                           href="<?php echo $page ?>&custom_url_id=<?php echo $id; ?>"></a>
                                                        <?php
                                                    } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-3 preview_btn_margin">
                                <a title="copy URL" class="copy_button "
                                   onclick="setClipboard('<?php echo SHARED_URL.$session_custom_url_is; ?>')"><i
                                            class="fas fa-copy"></i> Copy URL</a>
                                <a title="Preview" target="_blank" class="preview_button"
                                   href="<?php echo SHARED_URL.$session_custom_url_is; ?>"><i
                                            class="fa fa-eye"></i>
                                    Preview</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="card">
                        <div class="body custom_card_padding">

                            <div class="clearfix">
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
                                <div class="col-md-12" style="margin-bottom: 0">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="manage_team_card.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                        </div>
                                    </div>
                                </div>
                                <form id="basic_user_info" method="POST" action=""
                                      enctype="multipart/form-data">
                                    <fieldset>
                                        <legend class="legend_font_size" align="left"> <?php if (isset($_GET['display_data'])) { ?>
                                                        Update User Details
                                            <?php } else { ?>
                                                Add User Details
                                            <?php } ?></legend>

                                        <ul class="profile-ul">
                                            <!--<li class="profile-pm-0">
                                                <div class="form-float text-align-profile" style="position: relative">
                                                    <div id="uploaded_image">
                                                        <img
                                                                src="<?php /*if (!file_exists($parentProfilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                                    echo "uploads/male_user.png";
                                                                } elseif (!file_exists($parentProfilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                                    echo "uploads/female_user.png";
                                                                } else {
                                                                    echo $parentProfilePath;
                                                                } */?>" class="profile_image">
                                                    </div>
                                                    <div class="contact-icon-btm">
                                                        <input type="file" name="upload_image" id="upload_image"
                                                               accept="image/*"/>
                                                        <a id="OpenImgUpload">
                                                            <div class="p-align"><i class="fas fa-camera"></i></div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>-->

                                            <li style="overflow: hidden">
                                     <!--   <div class="width-prf">
                                            <label class="form-label">Upload Profile</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input class="form-control" type="file" id="upload" name="upload[]" accept=".png, .jpg, .jpeg,.JPG,.PNG" >
                                                </div>
                                            </div>
                                        </div>-->
                                                <div class="form-group form-float">
                                                    <div class="row">
                                                        <div class="col-md-12 m_b_0" >
                                                            <label class="form-label">Upload Image</label><br>
                                                            <!--<input type="file" id="upload" name="upload[]"
                                                       multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"
                                                       value="<?php /*if (isset($filename)) echo $filename; */ ?>">-->
                                                            <input type="file" name="upload[]" id="file-7"
                                                                   class="inputfile inputfile-6"
                                                                   data-multiple-caption="{count} files selected" multiple
                                                                   onchange="readURL(this);"
                                                                   accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"/>
                                                            <label for="file-7"><span></span> <img
                                                                        class="input_choose_file blah"
                                                                        src=""
                                                                        alt=""/><strong
                                                                        class="input_choose_file">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                         height="17" viewBox="0 0 20 17">
                                                                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                                                                    </svg>
                                                                    Choose a file&hellip;</strong></label>

                                                        </div>
                                                        <div class="col-md-12 m_b_0">
                                                            <?php
                                                            echo FILE_NOTE;
                                                            ?>
                                                        </div>

                                                    </div>

                                                    <?php if (isset($_GET['display_data']) && $form_data['img_name'] != "") {

                                                        ?>
                                                        <img src="<?php if (!file_exists($parentProfilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                                    echo "uploads/male_user.png";
                                                                } elseif (!file_exists($parentProfilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                                    echo "uploads/female_user.png";
                                                                } else {
                                                                    echo $parentProfilePath;
                                                                } ?>" class="profile_image">
                                                    <?php
                                                    }
                                                    ?>
                                                    <div>
                                                    </div>

                                                </div>
                                    </li>
                                            <li>
                                                <div class="width-prf">
                                                    <label class="form-label">Name</label> <span
                                                            class="required_field">*</span>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input name="txt_name" class="form-control" placeholder="Enter Name"
                                                                   value="<?php if(isset($_POST['txt_name'])){
                                                                       echo $_POST['txt_name'];
                                                                   }elseif (isset($name)){ echo $name; }  ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="width-prf">
                                                    <label>Gender</label> <span class="required_field">*</span>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <select id="gender" name="gender"
                                                                    class="form-control gender_li">
                                                                <option name="">Select an option</option>
                                                                <option <?php if ($gender == 'Male') echo 'selected' ?>
                                                                        name="male">Male
                                                                </option>
                                                                <option <?php if ($gender == 'Female') echo 'selected' ?>
                                                                        name="female">Female
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="width-prf">
                                                    <label class="form-label">Designation</label> <!--<span
                                                                class="required_field">*</span>-->

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <input name="basic-desiganation"
                                                                   class="form-control"
                                                                   placeholder="Designation"
                                                                   value="<?php if(isset($_POST['basic-desiganation'])){ echo $_POST['basic-desiganation']; } elseif (isset($designation)) { echo $designation; } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <label class="control-label">Date of birth</label>
                                                    <!--                             <span class="required_field">*</span>-->

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="date" class="form-control " id="dob"
                                                                   name="dob"
                                                                   value="<?php
                                                                   if(isset($_POST['dob'])){
                                                                       echo $_POST['dob'];
                                                                   }elseif (isset($date_of_birth)){ echo $date_of_birth; } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="width-prf">
                                                    <label class="form-label">Display Email (In digital Card)</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                           <input type="text" name="saved_email"
                                                                   class="form-control"
                                                                   placeholder="Display Email"
                                                                   value="<?php
                                                                   if(isset($_POST['saved_email'])){
                                                                       echo $_POST['saved_email'];
                                                                   }elseif (isset($saved_email) && $saved_email != "") {
                                                                       echo $saved_email;
                                                                   } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="user_alt_contact">
                                                <div class="width-prf">
                                                    <label class="form-label">Contact Number</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <input type="number" name="txt_contact"
                                                                   class="form-control"

                                                                   placeholder="Contact Number"
                                                                   value="<?php if(isset($_POST['txt_contact'])){ echo $_POST['txt_contact']; } elseif (isset($contact_no)){ echo $contact_no; } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="width-prf">
                                                    <label>Business Category</label>
                                                    <!--<span class="required_field">*</span>-->
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <select id="business_select"
                                                                    name="drp_business_category"
                                                                    data-live-search="true"
                                                                    class="gender_li form-control"
                                                                    onchange="UIHideShow(this.value)">
                                                                <option value="">Select an option</option>
                                                                <option value="other" <?php if ((isset($optional_status) && $optional_status == 1)) echo 'selected="selected"'; ?>>Other
                                                                </option>
                                                                <?php
                                                                if ($get_business != null) {
                                                                    while ($get_data = mysqli_fetch_array($get_business)) {
                                                                        ?>
                                                                        <option <?php if (isset($business_category) && (isset($optional_status) && $optional_status == 0) && $business_category == $get_data['business_category']) echo 'selected' ?>><?php echo $get_data['business_category']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group <?php if (!$drp_other) echo "other_input"; ?> form-float"
                                                         style="display: none">
                                                        <div class="form-line">
                                                            <input type="text" class="form-control"
                                                                   value="<?php if (isset($business_category)) echo $business_category ?>"
                                                                   placeholder="Other Business Category Name"
                                                                   name="other_business">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="user_alt_contact">
                                                <div class="width-prf">
                                                    <label class="form-label">Alternate Contact</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <input type="number" name="txt_alt_contact"
                                                                   class="form-control"
                                                                   placeholder="Alternet Contact Number"
                                                                   value="<?php if(isset($_POST['txt_alt_contact'])){
                                                                       echo $_POST['txt_alt_contact'];
                                                                   } elseif (isset($alter_contact_no)) echo $alter_contact_no; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="user_alt_contact">
                                                <div class="width-prf">
                                                    <label class="form-label">WhatsApp Number</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <input type="text" name="whatsapp_no"
                                                                   class="form-control"
                                                                   onkeypress="return isNumberKey(event)"
                                                                   placeholder="WhatsApp Number"
                                                                   value="<?php
                                                                   if(isset($_POST['whatsapp_no'])){
                                                                       echo $_POST['whatsapp_no'];
                                                                   }elseif (isset($whatsapp_no) && $whatsapp_no != "") {
                                                                       echo $whatsapp_no;
                                                                   } else {
                                                                       echo $session_contact_no;
                                                                   }; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="user_address">
                                                <div class="width-prf">
                                                    <label class="form-label">Keywords (For search
                                                        improvement)</label>

                                                    <div class="form-group form-group-xl form-float" style="transition: all 0.25s ease-in 0s;
    border-bottom: 2px solid rgb(31, 145, 243);">

                                                        <input name="txt_keyword" id="skill"
                                                               class="form-control"
                                                               value="<?php
                                                               if(isset($_POST['txt_keyword'])){
                                                                   echo  $_POST['txt_keyword'];
                                                               }elseif(isset($keyword)) echo $keyword; ?>">
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="user_alt_contact">
                                                <div class="width-prf">
                                                    <label class="form-label">State</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <!--<input type="text" name="txt_state"
                                                                           class="form-control"
                                                                           placeholder="Enter State"
                                                                           value="<?php /*if (isset($state) && $state !=""){ echo $state; }else{ echo $current_region; } */ ?>">-->
                                                            <select id="state_select" name="txt_state"
                                                                    class="gender_li form-control"
                                                                    onchange="getCityByStateId(this.value)">
                                                                <option value="">Select an option</option>
                                                                <?php
                                                                if ($get_state != null) {
                                                                    while ($get_state_data = mysqli_fetch_array($get_state)) {
                                                                        ?>
                                                                        <option <?php if (isset($state) && $state == $get_state_data['id']) echo 'selected' ?>
                                                                                value="<?php echo $get_state_data['id']; ?>"><?php echo $get_state_data['name']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="user_alt_contact">
                                                <div class="width-prf">
                                                    <label class="form-label">City</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <!--<input type="text" name="txt_city"
                                                                           class="form-control"
                                                                           placeholder="Enter City"
                                                                           value="<?php /*if (isset($city) && $city !=""){ echo $city; }else{ echo $current_city; } */ ?>">-->
                                                            <div id="city_select">
                                                                <select name="txt_city"
                                                                        class="gender_li form-control">
                                                                    <option value="">Select an option</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="user_keyword">
                                                <div class="width-prf">
                                                    <label class="form-label">Locality</label>

                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                            <input type="text" name="txt_locality"
                                                                   class="form-control"
                                                                   placeholder="Enter locality"
                                                                   value="<?php
                                                                   if(isset($_POST['txt_locality'])){
                                                                       echo $_POST['txt_locality'];
                                                                   }elseif (isset($locality)) echo $locality; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="user_address">
                                                <div class="width-prf">
                                                    <label class="form-label">Address</label>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                    <textarea name="basic-address" class="form-control"
                                              placeholder="Address"><?php if(isset($_POST['basic-address'])){
                                                  echo $_POST['basic-address'];
                                        }elseif(isset($address)) echo $address; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </fieldset>
                                    <div class="altenet-div">
                                        <fieldset>
                                            <legend class="legend_font_size" align="left">Important Links (
                                                Optional
                                                )
                                            </legend>
                                            <ul class="profile-ul">
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Website</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input name="basic_website" class="form-control"
                                                                       placeholder="Enter Website"
                                                                       value="<?php if (isset($website)) echo $website; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Map link</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="txt_map" class="form-control"
                                                                       placeholder="Enter Map Link"
                                                                       value="<?php if (isset($map)) echo $map; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">LinkedIn</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input type="url" name="txt_linked"
                                                                       class="form-control"
                                                                       placeholder="Please Uplaod LinkedIn link"
                                                                       value="<?php if (isset($linked_in)) echo $linked_in; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Youtube</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="basic_youtube" class="form-control"
                                                                       placeholder="Please Upload Youtube Link"
                                                                       value="<?php if (isset($youtube)) echo $youtube; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Facebook</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input name="basic_facebook"
                                                                       class="form-control"
                                                                       placeholder="Please Uplaod Facebook Link"
                                                                       value="<?php if (isset($facebook)) echo $facebook; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Instagram</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input name="txt_instagram" class="form-control"
                                                                       placeholder="Please Uplaod Instagram Link"
                                                                       value="<?php if (isset($instagram)) echo $instagram; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Twitter</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input name="basic_twitter" class="form-control"
                                                                       placeholder="Please Uplaod Twiiter Link"
                                                                       value="<?php if (isset($twitter)) echo $twitter; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_alt_contact">
                                                    <div class="width-prf">
                                                        <label class="form-label">Play Store</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input name="txt_playstore" class="form-control"
                                                                       placeholder="Any app link if you have to showcase"
                                                                       value="<?php if (isset($playstore)) echo $playstore; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </fieldset>
                                    </div>

                                    <div class="form-group text-center">
                                        <!--<button name="btn_update" type="submit"
                                                class="btn btn-primary waves-effect">Update
                                        </button>-->
                                        <?php if (isset($_GET['display_data'])) { ?>
                                            <div>
                                                <input value="Update Profile" type="submit" name="btn_update"
                                                       class="btn btn-primary waves-effect">
                                            </div>
                                        <?php } else { ?>
                                            <div>
                                                <button type="submit" name="btn_insert" id="myBtn"
                                                        class="btn btn-primary waves-effect">Create Digital Card
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <!--&nbsp;&nbsp;
                                          <div>
                                              <input type="reset" class="btn btn-default" value="reset">
                                          </div>-->
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->

            <!-- #Footer -->
        </div>


    </section>


    <?php include "assets/common-includes/footer_includes.php" ?>

    <script src="assets/croppie/croppie.js"></script>
    <!--<script type="text/javascript" src="upload.js"></script>-->
    <div id="uploadimageModal" class="modal" role="dialog">
        <div class="modal-dialog dialog_width">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload & Crop Image</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="image_demo" style="margin-top:30px"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success crop_image">Upload Image</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($number_verified) {
        ?>
        <script>
            function successMessage() {
                swal("Success!", "Your number has been verified", "success");
            }

            $(document).ready(function () {
                successMessage();
            });
        </script>
        <?php
    }
    ?>
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
    <?php
    if (isset($state) && $state != "") {
        ?>
        <script>
            $(document).ready(function () {
                getCityByStateId($('select[name=txt_state]').val());
            })
        </script>
        <?php
    }
    ?>
    <script>
        $(document).ready(function () {
            $image_crop = $('#image_demo').croppie({
                enableExif: true,
                viewport: {
                    width: 250,
                    height: 250,
                    type: 'square' //circle
                },
                boundary: {
                    width: 300,
                    height: 300
                }
            });
            $('.cr-viewport').css('border-radius', '50%');
            $('#upload_image').on('change', function () {
                var reader = new FileReader();
                reader.onload = function (event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadimageModal').modal('show');
            });

            $('.crop_image').click(function (event) {
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (response) {
                    $.ajax({
                        url: "upload.php",
                        type: "POST",
                        data: {"image": response},
                        success: function (data) {
                            $('#uploadimageModal').modal('hide');
                            $('#uploaded_image').html(data);
                        }
                    });
                })
            });

        });
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('change', '#cover_image', function () {
                var name = document.getElementById("cover_image").files[0].name;
                var form_data = new FormData();
                var ext = name.split('.').pop().toLowerCase();
                if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    alert("Invalid Image File");
                }
                var oFReader = new FileReader();
                oFReader.readAsDataURL(document.getElementById("cover_image").files[0]);
                var f = document.getElementById("cover_image").files[0];
                var fsize = f.size || f.fileSize;
                if (fsize > 2000000) {
                    alert("Image File Size is very big");
                } else {
                    form_data.append("file", document.getElementById('cover_image').files[0]);
                    $.ajax({
                        url: "upload-cover.php",
                        method: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            $('#upload_cover_data').html(data);
                        }
                    });
                }
            });
        });
    </script>


    <!-- verify close -->
    <script>
        function close_verify_modal() {
            $('#overlay').hide();
            $('.verify_number_div').hide();
        }

    </script>
    <!-- end  -->
    <script>
        $('#OpenImgUpload').click(function () {
            $('#upload_image').trigger('click');
        });
        $('#OpenCoverImage').click(function () {
            $('#cover_image').trigger('click');
        });
    </script>
    <script>
        function getCityByStateId(value) {

            var dataString = 'state_id=' + value<?php if (isset($city) && $city != "") echo "+'&city_name='+'" . $city . "'"; ?>;
            if (value != '') {

                $.ajax({
                    url: "get_city_ajax.php",
                    type: "POST",
                    data: dataString,
                    success: function (html) {
                        $('#city_select').html(html);
                    }
                });
            } else {
                $('#city_select').html(' <select name="txt_city" class="form-control"><option value="">select an option</option></select>');
            }
        }
    </script>
    <script>
        function UIHideShow(value) {
            if (value == 'other') {
                $(".other_input").show();
            } else {
                $(".other_input").hide();
            }
        }
    </script>
    <!-- <script src='assets/select2/dist/js/select2.min.js' type='text/javascript'></script>
     <script>
         $(document).ready(function() {
             // Initialize select2
             $("#business_select").select2();
         });
     </script>
 -->
    <script>
        $(document).ready(function () {
            $('#skill').tokenfield({
                autocomplete: {
                    source: ['PHP', 'Codeigniter', 'HTML', 'JQuery', 'Javascript', 'CSS', 'Laravel', 'CakePHP', 'Symfony', 'Yii 2', 'Phalcon', 'Zend', 'Slim', 'FuelPHP', 'PHPixie', 'Mysql'],
                    delay: 100
                },
                showAutocompleteOnFocus: true
            });
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            new LiteEditor('.js-lite-editor', {
                disableEditorMode: true
            });
            new LiteEditor('.js-lite-editor-mission', {
                disableEditorMode: true
            });
        });
    </script>
    <?php
    if (isset($optional_status) && $optional_status == 1) {
        ?>
        <script>
            $(".other_input").show();
        </script>
        <?php
    }
    ?>
    <!--<script type="text/javascript">
        document.getElementById("b3").onclick = function () {
            swal("Good job!", "You clicked the button!", "success");
        };
    </script>-->


</body>
</html>