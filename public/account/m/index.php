<?php
include_once '../whitelist.php';
include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/validator.php";
$validate = new Validator();
include_once('../user/lib/ImgCompressor.class.php');
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$Themerror = false;
$ThemerrorMessage = "";
include "assets/common-includes/count-includes.php";
/*@session_start() ;
@session_destroy() ;*/


$date = date("Y-m-d");


$maxsize = 4194304;
$imgUploadStatus = false;
$fileUploadStatus = false;
if (isset($_GET['custom_url']) && (isset($_GET['theme']) && $_GET['theme'] == "active")) {
    if ((isset($_POST['background_image1']))) {
        if (isset($_POST['btn_apply'])) {
            if (!$Themerror) {
                $updateTheme = $manage->updateUserTheme($_POST['background_image1'], $_GET['custom_url']);
                /* if ($updateTheme) {
                     header('location:index.php?custom_url=' . $_GET['custom_url']);
                 }*/
            } else {
                $Themerror = true;
                $ThemerrorMessage = "Please select Theme!";
            }
        }
    }
}
/*if (isset($_POST['send_otp'])) {
    $sms_contact = $_POST['sms_contact'];
    $result = $manage->validContactForCustomUrl($sms_contact);
    if ($result != null) {
        $custom_url_user = $result['custom_url'];
        $_SESSION['new_custom_url'] = $custom_url_user;
    }
    if ($result) {
        $send_sms = $manage->sendSMS($sms_contact, substr_replace($random_sms,'-',3,0));
        $_SESSION['sms_contact'] = $sms_contact;
        $_SESSION['random_sms'] = $random_sms;
    } else {
        $error1 = false;
        $errorMessage1 = "You have entered wrong number";
    }
}

if (isset($_POST['verify_otp'])) {
    $sms_otp = $_POST['sms_otp'];
    if ($sms_otp == $_SESSION['random_sms']) {
        echo "<script>alert('SMS Verified')</script>";
        $url = "index.php?custom_url=" . $_SESSION['new_custom_url'] . "";
        $urlMessage = SHARED_URL. $_SESSION['new_custom_url'];
        echo "<script type=\"text/javascript\">
        window.open('" . $url . "', '_blank')
    </script>";
        $send_sms = $manage->sendSMS($_SESSION['sms_contact'], $urlMessage);
        session_destroy();
    } else {
        $error1 = true;
        $errorMessage1 .= "OTP Mismatched<br>";
    }
}*/


if (isset($_GET['custom_url'])) {
    $custom_url = $_GET['custom_url'];
    $validate_custom_url = $manage->validCustomUrl($custom_url);
    if ($validate_custom_url) {

        $get_data = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
        if ($get_data != null) {
            $country = $get_data['country'];
            $user_status = $get_data['status'];
            $keyword = $get_data['user_keyword'];
            $parent_id = $get_data['parent_id'];
            $expiry_date = $get_data['expiry_date'];
            $user_city = $get_data['city'];
            if ($keyword != "") {
                $keyword_array_data = explode(',', $keyword);
            } else {
                $keyword_array_data = "";
            }
        }
        if ($parent_id != "") {
            $getParentData = $manage->getSpecificUserProfileById($parent_id);
            $parent_custom_url = $getParentData['custom_url'];
            $email = $getParentData['email'];
            $user_id = $getParentData['user_id'];
            $default_user_id = $getParentData['user_id'];
            $about_company = $getParentData['about_company'];
            $company_name = $getParentData['company_name'];
            $our_mission = $getParentData['our_mission'];
            $company_profile = $getParentData['company_profile'];
            $cover_pic = $getParentData['cover_pic'];
            $user_theme = $getParentData['user_theme'];
            if ($getParentData['cover_pic'] != "") {
                $key_data = explode(',', $getParentData['cover_pic']);
            } else {
                $key_data = 0;
            }
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
            $user_theme = $get_data['user_theme'];
            $parent_custom_url = $get_data['custom_url'];
            $user_id = $get_data['user_id'];
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
        /*   $validStatus = $manage->validateUserStatus($default_user_id);
           if ($validStatus) {*/

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
            }
        } else {
            header('location:find-out-link.php');
        }
    }

} else {
    header('location:../index.php');
}
if (isset($_GET['custom_url'])) {
    $custom_url = trim($_GET['custom_url']);

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
    $user_theme = $get_data['user_theme'];
    $address = $get_data['address'];
    $country = $get_data['country'];
    $parent_id = $get_data['parent_id'];
    $gst_no = $get_data['gst_no'];
    $pan_no = $get_data['pan_no'];
    $user_email = $get_data['email'];
    $contact_no = $get_data['contact_no'];
    $playstore = $get_data['playstore_url'];
    $saved_email = $get_data['saved_email'];
    $referral_by = $get_data['referer_code'];
    $landline_number = $get_data['landline_number'];
    $hide_social_status = $get_data['hide_social_status'];
} else {
    header('location:../index.php');
}

if (isset($_POST['upload_theme'])) {
    $custom_url = trim($_GET['custom_url']);
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
    $user_id = $get_data['user_id'];
    $name = $get_data['name'];
    /*if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $title = $_POST['txt_title'];
    } else {
        $title = $name.rand(10,100);
    }*/
    $title = $name . rand(10, 100);
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
                    $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                } else {
                    $update_photo = $manage->addTheme($user_id, $title, $cover_name, $cover_name);
                    if ($update_photo) {
                        $updateTheme = $manage->updateUserTheme($cover_name, $_GET['custom_url']);
                        if ($updateTheme) {
                            $error = false;
                            $errorMessage = "Image has been uploaded and set as your default theme.";
                        } else {
                            $error = true;
                            $errorMessage = "Issue while uploading\nNote: File too large. File must be less than 4 megabytes";
                        }

                    }
                }
            }
        }

    }
}

if (isset($_SESSION['email']) or (isset($_GET['user_id']))) {

    if (!isset($_SESSION['email'])) {
        $user_id = str_replace('equal', '=', $_GET['user_id']);
        $user_id = $security->decryptWebservice($_GET['user_id']);
    }
    $validateUserId = $manage->validThemeUserId($parent_custom_url, $user_id);
    if ($validateUserId) {
        if (isset($_GET['theme']) && $_GET['theme'] == "active") {
            $theme_data = $manage->displayAllThemeImage($user_id);
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

function parse_url_all($url)
{
    $url = substr($url, 0, 4) == 'http' ? $url : 'http://' . $url;
    $d = parse_url($url);
    $tmp = explode('.', $d['host']);
    $n = count($tmp);
    if ($n >= 2) {
        if ($n == 4 || ($n == 3 && strlen($tmp[($n - 2)]) <= 3)) {
            $d['domain'] = $tmp[($n - 3)] . "." . $tmp[($n - 2)] . "." . $tmp[($n - 1)];
            $d['domainX'] = $tmp[($n - 3)];
        } else {
            $d['domain'] = $tmp[($n - 2)] . "." . $tmp[($n - 1)];
            $d['domainX'] = $tmp[($n - 2)];
        }
    }
    return $d;
}

$getDomain = $manage->getAllBusinessLinksById($user_id);

/*if ($ProfileSectionStatus != 1) {
    $redirect = get_url_param_for_mobile('services.php');
    header('Location: ' . $redirect);
    die();
}*/

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <meta property="og:image" content="<?php /*if ($img_name == "" && $gender == "Male") {
        echo FULL_WEBSITE_URL."user/uploads/male_user.png";
    } elseif ($img_name == "" && $gender == "Female") {
        echo FULL_WEBSITE_URL."user/uploads/female_user.png";
    } else {
        echo $profilePath;
    } */ ?>">-->
    <?php include "assets/common-includes/header_includes.php" ?>

    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.1/tiny-slider.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>-->
    <?php
    if ($user_status != 1) {
        ?>
        <script>
            document.getElementsByTagName("body")[0].removeAttribute("class");
            document.getElementsByTagName("body")[0].setAttribute("class", "invaliduser");
        </script>
    <?php
    }
    ?>
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/component.css"/> -->

    <!--<script src="<?php echo FULL_MOBILE_URL; ?>assets/js/jquery.mobile.swiper.js" type="text/javascript"></script>-->
    <link href="<?php echo FULL_DESKTOP_URL ?>assets/css/colorpicker.css" rel="stylesheet" type="text/css">
    <style>
        .loader-overlay {
            display: none;
            opacity: 0.8;
            background: #fff;
            width: 100%;
            height: 100%;
            z-index: 999;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: fixed;
        }
    </style>


    <!--<link rel="manifest" id="manifest-placeholder">
    <script>
        var dynamicManifest = {
            "name": "<?php /*if(isset($company_name) && $company_name !=''){ echo $company_name; }elseif (isset($name)) echo $name */ ?>",
            "short_name": "<?php /*if(isset($company_name) && $company_name !=''){ echo $company_name; }elseif (isset($name)) echo $name */ ?>",
            "description": "<?php /*if(isset($company_name) && $company_name !=''){ echo $company_name; }elseif (isset($name)) echo $name */ ?>",
            "start_url": "<?php /*echo $link; */ ?>",
            "background_color": "#000000",
            "theme_color": "#0f4a73",
            "icons": [{
                "src": "assets/images/icon/256.png",
                "sizes": "256x256",
                "type": "image/png"
            }],
            "display": "standalone"
        }
        const stringManifest = JSON.stringify(dynamicManifest);
        const blob = new Blob([stringManifest], {type: 'application/json'});
        const manifestURL = URL.createObjectURL(blob);
        document.querySelector('#manifest-placeholder').setAttribute('href', manifestURL);
     /*   console.log(manifestURL);*/
    </script>-->
    <link rel="manifest" href="../manifest.json">
</head>
<body>
<div class="loader-overlay"></div>
<div class="end_sub_overlay">
    <div style="margin-top: 62%;text-align: center;"><!--class="bg-text"-->
        <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/sub.png" style="width: 80%">
    </div>
</div>
<?php
if ($user_status == 1) {

    ?>
    <section>
        <div class="content-main" id="content-main">
            <div class="overlay overlay-height profile_padding">
                <?php include "assets/common-includes/profile.php"; ?>
                <?php
                if (!$user_expired_status) {
                    ?>
                    <ul class="company_nav_ul nav sticky_tab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#Contact"
                                                                  aria-controls="home"
                                                                  role="tab"
                                                                  data-toggle="tab"><?php echo $basic_info ?></a>
                        </li>
                        <li role="presentation"><a href="#Company"
                                                   aria-controls="profile"
                                                   role="tab"
                                                   data-toggle="tab"><?php echo $company_info ?></a>
                        </li>
                    </ul>

                    <div class="bank-up-div">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="Contact">
                                <div class="deatil-table">
                                    <div class="contact-bottom">
                                        <ul class="social-ul">
                                            <?php
                                            if ($youtube != "" && $hide_social_status != 0) {
                                                ?>
                                                <li><a <?php if ($youtube == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php if (isset($youtube) && ($youtube) != "") {
                                                        echo $youtube;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="linkedin"><img src="<?php echo $youtube_icon; ?>"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>

                                            <?php
                                            if (!$user_expired_status) {
                                                ?>
                                                <li><a <?php if ($whatsapp_no == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } ?>href="<?php if ($whatsapp_no != "") { ?>https://api.whatsapp.com/send?phone=<?php echo $country_code . $whatsapp_no;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="facebook" target="_blank"> <img
                                                            src="<?php echo $whatsapp_share_icon; ?>"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($facebook != "" && $hide_social_status != 0) {
                                                ?>
                                                <li><a <?php if ($facebook == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php if (isset($facebook) && ($facebook) != "") {
                                                        echo $facebook;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="facebook"> <img
                                                            src="<?php echo $facebook_share_con; ?>"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>


                                            <?php
                                            if (!$user_expired_status) {
                                                ?>
                                                <li><a <?php if ($contact_no == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?>href="sms:<?php if (isset($contact_no) && ($contact_no) != "") {
                                                        echo $country_code . $contact_no;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="facebook"> <img
                                                            src="<?php echo $sms_model_icon; ?>"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>

                                            <?php
                                            if ($twitter != "" && $hide_social_status != 0) {
                                                ?>
                                                <li><a <?php if ($twitter == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php if (isset($twitter) && ($twitter) != "") {
                                                        echo $twitter;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="twitter"><img src="<?php echo $twitter_icon; ?>"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($instagram != "" && $hide_social_status != 0) {
                                                ?>
                                                <li><a <?php if ($instagram == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php if (isset($instagram) && ($instagram) != "") {
                                                        echo $instagram;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="instagram"><img
                                                            src="<?php echo $instagram_icon; ?>"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($linked_in != "" && $hide_social_status != 0) {
                                                ?>
                                                <li><a <?php if ($linked_in == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php if (isset($linked_in) && ($linked_in) != "") {
                                                        echo $linked_in;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="linkedin"><img
                                                            src="<?php echo $linked_in_icon; ?>"></a></li>
                                            <?php
                                            }
                                            ?>

                                            <?php if ($playstore != "") {
                                                ?>
                                                <li><a <?php if ($playstore == "") {
                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                    } else {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php if (isset($playstore) && ($playstore) != "") {
                                                        echo $playstore;
                                                    } else {
                                                        echo "#";
                                                    } ?>" class="linkedin"><img
                                                            src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/playstore.png"></a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($getDomain != null) {
                                                while ($rowDomain = mysqli_fetch_array($getDomain)) {
                                                    $domain_full_url = parse_url_all($rowDomain['link']);
                                                    if ($domain_full_url['domain'] == "t.me") {
                                                        $domain_image_url = FULL_WEBSITE_URL . "assets/img/business-icon/" . $domain_full_url['domain'] . ".png";
                                                    } else {
                                                        $domain_image_url = FULL_WEBSITE_URL . "assets/img/business-icon/" . $domain_full_url['domainX'] . ".png";
                                                    }

                                                    if (!check_url_exits($domain_image_url)) {
                                                        $domain_image_url = FULL_WEBSITE_URL . "assets/img/business-icon/browser.png";
                                                    }
                                                    ?>
                                                    <li><a target='_blank'
                                                           href="<?php echo urlChecker($rowDomain['link']); ?>"
                                                           class="playstore"><img
                                                                src="<?php echo $domain_image_url; ?>"
                                                                onerror="this.src='assets/img/business-icon/browser.png'"></a>
                                                    </li>
                                                <?php
                                                }
                                            }

                                            ?>
                                        </ul>
                                    </div>
                                    <table class="info-table">
                                        <?php
                                        if ($get_data['display_country_code'] == 1) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="tel:<?php if (isset($contact_no)) echo "+" . $country_code . $contact_no; ?>"
                                                       class="profile-img-a">
                                                        <div class="p-align">
                                                            <div class="">
                                                                <i class="fa fa-mobile font-size-20"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="display_flex"><a class="text-color-p"
                                                                                  href="tel:<?php if (isset($contact_no)) echo "+" . $country_code . $contact_no; ?>"><?php if (isset($contact_no)) echo $country_code . $contact_no; ?></a><a
                                                            class="text-color-p"
                                                            href="tel:<?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "+" . $country_code . $altr_contact_no; ?>"><?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "&nbsp;/" . $country_code . $altr_contact_no; ?></a>
                                        </span>
                                                </td>
                                                <!--<td>
                                                </td>-->
                                            </tr>
                                        <?php
                                        } else {
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="tel:<?php if (isset($contact_no)) echo $contact_no; ?>"
                                                       class="profile-img-a">
                                                        <div class="p-align">
                                                            <div class=""><i class="fa fa-mobile font-size-20"></i>
                                                            </div>
                                                        </div>
                                                    </a></td>
                                                <td><span class="display_flex"><a class="text-color-p"
                                                                                  href="tel:<?php if (isset($contact_no)) echo $contact_no; ?>"><?php if (isset($contact_no)) echo $contact_no; ?></a><a
                                                            class="text-color-p"
                                                            href="tel:<?php if (isset($altr_contact_no) && $altr_contact_no != "") echo $altr_contact_no; ?>"><?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "&nbsp;/" . $altr_contact_no; ?></a>
                                        </span>
                                                </td>
                                                <!--<td>
                                                </td>-->
                                            </tr>
                                        <?php
                                        }
                                        if (isset($landline_number) && $landline_number != '') {
                                            ?>
                                            <tr>
                                                <td class="text-center"><i class="fa fa-phone"></i></td>
                                                <td><span><a class="text-color-p"
                                                             href="tel:<?php echo $landline_number; ?>"><?php echo $landline_number; ?></a></span>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <?php

                                            if (isset($saved_email) && $saved_email != "") {

                                                ?>
                                                <td>
                                                    <a class="profile-img-a">
                                                        <div class="p-align">
                                                            <div class=""><i class="fas fa-envelope"></i></div>
                                                        </div>
                                                    </a></td>
                                                <td>
                                                    <?php
                                                    $display_email = explode(',', $saved_email);
                                                    $i = 1;
                                                    $len = count($display_email);
                                                    foreach ($display_email as $key) {
                                                        ?>
                                                        <a target="_blank" class="text-color-p"
                                                           href="mailto:<?php if (isset($key)) echo $key; ?>"><?php if (isset($key)) echo $key; ?></a>
                                                        <?php if ($i != $len) echo " / ";
                                                        $i++;
                                                    } ?>
                                                </td>
                                            <?php
                                            } else {
                                                ?>
                                                <td><a href="mailto:<?php if (isset($email)) echo $email; ?>"
                                                       class="profile-img-a">
                                                        <div class="p-align">
                                                            <div class=""><i class="fas fa-envelope"></i></div>
                                                        </div>
                                                    </a></td>
                                                <td>
                                                    <a target="_blank" class="text-color-p"
                                                       href="mailto:<?php if (isset($email)) echo $email; ?>"><?php if (isset($email)) echo $email; ?></a>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                            <!--<td>
                                            </td>-->
                                        </tr>

                                        <tr>
                                            <td><a target="_blank"
                                                   href="<?php if (isset($website)) echo urlChecker($website); ?>"
                                                   class="profile-img-a">
                                                    <div class="p-align">
                                                        <div class=""><i class="fas fa-globe-europe"></i></div>
                                                    </div>
                                                </a></td>
                                            <td><span><?php
                                                    if ($website != null) {
                                                        ?><a
                                                        href="<?php if (isset($website)) echo urlChecker($website); ?>"
                                                        class="text-color-p"
                                                        target="_blank"><?php if (isset($website)) echo strlen($website) > 35 ? substr($website, 0, 35) . "..." : $website; ?></a>
                                                    <?php
                                                    } else {
                                                        echo "Website not available";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($_GET['custom_url'] == 'Fahim_SK'){
                                                    ?>
                                                    <a href="javascript:void(0);" class="add_contact save-card-button"
                                                       title="Export to vCard"><i class="fa fa-save"></i> Save Card</a></span>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <!--<td>

                                            </td>-->
                                        </tr>

                                        <tr>
                                            <td><a href="<?php echo $map_link; ?>" class="profile-img-a">
                                                    <div class="p-align">
                                                        <div class=""><i class="fas fa-map-marker-alt"></i></div>
                                                    </div>
                                                </a></td>

                                            <td><?php
                                                if ($address != null) { ?><p><a class="text-color-p"
                                                                                href="<?php if (isset($map_link)) echo urlChecker($map_link); ?>"
                                                                                target="_blank"><?php if (isset($address)) echo wordwrap($address, 40, "\n"); ?></a>
                                                    </p>
                                                <?php
                                                } else {
                                                    echo "Address not available";
                                                } ?>


                                            </td>
                                            <!--<td>
                                            </td>-->
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <a href="<?php echo get_url_param_for_mobile('export-vcf.php'); ?>"
                                                   class="cust_them2_icon cust_save_contact"
                                                   title="Export to vCard"><i class="fa fa-address-book-o"></i> Save
                                                    Contact</a>
                                            </td>
                                        </tr>

                                        <!-- <tr class="text-center">
                                            <td colspan="2" style="padding-right:0px;">
                                                <a href="tel:91<?php /*if (isset($contact_no)) echo $contact_no; */ ?>"><span class="label label-default profile-footer-icon"><i class="fa fa-phone"></i> call us</span></a>
                                                <span class="label label-info profile-footer-icon"><i class="fas fa-envelope"></i> email us</span>
                                                <span class="label label-success profile-footer-icon"><i class="fas fa-globe-europe"></i> visit</span>
                                                <span class="label label-primary profile-footer-icon"><i class="fas fa-map-marker-alt"></i> visit on map</span>
                                            </td>
                                        </tr>-->

                                    </table>
                                    <?php
                                    if (isset($_GET['custom_url']) && $_GET['custom_url'] == 'Fahim_Sk') {
                                        ?>
                                        <div class="theme2_save_contact_manin">
                                            <ul>
                                                <li>
                                                    <div class="theme2_first_div">
                                                        <a href="export-vcf.php?custom_url=<?php echo $custom_url; ?>">
                                                            <div class="them2_second_div">
                                                                <h5>Save Contact </h5>
                                                            </div>
                                                            <div class="width_20_block">
                                                                <i class="fas fa-user-plus"></i>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="theme2_first_div save-card-button">
                                                        <!-- <div class="width_20_block">
                                                             <i class="far fa-address-card"></i>
                                                         </div>-->
                                                        <div class="them2_second_div">
                                                            <h5>Save DG Card</h5>
                                                        </div>
                                                        <div class="width_20_block">
                                                            <i class="fas fa-download"></i>
                                                        </div>


                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="Company">
                                <?php if ($company_name != "") { ?>
                                    <div class="deatil-table">
                                        <table class="company_table">
                                            <tbody>
                                            <tr class="text-center">
                                                <td colspan="2">Name : <?php echo $company_name; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <ul class="contact-ul mb_15">
                                            <li class="our_mission"><a
                                                    href="<?php echo get_url_param_for_mobile('about-company.php'); ?>&tab=misssion"
                                                    class="profile-img-a">
                                                    <div class="p-align">
                                                        <div class="company_icon"><i class="fas fa-eye"></i> Our Mission
                                                        </div>
                                                    </div>
                                                </a></li>
                                            <li class="company_profile"><a
                                                    href="<?php echo get_url_param_for_mobile('about-company.php'); ?>&tab=profile"
                                                    class="profile-img-a">
                                                    <div class="p-align">
                                                        <div class="company_icon"><i class="fas fa-file-pdf"></i>
                                                            Company
                                                            Profile
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                        <?php
                                        $removeHTML = strip_tags($about_company);
                                        ?>

                                        <div class="about_company">
                                            <p>About Company</p>
                                            <?php
                                            echo substr($removeHTML, 0, 100) . "...";
                                            ?>
                                            <div class="about_icon_top">
                                                <a href="<?php echo get_url_param_for_mobile('about-company.php'); ?>">
                                                    <div class="about_company_icon">
                                                        <span class="fas fa-chevron-down"></span>
                                                    </div>
                                                </a>

                                            </div>
                                        </div>
                                        <?php

                                        if ($country == '101') {
                                            ?>
                                            <table class="company_table">
                                                <tbody>
                                                <tr>
                                                    <td>GST : <?php if ($gst_no != "") {
                                                            echo $gst_no;
                                                        } else {
                                                            echo "Not available";
                                                        } ?></td>
                                                    <td class="text-right">PAN : <?php if ($pan_no != "") {
                                                            echo $pan_no;
                                                        } else {
                                                            echo "Not available";
                                                        } ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        <?php

                                        }
                                        ?>

                                        <!--<div class="contact-bottom">
                                    <ul class="company-ul">
                                        <li><a href="about-company.php?custom_url=<?php /*echo $custom_url; */ ?>&tab=about"
                                               class="profile-img-a">
                                                <div class="p-align">
                                                    <div class="company_logo"><i class="fas fa-info"></i></div>
                                                    <p>About Company</p></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="about-company.php?custom_url=<?php /*echo $custom_url; */ ?>&tab=misssion"
                                               class="profile-img-a">
                                                <div class="p-align">
                                                    <div class="company_logo"><i class="fas fa-eye"></i></div>
                                                    <p>Mission & Vision</p></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="about-company.php?custom_url=<?php /*echo $custom_url; */ ?>&tab=profile"
                                               class="profile-img-a">
                                                <div class="p-align">
                                                    <div class="company_logo"><i class="fas fa-file-pdf"></i></div>
                                                    <p>Company Profile</p></div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>-->
                                    </div>
                                <?php } else { ?>
                                    <div class="text-center no_data_found">
                                        <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/112.png">
                                        <h5>Company Info will Appear Soon in this Section.</h5>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>

                    </div>
                <?php
                }
                if ($user_expired_status) {
                    if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
                        ?>
                        <button class="btn" style="display: none" data-toggle="modal" id="show_premiuim"
                                data-target="#premiuimModal"></button>
                        <div class="col-xs-12">
                            <div class="expire_div_dg">
                                <div>
                                    <h3><img src="<?php echo FULL_WEBSITE_URL ?>assets/img/logo/logo.png"> Share Digital
                                        Card</h3>
                                </div>
                                <div>
                                    <?php
                                    if (like_match('%dealer%', $referral_by) == 1) {
                                        $getDealer = $manage->getDealerProfile($referral_by);
                                        ?>
                                        <div class="text-center">
                                            <a class="" href="tel:<?php echo $getDealer['contact_no']; ?>"><i
                                                    class="fa fa-volume-control-phone" aria-hidden="true"></i> Call Now</a>
                                        </div>
                                    <?php
                                    } else {
                                        ?>
                                        <div class="text-center">
                                            <a class="" href="<?php echo FULL_WEBSITE_URL ?>demo-cards.php"
                                               target="_blank"><i
                                                    class="fa fa-external-link" aria-hidden="true"></i> Get Your Free
                                                Digital Card</a>
                                        </div>

                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>

                <!--<div class="contact-heading">
                     <div class="row cust-margin">
                         <div class="col-xs-7">
                             <h4 class="contact-h4">Contact Information</h4>
                         </div>
                     </div>
                 </div>-->

                <?php include "assets/common-includes/footer.php" ?>
            </div>
        </div>

    </section>
<?php
} else {
    ?>
    <section>

        <div class="container">
            <div class="inavlid-div">
                <?php
                if ($user_status == 2) {
                    ?>
                    <div>
                        <img src="../theme/blocked.png">
                        <h4>
                            The user of this Digital card has Deactivated his/her account.
                        </h4>

                        <p>If you have any concern regarding this Digital Card user then please email us at <a
                                href="mailto:support@sharedigitalcard.com">support@sharedigitalcard.com</a></p>
                    </div>
                <?php
                } elseif ($user_status == 3) {
                    ?>
                    <div>
                        <img src="../theme/trash.png">
                        <h4>
                            The user of this Digital card has Deleted his/her account.
                        </h4>

                        <p>If you have any concern regarding this Digital Card user then please email us at <a
                                href="mailto:support@sharedigitalcard.com">support@sharedigitalcard.com</a></p>
                    </div>
                <?php
                } elseif ($user_status == 0) {
                    ?>
                    <div>
                        <img src="../theme/blocked.png">
                        <h4>
                            The user of this Digital card has Blocked his/her account.
                        </h4>

                        <p>If you have any concern regarding this Digital Card user then please email us at <a
                                href="mailto:support@sharedigitalcard.com">support@sharedigitalcard.com</a></p>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
    </section>
<?php
}
?>
<?php
if (isset($_GET['premium']) && $_GET['premium'] == 'active') {
    ?>

    <div class="modal modal_padding animated fadeInUpBig cust-model" id="premiuimModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title cust-model-heading">Share Digital Card</h4>
                 </div>-->
                <div class="modal-body expire_premuim_div">
                    <!--Premiuim Popup start-->

                    <div class="premiuim_first_div">
                        <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/quality.png">
                    </div>
                    <div class="premuim_main_div">
                        <div>
                            <ul>
                                <li><i class="fa fa-check"></i> Showcase Company / Basic Info</li>
                                <li><i class="fa fa-check"></i> Unlimited Modification</li>
                                <li><i class="fa fa-check"></i> Unlimited Image/Video Upload</li>
                                <li><i class="fa fa-check"></i> Edit Anytime with Control Panel</li>
                                <li><i class="fa fa-check"></i> Free Android App</li>
                                <li><i class="fa fa-check"></i> Free 5 Days Trial for Premium Plan</li>
                            </ul>
                        </div>
                        <div>
                            <a class="btn btn-primary get_card_btn" href="javascript:void(0);">Get Your Digital Card</a>
                        </div>
                        <?php
                        if (like_match('%dealer%', $referral_by) == 1) {
                            $getDealer = $manage->getDealerProfile($referral_by);
                            ?>
                            <div class="text-center">
                                <a class="btn btn-primary get_card_btn_2"
                                   href="tel:<?php echo $getDealer['contact_no']; ?>"><i
                                        class="fa fa-volume-control-phone" aria-hidden="true"></i> Call Now</a>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="text-center">
                                <a class="btn btn-primary get_card_btn_2"
                                   href="<?php echo FULL_WEBSITE_URL ?>demo-cards.php" target="_blank"><i
                                        class="fa fa-external-link" aria-hidden="true"></i> Get Free Card</a>
                            </div>

                        <?php
                        }
                        ?>

                    </div>

                    <!--Premiuim Popup end-->
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#show_premiuim')[0].click();
        });
    </script>
<?php
}
?>

<script>
    $(window).load(function () {
        setTimeout(function () {
            $('.spinner').fadeOut();
            $('.back-color').fadeOut();
            $('.path').fadeOut();
        }, 10);
    });
</script>
<?php
if (isset($_GET['theme']) && $_GET['theme'] == "active") {
    ?>
    <div class="theme_div">
        <!-- END OF TESTIMONIALS -->
        <form method="post" action="">
            <diV class="col-md-12">
                <div class="row">
                    <div class="thene_back_grey col-xs-10">
                        <ul class="my-theme-slider">
                            <?php
                            if ($theme_data != null) {
                                $i = 1;
                                while ($display_data = mysqli_fetch_array($theme_data)) {
                                    ?>
                                    <li>
                                        <div class="card-theme image_grid">
                                            <label>
                                                <input type="radio" name="selimg">
                                            <span class="caption">
                                            <span></span></span>
                                                <img class="img-responsive"
                                                     src="<?php echo FULL_WEBSITE_URL . "theme/" . $display_data['thumb_img']; ?>"
                                                     onclick="change_background_image(<?php echo $i; ?>)"
                                                     alt="<?php echo $display_data['img_name']; ?>"
                                                     id="slider_image<?php echo $i; ?>">
                                            </label>

                                            <?php
                                            if ($user_theme == $display_data['img_name']) {
                                                ?>
                                                <div>
                                                    <div class="check_theme">
                                                        <i class="fa check fa-check" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <!--  <p><?php /*echo $display_data['title']; */ ?></p>-->
                                        </div>
                                    </li>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>

                        </ul>
                    </div>
                    <div class="col-xs-2">
                        <div class="mt-10">
                            <div id="price"></div>
                            <button type="submit" name="btn_apply" class="change_theme_btn btn btn-success"><i
                                    class="fa fa-check"
                                    aria-hidden="true"></i>
                            </button>
                            <button type="button" class="change_theme_btn btn btn-primary mt-10"
                                    data-target="#enquiryModal" data-toggle="modal">
                                <i class="fa fa-upload"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="row">


                            <div class="pcolor col-xs-6">
                                <div class="input-group mb-3">
                                    <label>Pick Background Color</label>
                                    <input type="text" id="picker-back-colour" aria-describedby="basic-addon3"
                                           aria-label="" readonly
                                           class="form-control textpicker picker" placeholder="Pick Background Color"
                                           autocomplete="off">
                                    <button type="button" onclick="applyBackColor(this)"
                                            class="mt-10 btn form-control btn-success"
                                            id="basic-addon3"><i class="fa fa-check" aria-hidden="true"></i> Apply
                                    </button>
                                </div>
                            </div>
                            <div class="pcolor col-xs-6">
                                <label>Change Font Family</label>

                                <div class="dropdown">
                                    <select class="form-control" onchange="body_font_family_color(this.value)">
                                        <option>Select Font</option>
                                        <option id="raleway-font" style="font-family: Raleway;">Raleway</option>
                                        <option id="montserrat-font" style="font-family: Montserrat">Montserrat</option>
                                        <option id="titillium-font" style="font-family: Titillium Web">Titillium Web
                                        </option>
                                        <option id="pacifico-font" style="font-family: Pacifico">Pacifico</option>
                                        <option id="josefin-slab-font" style="font-family: Josefin Slab">Josefin Slab
                                        </option>
                                        <option id="orbitron-font" style="font-family: Orbitron">Orbitron</option>
                                        <option id="comfortaa-font" style="font-family: Comfortaa;">Comfortaa</option>
                                        <option id="courgette-font" style="font-family: Courgette;">Courgette</option>
                                        <option id="ubuntu-font" style="font-family: Ubuntu;">Ubuntu</option>
                                        <option id="chewy-font" style="font-family: Chewy;">Chewy</option>
                                        <option id="lobster-two-font" style="font-family: Lobster Two;">Lobster Two
                                        </option>
                                        <option id="kaushan-script-font" style="font-family: Kaushan Script;">Kaushan
                                            Script
                                        </option>
                                        <option id="economica-font" style="font-family: Economica;">Economica</option>
                                        <option id="satisfy-font" style="font-family: Satisfy;">Satisfy</option>
                                        <option id="FreigDisProBoo" style="font-family: FreigDisProBoo;">
                                            FreigDisProBoo
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: GothamNarrow-Bold;"
                                                value="GothamNarrow-Bold">Gotham Narrow Bold
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: GothamNarrow-Bold;"
                                                value="GothamNarrow-Book">Gotham Narrow Book
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: Graphik-Starwood-Regular;"
                                                value="Graphik-Starwood-Regular">Graphik Starwood Regular
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: Graphik-Starwood-Semibold;"
                                                value="Graphik-Starwood-Semibold">Graphik Starwood Semibold
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: GriffithGothic-Bold;"
                                                value="GriffithGothic-Bold">Griffith Gothic Bold
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: GriffithGothic-Light;"
                                                value="GriffithGothic-Light">Griffith Gothic Light
                                        </option>
                                        <option id="GothamNarrow-Bold" style="font-family: GriffithGothic-Thin;"
                                                value="GriffithGothic-Thin">Griffith Gothic Thin
                                        </option>
                                    </select>
                                    <input type="hidden" class="font_family_hidden">
                                    <button type="button" onclick="applyFontFamily(this)"
                                            class=" btn text_color_btn btn-success"><i class="fa fa-check"></i>
                                    </button>

                                </div>

                                <div class="input-group mb-3 mt-10">
                                    <label>Pick Text Color</label>
                                    <input type="text" id="picker-text-colour" aria-describedby="basic-addon2"
                                           aria-label="" readonly
                                           class="form-control textpicker picker" placeholder="Pick Text Color"
                                           autocomplete="off">
                                    <button type="button" onclick="applyTextColor(this)"
                                            class=" btn text_color_btn btn-success"
                                            id="basic-addon2"><i class="fa fa-check" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"
                                type="text/javascript"></script>
                        <!-- <script src="<?php //echo FULL_DESKTOP_URL ?>/assets/css/colorpicker.js"
                                type="text/javascript"></script> -->
                        <?php if ($Themerror) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($ThemerrorMessage)) echo $ThemerrorMessage; ?>
                            </div>
                        <?php
                        } else if (!$Themerror && $ThemerrorMessage != "") {
                            ?>
                            <div class="alert alert-success">
                                <?php if (isset($ThemerrorMessage)) echo $ThemerrorMessage; ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </diV>
        </form>
    </div>
    <script>
        var custom_url = '&custom_url=<?php echo $_GET['custom_url'] ?>';
        var master = $('p,h1,h2,h3,h4,h5,h6,a,span,.text-color-p');
        var input_family_hidden = $('.font_family_hidden');
        function body_font_family_color(val) {
            master.css("font-family", val);
            input_family_hidden.val(val);
        }
        function applyFontFamily(val) {
            var dataString = "change_font_family=" + encodeURIComponent(input_family_hidden.val());
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php", // Name of the php files
                data: dataString + custom_url,
                beforeSend: function () {
                    $(val).attr("disabled", 'disabled');
                },
                success: function (html) {
                    $(val).removeAttr("disabled");
                }
            });
        }
    </script>

    <script>
        window.addEventListener("load", function () {
            setTimeout(function () {
                // This hides the address bar:
                window.scrollTo(0, 1);
            }, 0);
        });
        $(document).ready(function () {
            if ($('#blah').attr('src') == "" || $('#blah').attr('src') == "unknown") {
                $('#blah').hide();
            }
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.blah')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $('#blah').show();
            }
        }
    </script>
    <script>
        function applyTextColor(val) {
            var color = $('#picker-text-colour').val();
            var dataString = "text_color=" + color + "&user_id=" +<?php echo $user_id; ?>;
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php", // Name of the php files
                data: dataString,
                beforeSend: function () {
                    $(val).attr("disabled", 'disabled');
                },
                success: function (html) {
                    $(val).removeAttr("disabled");
                }
            });
        }
        function applyBackColor(val) {
            var color = $('#picker-back-colour').val();
            var dataString = "back_color=" + color;
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php", // Name of the php files
                data: dataString + custom_url,
                beforeSend: function () {
                    $(val).text('Applying...').attr("disabled", 'disabled');
                },
                success: function (html) {
                    $(val).text('Applied').removeAttr("disabled");
                }
            });
        }
    </script>

    <div class="modal fade cust-model" id="enquiryModal"
         role="dialog" style="z-index: 9999">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header service_modal_title">
                    <button type="button" class="close cust-close custom_modal_close"
                            data-dismiss="modal" style="margin-top: -4px;font-size: 30px;">&times;</button>
                    <h4 class="modal-title" style="font-size: 16px;
    color: #555;">Upload Theme</h4>
                </div>
                <div class="modal-body">
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
                    <form method="post" action="" enctype="multipart/form-data">
                        <div>
                            <label class="form-label">Upload Image</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="file" name="upload[]" id="file-7"
                                           class="inputfile inputfile-6"
                                           data-multiple-caption="{count} files selected"
                                           multiple
                                           onchange="readURL(this);"
                                           accept="image/*" style="display: none"/>
                                    <label for="file-7"><span></span> <img id="blah"
                                                                           class="input_choose_file blah"
                                                                           src=""
                                                                           alt=""/><strong
                                            class="input_choose_file" style="height: auto">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="20"
                                                 height="17" viewBox="0 0 20 17">
                                                <path
                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                                            </svg>
                                            Choose a file&hellip;</strong></label>
                                </div>
                            </div>
                        </div>
                        <!--<div class="form-group text_box">
                            <label class="f_p text_c f_400">Image Name</label>
                            <input type="text" placeholder="Enter Image Name" class="form-control" name="txt_title">
                        </div>-->
                    </form>
                </div>
                <div class="custom-modal-footer">
                    <button class="btn_hover btn btn-info app_btn" type="submit" name="upload_theme">Upload</button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function change_background_image(id) {
            var img = document.getElementById('slider_image' + id);
            var d = img.getAttribute("alt");
            $('#content-main').css("background-image", "url(<?php echo FULL_WEBSITE_URL; ?>theme/" + d + ")");
            var dataString = "background_image=" + d;
            $.ajax({
                type: "POST",
                url: "<?php echo FULL_MOBILE_URL; ?>theme-change.php", // Name of the php files
                data: dataString,
                success: function (html) {
                    $("#price").html(html);
                    $("#price").css({"display": "none"});
                }
            });
        }
    </script>
<?php
}
?>
<script>
    function successMessage(text) {
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Success!</strong>',
            icon: 'success',
            html: '<p>' + text + '</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }

    function dangerMessage(text) {
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Warning!</strong>',
            icon: 'warning',
            html: '<p>' + text + '</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }

</script>
<?php
if ($error && $errorMessage != "") {
    ?>
    <script>
        $(document).ready(function () {
            $('.upload-theme-img').click();
            //dangerMessage('<?php //echo $errorMessage; ?>//');
        });

    </script>
<?php
}elseif (!$error && $errorMessage != "") {

?>
    <script>
        $(document).ready(function () {
            successMessage('<?php echo $errorMessage; ?>');
        });
    </script>
<?php
}
?>

<?php include "assets/common-includes/footer_includes.php" ?>
<?php
include "share-card-flyout.php";

?>
<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.3.11/min/tiny-slider.js"></script>-->
<!--<script src="assets/add-to-homescreen-master/src/addtohomescreen.js"></script>
<script>
    if(
        (("standalone" in window.navigator) && !window.navigator.standalone) // ios
        ||
        ( !window.matchMedia('(display-mode:standalone)').matches ) // andriod
    ){
        addToHomescreen();
    }
</script>-->
<!-- <script>
    if ('serviceWorker' in navigator) {
        console.log("Will the service worker register?");
        navigator.serviceWorker.register('service-worker.js')
            .then(function (reg) {
                console.log("Yes, it did.");
            }).catch(function (err) {
                console.log("No it didn't. This happened:", err)
            });
    }

    window.addEventListener('DOMContentLoaded', () => {
  let deferredPrompt;
  const saveBtn = document.querySelector('.save-card-button');

  window.addEventListener('beforeinstallprompt', (e) => {
    
    e.preventDefault();
    
    deferredPrompt = e;
    
    saveBtn.style.display = 'block';

    saveBtn.addEventListener('click', (e) => {
      
      saveBtn.style.display = 'none';
      
      deferredPrompt.prompt();
      
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the A2HS prompt');
        } else {
          console.log('User dismissed the A2HS prompt');
        }
        deferredPrompt = null;
      });
    });
  });
});

</script> -->


</body>
</html>