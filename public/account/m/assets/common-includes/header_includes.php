<script>
    var full_url = window.location.href;
    var new_url = "";
    if (full_url.includes(".com")) {
        new_url = full_url.replace(".com/", ".com/d/home/");
    } else if (full_url.includes(".in")) {
        new_url = full_url.replace(".in/", ".in/d/home/");
    } else if (full_url.includes(".online")) {
        new_url = full_url.replace(".online/", ".online/d/home/");
    }

    if (screen.width >= 768)  //if 1024x768
        window.location = new_url;

</script>


<?php

$user_expired_status = false;

include "../data-uri-image.php";

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";

// Here append the common URL characters.
$link .= "://";

// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
$link .= $_SERVER['REQUEST_URI'];


if (isset($_GET['custom_url']) && $_GET['custom_url'] != '') {
    $custom_url = trim($_GET['custom_url']);
    $validate_custom_url = $manage->validCustomUrl($custom_url);
    if ($validate_custom_url) {
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
        $expiry_date = $get_data['expiry_date'];
        $enquiry_email = $get_data['enquiry_email'];
        $verified_email_status = $get_data['verified_email_status'];
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
    /*if (FULL_WEBSITE_URL == "popupbusinesscard.in") {
        echo $profilePath;
        die();
    }*/
    //echo $profilePath;
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
    //echo $profilePath;
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
$getSubscription = $manage->getUserSubscriptionDetails($default_user_id);

if ($getSubscription != null) {

    if ($getSubscription['year'] != "Life Time") {
        if ($expiry_date < $date) {
            if (isset($_SESSION['type']) && ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor")) {
                echo '<style>.sub_expired{ display: block !important;}</style>';
            } else {
                /*   echo "<style>.end_sub_overlay{display: block!important;}</style>";*/
                $user_expired_status = true;
            }
        }
    }
} else {
    if (isset($_SESSION['type']) && ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor")) {
        echo '<style>.sub_expired{ display: block !important;}</style>';
    } else {
        /*echo "<style>.end_sub_overlay{display: block!important;}</style>";*/
        $user_expired_status = true;
    }

}

$folder_url = FULL_MOBILE_URL;/*m/*/

$validToken = false;

$get_payment_status = $manage->displayOnOffStatus($custom_url, "7");

if ($get_payment_status != null) {
    if ($get_payment_status['digital_card'] == 1) {
        $validToken = true;
    }
}
if (isset($_GET['token']) && $_GET['token'] != '') {
    $token = trim($_GET['token']);
    $validToken = $manage->getTokenDetails($user_id, $token);
}
/*if(!$validToken){
    echo '<style>
    .footer-ul li {
    width: 18% !important;
}
.footer-ul img {
    width: 39% !important;
}
    </style>';
}*/

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

if ($user_expired_status && basename($_SERVER['PHP_SELF']) != "index.php") {
    header('location:' . get_url_param_for_mobile('index.php') . '&premium=active');
}
$domain_link = $get_data['domain_link'];
if (isset($domain_link) && $domain_link != '') {
    $final_link = $domain_link;
} else {
    $final_link = SHARED_URL . $_GET['custom_url'];
}
$total_page_count = $manage->mdm_totalPageCount($user_id);
if ($total_page_count['total_count'] != null) {
    $home_page_count = $total_page_count['total_count'];
} else {
    $home_page_count = 0;
}
?>

<?php


function urlChecker($url)
{
    $status = preg_replace('/^(?!https?:\/\/)/', 'http://', $url);
    return $status;
}

$theme_path = FULL_WEBSITE_URL . "theme/" . $user_theme;

if ($user_theme != '' && check_url_exits($theme_path)) {
    $theme_path = $theme_path;
    echo '<style>
    .content-main {
        background-image: url(' . $theme_path . ');
    }
</style>';
} elseif (isset($user_theme) && (strpos($user_theme, ".") === false)) {
    echo '<style>
    .content-main {
        background-color: #' . $user_theme . ';
    }
</style>';

} else {
    $theme_path = FULL_WEBSITE_URL . "theme/6.png";

    echo '<style>
    .content-main {
        background-image: url(' . $theme_path . ');
    }
</style>';
}

?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css"> -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css"
      href="<?php echo $folder_url; ?>assets/css/style.css?version=<?php echo $version; ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo $folder_url; ?>assets/css/responsive.css?version=<?php echo $version; ?>">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
      integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<!-- wow css -->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js" rel="stylesheet" />-->
<!-- animate css -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet"/> -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
<!--<link rel="shortcut icon" type="image/png" href="<?php /*echo $folder_url; */ ?>assets/img/logo/favicon.png">-->
<?php
if ($_SERVER['HTTP_HOST'] == "sharedigitalcard.com") {
    ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo FULL_WEBSITE_URL; ?>assets/img/logo/favicon.png">
    <?php
} else {
    ?>
    <link rel="shortcut icon" type="image/png"
          href="https://freepngimg.com/download/logo/81920-world-globe-computer-silhouette-icons-hq-image-free-png.png">
    <?php
}
?>

<title style="text-transform: capitalize"><?php echo $name; ?> - <?php echo $designation; ?></title>
<meta name="description" content="<?php echo $contact_no; ?> - <?php echo $user_email; ?>">
<meta property="og:title" content="<?php echo $name; ?> - <?php echo $designation; ?>"/>
<meta property="og:url" content="<?php echo SHARED_URL . $_GET['custom_url'] ?>"/>
<meta property="og:description"
      content="<?php echo $contact_no; ?> - <?php echo $user_email; ?>">

<meta property="og:site_name" content="Digital Card">
<meta property="og:image" itemprop="image"
      content="<?php
      if ($metaProfilePath != "") {
          echo $metaProfilePath;
      } elseif ($gender == "Male") {
          echo "https://sharedigitalcard.com/user/uploads/male_user.png";
      } elseif ($gender == "Female") {
          echo "https://sharedigitalcard.com/user/uploads/female_user.png";
      } else {
          echo "https://sharedigitalcard.com/user/uploads/male_user.png";
      } ?>">
<meta property="og:type" content="website"/>
<meta property="og:updated_time" content="1440432930"/>
<link
        href='https://fonts.googleapis.com/css?family=Ubuntu|Chewy|Quattrocento+Sans|Kaushan+Script|Comfortaa|Lobster+Two|Raleway|Montserrat|Titillium+Web|Josefin+Sans|Pacifico|Orbitron|Josefin+Slab|Satisfy|Economica|Courgette'
        rel='stylesheet' type='text/css'>

<?php

if (isset($companyLogoPath) && check_url_exits($companyLogoPath)) {
    ?>
    <div class="back-color spinner">
        <div>
            <img src="<?php echo $companyLogoPath; ?>" style="width: 100%; ">
            <?php if (isset($company_name) && $company_name != '') echo "<h3 style='margin-bottom: 4px;'>" . $company_name . "</h3><div style='width: 45%;margin: 0 auto;'><img src='" . FULL_MOBILE_URL . "assets/images/1.gif' style='width: 100%'></div>"; ?>
        </div>
    </div>
    <?php
} else {
    ?>

    <div class="back-color spinner">
        <div>
            <!--   <img src="<?php /*echo $folder_url; */ ?>assets/images/logo-loading.gif" style="width: auto; height: 150px; ">-->
            <?php if (isset($company_name) && $company_name != '') echo "<h3 style='margin-bottom: 4px;'>" . $company_name . "</h3><div style='width: 45%;margin: 0 auto;'><img src='" . FULL_MOBILE_URL . "assets/images/1.gif' style='width: 100%'></div>"; ?>
        </div>
    </div>
    <?php
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" async></script>
<!--<script src="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" type="text/css"/>-->
<?php
if ($get_data['text_color'] != null) {
    echo '<style>
.text-color-p{
color: #' . $get_data['text_color'] . ';
}
</style>';
}

if ($get_data['background_color'] != null) {
    echo '<style>
    p,h1,h2,h3,h4,h5,h6,.nav-tabs > li > a,span,.text-color-p,.btn,.bank-model-table td,.bank-detail a,.about_company,.company_table tbody tr td,.company_icon,.bank-detail,.tab-content>.tab-pane{
        font-family: ' . $get_data['background_color'] . ';
            }
</style>';
}
?>