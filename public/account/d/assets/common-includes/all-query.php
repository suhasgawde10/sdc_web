<?php
/*print_r($_GET);
die();*/
$date = date("Y-m-d");
$user_expired_status = false;
if (isset($_GET['custom_url']) && (isset($_GET['theme']) && $_GET['theme'] == "active")) {
    if ((isset($_POST['background_image1']))) {
        if (isset($_POST['btn_apply'])) {
            if (!$Themerror) {
                $updateTheme = $manage->updateUserTheme($_POST['background_image1'], $_GET['custom_url']);
                if ($updateTheme) {
                    /*header('location:index.php?custom_url=' . $_GET['custom_url']);*/
                }
            } else {
                $Themerror = true;
                $ThemerrorMessage = "Please select Theme!";
            }
        }
    }
}
if (isset($_POST['upload_theme'])) {
    $custom_url = trim($_GET['custom_url']);
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
    $user_id = $get_data['user_id'];
    $name = $get_data['name'];
    if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $title = $_POST['txt_title'];
    } else {
        $title = rand(10, 100);
    }

    if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 /* 4 means there is no file selected*/) {
        $imgUploadStatus = true;
        $directory_name = "../theme/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $maxsize = 2097152;
        $total = count($_FILES['upload']['name']);
        for ($i = 0; $i < $total; $i++) {
            $filename = $_FILES['upload']['name'][$i];
            $extensionStatus = $validate->validateFileExtension($filename, $extension);
            if (!$extensionStatus) {
                $error = true;
                $errorMessage .= "Please select valid file extension";
            }
            if (($_FILES['upload']['size'][$i] >= $maxsize)) {
                $error = true;
                $errorMessage .= 'File too large. File must be less than 2 megabytes.';
            }
        }
    } else {
        $error = true;
        $errorMessage .= 'Please select file';
    }

    if (!$error) {
        if ($imgUploadStatus) {
            $digits = 4;
            $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $newimgname = "";

            for ($i = 0; $i < $total; $i++) {
                $filearray = array();
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                $newimgname = $randomNum . '.' . $file_extension;
                $cover_name = str_replace([' ', '_'], '-', $newimgname);
                $newPath = $directory_name . $cover_name;
                // Compress Image
                $upload = compressImage($tmpFilePath, $newPath, 60);
                if (!$upload) {
                    $error = true;
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 1213 megabytes";
                } else {
                    $update_photo = $manage->addTheme($user_id, $title, $cover_name, $cover_name);
                    if ($update_photo) {
                        $updateTheme = $manage->updateUserTheme($cover_name, $_GET['custom_url']);
                        if ($updateTheme) {
                            $error = false;
                            $errorMessage = "Image has been uploaded and set as your default theme.";
                        } else {
                            $error = true;
                            $errorMessage = "Issue while uploading123\nNote: File too large. File must be less than 4 megabytes";
                        }

                    }
                }
            }
        } else {
            $error = true;
            $errorMessage .= "Issue while uploading\nNote: File too large. File must be less than 1 megabytes";
        }

    }
}

if (isset($_GET['custom_url']) && $_GET['custom_url'] != '') {
    $custom_url = $_GET['custom_url'];

    $validate_custom_url = $manage->validCustomUrl($custom_url);
    if ($validate_custom_url) {

        $get_data = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
        // dd($get_data);
        if ($get_data != null) {
            $user_status = $get_data['status'];
            $keyword = $get_data['user_keyword'];
            $parent_id = $get_data['parent_id'];
            $expiry_date = $get_data['expiry_date'];
            $user_email = $get_data['email'];
            $img_name = $get_data['img_name'];

            if ($parent_id != "") {
                $getParentData = $manage->getSpecificUserProfileById($parent_id);
                $parent_custom_url = $getParentData['custom_url'];
                $email = $getParentData['email'];
                $user_id = $getParentData['user_id'];
                $default_user_id = $getParentData['user_id'];
                $section_user_id = $getParentData['user_id'];
                $about_company = $getParentData['about_company'];
                $company_name = $getParentData['company_name'];
                $our_mission = $getParentData['our_mission'];
                $company_profile = $getParentData['company_profile'];
                $user_theme = $getParentData['user_theme'];
                $cover_pic = $getParentData['cover_pic'];
                if ($getParentData['cover_pic'] != "") {
                    $key_data = explode(',', $getParentData['cover_pic']);
                } else {
                    $key_data = 0;
                }
            } else {
                $user_theme = $get_data['user_theme'];
                $parent_custom_url = $get_data['custom_url'];
                $user_id = $get_data['user_id'];
                $section_user_id = $get_data['user_id'];
                $about_company = $get_data['about_company'];
                $company_name = $get_data['company_name'];
                $our_mission = $get_data['our_mission'];
                $email = $get_data['email'];
                $company_profile = $get_data['company_profile'];
                $cover_pic = $get_data['cover_pic'];
                if ($get_data['cover_pic'] != "") {
                    $key_data = explode(',', $get_data['cover_pic']);
                } else {
                    $key_data = 0;
                }
                $default_user_id = $get_data['user_id'];
            }

            $sectionStatus = $manage->getSectionStatusByUser($section_user_id);
            // dd($sectionStatus);
            // exit;

            if($sectionStatus!=null){
                $ProfileSectionStatus = $sectionStatus["profile_status"];
                $ServiceSectionStatus = $sectionStatus["service_status"];
                $ProductSectionStatus = $sectionStatus["product_status"];
                $gallerySectionStatus = $sectionStatus["gallery_status"];
                $ClientSectionStatus = $sectionStatus["client_status"];
                $TeamSectionStatus = $sectionStatus["team_status"];
                $BankSectionStatus = $sectionStatus["bank_status"];
            }

            /*$ProfileSectionStatus = $manage->getSectionStatus($section_user_id, 10);
            $ServiceSectionStatus = $manage->getSectionStatus($section_user_id, 1);
            $ProductSectionStatus = $manage->getSectionStatus($section_user_id, 11);
            $gallerySectionStatus = $manage->getSectionStatus($section_user_id, 2);
            $ClientSectionStatus = $manage->getSectionStatus($section_user_id, 4);
            $TeamSectionStatus = $manage->getSectionStatus($section_user_id, 6);
            $BankSectionStatus = $manage->getSectionStatus($section_user_id, 7);*/

            $sectionImage = $manage->getSectionImageDataByUser($section_user_id);
            // dd($sectionImage);
            // exit;
            if($sectionImage!=null){
                $profileTabIcon = $sectionImage["profile_status"] ?? "";
                $ServiceTabIcon = $sectionImage["service_status"] ?? "";
                $ProductTabIcon = $sectionImage["product_status"] ?? "";
                $galleryTabIcon = $sectionImage["gallery_status"] ?? "";
                $ClientTabIcon = $sectionImage["client_status"] ?? "";
                $TeamTabIcon = $sectionImage["team_status"] ?? "";
                $BankTabIcon = $sectionImage["bank_status"] ?? "";
            }
            else{
                $profileTabIcon = "";
                $ServiceTabIcon = "";
                $ProductTabIcon = "";
                $galleryTabIcon = "";
                $ClientTabIcon = "";
                $TeamTabIcon = "";
                $BankTabIcon = "";
            }

            /*$profileTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 10);
            $ServiceTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 1);
            $ProductTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 11);
            $galleryTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 2);
            $ClientTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 4);
            $TeamTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 6);
            $BankTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 7);*/


            $user_city = $get_data['city'];
            if ($keyword != "") {
                $keyword_array_data = explode(',', $keyword);
            } else {
                $keyword_array_data = "";
            }
        }
        /*   $validStatus = $manage->validateUserStatus($default_user_id);
           if ($validStatus) {*/

        $getSubscription = $manage->getUserSubscriptionDetails($default_user_id);
        if ($getSubscription != null) {
            if ($getSubscription['year'] != "Life Time") {
                if ($expiry_date < $date) {
                    if (isset($_SESSION['type']) && ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor")) {
                        echo '<style>.sub_expired{ display: block !important;}</style>';
                    } else {
                        /* echo "<style>.end_sub_overlay{display: block!important;}</style>";*/
                        $user_expired_status = true;
                    }
                }
            }
        } else {
            if (isset($_SESSION['type']) && ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor")) {
                echo '<style>.sub_expired{ display: block !important;}</style>';
            } else {
                $user_expired_status = true;
                /* echo "<style>.end_sub_overlay{display: block!important;}</style>";*/
            }

        }
        /* $section_service_id = 1;
         $get_service_status = $manage->displayOnOffStatus($custom_url, $section_service_id);
         $section_image_id = 2;
         $get_image_status = $manage->displayOnOffStatus($custom_url, $section_image_id);
         $section_video_id = 3;
         $get_video_status = $manage->displayOnOffStatus($custom_url, $section_video_id);
         $section_client_id = 4;
         $get_client_status = $manage->displayOnOffStatus($custom_url, $section_client_id);
         $section_client_review_id = 5;
         $get_client_review_status = $manage->displayOnOffStatus($custom_url, $section_client_review_id);
         $section_our_team_id = 6;
         $get_our_team_status = $manage->displayOnOffStatus($custom_url, $section_our_team_id);
         $section_bank_id = 7;
         $get_bank_status = $manage->displayOnOffStatus($custom_url, $section_bank_id);*/
        /*  } else {

              header('location:../login.php');
          }*/

    } else {
        $validate_custom_url_log = $manage->validCustomUrlFromLog($custom_url);
        if ($validate_custom_url_log != null) {
            $get_user_id = $validate_custom_url_log['user_id'];
            $get_custom_url = $manage->gettingCustomUrl($get_user_id);
            if ($get_custom_url != null) {
                $custom_url = $get_custom_url['custom_url'];
                header('location:index.php?custom_url=' . $custom_url);
            } else {
                header('location:../index.php');
            }
        } else {
            header('location:../index.php');
        }
    }

} else {
    header('location:../index.php');
}

if ($parent_id != "") {
    $profilePath = FULL_WEBSITE_URL . "user/uploads/" . $email . "/" . $user_email . "/profile/" . $img_name;

    if ($img_name != "" && check_url_exits($profilePath)) {
        $metaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $email . "/" . $user_email . "/profile/" . $img_name;
    } else {
        $metaProfilePath = "";
    }
    if ($getParentData['company_logo'] != "") {
        $companyLogoPath = FULL_WEBSITE_URL . "user/uploads/" . $email . "/profile/" . $getParentData['company_logo'];
    } else {
        $companyLogoPath = $getParentData['company_logo'];
    }
} else {
    $profilePath = FULL_WEBSITE_URL . "user/uploads/" . $user_email . "/profile/" . $img_name;

    if ($img_name != "" && check_url_exits($profilePath)) {
        $metaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $user_email . "/profile/" . $img_name;
    } else {
        $metaProfilePath = "";
    }
    if ($get_data['company_logo'] != "") {
        $companyLogoPath = FULL_WEBSITE_URL . "user/uploads/" . $email . "/profile/" . $get_data['company_logo'];
    } else {
        $companyLogoPath = $get_data['company_logo'];
    }
}

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
    $link .= "://";
    $link = "";
    $link .= $_SERVER['HTTP_HOST'];

    $link .= $_SERVER['REQUEST_URI'];

    $date = date("Y-m-d");

    $maxsize = 4194304;
    $imgUploadStatus = false;
    $fileUploadStatus = false;


$page_type = basename($_SERVER['PHP_SELF']);
$date = date("Y-m-d");


if (isset($_GET['custom_url'])) {
    $referral_by = $get_data['referer_code'];
    $custom_url = trim($_GET['custom_url']);
    $contact_no = $get_data['contact_no'];
    $name = $get_data['name'];
    $designation = $get_data['designation'];
    $altr_contact_no = $get_data['altr_contact_no'];
    $gender = $get_data['gender'];
    $img_name = $get_data['img_name'];
    $website = $get_data['website_url'];
    $linked_in = $get_data['linked_in'];
    $whatsapp_no = $get_data['whatsapp_no'];
    $youtube = $get_data['youtube'];
    $facebook = $get_data['facebook'];
    $twitter = $get_data['twitter'];
    $instagram = $get_data['instagram'];
    $map_link = $get_data['map_link'];
    $soacil_media_status = $get_data['hide_social_status'];

    $address = $get_data['address'];
    $verified_email_status = $get_data['verified_email_status'];
    $gst_no = $get_data['gst_no'];
    $pan_no = $get_data['pan_no'];
    $user_email = $get_data['email'];
    $country = $get_data['country'];
    $playstore = $get_data['playstore_url'];
    $saved_email = $get_data['saved_email'];
    $landline_number = $get_data['landline_number'];
    $enquiry_email = $get_data['enquiry_email'];
    $get_section = $manage->getSectionName($user_id);
    if ($get_section != null) {
        $profile = $get_section['profile'];
        $services = $get_section['services'];
        $our_service = $get_section['our_service'];
        $product = $get_section['products'];
        $our_product = $get_section['our_product'];
        $gallery = $get_section['gallery'];
        $images = $get_section['images'];
        $videos = $get_section['videos'];
        $clients = $get_section['clients'];
        $client_name = $get_section['client_name'];
        $client_review_tab = $get_section['client_review'];
        $team = $get_section['team'];
        $our_team = $get_section['our_team'];
        $bank = $get_section['bank'];
        $payment = $get_section['payment'];
        $basic_info = $get_section['basic_info'];
        $company_info = $get_section['company_info'];
    } else {
        $profile = "Profile";
        $services = "Services";
        $our_service = "Our Services";
        $product = "Products";
        $our_product = "Our Products";
        $gallery = "Gallery";
        $images = "Images";
        $videos = "Videos";
        $clients = "Clients";
        $client_name = "Clients";
        $client_review_tab = "Client's Reviews";
        $team = "Team";
        $our_team = "Our Team";
        $bank = "Bank";
        $payment = "Payment";
        $basic_info = "Basic Info";
        $company_info = "Company Info";

    }

} else {
    header('location:../index.php');
}


function urlChecker($url)
{
    $status = preg_replace('/^(?!https?:\/\/)/', 'http://', $url);
    return $status;
}


if (isset($_SESSION['email']) or (isset($_GET['user_id']) && $_GET['theme'] == "active")) {
    if (!isset($_SESSION['email'])) {
        $default_user_id = $security->decryptWebservice($_GET['user_id']);
    }
    $validateUserId = $manage->validThemeUserId($parent_custom_url, $default_user_id);
    if ($validateUserId) {
        if (isset($_GET['theme']) && $_GET['theme'] == "active") {
            $theme_data = $manage->displayAllThemeImage($default_user_id);
            echo "<style>
.theme_div{
display: block !important;
}
</style>";
        }
    } else {
        header('../index.php');
    }


}

function like_match($pattern, $subject)
{
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool)preg_match("/^{$pattern}$/i", $subject);
}

$get_payment_status = $manage->displayOnOffStatus($custom_url, "7");
$validToken = false;
if ($get_payment_status != null) {
    if ($get_payment_status['digital_card'] == 1) {
        $validToken = true;
    }
}

if (isset($_GET['token']) && $_GET['token'] != '') {
    $token = trim($_GET['token']);
    $validToken = $manage->getTokenDetails($user_id, $token);
}

