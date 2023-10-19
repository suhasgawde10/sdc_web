<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/validator.php";
$validate = new Validator();

$date = date("Y-m-d");
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
            if($parent_id !=""){
                $getParentData = $manage->getSpecificUserProfileById($parent_id);
                $parent_custom_url = $getParentData['custom_url'];
                $email = $getParentData['email'];
                $user_id = $getParentData['user_id'];
                $contact_no = $getParentData['contact_no'];
                $default_user_id = $getParentData['user_id'];
                $about_company = $getParentData['about_company'];
                $company_name = $getParentData['company_name'];
                $our_mission = $getParentData['our_mission'];
                $company_profile = $getParentData['company_profile'];
                $cover_pic = $getParentData['cover_pic'];
                if ($getParentData['cover_pic'] != "") { $key_data = explode(',',$getParentData['cover_pic']); } else { $key_data = 0; }
            }else{
                $parent_custom_url = $get_data['custom_url'];
                $user_id = $get_data['user_id'];
                $contact_no = $get_data['contact_no'];
                $about_company = $get_data['about_company'];
                $company_name = $get_data['company_name'];
                $our_mission = $get_data['our_mission'];
                $email = $get_data['email'];
                $company_profile = $get_data['company_profile'];
                $cover_pic = $get_data['cover_pic'];
                if ($get_data['cover_pic'] != "") { $key_data = explode(',',$get_data['cover_pic']); } else { $key_data = 0; }
                $default_user_id = $get_data['user_id'];
            }

            $user_city = $get_data['city'];
            if($keyword !=""){
                $keyword_array_data = explode(',',$keyword);
            }else{
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
                        echo "<style>.end_sub_overlay{display: block!important;}</style>";
                    }
                }
            }
        } else {
            if (isset($_SESSION['type']) && ($_SESSION['type'] == "Admin" OR $_SESSION['type'] == "Editor")) {
                echo '<style>.sub_expired{ display: block !important;}</style>';
            } else {
                echo "<style>.end_sub_overlay{display: block!important;}</style>";
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
            }
        } else {
            header('location:find-out-link.php');
        }
    }

} else {
    header('location:../index.php');
}

function urlChecker($url){
    $status = preg_replace('/^(?!https?:\/\/)/', 'http://', $url);
    return $status;
}



$htm_data1 = '
    <meta name="viewport" content="width=device-width, initial-scale=1.0">';

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

    $playstore = $get_data['playstore_url'];
    $saved_email = $get_data['saved_email'];

    $get_section = $manage->getSectionName($user_id);
    if ($get_section != null) {
        $profile = $get_section['profile'];
        $services = $get_section['services'];
        $our_service = $get_section['our_service'];
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

if($parent_id !=""){
    $getParentData = $manage->getSpecificUserProfileById($parent_id);
    $email = $getParentData['email'];
    $profilePath = FULL_WEBSITE_URL."user/uploads/" . $email. "/" . $user_email . "/profile/" . $img_name;
    if ($img_name != "" && check_url_exits($profilePath)) {
        $metaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $email. "/" . $user_email . "/profile/" . $img_name;
    } else {
        $metaProfilePath = "";
    }
}else {

    $profilePath = FULL_WEBSITE_URL."user/uploads/" . $user_email . "/profile/" . $img_name;

    if ($img_name != "" && check_url_exits($profilePath)) {
        $metaProfilePath = "https://sharedigitalcard.com/user/uploads/" . $user_email . "/profile/" . $img_name;
    } else {
        $metaProfilePath = "";
    }

}

$folder_url = "";/*m/*/

$validToken = false;
if(isset($_GET['token']) && $_GET['token'] !=''){
    $token = trim($_GET['token']);
    $validToken = $manage->getTokenDetails($user_id,$token);

}
if(!$validToken){
    $htm_data1 .= '<style>
    .footer-ul li {
    width: 18% !important;
}';
$htm_data1 .= '
.footer-ul img {
    width: 39% !important;
}
    </style>';
}
function get_all_get()
{
    $output = "?";
    $firstRun = true;

    foreach($_GET as $key=>$val) {
        if(!$firstRun) {
            $output .= "&";
        } else {
            $firstRun = false;
        }
        $output .= $key."=".$val;
    }

    return $output;
}
$domain_link = $get_data['domain_link'];
if(isset($domain_link) && $domain_link !=''){
    $final_link = $domain_link;
}else{
    $final_link = "https://sharedigitalcard.com/m/index.php?custom_url=" . $_GET['custom_url'];
}

$theme_path = FULL_WEBSITE_URL."theme/".$user_theme;
echo $theme_path."<br>";
die();
if($user_theme !='' && check_url_exits($theme_path)){
    $theme_path = $theme_path;
}else{
    $theme_path = "../theme/6.png";
}

$htm_data1 .= '
<style>
    .content-main {
        background-image: url(' . $theme_path.  ');
    }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="assets/css/style.css?version=' . $version . '">
<link rel="stylesheet" type="text/css" href="assets/css/responsive.css?version=' .  $version . '">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
      integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<!-- wow css -->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js" rel="stylesheet" />-->
<!-- animate css -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet"/>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
<link rel="shortcut icon" type="image/png" href="../assets/img/logo/favicon.png">

<meta property="og:site_name" content="Share Digital Card">
<meta property="og:image" itemprop="image"
      content="';
      if ($metaProfilePath != "") {
          $htm_data1 .= '' . $metaProfilePath .'';
      } elseif ($gender == "Male") {
          $htm_data1 .= ' "https://sharedigitalcard.com/user/uploads/male_user.png"';
      } elseif ($gender == "Female") {
          $htm_data1 .= ' "https://sharedigitalcard.com/user/uploads/female_user.png"';
      } $htm_data1 .= '">
<meta property="og:type" content="website"/>
<meta property="og:updated_time" content="1440432930"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<!--<script src="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" type="text/css"/>-->
    ';

$htm_data1 .= '

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.1/tiny-slider.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <link rel="stylesheet" type="text/css" href="assets/css/component.css"/>

<section>
    <div class="content-main" id="content-main">
        <div class="overlay overlay-height profile_padding">';

$paymentModel = false;
$section_bank_id = 7;
$get_bank_status = $manage->displayOnOffStatus($custom_url, $section_bank_id);

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";
$link .= "://";

$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];

$getDetails = $manage->getGatewayPaymentDetails($user_id);
if ($getDetails != null) {
    $upi_id = $getDetails['upi_id'];
    $upi_mobile_no = $getDetails['upi_mobile_no'];

}else {
        $upi_mobile_no = '0';
    $upi_id = "0";
}

$verify_user = $manage->displayVerifiedUser($user_id);
$get_country = $manage->mdm_getCountryCode($country);
if($get_country !=null){
    $country_code = $get_country['phonecode'];
}else{
    $country_code = "91";
}
if (isset($_POST['add_contact'])) {
    $contactResult = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
    require_once "VcardExport.php";
    $vcardExport = new VcardExport();
    $vcardExport->contactVcardExportService($contactResult);
    exit();
}
$getDetails = $manage->getGatewayPaymentDetails($user_id);
if ($getDetails != null) {
    $upi_id = $getDetails['upi_id'];
    $upi_mobile_no = $getDetails['upi_mobile_no'];
}else{
    echo '<style>#paymentGateway{ display: block}</style>';
    $upi_id = "";
    $upi_mobile_no = "";
    $paymentModel = true;
}


$get_cover_data = $manage->getCoverImageOfUser($user_id);
if($get_cover_data !=null) {
    $coverCount = mysqli_num_rows($get_cover_data);
}else{
    $coverCount = 0;
}
function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}

function rep_escape($string){
    return str_replace(['\r\n','\r','\n','\\'],'',$string);
}

if(basename($_SERVER['PHP_SELF']) != "index.php") {
    if ($get_data != null) {

        $user_status = $get_data['status'];
        $keyword = $get_data['user_keyword'];
        $parent_id = $get_data['parent_id'];
        $expiry_date = $get_data['expiry_date'];
        if ($parent_id != "") {
            $getParentData = $manage->getSpecificUserProfileById($parent_id);
            $email = $getParentData['email'];
            $user_id = $getParentData['user_id'];
            $contact_no = $getParentData['contact_no'];
            $default_user_id = $getParentData['user_id'];
            $about_company = $getParentData['about_company'];
            $company_name = $getParentData['company_name'];
            $our_mission = $getParentData['our_mission'];
            $company_profile = $getParentData['company_profile'];
            $cover_pic = $getParentData['cover_pic'];
            if ($getParentData['cover_pic'] != "") {
                $key_data = explode(',', $getParentData['cover_pic']);
            } else {
                $key_data = 0;
            }
        } else {
            $user_id = $get_data['user_id'];
            $contact_no = $get_data['contact_no'];
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

        $user_city = $get_data['city'];
        if ($keyword != "") {
            $keyword_array_data = explode(',', $keyword);
        } else {
            $keyword_array_data = "";
        }
    }
}

 $getReviews = $manage->getTotalReviews($user_id);

$htm_data1 .= '
<div class="profile-img pad_top">
        <img id="myImg" class="img-circle user-profile-img" src="'; if (!check_url_exits($profilePath) && $gender == "Male" or $img_name == "") {
$htm_data1 .= ' "../user/uploads/male_user.png"';
        } elseif (!check_url_exits($profilePath) && $gender == "Female" or $img_name == "") {
    $htm_data1 .= ' "../user/uploads/female_user.png"';
        } else {
$htm_data1 .= ' ' . $profilePath .'';
        } $htm_data1 .= '">
    <div class="whats-app">
        ';
        if ($get_bank_status != null) {
            if (isset($_GET['custom_url']) && $get_bank_status['digital_card'] == 1) {
    $htm_data1 .= '<a href="tel:' . "+".$country_code.$contact_no .'"><img
                        class="whats-app-logo img_top" src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/call_now.png"></a>
            '; }else { $htm_data1 .= '
                <a href="https://api.whatsapp.com/send?phone=' . $country_code.$whatsapp_no.  '"><img
                        class="whats-app-logo" src="' . $whatsapp_share_icon.  '"></a>
            ';
            }}
            $htm_data1 .= '
            <form method="post" action="">
                <a  href="export-vcf.php?custom_url=' . $custom_url. '" name="add_contact" class="btn_transparent img_top"
                        title="Export to vCard"> <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/save-contact.png" ></a>
            </form>
        ';
        $htm_data1 .= '
    </div>
</div>

<div class="client-name">
    <a href="index.php?custom_url=' . $custom_url . '">
    <h1>' . $name .'';

if($verify_user==1){
$htm_data1 .= '
            <img class="blue-tick" src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/blue_tick.png">
    ';
    }
$htm_data1 .= '
    </h1></a>

    <h3>'; if (isset($designation)) $htm_data1 .= ' ' . $designation .'</h3>';
    if($getReviews !=null) {
       if ($getReviews['average_rating'] <= "2") {
            $rating = "30";
        }elseif ($getReviews['average_rating'] <= "3") {
            $rating = "45";
        }elseif ($getReviews['average_rating'] <= "4") {
            $rating = "60";
        }elseif ($getReviews['average_rating'] <= "5") {
            $rating = "75";
        }
        $htm_data1 .= '
        <g id="G-REVIEW-STARS_21">
            <a href="testimonial.php?custom_url=' . $custom_url . '">' .$getReviews['average_rating']. '
             <span><i class="fa fa-star ';  if ($getReviews['average_rating'] >= "0.5") {
            $htm_data1 .= ' "fill-star"';
        } $htm_data1 .= '"></i><i class="fa fa-star ';  if ($getReviews['average_rating'] >= "1.5") {
            $htm_data1 .= ' "fill-star"';
        } $htm_data1 .= '"></i><i class="fa fa-star ';  if ($getReviews['average_rating'] >= "2.5") {
            $htm_data1 .= ' "fill-star"';
        } $htm_data1 .= '"></i><i class="fa fa-star ';  if ($getReviews['average_rating'] >= "3.5") {
            $htm_data1 .= ' "fill-star"';
        } $htm_data1 .= '"></i><i class="fa fa-star ';  if ($getReviews['average_rating'] >= "4.5") {
            $htm_data1 .= ' "fill-star"';
        } $htm_data1 .= '"></i></span> ' . $getReviews['rating_num']. '  Reviews</a>
        </g>
        ';
    }
    $htm_data1 .= '
</div></div></div>';
$htm_data = '<div class="social-logo">
  
    <ul class="contact-ul">
        <li>
        <a href="https://api.whatsapp.com/send?phone='; if($whatsapp_no !=''){ $htm_data .= ' '. $country_code. $whatsapp_no .''; }elseif ($contact_no !='') $htm_data .= ''. $country_code.$contact_no .'';  $htm_data .= ' "class="profile-img-a">
                <div class="p-align">
                    <div class="contact-icon-btm"><i class="fa fa-whatsapp"></i> WhatsApp</div>
                    <!--<p></p>--></div>
            </a></li>
        <li><a href="mailto:' . $email. '"
               class="profile-img-a">
                <div class="p-align">
                    <div class="contact-icon-btm"><i class="fas fa-envelope"></i> Mail</div>
                    <!--<p></p>--></div>
            </a></li>

        <li><a target="_blank" href="'; if (isset($website) && ($website) != "") {
    $htm_data .= ''. $website .'';
            } else {
    $htm_data .= '"#"';
            }  $htm_data .= '"
               class="profile-img-a">
                <div class="p-align">
                    <div class="contact-icon-btm
                                    '; if (isset($website) && ($website) == "") {
    $htm_data .= ' "disabled-icon"';
                    }  $htm_data .= '"><i class="fas fa-globe-europe"></i> Website
                    </div>
                    <!--<p></p>--></div>
            </a></li>
        <li><a href="
                        '; if (isset($map_link) && ($map_link) != "") {
    $htm_data .= ''. $map_link.'';
            } else {
    $htm_data .= ' "#"';
            }  $htm_data .= '" class="profile-img-a">
                <div class="p-align">
                    <div class="contact-icon-btm
                                    '; if (isset($map_link) && ($map_link) == "") {
    $htm_data .= '"disabled-icon"';
                    }  $htm_data .= '
                                    "><i class="fas fa-map-marker-alt"></i> Direction
                    </div>
                    <!--<p></p>--></div>
            </a></li>

    </ul>
</div>
            <ul class="company_nav_ul nav sticky_tab nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#Contact"
                                                          aria-controls="home"
                                                          role="tab"
                                                          data-toggle="tab"> '.$basic_info.' ' ;
$htm_data .= '</a>
                </li>

                <li role="presentation"><a href="#Company"
                                           aria-controls="profile"
                                           role="tab"
                                           data-toggle="tab">' . $company_info.'';
$htm_data .= '</a>
                </li>
            </ul>
            <div class="bank-up-div">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="Contact">
                        <div class="deatil-table">
                            <table class="info-table">
                                <tr>
                                    <td><a href="tel:' . "+".$country_code.$contact_no . '" class="profile-img-a">
                                            <div class="p-align">
                                                <div class=""><i class="fa fa-phone"></i></div>
                                            </div>
                                        </a></td>
                                    <td><span class="display_flex"><a
                                                    href="tel:' . "+".$country_code.$contact_no .'">' . $country_code.$contact_no . '</a><a href="tel:';  if (isset($altr_contact_no) && $altr_contact_no != "")  $htm_data .= '' . "+".$country_code.$altr_contact_no. ' ">';  if (isset($altr_contact_no) && $altr_contact_no != "")  $htm_data .= '' . "&nbsp;" . $country_code.$altr_contact_no .'</a>
                                       &nbsp; &nbsp;<form method="post" action="">
                                                <a href="export-vcf.php?custom_url=' . $custom_url . '" class="add_contact"
                                                   title="Export to vCard"><i class="fa fa-address-book-o"></i> Save Contact</a>
                                            </form>
                                        </span>
                                    </td>
                                    <!--<td>
                                    </td>-->
                                </tr>
                                <tr>
                                    '; 
                                    if (isset($saved_email) && $saved_email != "") {
                                        $htm_data .= '
                                        <td><a href="mailto:' .$saved_email .  '"
                                               class="profile-img-a">
                                                <div class="p-align">
                                                    <div class=""><i class="fas fa-envelope"></i></div>
                                                </div>
                                            </a></td>
                                        <td>
                                            <a target="_blank"
                                               href="mailto:' . $saved_email. '">' . $saved_email. '</a>
                                        </td>
                                        '; 
                                    } else {
                                        $htm_data .= '
                                        <td><a href="mailto:' . $email.  '"
                                               class="profile-img-a">
                                                <div class="p-align">
                                                    <div class=""><i class="fas fa-envelope"></i></div>
                                                </div>
                                            </a></td>
                                        <td>
                                            <a target="_blank"
                                               href="mailto:' . $email. '">' . $email. '</a>
                                        </td>';
                                    }
                                    $htm_data .= '

                                    <!--<td>

                                    </td>-->
                                </tr>

                                <tr>
                                    <td><a target="_blank"
                                           href="';  if (isset($website)) $htm_data .= '' . urlChecker($website) .'"
                                           class="profile-img-a">
                                            <div class="p-align">
                                                <div class=""><i class="fas fa-globe-europe"></i></div>
                                            </div>
                                        </a></td>
                                    <td>';
                                        if ($website != null) {
                                            $htm_data .= '<a href="'. urlChecker($website) .'" target="_blank"> '.  strlen($website) > 35 . '' ?  ' '.substr($website, 0, 35) . "..." :  ' '.$website. '</a>';
                                        } else {
                                            $htm_data .= ' "Website not available"';
                                        }
                                        $htm_data .= '</td>
                                    <!--<td>

                                    </td>-->
                                </tr>

                                <tr>
                                    <td><a href="' . $map_link . '" class="profile-img-a">
                                            <div class="p-align">
                                                <div class=""><i class="fas fa-map-marker-alt"></i></div>
                                            </div>
                                        </a></td>

                                    <td>'; 
                                        if ($address != null) { $htm_data .= '<p><a
                                                    href="' . $map_link.  '"
                                                    target="_blank">';  if (isset($address)) $htm_data .= ''. wordwrap($address, 40, "\n") .' </a>
                                            </p>
                                            '; 
                                        } else {
                                            $htm_data .= ' "Address not available"';
                                        } $htm_data .= '
                                    </td>
                                    <!--<td>
                                    </td>-->
                                </tr>
                            </table>

                            <div class="contact-bottom">
                                <ul class="social-ul">
                                    <li><a ';  if ($youtube == "") {

    $htm_data .= 'style="cursor: not-allowed;filter: grayscale(100%);"  "target="_self"';
                                        } else {
    $htm_data .= 'target="_blank"';
                                        } $htm_data .= ' href="';  if (isset($youtube) && ($youtube) != "") {
    $htm_data .= ''. $youtube.'';
                                        } else {
    $htm_data .= '"#"';
                                        } $htm_data .= '" class="linkedin"><img src="' . $youtube_icon.  '"></a>
                                    </li>
                                    <li><a ';  if ($whatsapp_no == "") {
                                            $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                        } $htm_data .= ' href="';  if ($whatsapp_no != "") { $htm_data .= 'https://api.whatsapp.com/send?phone=' . $country_code. $whatsapp_no . '"';
                                        } else {
                                            $htm_data .= ' "#"';
                                        } $htm_data .= ' class="facebook"> <img src="' . $whatsapp_share_icon . '"></a>
                                    </li>
                                    <li><a ';  if ($facebook == "") {
                                            $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                        } else {
                                            $htm_data .= ' "target="_blank"';
                                        } $htm_data .= ' href="';  if (isset($facebook) && ($facebook) != "") {
                                            $htm_data .= ' ' . $facebook . '';
                                        } else {
                                            $htm_data .= ' "#"';
                                        } $htm_data .= '" class="facebook"> <img src="' . $facebook_share_con . '"></a>
                                    </li>
                                    <li><a ';  if ($contact_no == "") {
                                            $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                        } else {
                                              $htm_data .= ' "target="_blank"';
                                        } $htm_data .= ' href="sms:';  if (isset($contact_no) && ($contact_no) != "") {
                                            $htm_data .= '' . $country_code.$contact_no .'';
                                        } else {
                                            $htm_data .= ' "#"';
                                        } $htm_data .= '" class="facebook"> <img src="' . $sms_model_icon . '"></a>
                                    </li>
                                    <li><a ';  if ($twitter == "") {
                                            $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                        } else {
                                            $htm_data .= ' "target="_blank"';
                                        } $htm_data .= ' href="';  if (isset($twitter) && ($twitter) != "") {
                                            $htm_data .= ' ' .$twitter . '';
                                        } else {
                                            $htm_data .= ' "#"';
                                        } $htm_data .= '" class="twitter"><img src="' . $twitter_icon . '"></a>
                                    </li>
                                    <li><a ';  if ($instagram == "") {
                                            $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                        } else {
                                             $htm_data .= ' "target="_blank"';
                                        } $htm_data .= ' href="';  if (isset($instagram) && ($instagram) != "") {
                                            $htm_data .= ' ' .$instagram .'';
                                        } else {
                                            $htm_data .= ' "#"';
                                        } $htm_data .= '" class="instagram"><img src="' . $instagram_icon . '"></a>
                                    </li>
                                    <li><a ';  if ($linked_in == "") {
                                            $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                        } else {
                                             $htm_data .= ' "target="_blank"';
                                        } $htm_data .= ' href="';  if (isset($linked_in) && ($linked_in) != "") {
                                            $htm_data .= ' ' .$linked_in .'';
                                        } else {
                                            $htm_data .= ' "#"';
                                        } $htm_data .= '" class="linkedin"><img
                                                    src="' . $linked_in_icon . '"></a></li>
                                    ';  if ($playstore != "") {
                                        $htm_data .= '
                                        <li><a ';  if ($playstore == "") {
                                                $htm_data .= ' "style="cursor: not-allowed;filter: grayscale(100%).""  "target="_self"';
                                            } else {
                                                  $htm_data .= ' "target="_blank"';
                                            } $htm_data .= ' href="';  if (isset($playstore) && ($playstore) != "") {
                                                $htm_data .= ''. $playstore .'';
                                            } else {
                                                $htm_data .= ' "#"';
                                            } $htm_data .= '" class="linkedin"><img
                                                        src="<?php echo FULL_MOBILE_URL; ?>assets/images/icon/playstore.png"></a></li>
                                        ';
                                    }
                                    $htm_data .= '
                                </ul>
                            </div>
                        </div>
                    </div>
                 
                </div>

            </div>


            <!--<div class="contact-heading">
                 <div class="row cust-margin">
                     <div class="col-xs-7">
                         <h4 class="contact-h4">Contact Information</h4>
                     </div>
                 </div>
             </div>-->

            ';
if (isset($_GET['custom_url'])) {
    /*$custom_url = $_GET['custom_url'];
    $section_service_id = 1;
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
   $baseName = basename($_SERVER['PHP_SELF']);


}


 $htm_data .= '
<div class="cust-footer mobile_footer">
    <ul class="footer-ul">
        <li><a href="index.php' . get_all_get() . '"><img src="' .  $mobile_profile_user . '"></a>
            <h6>' .  $profile . '</h6>
        </li>';
         $htm_data .= '<li>
                <a href="services.php' .  get_all_get() . '"><img src="' .  $service_tab_icon . '"></a>
                <h6>' .  $services . '</h6>
            </li>';
          $htm_data .= '  <li>
                <a href="gallery.php' .  get_all_get() . '"><img src="' .  $gallery_tab_icon . '"></a>
                <h6>' .  $gallery . '</h6>
            </li>';
             $htm_data .= '  <li>
                <a href="testimonial.php' .  get_all_get() . '"><img src="' .  $client_tab_icon . '"> </a>
                <h6>' .  $clients . '</h6>
            </li>';
             $htm_data .= ' <li><a href="our-team.php' .  get_all_get() . '"><img src="' .  $our_team_tab_icon . '"></i></a>
                <h6>' .  $team . '</h6>
            </li>';
        if($validToken) {
             $htm_data .= '
            <li><a href="payment.php' .  get_all_get() . '"><img
                            src="' .  $bank_tab_icon . '"></a>
                <h6>' .  $bank . '</h6>
            </li>';
        }
$htm_data .= '
    </ul>
</div>
        </div>
    </div>
</section>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>';

  echo $htm_data1;

/*$data = "<h2>Hello World!</h2>";
$filename = "digital-card";

// include autoloader
require_once '../dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($htm_data1);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($filename);*/

