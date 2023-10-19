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
$errorBusiness = false;
$errorBMessage = "";
$errorFile = false;
$errorMessageFile = "";
$emailError = false;
$emailErrorMessage = "";
$contactError = false;
$contactErrorMessage = "";
$business_tab = false;
$number_verified = false;
/*@session_start() ;
session_destroy() ;*/

$drp_other = false;
/*echo $_SESSION['user_code'];
die();*/


if (isset($_GET['update_link'])) {
    $business_tab = true;
    $update_link_id = $security->decrypt($_GET['update_link']);
    $business_data = $manage->getAllBusinessLinksById($update_link_id);
    if ($business_data != null) {
        $txt_link = $business_data['link'];
    }
}
include("session_includes.php");
if (isset($_GET['delete_data'])) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->masterDelete($delete_data, "tb_other_link");
    header('location:basic-user-info.php');
}
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

/*if ($expiry_date != "") {
    include "validate-page.php";
    $notification_token = $userSpecificResult['user_notification'];
} else {
    $notification_token = "";
}*/


$userSpecificResult = $manage->displayUserSubscriptionDetails();


if ($userSpecificResult != null) {
    $expiry_date = $userSpecificResult['expiry_date'];
    $plan_name = $userSpecificResult['year'];
    $referral_by = $userSpecificResult['referer_code'];
    $sell_ref = $userSpecificResult['sell_ref'];
    if ($sell_ref == "") {
        $sell_ref = "dealer_link";
    }
} else {
    $plan_name = "trial";
}
$dealerProfileResult = $manage->getDealerProfile($referral_by);
if ($dealerProfileResult) {
    $dealerWebname = $dealerProfileResult['dg_card_site_link'];
}


$active_tab = false;
if (isset($_POST['ver_verify_contact_otp'])) {
    unset($_SESSION['tmp_email']);
    $contact_otp = implode('', $_POST['contact_otp']);
    if ($contact_otp == $_SESSION['randomSMS']) {
        $update_contact = $manage->verifyContactNumber($_SESSION['new_contact']);
        if ($update_contact) {
            /*if ($android_url != "") {
                header('location:basic-user-info.php?' . $android_url);
            } else {
                header('location:basic-user-info.php');
            }*/
            unset($_SESSION["randomSMS"]);
            unset($_SESSION["new_contact"]);
            $number_verified = true;
        }
    } else {
        $contactError = true;
        $contactErrorMessage .= "OTP is not correct<br>";
    }
}

if (isset($_SESSION['dealer_login_type'])) {
    $by = 'by dealer.';
} else {
    $by = 'by user.';
}
if (isset($_POST['verify_email_verified_otp'])) {

    $txt_otp = implode('', $_POST['contact_otp']);
    if ($txt_otp == $_SESSION['randomSMS']) {
        $result = $manage->validateRegisterEmailByID($_SESSION['new_email_id'], $id);
        if ($result) {
            $contactError = true;
            $contactErrorMessage .= "Email Id Already Exist<br>";
        } else {

        }
        $update_email_id = $manage->update_email_id($_SESSION['new_email_id']);
        if ($update_email_id) {
            $update = $manage->update($manage->profileTable, array('verified_email_status' => '1'), array('id' => $id));
            $oldname = 'uploads/' . $session_email . '';
            $newname = 'uploads/' . $_SESSION['new_email_id'];
            rename($oldname, $newname);

            $remark = "Email Id has been changed " . $by . "<br>";
            $remark .= $session_email . " TO " . $_SESSION['new_email_id'];
            if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                $_SESSION['create_user_email'] = $_SESSION['new_email_id'];
            } else {
                $_SESSION['email'] = $_SESSION['new_email_id'];
            }
            $session_email = $_SESSION['new_email_id'];
            $page_name = $_SESSION['menu']['s_profile'];
            $action = "Changed";
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            unset($_SESSION["email_otp"]);
            unset($_SESSION["randomSMS"]);
            unset($_SESSION["new_email_id"]);
            if ($android_url != "") {
                header('location:basic-user-info.php?' . $android_url);
            } else {
                header('location:basic-user-info.php');
            }
        }
    } else {
        $contactError = true;
        $contactErrorMessage .= "OTP is not correct<br>";
    }
}
if ($id != 0) {
    $form_data = $manage->getSpecificUserProfile();
    if ($form_data != null) {
        $name = $form_data['name'];
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
        $company_logo = $form_data['company_logo'];
        $company_name = $form_data['company_name'];
        $gst_no = $form_data['gst_no'];
        $pan_no = $form_data['pan_no'];
        $about_us = $form_data['about_company'];
        $our_mission = $form_data['our_mission'];
        $playstore = $form_data['playstore_url'];
        $whatsapp_no = $form_data['whatsapp_no'];
        $saved_email = $form_data['saved_email'];
        $business_category = $form_data['business_category'];
        $landline_number = $form_data['landline_number'];
        $hide_social_status = $form_data['hide_social_status'];
        $profilePath = "uploads/" . $session_email . "/profile/" . $form_data['img_name'];
        if ($form_data['cover_pic'] != "") {
            $key_data = explode(',', $form_data['cover_pic']);
        } else {
            $key_data = 0;
        }


        if ($form_data['company_profile'] != "") {
            $companyPath = "uploads/" . $session_email . "/profile/" . $form_data['company_profile'];
        } else {
            $companyPath = $form_data['company_profile'];
        }

        if ($form_data['company_logo'] != "") {
            $companyLogoPath = "uploads/" . $session_email . "/profile/" . $form_data['company_logo'];
        } else {
            $companyLogoPath = $form_data['company_logo'];
        }
        $verified_number = $form_data['verify_number'];
        $user_state = $form_data['state'];
        $city = $form_data['city'];
        $locality = $form_data['locality'];
        $optional_status = $form_data['optional_status'];
        $verified_email_status = $form_data['verified_email_status'];
    }
}

if (isset($_GET['remove_profile']) && $_GET['remove_profile'] == "true") {
    unlink($companyPath);
    $company_blank = "";
    $updateCompany = $manage->updateDeleteCompany($company_blank);
    header('location:basic-user-info.php?company_info_tab=true');
}

if (isset($_GET['remove_profile_logo']) && $_GET['remove_profile_logo'] == "true") {
    unlink($companyLogoPath);
    $company_blank = "";
    $updateCompany = $manage->mu_updateDeleteCompanyLogo($company_blank);
    header('location:basic-user-info.php?company_info_tab=true');
}

$five_day = date('Y-m-d', strtotime(date_create("Y-m-d") . ' + 5 days'));


$imgUploadStatus = false;
$fileUploadStatus = false;
/*This method used for update the Branch data*/

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
        $post_city = $_POST['txt_city'];
    } else {
        $post_city = "";
    }
    if (isset($_POST['txt_locality']) && $_POST['txt_locality'] != "") {
        $post_locality = $_POST['txt_locality'];
    } else {
        $post_locality = "";
    }
    if (isset($_POST['drp_business_category']) && $_POST['drp_business_category'] != "" && $_POST['drp_business_category'] != "other") {
        $drp_business_category = mysqli_real_escape_string($con, $_POST['drp_business_category']);
        $other_optional_status = 0;
    } elseif (isset($_POST['other_business']) && $_POST['other_business'] != "") {
        $other_optional_status = 1;
        $drp_business_category = mysqli_real_escape_string($con, $_POST['other_business']);
    } else {
        $other_optional_status = 0;
        $drp_business_category = "";
    }
    if (!$error) {
        $date1 = date("Y-m-d");
        $date = date_create("$date1");
        date_add($date, date_interval_create_from_date_string("5 days"));
        $final_date = date_format($date, "Y-m-d");
        
        $get_user_expiry_count = $manage->selectTheme();
        if ($get_user_expiry_count != null) {
            $update_user_count = $get_user_expiry_count['update_user_count'];
            $get_email_count = $get_user_expiry_count['email_count'];
        }

        if (isset($_POST['dob']) && $_POST['dob'] != '') {
            $dob = $_POST['dob'];
        } else {
            $dob = '0000-00-00';
        }
        if ($_POST['social_status'] == "" || $_POST['social_status'] == 0) {
            $status_social_link = 0;
        } else {
            $status_social_link = $_POST['social_status'];
        }
        
        $status = $manage->updateUserProfile($_POST['txt_name'], $txt_designation, $_POST["gender"], $dob,
            $_POST['txt_alt_contact'], $_POST['basic_website'], $_POST['txt_linked'], $_POST['basic_youtube'],
            $_POST['basic_facebook'], $_POST['basic_twitter'], $_POST['txt_instagram'], $_POST['txt_map'],
            $_POST['basic-address'], $_POST['txt_keyword'], $_POST['txt_playstore'], $_POST['whatsapp_no'],
            $_POST['saved_email'], $state, $post_city, $post_locality, $drp_business_category, $other_optional_status, $_POST['landline_number'], $status_social_link);
            
            if ($status) {
            if ($drp_business_category != '') {
                $insert_business = $manage->mu_validateBusniessCategoryByName($drp_business_category);
                if ($insert_business == null) {
                    $insert = $manage->insertBusniessCategory($drp_business_category);
                }
            }
            $page_name = $_SESSION['menu']['s_profile'];
            $action = "Update";

            $remark = $_SESSION['menu']['s_basic_info'] . " details changed " . $by;
            $message = "";
            if (isset($_POST['txt_name']) && $_POST['txt_name'] != $name) {
                $message .= $name . " TO " . $_POST['txt_name'] . ",<br>";
            }
            if ($txt_designation != $designation) {
                $message .= $designation . " TO " . $txt_designation . ",<br>";
            }
            if (isset($_POST['gender']) && $_POST["gender"] != $gender) {
                $message .= $gender . " TO " . $_POST['gender'] . ",<br>";
            }
            if (isset($_POST['dob']) && $_POST['dob'] != $date_of_birth) {
                $message .= $date_of_birth . " TO " . $_POST['dob'] . ",<br>";
            }
            if (isset($_POST['txt_alt_contact']) && $_POST['txt_alt_contact'] != $alter_contact_no) {
                $message .= $alter_contact_no . " TO " . $_POST['txt_alt_contact'] . ",<br>";
            }
            if (isset($_POST['basic_website']) && $_POST['basic_website'] != $website) {
                $message .= $website . " TO " . $_POST['basic_website'] . ",<br>";
            }
            if (isset($_POST['txt_linked']) && $_POST['txt_linked'] != $linked_in) {
                $message .= $linked_in . " TO " . $_POST['txt_linked'] . ",<br>";
            }
            if (isset($_POST['basic_youtube']) && $_POST['basic_youtube'] != $youtube) {
                $message .= $youtube . " TO " . $_POST['basic_youtube'] . ",<br>";
            }
            if (isset($_POST['basic_facebook']) && $_POST['basic_facebook'] != $facebook) {
                $message .= $facebook . " TO " . $_POST['basic_facebook'] . ",<br>";
            }
            if (isset($_POST['basic_twitter']) && $_POST['basic_twitter'] != $twitter) {
                $message .= $twitter . " TO " . $_POST['basic_twitter'] . ",<br>";
            }
            if (isset($_POST['txt_instagram']) && $_POST['txt_instagram'] != $instagram) {
                $message .= $instagram . " TO " . $_POST['txt_instagram'] . ",<br>";
            }
            if (isset($_POST['txt_map']) && $_POST['txt_map'] != $map) {
                $message .= $map . " TO " . $_POST['txt_map'] . ",<br>";
            }
            if (isset($_POST['basic-address']) && $_POST['basic-address'] != $address) {
                $message .= $address . " TO " . $_POST['basic-address'] . ",<br>";
            }
            if (isset($_POST['txt_keyword']) && $_POST['txt_keyword'] != $keyword) {
                $message .= $keyword . " TO " . $_POST['txt_keyword'] . ",<br>";
            }
            if (isset($_POST['txt_playstore']) && $_POST['txt_playstore'] != $playstore) {
                $message .= $playstore . " TO " . $_POST['txt_playstore'] . ",<br>";
            }
            if (isset($_POST['whatsapp_no']) && $_POST['whatsapp_no'] != $whatsapp_no) {
                $message .= $whatsapp_no . " TO " . $_POST['whatsapp_no'] . ",<br>";
            }
            if (isset($_POST['saved_email']) && $_POST['saved_email'] != $saved_email) {
                $message .= $saved_email . " TO " . $_POST['saved_email'] . ",<br>";
            }
            if ($post_city != $city) {
                $message .= $city . " TO " . $post_city . ",<br>";
            }
            if ($post_locality != $locality) {
                $message .= $locality . " TO " . $post_locality . ",<br>";
            }
            if ($drp_business_category != $business_category) {
                $message .= $business_category . " TO " . $drp_business_category . ",<br>";
            }
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark . "<br>" . $message);
           
            if ($update_user_count != 0) {
                $form_data = $manage->getSpecificUserProfile();
                if ($form_data != null) {
                    $name = $form_data['name'];
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
                    $playstore = $form_data['playstore_url'];
                    $whatsapp_no = $form_data['whatsapp_no'];
                    $saved_email = $form_data['saved_email'];
                    $user_state = $form_data['state'];
                    $city = $form_data['city'];
                    $locality = $form_data['locality'];
                    $business_category = $form_data['business_category'];
                    $optional_status = $form_data['optional_status'];
                    $landline_number = $form_data['landline_number'];
                }
                $error = false;
                $errorMessage = "Profile updated successfully";
            } else {
                if ($android_url != "") {
                    header('location:free-trial.php?' . $android_url);
                } else {
                    header('location:free-trial.php');
                }
            }
        } else {
            $error = true;
            $errorMessage = "Issue while updating details please try again.";
        }
    }
}
$logoUploadStatus = false;
if (isset($_POST['update_company'])) {
    $file_size = 4718592;
    if (isset($_POST['company_name']) && $_POST['company_name'] != "") {
        $post_company_name = mysqli_real_escape_string($con, $_POST['company_name']);
    } else {
        $active_tab = true;
        $errorCompany = true;
        $errorCMessage .= "Please enter company name.<br>";
    }
    /* if (isset($_POST['about_us']) && $_POST['about_us'] != "") {
         $about_us = mysqli_real_escape_string($con,$_POST['about_us']);
     } else {
         $active_tab = true;
         $errorCompany = true;
         $errorCMessage .= "Please enter about your company.<br>";
     }

     if (isset($_POST['txt_mission']) && $_POST['txt_mission'] != "") {
         $txt_mission = mysqli_real_escape_string($con,$_POST['txt_mission']);
     } else {
         $txt_mission = "";
     }*/

    /*if (isset($_POST['txt_gst_no']) && $_POST['txt_gst_no'] != "") {
        $gst_no = mysqli_real_escape_string($con, $_POST['txt_gst_no']);
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }*/

    if (isset($_FILES['company-profile']) && $_FILES['company-profile']['error'][0] != 4 /*4 means there is no file selected*/) {
        $fileUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/profile/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG', '.pdf', '.PDF');
        $total = count($_FILES['company-profile']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['company-profile']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $active_tab = true;
                $errorCompany = true;
                $errorCMessage .= "Please select valid file extension";
            }
            if (($_FILES['company-profile']['size'][$i] >= $file_size)) {
                $active_tab = true;
                $errorCompany = true;
                $errorCMessage .= 'File too large. File must be less than 2 megabytes1.';
            }
        }
    }
    if (isset($_FILES['company-logo']) && $_FILES['company-logo']['error'][0] != 4 /*4 means there is no file selected*/) {
        $logoUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/profile/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG', '.pdf', '.PDF', '.DOC', '.docx', '.DOCX', '.doc');
        $total = count($_FILES['company-logo']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['company-logo']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $active_tab = true;
                $errorCompany = true;
                $errorCMessage .= "Please select valid file extension";
            }
            if (($_FILES['company-logo']['size'][$i] >= $file_size)) {
                $active_tab = true;
                $errorCompany = true;
                $errorCMessage .= 'File too large. File must be less than 2 megabytes2.';
            }
        }
    }
    /*else{
        if ($form_data['company_profile']=="") {
            $active_tab = true;
            $errorCompany = true;
            $errorCMessage .= 'Please upload file';
        }
    }*/
    if (!$errorCompany) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        $file_name = "";
        $logo_name = "";
        if ($fileUploadStatus) {
            if (file_exists($companyPath) && $form_data['company_profile'] != "") {
                unlink('uploads/' . $session_email . '/profile/' . $form_data['company_profile']);
            }
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['company-profile']['tmp_name'][$i];
                $file_original_name = substr($_FILES['company-profile']['name'][$i], 0, strrpos($_FILES['company-profile']['name'][$i], '.'));
                $file_extension = substr($_FILES['company-profile']['name'][$i], (strrpos($_FILES['company-profile']['name'][$i], '.') + 1));
                $newfilename = $randomNum . '.' . $file_extension;
                $file_name = str_replace([' ', '_'], '-', $newfilename);
                $newPath = $directory_name . $file_name;
                /*  echo $newPath."<br>";
                  echo $tmpFilePath;
                  die();*/
                if (!move_uploaded_file($tmpFilePath, $newPath)) {
                    $active_tab = true;
                    $errorCompany = true;
                    $errorCMessage .= "Failed to upload file";
                }
            }

        }
        if ($logoUploadStatus) {
            if (file_exists($companyLogoPath) && $form_data['company_logo'] != "") {
                unlink('uploads/' . $session_email . '/profile/' . $form_data['company_logo']);
            }
            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpLogoPath = $_FILES['company-logo']['tmp_name'][$i];
                $file_original_name = substr($_FILES['company-logo']['name'][$i], 0, strrpos($_FILES['company-logo']['name'][$i], '.'));
                $file_extension = substr($_FILES['company-logo']['name'][$i], (strrpos($_FILES['company-logo']['name'][$i], '.') + 1));
                $logo_name = $randomNum . '.' . $file_extension;
                $logoPath = $directory_name . $logo_name;

                if (!move_uploaded_file($tmpLogoPath, $logoPath)) {
                    $active_tab = true;
                    $errorCompany = true;
                    $errorCMessage = "Failed to upload file";
                }
            }
        }
        if (!$errorCompany) {
            $status = $manage->updateCompany($post_company_name, $_POST['txt_gst_no'], $_POST['txt_pan_no'], $_POST['about_us'],
                $_POST['txt_mission'], $file_name, $logo_name);
            if ($status) {
                $company_message_data = "<br>";
                if (isset($post_company_name) && $post_company_name != $company_name) {
                    $company_message_data .= $company_name . " TO " . $post_company_name . ",<br>";
                }
                if (isset($_POST['txt_gst_no']) && $_POST['txt_gst_no'] != $gst_no) {
                    $company_message_data .= $gst_no . " TO " . $_POST['txt_gst_no'] . ",<br>";
                }
                if (isset($_POST['txt_pan_no']) && $_POST['txt_pan_no'] != $pan_no) {
                    $company_message_data .= $pan_no . " TO " . $_POST['txt_pan_no'] . ",<br>";
                }
                if (isset($_POST['about_us']) && $_POST['about_us'] != $about_us) {
                    $company_message_data .= $about_us . " TO " . $_POST['about_us'] . ",<br>";
                }
                if (isset($_POST['txt_mission']) && $_POST['txt_mission'] != $our_mission) {
                    $company_message_data .= $our_mission . " TO " . $_POST['txt_mission'] . ",<br>";
                }


                $_SESSION['red_dot']['company_name'] = false;
                $page_name = $_SESSION['menu']['s_profile'];
                $action = "Update";
                $remark = $_SESSION['menu']['s_company_info'] . " has been updated " . $by;
                $insertLog = $manage->insertUserLogData($page_name, $action, $remark . $company_message_data);
                $form_data = $manage->getSpecificUserProfile();
                if ($form_data != null) {
                    $company_name = $form_data['company_name'];
                    $company_logo = $form_data['company_logo'];
                    $gst_no = $form_data['gst_no'];
                    $pan_no = $form_data['pan_no'];
                    $about_us = $form_data['about_company'];
                    $our_mission = $form_data['our_mission'];
                    if ($form_data['company_profile'] != "") {
                        $companyPath = "uploads/" . $session_email . "/profile/" . $form_data['company_profile'];
                    } else {
                        $companyPath = $form_data['company_profile'];
                    }
                }
                $errorCompany = false;
                $errorCMessage = "Company details updated successfully";
                $active_tab = true;
                /*header('location:basic-user-info.php');*/
            } else {
                $active_tab = true;
                $errorCompany = true;
                $errorCMessage = "Issue while updating details, Please try again.";
            }
        } else {
            $active_tab = true;
            $errorCompany = true;
            $errorCMessage .= "Issue while uploading\nNote: File too large. File must be less than 2 megabytes.";
        }

    }
}


if (isset($_POST['upload_photo'])) {
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "uploads/" . $session_email . "/profile/";
        $extension = array('.jpg', 'JPG', '.JPG', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $total1 = count($_FILES['cover_image']['name']);
        for ($i = 0; $i < $total1; $i++) {
            $filename = $_FILES['cover_image']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $errorFile = true;
                $errorMessageFile = "Please select valid file extension";
            }
            if (($_FILES['cover_image']['size'][$i] >= $maxsize)) {
                $errorFile = true;
                $errorMessageFile = 'File too large. File must be less than 4 megabytes.';
            }
        }
    } else {
        $errorFile = true;
        $errorMessageFile = "Please select file";
    }
    if (!$errorFile) {
        $digits = 4;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newfilename = "";
        if ($imgUploadStatus) {
            for ($i = 0; $i < $total1; $i++) {
                $filearray = array();
                $tmpFilePath1 = $_FILES['cover_image']['tmp_name'][$i];
                $file_original_name = substr($_FILES['cover_image']['name'][$i], 0, strrpos($_FILES['cover_image']['name'][$i], '.'));
                $file_extension = substr($_FILES['cover_image']['name'][$i], (strrpos($_FILES['cover_image']['name'][$i], '.') + 1));
                $setting = array(
                    'directory' => $directory_name, // directory file compressed output
                    'file_type' => array( // file format allowed
                        'image/jpeg',
                        'image/png',
                        'image/gif'
                    )
                );
                $ImgCompressor = new ImgCompressor($setting);
                $result = $ImgCompressor->run($tmpFilePath1, 'jpg', 5);
            }
        }
        $key = json_encode($result);
        $decode = json_decode($key);
        $value = 'status';
        $fileStatus = $decode->$value;
        if ($fileStatus == "success") {
            if (file_exists($coverPic)) {
                unlink('uploads/' . $session_email . '/profile/' . $form_data['cover_pic'] . '');
            }
            $data = "data";
            $compressed = "compressed";
            $img_name = "name";
            $cover_name = $decode->$data->$compressed->$img_name;
            $update_photo = $manage->updateCoverPhoto($cover_name);
            if ($update_photo) {
                if ($android_url != "") {
                    header('location:basic-user-info.php?' . $android_url);
                } else {
                    header('location:basic-user-info.php');
                }
            }
        } else {
            $errorFile = true;
            $errorMessageFile = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
        }

    }

}

/*change contact number*/
if (isset($_GET['change_contact'])) {
    if (isset($_POST['send_contact_otp'])) {
        if (isset($_POST['new_contact']) && $_POST['new_contact'] != "") {
            if (($_GET['change_contact']) == ($_POST['new_contact'])) {
                $contactError = true;
                $contactErrorMessage .= "Contact is same as above.<br>";
            }
            $new_contact = $_POST['new_contact'];
        } else {
            $contactError = true;
            $contactErrorMessage .= "Please enter contact no.<br>";
        }
        if (!$contactError) {
            $result = $manage->validateContact($new_contact);
            if ($result) {
                $contactError = true;
                $contactErrorMessage .= "Contact No Already Exist<br>";
            } else {
                /*$message = substr_replace($random_sms, '-', 3, 0) . " is your one time password(OTP), Please enter the OTP to proceed.\n Thank you,\n Team Kubic";
                $send_sms = $manage->sendSMS($new_contact, $message);*/
                //$sms_message = "Dear Customer, " . substr_replace($random_sms, '-', 3, 0) . " is your one time password - OTP. Please do not share this OTP with anyone for security reasons.";
                $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
                $send_sms = $manage->sendSMSWithTemplateId($new_contact, $sms_message, TEMPLATE_REGISTRATION);
                $_SESSION['new_contact'] = $new_contact;
                $_SESSION['randomSMS'] = $random_sms;
                $contactError = false;
                $contactErrorMessage .= "OTP has been send.<br>";
            }
        }
    }
}

if (isset($_GET['change_contact'])) {
    if (isset($_POST['resend_contact_otp'])) {
        if (!$contactError) {
            $result = $manage->validateContact($_SESSION['new_contact']);
            if ($result) {
                $contactError = true;
                $contactErrorMessage .= "Contact No Already Exist<br>";
            } else {
                /*$message = substr_replace($random_sms, '-', 3, 0) . " is your one time password(OTP), Please enter the OTP to proceed.\n Thank you,\n Team Kubic";
                $send_sms = $manage->sendSMS($_SESSION['new_contact'], $message);*/
                //$sms_message = "Dear Customer, " . substr_replace($random_sms, '-', 3, 0) . " is your one time password - OTP. Please do not share this OTP with anyone for security reasons.";
                $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
                $send_sms = $manage->sendSMSWithTemplateId($_SESSION['new_contact'], $sms_message, TEMPLATE_REGISTRATION);
                /*$_SESSION['new_contact'] = $new_contact;*/
                $_SESSION['randomSMS'] = $random_sms;
                $contactError = false;
                $contactErrorMessage .= "OTP has been re send.<br>";
            }
        }
    }
}
/*verify contact number*/

if (isset($_POST['ver_send_contact_otp'])) {
    unset($_SESSION['tmp_email']);
    /*   if (isset($_POST['new_contact']) && $_POST['new_contact'] != "") {
           $new_contact = $_POST['new_contact'];
       } else {
           $contactError = true;
           $contactErrorMessage .= "Please enter contact no.<br>";
       }*/
    if (!$contactError) {
        //$sms_message = "Dear Customer, " . substr_replace($random_sms, '-', 3, 0) . " is your one time password - OTP. Please do not share this OTP with anyone for security reasons.";
        $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
        $send_sms = $manage->sendSMSWithTemplateId($session_contact_no, $sms_message, TEMPLATE_REGISTRATION);
        $_SESSION['new_contact'] = $session_contact_no;
        $_SESSION['randomSMS'] = $random_sms;
        $contactError = false;
        $contactErrorMessage .= "OTP has been send.<br>";
    }
}

if (isset($_POST['ver_resend_contact_otp'])) {
    unset($_SESSION['tmp_email']);
    if (!$contactError) {
        /* $message = substr_replace($random_sms, '-', 3, 0) . " is your one time password(OTP), Please enter the OTP to proceed.\n Thank you,\n Team Kubic";
         $send_sms = $manage->sendSMS($session_contact_no, $message);*/
        //$sms_message = "Dear Customer, " . substr_replace($random_sms, '-', 3, 0) . " is your one time password - OTP. Please do not share this OTP with anyone for security reasons.";
        $sms_message = "Dear%20Customer%2C%20%0AFor%20registration%20into%20the%20website%20or%20mobile%20application%2C%20Your%20One-Time%20Password%20%28OTP%29%20is%20".substr_replace($random_sms, '-', 3, 0).".%20Please%20do%20not%20share%20this%20OTP%20with%20anyone.%20Message%20ID%3A%20asdasd545454%0ABest%20Regards%20%0ADGCARD";
        $send_sms = $manage->sendSMSWithTemplateId($session_contact_no, $sms_message, TEMPLATE_REGISTRATION);
        /*$_SESSION['new_contact'] = $new_contact;*/
        $_SESSION['randomSMS'] = $random_sms;
        $contactError = false;
        $contactErrorMessage .= "OTP has been re send.<br>";
    }
}

/*end verify contact number*/
/*Verify Email Address Start*/
if (isset($_POST['ver_send_email_otp'])) {
    if (isset($_POST['new_email_id']) && $_POST['new_email_id'] != "") {
        $new_email_id = $_POST['new_email_id'];
    } else {
        $contactError = true;
        $contactErrorMessage .= "Please enter email id.<br>";
    }
    if (!$contactError) {
        $message = substr_replace($random_sms, '-', 3, 0) . " is your one time password(OTP), Please enter the OTP to proceed.\n Thank you,\n Team Kubic";
        $send_sms = $manage->sendMail(MAIL_FROM_NAME, $new_email_id, $message, $message);
        if ($send_sms) {
            $_SESSION['new_email_id'] = $new_email_id;
            $_SESSION['randomSMS'] = $random_sms;
            $contactError = false;
            $contactErrorMessage .= "OTP has been send.<br>";
        } else {
            $contactError = true;
            $contactErrorMessage .= "Issue while sending otp please try after some time.";
        }


    }
}

if (isset($_POST['ver_resend_email_otp'])) {
    if (!$contactError) {
        $message = substr_replace($random_sms, '-', 3, 0) . " is your one time password(OTP), Please enter the OTP to proceed.\n Thank you,\n Team Kubic";
        $send_sms = $manage->sendMail(MAIL_FROM_NAME, $_SESSION['new_email_id'], $message, $message);
        /*$_SESSION['new_contact'] = $new_contact;*/
        $_SESSION['randomSMS'] = $random_sms;
        $contactError = false;
        $contactErrorMessage .= "OTP has been re send.<br>";
    }
}

/*Verify Email Address End*/


if (isset($_POST['cancel_sms'])) {
    if ($android_url != "") {
        header('location:basic-user-info.php?' . $android_url);
    } else {
        header('location:basic-user-info.php');
    }
    unset($_SESSION["randomSMS"]);
    unset($_SESSION["new_contact"]);
}

if (isset($_POST['verify_contact_otp'])) {
    $contact_otp = implode('', $_POST['contact_otp']);
    if ($contact_otp == $_SESSION['randomSMS']) {
        $update_contact = $manage->update_contact_no($_SESSION['new_contact']);
        if ($update_contact) {
            $remark = "Contact Number has been changed " . $by . "<br>";
            $remark .= $session_contact_no . " TO " . $_SESSION['new_contact'];
            if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                $_SESSION['create_user_contact'] = $_SESSION['new_contact'];
            } else {
                $_SESSION['contact'] = $_SESSION['new_contact'];
            }
            $session_contact_no = $_SESSION['new_contact'];
            $page_name = $_SESSION['menu']['s_profile'];
            $action = "Changed";
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            unset($_SESSION["randomSMS"]);
            unset($_SESSION["new_contact"]);
            if ($android_url != "") {
                header('location:basic-user-info.php?' . $android_url);
            } else {
                header('location:basic-user-info.php');
            }
        }
    } else {
        $contactError = true;
        $contactErrorMessage .= "OTP is not correct<br>";
    }
}

/*End*/
/*for admin to change contact number*/
if (isset($_POST['admin_change_contact'])) {
    if (isset($_POST['new_contact']) && $_POST['new_contact'] != "") {
        if (($_GET['change_contact']) == ($_POST['new_contact'])) {
            $contactError = true;
            $contactErrorMessage .= "Contact is same as above.<br>";
        }
        $new_contact = $_POST['new_contact'];
    } else {
        $contactError = true;
        $contactErrorMessage .= "Please enter contact no.<br>";
    }
    if (!$contactError) {
        $result = $manage->validateContact($new_contact);
        if ($result) {
            $contactError = true;
            $contactErrorMessage .= "Contact No Already Exist<br>";
        } else {
            $update_contact = $manage->update_contact_no($new_contact);
            if ($update_contact) {
                if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                    $_SESSION['create_user_contact'] = $new_contact;
                } else {
                    $_SESSION['contact'] = $new_contact;
                }
                $session_contact_no = $new_contact;
                unset($_SESSION["randomSMS"]);
                unset($_SESSION["new_contact"]);
                if ($android_url != "") {
                    header('location:basic-user-info.php?' . $android_url);
                } else {
                    header('location:basic-user-info.php');
                }
            }
        }
    }

}
/*Change Email Id*/
if (isset($_GET['change_email'])) {
    $change_email = $_GET['change_email'];
    if (isset($_POST['send_email_otp'])) {
        if (isset($_POST['new_email']) && $_POST['new_email'] != "") {
            if (($_GET['change_email']) == ($_POST['new_email'])) {
                $emailError = true;
                $emailErrorMessage .= "Email is not same as above.<br>";
            }
            $new_email = $_POST['new_email'];
        } else {
            $emailError = true;
            $emailErrorMessage .= "Please enter your Email.<br>";
        }

        if (!$emailError) {
            $result = $manage->validateAdminEmail($new_email);
            if ($result) {
                $emailError = true;
                $emailErrorMessage .= "Email Id Already Exist<br>";
            } else {
                $toName = "Kubic";
                $toEmail = $new_email;
                $subject = "OTP";
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
                        <label style="background: #deddd9;color: #666563;">Your OTP Is <br><span style="font-weight: bold;background: #deddd9;color: #666563;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
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


                $message = '<table style="width: 100%">
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
                        <label style="background: #deddd9;color: #666563;">Your OTP Is <br><span style="font-weight: bold;background: #deddd9;color: #666563;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
</table>';


                $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
                $_SESSION['new_email'] = $new_email;
                $_SESSION['email_otp'] = $random_sms;
                $emailError = false;
                $emailErrorMessage .= "OTP has been resend.";
            }
        }
    }
}
if (isset($_GET['change_email'])) {
    $change_email = $_GET['change_email'];
    if (isset($_POST['resend_email_otp'])) {
        /*if (isset($_POST['new_email']) && $_POST['new_email'] != "") {
            if (($_POST['old_email']) == ($_POST['new_email'])) {
                $emailError = true;
                $emailErrorMessage .= "Email is not same as above.<br>";
            }
            $new_email = $_POST['new_email'];
        } else {
            $emailError = true;
            $emailErrorMessage .= "Please enter your Email.<br>";
        }*/
        if (!$emailError) {
            $result = $manage->validateAdminEmail($_SESSION['new_email']);
            if ($result) {
                $emailError = true;
                $emailErrorMessage .= "Email Id Already Exist<br>";
            } else {
                $toName = "Kubic";
                $toEmail = $_SESSION['new_email'];
                $subject = "OTP";
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
                        <label style="background: #deddd9;color: #666563;">Your OTP Is <br><span style="font-weight: bold;background: #deddd9;color: #666563;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
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


                $message = '<table style="width: 100%">
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
                        <label style="background: #deddd9;color: #666563;">Your OTP Is <br><span style="font-weight: bold;background: #deddd9;color: #666563;">' . substr_replace($random_sms, '-', 3, 0) . '</span></label>
                    </div>
                </div>
                </div>
</td>
</tr>
</table>';

                $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
                $_SESSION['email_otp'] = $random_sms;
                $emailError = false;
                $emailErrorMessage .= "OTP has been resend.";
            }
        }
    }
}
// for admin to change email id
if (isset($_POST['admin_send_email_otp'])) {
    $change_email = $_GET['change_email'];
    if (isset($_POST['new_email']) && $_POST['new_email'] != "") {
        if (($_GET['change_email']) == ($_POST['new_email'])) {
            $emailError = true;
            $emailErrorMessage .= "Email is not same as above.<br>";
        }
        $new_email = $_POST['new_email'];
    } else {
        $emailError = true;
        $emailErrorMessage .= "Please enter your Email.<br>";
    }

    if (!$emailError) {
        $result = $manage->validateAdminEmail($new_email);
        if ($result) {
            $emailError = true;
            $emailErrorMessage .= "Email Id Already Exist<br>";
        } else {
            $update_email_id = $manage->update_email_id($new_email);
            if ($update_email_id) {
                $oldname = 'uploads/' . $session_email . '';
                $newname = 'uploads/' . $new_email;
                rename($oldname, $newname);

                $remark = "Email Id has been changed " . $by . "<br>";
                $remark .= $session_email . " TO " . $_SESSION['new_email'];
                if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                    $_SESSION['create_user_email'] = $new_email;
                } else {
                    $_SESSION['email'] = $new_email;
                }
                $session_email = $new_email;
                /* $page_name =$_SESSION['menu']['s_profile'];
                $action = "Changed";
                $insertLog = $manage->insertUserLogData($page_name,$action,$remark);*/
                unset($_SESSION["email_otp"]);
                unset($_SESSION["new_email"]);
                if ($android_url != "") {
                    header('location:basic-user-info.php?' . $android_url);
                } else {
                    header('location:basic-user-info.php');
                }
            }
        }
    }

}
if (isset($_POST['cancel_email'])) {
    if ($android_url != "") {
        header('location:basic-user-info.php?' . $android_url);
    } else {
        header('location:basic-user-info.php');
    }
    unset($_SESSION["email_otp"]);
    unset($_SESSION["new_email"]);
}


if (isset($_POST['verify_email_otp'])) {
    $txt_otp = implode('', $_POST['txt_otp']);
    if ($txt_otp == $_SESSION['email_otp']) {
        $update_email_id = $manage->update_email_id($_SESSION['new_email']);
        if ($update_email_id) {
            $oldname = 'uploads/' . $session_email . '';
            $newname = 'uploads/' . $_SESSION['new_email'];
            rename($oldname, $newname);

            $remark = "Email Id has been changed " . $by . "<br>";
            $remark .= $session_email . " TO " . $_SESSION['new_email'];
            if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
                $_SESSION['create_user_email'] = $_SESSION['new_email'];
            } else {
                $_SESSION['email'] = $_SESSION['new_email'];
            }
            $session_email = $_SESSION['new_email'];
            $page_name = $_SESSION['menu']['s_profile'];
            $action = "Changed";
            $insertLog = $manage->insertUserLogData($page_name, $action, $remark);
            unset($_SESSION["email_otp"]);
            unset($_SESSION["new_email"]);
            if ($android_url != "") {
                header('location:basic-user-info.php?' . $android_url);
            } else {
                header('location:basic-user-info.php');
            }
        }
    } else {
        $emailError = true;
        $emailErrorMessage .= "OTP is not correct<br>";
    }
}
/*
function compress_image($source_file, $target_file, $nwidth, $nheight, $quality)
{
    //Return an array consisting of image type, height, widh and mime type.
    $image_info = getimagesize($source_file);
    if (!($nwidth > 0)) $nwidth = $image_info[0];
    if (!($nheight > 0)) $nheight = $image_info[1];

    if (!empty($image_info)) {
        switch ($image_info['mime']) {
            case 'image/jpeg' :
                if ($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
                // Create a new image from the file or the url.
                $image = imagecreatefromjpeg($source_file);
                $thumb = imagecreatetruecolor($nwidth, $nheight);
                //Resize the $thumb image
                imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                //Output image to the browser or file.
                return imagejpeg($thumb, $target_file, $quality);

                break;

            case 'image/png' :
                if ($quality == '' || $quality < 0 || $quality > 9) $quality = 6; //Default quality
                // Create a new image from the file or the url.
                $image = imagecreatefrompng($source_file);
                $thumb = imagecreatetruecolor($nwidth, $nheight);
                //Resize the $thumb image
                imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                // Output image to the browser or file.
                return imagepng($thumb, $target_file, $quality);
                break;
            case 'image/gif' :
                if ($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
                // Create a new image from the file or the url.
                $image = imagecreatefromgif($source_file);
                $thumb = imagecreatetruecolor($nwidth, $nheight);
                //Resize the $thumb image
                imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                // Output image to the browser or file.
                return imagegif($thumb, $target_file, $quality); //$success = true;
                break;

            default:
                echo "<h4>File type not supported!</h4>";
                break;
        }
    }
}*/

if (isset($_GET['delete_cover_id']) && $_GET['delete_cover_id'] != "" && isset($_GET['cover_img_path']) && $_GET['cover_img_path'] != "") {
    $cover_id = $security->decrypt($_GET['delete_cover_id']);
    $cover_img_name = $_GET['cover_img_path'];
    unlink('uploads/' . $session_email . '/profile/' . $cover_img_name . '');
    $coverImageName = "";
    $update = $manage->updateCoverPhoto($coverImageName);
    header('location:basic-user-info.php');
}
if (isset($_GET['company_info_tab']) && $_GET['company_info_tab'] != "") {
    $active_tab = true;
}
/*$get_business = $manage->getBusniessCategory();*/
$get_state = $manage->getStateCategory($country);
$get_cover_data = $manage->getCoverImageOfUser();
if ($get_cover_data != null) {
    $coverCount = mysqli_num_rows($get_cover_data);
} else {
    $coverCount = 0;
}
function fetch_all_data($result)
{
    $all = array();
    while ($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}

$section_id = 0;
if (isset($_POST['btn_update_theme'])) {
    if (isset($_POST['rd_theme'])) {
        $rd_theme = $_POST['rd_theme'];
    } else {
        $rd_theme = "1";
    }
    $update = $manage->mu_updateUserSectionTheme($rd_theme, $section_id, $id);
    if ($update) {
        $error = false;
        $errorMessage = "Theme has been updated successfully.";
    } else {
        $error = true;
        $errorMessage = "Issue while updating theme please try after some time.";
    }

}

$get_section_theme = $manage->mdm_displaySectionTheme($id, $section_id);
if ($get_section_theme != null) {
    $section_theme = $get_section_theme['theme_id'];
} else {
    $section_theme = 1;
}
if (isset($_POST['insert_business'])) {
    $txt_links = $_POST['txt_links'];
    if (is_array($txt_links)) {
        for ($i = 0; $i < count($txt_links); $i++) {
            $insert = $manage->insertBusinessLink($txt_links[$i]);
        }
    }

    $business_tab = true;
    $errorBusiness = false;
    $errorBMessage = "Business Link Added Successfully!";
}

if (isset($_POST['update_business'])) {
    $txt_links = $_POST['txt_links'];
    if (is_array($txt_links)) {
        for ($i = 0; $i < count($txt_links); $i++) {
            $update = $manage->updateBusinessLink($txt_links[$i], $update_link_id);
        }
    }
    $business_data = $manage->getAllBusinessLinksById($update_link_id);
    if ($business_data != null) {
        $txt_link = $business_data['link'];
    }
    $business_tab = true;
    $errorBusiness = false;
    $errorBMessage = "Business Link Updated Successfully!";
}


$get_result = $manage->getAllBusinessLinks();

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

    <?php include "assets/common-includes/header_includes.php" ?>
    <link rel="stylesheet" href="assets/croppie/croppie.css">
    <script src="https://unpkg.com/lite-editor@1.6.39/js/lite-editor.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/lite-editor@1.6.39/css/lite-editor.css">
    <link href="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css" rel="stylesheet">
    <!-- CSS -->
    <!--<link href='assets/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>-->
    <style>
        #upload_image {
            display: none;
        }

        input[type="file"] {
            display: block;
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

        .frmSearch {
            position: relative;
        }

        #country-list {
            float: left;
            list-style: none;
            margin-top: 6px;
            padding: 0;
            width: 100%;
            position: absolute;
            z-index: 99;
        }

        #country-list li {
            padding: 10px;
            background: #f0f0f0;
            border-bottom: #bbb9b9 1px solid;
            width: 100%;
        }

        #country-list li:hover {
            background: #ece3d2;
            cursor: pointer;
        }
    </style>

    <script>

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
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        include "assets/common-includes/session_button_includes.php";
        echo "<br>";
    }
    ?>
    <?php

    if ($get_email_count == "1")
        include "assets/common-includes/preview.php" ?>

    <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
        <?php include 'assets/common-includes/menu_bar_include.php' ?>
    </div>
    <?php
    }elseif (isset($_GET['android_user_id']) && (isset($_GET['type']) && $_GET['type'] == "android") && (isset($_GET['api_key']))) {
    ?>
    <section class="androidSection">
        <?php
        }
        ?>

        <?php
        if (!isset($_SESSION['tmp_email'])) {
            $_SESSION['tmp_email'] = "true";
            if (isset($_SESSION['type']) && $_SESSION['type'] == "User") {
                if ($countryName == "India" && $country != "") {
                    if ($verified_number != 1) {
                        ?>
                        <div id="overlay"></div>
                        <div class="col-lg-5 verify_number_div">
                            <div class="card">
                                <a href="#" id="btnClose" onclick="close_verify_modal()"
                                   title="Click here to close this deal box." style="right: 10px;z-index: 9;">
                                    <i class="fa fa-times-circle"></i>
                                </a>

                                <div class="body">
                                    <form id="contact_reset" method="POST" action="" enctype="multipart/form-data">
                                        <div class="col-md-12 mb- text-center">
                                            <h3>Verify Your Mobile Number</h3>
                                            <img src="assets/images/smartphone.png">

                                            <p>Your Mobile Verification is still pending, please verify your Mobile
                                                Number
                                                with
                                                OTP in order to start using your Digital card panel.</p>
                                        </div>


                                        <div style="padding: 15px;overflow: hidden">

                                            <?php if ($contactError) {
                                                ?>
                                                <div class="alert alert-danger">
                                                    <?php if (isset($contactErrorMessage)) echo $contactErrorMessage; ?>
                                                </div>
                                            <?php
                                            } else if (!$contactError && $contactErrorMessage != "") {
                                                ?>
                                                <div class="alert alert-success">
                                                    <?php if (isset($contactErrorMessage)) echo $contactErrorMessage; ?>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <label class="form-label">Your Mobile no</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="new_contact" class="form-control"
                                                           placeholder="Enter Mobile no"
                                                           onkeypress="return isNumberKey(event)"
                                                           value="<?php echo $session_contact_no ?>" disabled>
                                                </div>
                                            </div>

                                            <?php if (isset($_SESSION['new_contact'])) { ?>

                                                <label class="form-label">Enter OTP</label>

                                                <div class="form-group form-float">
                                                    <div class="otp_section">
                                                        <div class="digit-group">
                                                            <input class="send_textbox" type="number" id="digit-1"
                                                                   name="contact_otp[]" data-next="digit-2"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-2"
                                                                   name="contact_otp[]" data-next="digit-3"
                                                                   data-previous="digit-1"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-3"
                                                                   name="contact_otp[]" data-next="digit-4"
                                                                   data-previous="digit-2"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <span class="splitter">&ndash;</span>
                                                            <input class="send_textbox" type="number" id="digit-4"
                                                                   name="contact_otp[]" data-next="digit-5"
                                                                   data-previous="digit-3"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-5"
                                                                   name="contact_otp[]" data-next="digit-6"
                                                                   data-previous="digit-4"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-6"
                                                                   name="contact_otp[]" data-previous="digit-5"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="form-group form_inline form-float">
                                                <?php
                                                if (isset($_SESSION['new_contact'])) {
                                                    ?>
                                                    <div style="width: 74% ">
                                                        <button type="submit" style="width: 30%"
                                                                class="btn btn-block bg-pink waves-effect"
                                                                name="ver_verify_contact_otp">
                                                            Verify
                                                            OTP
                                                        </button>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <button class="btn btn-default" type="submit" name="cancel_sms">
                                                            Cancel
                                                        </button>
                                                        <br>
                                                    </div>
                                                    <button class="resend_otp" name="ver_resend_contact_otp">Resend OTP
                                                    </button>
                                                <?php
                                                } else {
                                                    ?>
                                                    <div class="text-center" style="width: 100%">
                                                        <button type="submit" class="btn btn-block bg-pink waves-effect"
                                                                name="ver_send_contact_otp" style="width: 50%">
                                                            Send OTP
                                                        </button>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
            }
        }
        ?>
        <?php
        if (isset($_SESSION['type']) && $_SESSION['type'] == "User") {
            if ($verified_email_status != 1) {
                ?>
                <div id="overlay" style="display: none"></div>
                <div class="col-lg-5 verify_number_div" style="display: none">
                    <div class="card">
                        <a href="#" id="btnClose" onclick="close_verify_modal()"
                           title="Click here to close this deal box." style="right: 10px;z-index: 9;">
                            <i class="fa fa-times-circle"></i>
                        </a>

                        <div class="body">
                            <form id="contact_reset" method="POST" action="" enctype="multipart/form-data">
                                <div class="col-md-12 mb- text-center">
                                    <h3>Verify Your Email Address</h3>
                                    <img src="assets/images/email.png">

                                    <p>Your Email Verification is still pending, please verify your Email Address
                                        with OTP in order to start using your Digital card panel.</p>
                                </div>


                                <div style="padding: 15px;overflow: hidden">

                                    <?php if ($contactError) {
                                        ?>
                                        <div class="alert alert-danger">
                                            <?php if (isset($contactErrorMessage)) echo $contactErrorMessage; ?>
                                        </div>
                                    <?php
                                    } else if (!$contactError && $contactErrorMessage != "") {
                                        ?>
                                        <div class="alert alert-success">
                                            <?php if (isset($contactErrorMessage)) echo $contactErrorMessage; ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <label class="form-label">Your Email Id</label>

                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="new_email_id" type="email" class="form-control"
                                                   placeholder="Enter Email Id"
                                                   value="<?php echo (isset($_SESSION['new_email_id']) && $_SESSION['new_email_id'] != '') ? $_SESSION['new_email_id'] : $session_email; ?>">
                                        </div>
                                    </div>

                                    <?php if (isset($_SESSION['new_email_id'])) { ?>

                                        <label class="form-label">Enter OTP</label>

                                        <div class="form-group form-float">
                                            <div class="otp_section">
                                                <div class="digit-group">
                                                    <input class="send_textbox" type="number" id="digit-1"
                                                           name="contact_otp[]" data-next="digit-2"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="1"/>
                                                    <input class="send_textbox" type="number" id="digit-2"
                                                           name="contact_otp[]" data-next="digit-3"
                                                           data-previous="digit-1"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="1"/>
                                                    <input class="send_textbox" type="number" id="digit-3"
                                                           name="contact_otp[]" data-next="digit-4"
                                                           data-previous="digit-2"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="1"/>
                                                    <span class="splitter">&ndash;</span>
                                                    <input class="send_textbox" type="number" id="digit-4"
                                                           name="contact_otp[]" data-next="digit-5"
                                                           data-previous="digit-3"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="1"/>
                                                    <input class="send_textbox" type="number" id="digit-5"
                                                           name="contact_otp[]" data-next="digit-6"
                                                           data-previous="digit-4"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="1"/>
                                                    <input class="send_textbox" type="number" id="digit-6"
                                                           name="contact_otp[]" data-previous="digit-5"
                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                           maxlength="1"/>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group form_inline form-float">
                                        <?php
                                        if (isset($_SESSION['new_email_id'])) {
                                            ?>
                                            <div style="width: 74% ">
                                                <button type="submit" style="width: 30%"
                                                        class="btn btn-block bg-pink waves-effect"
                                                        name="verify_email_verified_otp">
                                                    Verify
                                                    OTP
                                                </button>
                                            </div>
                                            <button class="resend_otp" name="ver_resend_email_otp">Resend OTP
                                            </button>
                                        <?php
                                        } else {
                                            ?>
                                            <div class="text-center" style="width: 100%">
                                                <button type="submit" class="btn btn-block bg-pink waves-effect"
                                                        name="ver_send_email_otp" style="width: 50%">
                                                    Send OTP
                                                </button>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        ?>
        <div class="clearfix padding_bottom_46">
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12 padding_zero_both">
                <div class="row margin_div1">
                    <?php if ($errorFile) {
                        ?>
                        <div class="alert alert-danger">
                            <?php if (isset($errorMessageFile)) echo $errorMessageFile; ?>
                        </div>
                    <?php
                    } else if (!$errorFile && $errorMessageFile != "") {
                        ?>
                        <div class="alert alert-success">
                            <?php if (isset($errorMessageFile)) echo $errorMessageFile; ?>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="card">
                        <div class="body card_padding">
                            <form id="basic_user_profile" method="POST" action="" enctype="multipart/form-data">

                                <div id="uploaded_cover_image" class="cover_image contact-icon-btm1">
                                    <?php
                                    if ($get_cover_data != null) {
                                        $cover_first_img = fetch_all_data($get_cover_data);
                                        foreach ($cover_first_img as $k) {
                                            $cover_first_img_path = "uploads/" . $session_email . "/profile/" . $k['cover_pic'];
                                            break;
                                        }

                                    }
                                    if ($get_cover_data == null) {
                                        echo "<img src='http://via.placeholder.com/640x360' style='height: 100%'>";
                                    } elseif (file_exists($cover_first_img_path) && $coverCount == 1) {
                                        echo '<img src="' . $cover_first_img_path . '" style="width:100%">';
                                    } elseif ($coverCount > 1) {
                                        ?>
                                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                            <!-- Indicators -->
                                            <!-- <ol class="carousel-indicators">
                                            <?php
                                            /*                                            $i = 1;
                                                                                        foreach ($key_data as $key) {
                                                                                            */
                                            ?>
                                                <li data-target="#carousel-example-generic" data-slide-to="0" <?php /*if($i == 1) echo 'class="active"'; */
                                            ?>></li>
                                                <?php
                                            /*                                            $i++;
                                                                                        }
                                                                                        */
                                            ?>
                                        </ol>-->

                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner" role="listbox">
                                                <?php

                                                foreach ($cover_first_img as $key) {
                                                    $path = "uploads/" . $session_email . "/profile/" . $key['cover_pic'];
                                                    if (file_exists($path) && $key != "") {
                                                        ?>
                                                        <div class="item <?php if ($i == 1) echo 'active'; ?>">
                                                            <?php
                                                            echo '<img src="' . $path . '" />';
                                                            ?>
                                                        </div>
                                                        <?php
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                            </div>

                                            <!-- Controls -->
                                            <a class="left carousel-control" href="#carousel-example-generic"
                                               role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"
                                                      aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#carousel-example-generic"
                                               role="button" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right"
                                                      aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    <?php
                                    } else {
                                        echo "<img src='uploads/admin_background.jpg' style='height: 100%'>";
                                    } ?>

                                    <div class="p-align">
                                        <a class="cover-anchor" href="cover-profile.php"><i class="fas fa-edit"></i></a>
                                        <!-- <?php /*if (file_exists($coverPic) && $form_data['cover_pic'] != "") {
                                            */ ?>
                                            <a href="basic-user-info.php?delete_cover_id=<?php /*echo $security->encrypt($id); */ ?>&cover_img_path=<?php /*echo $form_data['cover_pic'];
                                        if ($android_url != "") echo "&" . $android_url; */ ?>"
                                           onclick="return confirm('Are You sure you want to remove cover photo?');" style="left: 10px;"><i class="fas fa-trash"></i></a>
                                        --><?php
                                        /*                                        }*/
                                        ?>
                                    </div>
                                </div>
                                <ul class="profile-left-ul">

                                    <li class="profile-pm-0">
                                        <div class="form-float text-align-profile" style="position: relative">
                                            <div id="uploaded_image">
                                                <img
                                                    src="<?php if (!file_exists($profilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                        echo "uploads/male_user.png";
                                                    } elseif (!file_exists($profilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                        echo "uploads/female_user.png";
                                                    } else {
                                                        echo $profilePath;
                                                    } ?>" class="profile_image">
                                            </div>
                                            <div class="contact-icon-btm">
                                                <input type="file" name="upload_image" id="upload_image"
                                                       accept="image/*"/>
                                                <a id="OpenImgUpload">
                                                    <div class="p-align"><i class="fas fa-camera"></i></div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
                                        ?>
                                        <li id="changeThemeLi">
                                            <div class="width-prf">
                                                <?php
                                                $user_id = $security->decrypt($id);
                                                $user_id = $security->encryptWebservice($user_id);
                                                ?>
                                                <a type="button" target="_blank"
                                                   href="<?php echo SHARED_URL . $session_custom_url_is ?>/<?php echo str_replace('=', 'equal', $user_id); ?>/active"
                                                   class="btn btn-info form-control change_theme"><img
                                                        src="assets/images/theme.png">Change Theme</a>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label"><i class="fas fa-user"></i></label>

                                            <div class="form-group form-group-left form-float">
                                                <div class="">
                                                    <lable name=label_txt_name"
                                                           class="form-control"> <?php if (isset($name)) echo $name; ?></lable>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label><i class="fas fa-restroom"></i></label>

                                            <div class="form-group form-group-left form-float">
                                                <div class="">
                                                    <lable name=label_txt_gender"
                                                           class="form-control"> <?php if (isset($gender)) echo $gender; ?></lable>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label"><i class="fa fa-phone"></i></label>

                                            <div class="form-group form-group-left form-float">
                                                <div class="">
                                                    <lable name=label_txt_name"
                                                           class="form-control"> <?php echo $session_contact_no; ?>
                                                    </lable>
                                                </div>
                                            </div>
                                            <a title="Edit Contact" class="add-icon-color fas fa-pencil-alt"
                                               href="basic-user-info.php?change_contact=<?php echo $session_contact_no;
                                               if ($android_url != "") echo "&" . $android_url; ?>"></a>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label"><i class="fas fa-envelope"></i></label>

                                            <div class="form-group form-group-left form-float">
                                                <div class="">
                                                    <lable name=label_txt_email"
                                                           class="form-control"><?php echo $session_email; ?>
                                                    </lable>
                                                </div>
                                            </div>
                                            <a title="Edit Email" class="add-icon-color fas fa-pencil-alt"
                                               href="basic-user-info.php?change_email=<?php echo $session_email;
                                               if ($android_url != "") echo "&" . $android_url; ?>"></a>
                                        </div>
                                    </li>
                                    <?php

                                    if ($verified_email_status != 1) {
                                        ?>
                                        <li>
                                            <div class="width-prf">
                                                <button class="btn btn-danger form-control"
                                                        onclick="show_verify_email_modal()" type="button"><i
                                                        class="fa fa-check-circle"></i> Verify Your Email
                                                </button>
                                            </div>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-7 col-sm-7 col-xs-12 padding_zero_both">
                <div class="row margin_div_web">
                    <?php if (isset($_GET['change_email'])) { ?>
                        <div class="card">
                            <div class="body">
                                <form id="form_validation" method="POST" action="" enctype="multipart/form-data">
                                    <fieldset class="padding_email_div">
                                        <legend class="legend_font_size" align="left">Change Email Id</legend>
                                        <?php if ($emailError) {
                                            ?>
                                            <div class="alert alert-danger">
                                                <?php if (isset($emailErrorMessage)) echo $emailErrorMessage; ?>
                                            </div>
                                        <?php
                                        } else if (!$emailError && $emailErrorMessage != "") {
                                            ?>
                                            <div class="alert alert-success">
                                                <?php if (isset($emailErrorMessage)) echo $emailErrorMessage; ?>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <div>
                                            <label class="form-label">Current Email Id</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="old_email" class="form-control"
                                                           placeholder="Email"
                                                           value="<?php echo $session_email; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Enter New Email Id</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="new_email" class="form-control"
                                                           placeholder="Enter New Email Id"
                                                           value="<?php if (isset($_SESSION['new_email'])) echo $_SESSION['new_email']; ?>" <?php if (isset($_SESSION['new_email'])) echo "disabled"; ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (isset($_SESSION['new_email'])) { ?>
                                            <div>
                                                <label class="form-label">Enter OTP</label>

                                                <div class="form-group form-float">
                                                    <div class="otp_section">
                                                        <div class="digit-group">
                                                            <input class="send_textbox" type="number" id="digit-1"
                                                                   name="txt_otp[]" data-next="digit-2"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-2"
                                                                   name="txt_otp[]" data-next="digit-3"
                                                                   data-previous="digit-1"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-3"
                                                                   name="txt_otp[]" data-next="digit-4"
                                                                   data-previous="digit-2"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <span class="splitter">&ndash;</span>
                                                            <input class="send_textbox" type="number" id="digit-4"
                                                                   name="txt_otp[]" data-next="digit-5"
                                                                   data-previous="digit-3"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-5"
                                                                   name="txt_otp[]" data-next="digit-6"
                                                                   data-previous="digit-4"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-6"
                                                                   name="txt_otp[]" data-previous="digit-5"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group form_inline form-float">
                                            <?php
                                            if (isset($_SESSION['new_email'])) {
                                                ?>
                                                <div style="width: 86% ">
                                                    <button type="submit" style="width: 30%"
                                                            class="btn btn-block bg-pink waves-effect"
                                                            name="verify_email_otp">
                                                        Verify
                                                        OTP
                                                    </button>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <button class="btn btn-default" type="submit" name="cancel_email">
                                                        Cancel
                                                    </button>
                                                </div>
                                                <button class="resend_otp" type="submit" name="resend_email_otp">Resend
                                                    Otp
                                                </button>
                                                <br>
                                            <?php
                                            } else {
                                                if (isset($_SESSION['type']) && $_SESSION['type'] == 'Admin') {
                                                    ?>
                                                    <button type="submit" class="btn btn-block bg-pink waves-effect"
                                                            style="width: 30%" name="admin_send_email_otp">
                                                        Change Email Id
                                                    </button>
                                                <?php
                                                } else {
                                                    ?>
                                                    <button type="submit" class="btn btn-block bg-pink waves-effect"
                                                            style="width: 30%" name="send_email_otp">
                                                        Send OTP
                                                    </button>
                                                <?php
                                                }
                                                ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a class="btn btn-default" href="basic-user-info.php">cancel</a>
                                            <?php

                                            }
                                            ?>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($_GET['change_contact'])) { ?>
                        <div class="card">
                            <div class="body">
                                <form id="contact_reset" method="POST" action="" enctype="multipart/form-data">
                                    <fieldset class="padding_email_div">
                                        <legend class="legend_font_size" align="left">update contact no</legend>
                                        <?php if ($contactError) {
                                            ?>
                                            <div class="alert alert-danger">
                                                <?php if (isset($contactErrorMessage)) echo $contactErrorMessage; ?>
                                            </div>
                                        <?php
                                        } else if (!$contactError && $contactErrorMessage != "") {
                                            ?>
                                            <div class="alert alert-success">
                                                <?php if (isset($contactErrorMessage)) echo $contactErrorMessage; ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <div>
                                            <label class="form-label">Current Mobile no</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="old_contact" class="form-control"
                                                           placeholder="Email"
                                                           value="<?php echo $session_contact_no; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Enter New Mobile no</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="new_contact" class="form-control"
                                                           placeholder="Enter New Mobile no"
                                                           onkeypress="return isNumberKey(event)"
                                                           value="<?php if (isset($_SESSION['new_contact'])) echo $_SESSION['new_contact']; ?>" <?php if (isset($_SESSION['new_contact'])) echo "disabled"; ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (isset($_SESSION['new_contact'])) { ?>
                                            <div>
                                                <label class="form-label">Enter OTP</label>

                                                <div class="form-group form-float">
                                                    <div class="otp_section">
                                                        <div class="digit-group">
                                                            <input class="send_textbox" type="number" id="digit-1"
                                                                   name="contact_otp[]" data-next="digit-2"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-2"
                                                                   name="contact_otp[]" data-next="digit-3"
                                                                   data-previous="digit-1"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-3"
                                                                   name="contact_otp[]" data-next="digit-4"
                                                                   data-previous="digit-2"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <span class="splitter">&ndash;</span>
                                                            <input class="send_textbox" type="number" id="digit-4"
                                                                   name="contact_otp[]" data-next="digit-5"
                                                                   data-previous="digit-3"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-5"
                                                                   name="contact_otp[]" data-next="digit-6"
                                                                   data-previous="digit-4"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                            <input class="send_textbox" type="number" id="digit-6"
                                                                   name="contact_otp[]" data-previous="digit-5"
                                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                   maxlength="1"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group form_inline form-float">
                                            <?php
                                            if (isset($_SESSION['new_contact'])) {
                                                ?>
                                                <div style="width: 86% ">
                                                    <button type="submit" style="width: 30%"
                                                            class="btn btn-block bg-pink waves-effect"
                                                            name="verify_contact_otp">
                                                        Verify
                                                        OTP
                                                    </button>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <button class="btn btn-default" type="submit" name="cancel_sms">
                                                        Cancel
                                                    </button>
                                                    <br>
                                                </div>
                                                <button class="resend_otp" name="resend_contact_otp">Resend Otp</button>
                                            <?php
                                            } else {
                                                ?>
                                                <?php
                                                if (isset($_SESSION['type']) && $_SESSION['type'] == 'Admin') {

                                                    ?>
                                                    <button type="submit" class="btn btn-block bg-pink waves-effect"
                                                            style="width: 30%" name="admin_change_contact">
                                                        Change Contact Number
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php
                                                } else {
                                                    ?>
                                                    <button type="submit" class="btn btn-block bg-pink waves-effect"
                                                            style="width: 30%" name="send_contact_otp">
                                                        Send OTP
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php
                                                }
                                                ?>
                                                <a class="btn btn-default" href="basic-user-info.php">cancel</a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-9 col-md-7 col-sm-7 col-xs-12 padding_zero_both">
                <div class="row margin_div_web">
                    <div class="card">
                        <div class="body custom_card_padding">
                            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                <li role="presentation" <?php if (!$active_tab && !$business_tab) { ?> class="active" <?php } ?>>
                                    <a
                                        href="#profile" class="custom_nav_tab"
                                        data-toggle="tab"><?php echo $_SESSION['menu']['s_basic_info'] ?></a>
                                </li>
                                <li role="presentation"
                                    onclick="openLogo_div(this)" <?php if ($active_tab) { ?> class="active" <?php } ?>>
                                    <a
                                        class="custom_nav_tab"
                                        data-toggle="tab"><?php echo $_SESSION['menu']['s_company_info'] ?> <label
                                            class="label label-success company_new_label">New</label>  <?php if ($_SESSION['red_dot']['company_name'] == true) echo '<div class="remaining_sub_form_dot"></div>' ?>
                                    </a>
                                </li>
                                <li role="presentation"  <?php if ($business_tab) { ?> class="active" <?php } ?>><a
                                        class="custom_nav_tab" href="#busines_link"
                                        data-toggle="tab">Business Link <label
                                            class="label label-success company_new_label">New</label></a>
                                </li>
                                <li class="profile_theme_btn">
                                    <button class="btn btn-warning shine " data-toggle="modal" data-target="#myModal">
                                        Profile Theme
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel"
                                     class="tab-pane <?php if (!$active_tab && !$business_tab) echo "fade in active" ?>"
                                     id="profile">
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
                                        <form id="basic_user_info" method="POST" action=""
                                              enctype="multipart/form-data">
                                            <fieldset>
                                                <legend class="legend_font_size" align="left">Basic Information</legend>
                                                <ul class="profile-ul">
                                                    <!--<li>
                                        <div class="width-prf">
                                            <label class="form-label">Upload Image</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input class="form-control" type="file" id="upload" name="upload[]"
                                                           multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG"
                                                           value="<?php /*if (isset($filename)) echo $filename; */ ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>-->
                                                    <li>
                                                        <div class="width-prf">
                                                            <label class="form-label">Name</label> <span
                                                                class="required_field">*</span>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <input name="txt_name" class="form-control"
                                                                           value="<?php if (isset($name)) echo $name; ?>">
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
                                                                           value="<?php if (isset($designation)) echo $designation; ?>">
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
                                                                           value="<?php if (isset($date_of_birth)) echo $date_of_birth; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="width-prf">
                                                            <label>Business Category</label>
                                                            <!--<span class="required_field">*</span>-->
                                                            <div class="form-group form-float">
                                                                <div class="form-line frmSearch">
                                                                    <input class="form-control"
                                                                           name="drp_business_category"
                                                                           placeholder="Search Business Category"
                                                                           type="text" autocomplete="off"
                                                                           id="search-box"
                                                                           value="<?php if (isset($business_category)) echo $business_category; ?>">

                                                                    <div id="suggesstion-box"></div>
                                                                    <!--      <select id="business_select"
                                                                            name="drp_business_category"
                                                                            data-live-search="true"
                                                                            class="gender_li form-control"
                                                                            onchange="UIHideShow(this.value)">
                                                                        <option value="">Select an option</option>
                                                                        <option value="other" <?php /*if ((isset($optional_status) && $optional_status == 1)) echo 'selected="selected"'; */ ?>>Other
                                                                        </option>
                                                                        <?php
                                                                    /*                                                                        if ($get_business != null) {
                                                                                                                                                while ($get_data = mysqli_fetch_array($get_business)) {
                                                                                                                                                    */ ?>
                                                                                <option <?php /*if (isset($business_category) && (isset($optional_status) && $optional_status == 0) && $business_category == $get_data['business_category']) echo 'selected' */ ?>><?php /*echo $get_data['business_category']; */ ?>
                                                                                </option>
                                                                                <?php
                                                                    /*                                                                            }
                                                                                                                                            }
                                                                                                                                            */ ?>
                                                                    </select>-->
                                                                </div>
                                                            </div>
                                                            <!-- <div class="form-group <?php /*if (!$drp_other) echo "other_input"; */ ?> form-float"
                                                                 style="display: none">
                                                                <div class="form-line">
                                                                    <input type="text" class="form-control"
                                                                           value="<?php /*if (isset($business_category)) echo $business_category */ ?>"
                                                                           placeholder="Other Business Category Name"
                                                                           name="other_business">
                                                                </div>
                                                            </div>-->
                                                        </div>
                                                    </li>
                                                    <li class="user_alt_contact">
                                                        <div class="width-prf">
                                                            <label class="form-label">Alternate Contact</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                    <input type="text" name="txt_alt_contact"
                                                                           class="form-control"
                                                                           onkeypress="return isNumberKey(event)"
                                                                           oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                           placeholder="Alternet Contact Number"
                                                                           maxlength="10"
                                                                           value="<?php if (isset($alter_contact_no)) echo $alter_contact_no; ?>">
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
                                                                           value="<?php if (isset($whatsapp_no) && $whatsapp_no != "") {
                                                                               echo $whatsapp_no;
                                                                           } else {
                                                                               echo $session_contact_no;
                                                                           }; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="width-prf">
                                                            <label class="form-label">Landline Number</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                    <input type="number" name="landline_number"
                                                                           class="form-control"
                                                                           placeholder="Enter Landline Number"
                                                                           value="<?php if (isset($landline_number)) echo $landline_number; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="user_address"><!-- class="user_keyword" -->
                                                        <div class="width-prf">
                                                            <label class="form-label">Display Email (In digital
                                                                Card)</label>

                                                            <div class="form-group form-group-xl form-float" style="transition: all 0.25s ease-in 0s;
    border-bottom: 2px solid rgb(31, 145, 243);">

                                                                <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                <input type="text" name="saved_email"
                                                                       class="form-control" id="display_email"
                                                                       value="<?php if (isset($saved_email) && $saved_email != "") {
                                                                           echo $saved_email;
                                                                       } else {
                                                                           echo $session_email;
                                                                       } ?>">

                                                            </div>
                                                            <span style='color: red'>Note: You Can Add Multiple Email id with comma(',') separated</span>
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
                                                                       value="<?php if (isset($keyword)) echo $keyword; ?>">
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
                                                                            data-live-search="true"
                                                                            onchange="getCityByStateId(this.value)">
                                                                        <option value="">Select an option</option>
                                                                        <?php
                                                                        if ($get_state != null) {

                                                                            while ($get_state_data = mysqli_fetch_array($get_state)) {
                                                                                ?>
                                                                                <option <?php if (isset($user_state) && $user_state == $get_state_data['id']) echo 'selected'; ?>
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
                                                                        <select name="txt_city" data-live-search="true"
                                                                                class="gender_li form-control">
                                                                            <option value="">Select an option</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="width-prf">
                                                            <label class="form-label">Locality</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                                    <input type="text" name="txt_locality"
                                                                           class="form-control"
                                                                           placeholder="Enter locality"
                                                                           value="<?php if (isset($locality)) echo $locality; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="user_address">
                                                        <div class="width-prf">
                                                            <label class="form-label">Address</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                    <textarea id="default" name="basic-address" class="form-control"
                                              placeholder="Address"><?php if (isset($address)) echo $address; ?></textarea>
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
                                                        <div style="padding-left: 11px">
                                                            <input type="checkbox" name="social_status"
                                                                   value="1" <?php if (isset($hide_social_status) && $hide_social_status == 1) echo 'checked'; ?> >
                                                            Hide
                                                            social link if not entered<br>
                                                        </div>
                                                    </ul>


                                                </fieldset>
                                            </div>

                                            <div class="form-group text-center">
                                                <button name="btn_update" type="submit"
                                                        class="btn btn-primary waves-effect">Update Profile
                                                </button>
                                                <!--&nbsp;&nbsp;
                                                  <div>
                                                      <input type="reset" class="btn btn-default" value="reset">
                                                  </div>-->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane <?php if ($active_tab) echo "fade in active" ?>"
                                     id="company_info">
                                    <?php if ($errorCompany) {
                                        ?>
                                        <div class="alert alert-danger">
                                            <?php if (isset($errorCMessage)) echo $errorCMessage; ?>
                                        </div>
                                    <?php
                                    } else if (!$errorCompany && $errorCMessage != "") {
                                        ?>
                                        <div class="alert alert-success">
                                            <?php if (isset($errorCMessage)) echo $errorCMessage; ?>
                                        </div>
                                    <?php
                                    } ?>
                                    <form id="company Info" method="POST" action="" enctype="multipart/form-data">
                                        <fieldset>
                                            <legend class="legend_font_size" align="left">Company Info</legend>
                                            <ul class="company_profile_ul">
                                                <li>
                                                    <div class="width-prf">
                                                        <label class="form-label">Company Name</label> <span>*</span>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input name="company_name" class="form-control"
                                                                       placeholder="Company Name"
                                                                       value="<?php if (isset($company_name)) echo $company_name; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                                if ($country == '101') {
                                                    ?>
                                                    <li>
                                                        <div class="width-prf">
                                                            <label class="form-label">GST No</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <input name="txt_gst_no" class="form-control"
                                                                           placeholder="GST NO"
                                                                           value="<?php if (isset($gst_no)) echo $gst_no; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="user_alt_contact">
                                                        <div class="width-prf">
                                                            <label class="form-label">PAN No</label>

                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <input name="txt_pan_no" class="form-control"
                                                                           placeholder="PAN NO"
                                                                           value="<?php if (isset($pan_no)) echo $pan_no; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                                <li class="user_address">
                                                    <div class="width-prf">
                                                        <label class="form-label">About us</label> <span>*</span>

                                                        <div class="form-group form-float">
                                    <textarea id="default" name="about_us" class="form-control "
                                              placeholder="About Your Company"><?php if (isset($about_us)) echo str_replace('\r\n', '', $about_us); ?></textarea>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="user_address">
                                                    <div class="width-prf">
                                                        <label class="form-label">Mission & Vision</label>

                                                        <div class="form-group form-float">
                                    <textarea id="default" name="txt_mission" class="form-control "
                                              placeholder="Mission And Vission Of Your Comoany"><?php if (isset($our_mission)) echo str_replace('\r\n', '', $our_mission); ?></textarea>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li style="width: 49%">
                                                    <div class="width-prf">
                                                        <label class="form-label">Upload Company Profile</label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input class="form-control" type="file"
                                                                       name="company-profile[]"
                                                                       accept=".pdf,.png, .jpg, .jpeg,.JPG,.PNG">
                                                                <span style='color: red'>Note: Allow only images & pdf (Max 2 MB)</span>
                                                            </div>
                                                            <div>
                                                                <?php
                                                                if (isset($companyPath) && file_exists($companyPath)) {
                                                                    ?>

                                                                    <br>
                                                                    <a href="<?php echo $companyPath ?>"
                                                                       target="_blank"><i class="fas fa-eye"></i>&nbsp;&nbsp;Preview</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                                                    <a href="basic-user-info.php?remove_profile=true&profile_path=<?php echo $security->encryptWebservice($form_data['company_profile']);
                                                                    if ($android_url != "") echo "&" . $android_url; ?>"
                                                                       onclick="return confirm('Are You sure you want to remove company profile?');">
                                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete
                                                                        profile</a>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li style="width: 49%" class="company_logo_li">
                                                    <div class="width-prf">
                                                        <label class="form-label">Upload Company Logo <label
                                                                class="label label-success company_new_label">New</label></label>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input class="form-control" type="file"
                                                                       name="company-logo[]"
                                                                       accept=".png, .jpg, .jpeg,.JPG,.PNG">
                                                                <span style='color: red'>Note: Allow only images (Max 2 MB)</span>
                                                            </div>
                                                            <div>
                                                                <?php
                                                                if (isset($companyLogoPath) && file_exists($companyLogoPath)) {
                                                                    ?>
                                                                    <br>
                                                                    <a href="<?php echo $companyLogoPath ?>"
                                                                       target="_blank"><i class="fas fa-eye"></i>&nbsp;&nbsp;Preview</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <a href="basic-user-info.php?remove_profile_logo=true&profile_path=<?php echo $security->encryptWebservice($form_data['company_logo']);
                                                                    if ($android_url != "") echo "&" . $android_url; ?>"
                                                                       onclick="return confirm('Are You sure you want to remove company profile?');">
                                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete
                                                                        Company Logo</a>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="form-group text-center">
                                                <button name="update_company" type="submit"
                                                        class="btn btn-primary waves-effect">Update Company Profile
                                                </button>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane <?php if ($business_tab) echo "fade in active" ?>"
                                     id="busines_link">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php if ($errorBusiness) {
                                                    ?>
                                                    <div class="alert alert-danger">
                                                        <?php if (isset($errorBMessage)) echo $errorBMessage; ?>
                                                    </div>
                                                <?php
                                                } else if (!$errorBusiness && $errorBMessage != "") {
                                                    ?>
                                                    <div class="alert alert-success">
                                                        <?php if (isset($errorBMessage)) echo $errorBMessage; ?>
                                                    </div>
                                                <?php
                                                } ?>
                                                <form method="POST" action="" enctype="multipart/form-data">
                                                    <fieldset>
                                                        <legend class="legend_font_size" align="left">Other Business
                                                            Link
                                                        </legend>

                                                        <div class="col-md-12">
                                                            <table rules="all" style="width: 100%; text-align: center">
                                                                <tr id="rowId">
                                                                    <td>
                                                                        <div class="width-prf">
                                                                            <div class="form-group form-float">
                                                                                <div class="form-line"
                                                                                     style="display: flex">
                                                                                     <!-- onchange="show_contact_div(this.value)" -->
                                                                                     <select
                                                                                        style="border-bottom: 0 !important;"
                                                                                        >
                                                                                        <option value="">Select
                                                                                            Business
                                                                                        </option>
                                                                                        <option>Google</option>
                                                                                        <option>Amazon</option>
                                                                                        <option>Just Dial</option>
                                                                                        <option>Swiggy</option>
                                                                                        <option>Zomato</option>
                                                                                        <option>India Mart</option>
                                                                                        <option>WhatsApp</option>
                                                                                        <option>Telegram</option>
                                                                                        <!-- <option>Other</option> -->
                                                                                    </select>

                                                                                    <input name="txt_links[]"
                                                                                           type="text"
                                                                                           id="business-input"
                                                                                           class="form-control"
                                                                                           placeholder="Enter Link"
                                                                                           required="required"
                                                                                           value="<?php if (isset($txt_link)) echo $txt_link; ?>"
                                                                                           autofocus/>
                                                                                    <?php
                                                                                    if (!isset($_GET['update_link'])) {
                                                                                        ?>
                                                                                        &nbsp;&nbsp;<span
                                                                                            class="plus_icon"
                                                                                            onclick="addMoreRows(this.form);"><i
                                                                                                class="fa fa-plus"
                                                                                                aria-hidden="true"></i></span>
                                                                                    <?php

                                                                                    }
                                                                                    ?>

                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </td>

                                                                </tr>

                                                            </table>
                                                            <!-- <div class="col-md-12" id="contact_div"
                                                                 style="display: none;background: #ffefb0;">
                                                                <div>
                                                                    <h4>Please Contact Us on this below number to add
                                                                        more business link : </h4>
                                                                    <ul>
                                                                        <li>+91 9773884631</li>
                                                                        <li>+91 9768904980</li>
                                                                    </ul>
                                                                </div>
                                                            </div> -->
                                                            <div id="addedRows"></div>
                                                            <br>

                                                            <div class="form-group">
                                                                <?php
                                                                if (isset($_GET['update_link'])) {
                                                                    ?>

                                                                    <button name="update_business" type="submit"
                                                                            class="btn btn-primary waves-effect">Update
                                                                        Business Link
                                                                    </button>

                                                                <?php
                                                                } else {
                                                                    ?>

                                                                    <button name="insert_business" type="submit"
                                                                            class="btn btn-primary waves-effect">Insert
                                                                        Business Link
                                                                    </button>

                                                                <?php
                                                                }
                                                                ?>
                                                                <a href="basic-user-info.php"
                                                                   class="btn btn-danger waves-effect">Cancel
                                                                </a>
                                                            </div>
                                                        </div>


                                                    </fieldset>
                                                </form>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="">

                                                    <div style="overflow-x: auto">
                                                        <table
                                                            class="table table-striped table-bordered table-sm "
                                                            cellspacing="0"
                                                            width="100%">
                                                            <thead>
                                                            <tr class="back-color">
                                                                <th style="width: 50%">Business Links</th>
                                                                <th style="width: 50%">ACTION</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="row_position">
                                                            <?php
                                                            if ($get_result != null) {
                                                                while ($result_data = mysqli_fetch_array($get_result)) {

                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php

                                                                            echo $result_data['link'];
                                                                            ?>
                                                                        </td>


                                                                        <td>
                                                                            <ul class="header-dropdown">
                                                                                <li class="dropdown dropdown-inner-table">
                                                                                    <a href="javascript:void(0);"
                                                                                       class="dropdown-toggle"
                                                                                       data-toggle="dropdown"
                                                                                       role="button"
                                                                                       aria-haspopup="true"
                                                                                       aria-expanded="false">
                                                                                        <i class="material-icons">more_vert</i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu pull-right">
                                                                                        <li>
                                                                                            <a href="basic-user-info.php?update_link=<?php echo $security->encrypt($result_data['id']);
                                                                                            if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                            <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="basic-user-info.php?delete_data=<?php echo $security->encrypt($result_data['id']);
                                                                                            if ($android_url != "") echo "&" . $android_url; ?>"
                                                                                               onclick="return confirm('Are You sure you want to delete?');">
                                                                                                <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            <?php
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="10" class="text-center">No data
                                                                        found!
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->

            <!-- #Footer -->

        </div>

        <?php
        /*if (!isset($_GET['android_user_id']) && (!isset($_GET['type']) && $_GET['type'] != "android") && (!isset($_GET['api_key']))) {
            */ ?><!--
            <div id="advert-once">
                <div id="bkgOverlay" class="backgroundOverlay"></div>

                <div id="delayedPopup" class="delayedPopupWindow">
                    <a href="#" id="btnClose" title="Click here to close this deal box.">
                        <i class="fa fa-times-circle"></i>
                    </a>

                    <div id="mc_embed_signup" class="text-center">
                        <form action="" method="post" id="mc-embedded-subscribe-form"
                              name="mc-embedded-subscribe-form"
                              class="validate"
                              target="_blank" novalidate="">
                            <a href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard"><img
                                    src="assets/images/download_app.png"></a>
                        </form>
                    </div>

                </div>
            </div>
        --><?php
        /*        }*/
        ?>


    </section>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header cust-upi-madal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Theme</h4>
                </div>
                <div class="modal-body">
                    <form id="upi_form_validation" method="POST" action="">
                        <div class="">
                            <ul class="company_ul">
                                <li class="company_ul_li">
                                    <input type="radio" id="myCheckbox1" name="rd_theme"
                                           value="1" <?php if (isset($section_theme) && $section_theme == 1) echo "checked"; ?>  />
                                    <label for="myCheckbox1"><img src="assets/images/theme/profile1.PNG"
                                                                  style="width: 90%"/></label>
                                </li>
                                <li class="company_ul_li">
                                    <input type="radio" id="myCheckbox2" name="rd_theme"
                                           value="2" <?php if (isset($section_theme) && $section_theme == 2) echo "checked"; ?> />
                                    <label for="myCheckbox2"><img src="assets/images/theme/profile2.PNG"/></label>
                                </li>
                                <li class="company_ul_li" style="width: 100%;margin-top: 15px;">
                                    <button class="btn btn-primary waves-effect form-control"
                                            name="btn_update_theme"
                                            type="submit">
                                        Update Theme
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js"></script>
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
    <script>
        function openLogo_div(val) {
            $('.nav-tabs > li').removeClass('active');
            $('#profile,#busines_link').removeClass('in active');
            $(val).addClass('active');
            $('#company_info').addClass('active');
            $('#company_info').addClass('in');
            $('html,body').animate({
                    scrollTop: $(".company_logo_li").offset().top
                },
                'slow');
        }
    </script>


    <script>

        $("#search-box").keyup(function () {
            $.ajax({
                type: "POST",
                url: "upload.php",
                data: 'keyword=' + $(this).val(),
                success: function (data) {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                }
            });
        });


        function selectCountry(val) {
            $("#search-box").val(val);
            $("#suggesstion-box").hide();
        }
    </script>
    <script>
        /*  const editor = new LiteEditor('.js-editor');*/
    </script>
    <?php
    if ($user_state != "") {
        ?>
        <script>
            $(document).ready(function () {
                getCityByStateId($('select[name=txt_state]').val());
            })
        </script>
    <?php
    }elseif (isset($current_region) && $current_region != ''){
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
    <!-- <script type="text/javascript">
         if (screen.width <= 768 || screen.height == 480){
             document.getElementById('delayedPopup').style.display='none';
             document.getElementById('bkgOverlay').style.display='none';
             document.getElementById('changeThemeLi').style.display='none';
         }
     </script>-->
    <script>

    </script>
    <!-- <script>
         $(document).ready(function () {
             $cover_image_crop = $('#cover_image_demo').croppie({
                 enableExif: true,
                 viewport: {
                     width: 720,
                     height: 194,
                     type: 'square' //circle
                 },
                 boundary: {
                     width: 720,
                     height: 194
                 }
             });
             $('#cover_image').on('change', function () {
                 var reader = new FileReader();
                 reader.onload = function (event) {
                     $cover_image_crop.croppie('bind', {
                         url: event.target.result
                     }).then(function () {
                         console.log('jQuery bind complete');
                     });
                 }
                 reader.readAsDataURL(this.files[0]);
                 $('#edit_cover_pic').modal('show');
             });

             $('.crop_cover_image').click(function (event) {
                 $cover_image_crop.croppie('result', {
                     type: 'canvas',
                     size: 'viewport'
                 }).then(function (response) {
                     $.ajax({
                         url: "upload.php",
                         type: "POST",
                         data: {"cover_image": response},
                         success: function (data) {
                             $('#edit_cover_pic').modal('hide');
                             $('#uploaded_cover_image').html(data);
                         }
                     });
                 })
             });
         });
     </script>-->
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
            localStorage.setItem('show_email_modal', 'false');
            $('#overlay').hide();
            $('.verify_number_div').hide();
        }
        function show_verify_email_modal() {
            localStorage.setItem('show_email_modal', 'true');
            $('#overlay').show();
            $('.verify_number_div').show();
        }
    </script>
    <script>
        if (localStorage.getItem('show_email_modal') == "false") {
            $('#overlay').hide();
            $('.verify_number_div').hide();
        } else {
            $('#overlay').show();
            $('.verify_number_div').show();
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
            var dataString = 'state_id=' + value<?php if (isset($city) && $city != ""){ echo "+'&city_name='+'" . $city . "'";}elseif (isset($current_city) && $current_city !="") echo "+'&city_name='+'" . $current_city . "'"; ?>;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>

    <script>
        $(document).ready(function () {
            $('#skill,#display_email').tokenfield({
                showAutocompleteOnFocus: false
            });
            /*$('#').tokenfield({
             showAutocompleteOnFocus: false
             });*/
        });
    </script>

    <script type="text/javascript">
        var rowCount = 1;

        function addMoreRows(frm) {
            rowCount++;

            /*var recRow = '<p id="rowCount'+rowCount+'" ><tr><td><div class="input-group" style="display: flex"><span class="input-group-addon" style="width: 49px;">+91</span> <input name="contact_no" type="number" class="form-control" placeholder="Enter Number" required="required"/> &nbsp;&nbsp;</div></td></tr><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times plus_icon" aria-hidden="true"></i></a></p>';*/
            var recRow = '<div class="form-group form-float" id="rowCount' + rowCount + '"><div class="form-line" style="display: flex"><tr><td style="display: table; position: relative;border-collapse: separate" > <input name="txt_links[]" type="text" class="form-control" placeholder="Enter Link" autofocus required="required" /></td></tr>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');"><i class="fa fa-minus plus_icon" aria-hidden="true"></i></a></div></div>';
            jQuery('#addedRows').append(recRow);
        }
        function removeRow(removeNum) {
            jQuery('#rowCount' + removeNum).remove();
        }
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
    <script>
        function show_contact_div(val) {
            if (val == "Other") {
                $('#contact_div').css('display', 'block');
                $('.plus_icon').css('display', 'none');
                $('#business-input').css('display', 'none');
            } else {
                $('.plus_icon').css('display', 'block');
                $('#contact_div').css('display', 'none');
                $('#business-input').css('display', 'block');
            }
        }
    </script>

    <!--
    <script type="text/javascript">
        document.getElementById("b3").onclick = function () {
            swal("Good job!", "You clicked the button!", "success");
        };
    </script>-->

</body>
</html>