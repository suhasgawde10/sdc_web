



<?php


require_once "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();

include_once '../sendMail/sendMail.php';
require_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../data-uri-image.php";
$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$Themerror = false;
$ThemerrorMessage = "";


include "assets/common-includes/all-query.php";


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <title>Gallery - <?php echo $name; ?> - <?php echo $designation; ?> -<?php echo $_SERVER['HTTP_HOST']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php
    if($about_company !=""){
        echo substr($about_company,0,160);
    }else{
        echo $contact_no . "-".  $user_email;
    }
    ?>">
    <meta name="keywords" content="<?php
    if($keyword_array_data !=""){
        foreach ($keyword_array_data as $array_key){
            echo $array_key;
            if($user_city !=""){
                echo " in ".$user_city.",";
            }else{
                echo ",";
            }
        }
    }
    ?>" >
    <meta property="og:title" content="<?php echo $name; ?> - <?php echo $designation; ?>"/>
    <meta property="og:url" content="<?php echo SHARED_URL. $_GET['custom_url'] ?>"/>
    <meta property="og:description"
          content="<?php echo $contact_no; ?> - <?php echo $user_email; ?>">
    <meta property="og:site_name" content="Share Digital Card">
    <meta property="og:image" itemprop="image" content="<?php if ($metaProfilePath != "") {
        echo $metaProfilePath;
    } elseif (!check_url_exits($metaProfilePath) && $gender == "Male" or $img_name == "") {
        echo "https://sharedigitalcard.com/user/uploads/male_user.png";
    } elseif (!check_url_exits($metaProfilePath) && $gender == "Female" or $img_name == "") {
        echo "https://sharedigitalcard.com/user/uploads/female_user.png";
    } ?>">
    <meta property="og:type" content="website"/>
    <meta property="og:updated_time" content="1440432930"/>
    <!-- <meta property="og:image" content="<?php /*if ($img_name == "" && $gender == "Male") {
        echo FULL_WEBSITE_URL."user/uploads/male_user.png";
    } elseif ($img_name == "" && $gender == "Female") {
        echo FULL_WEBSITE_URL."user/uploads/female_user.png";
    } else {
        echo $profilePath;
    } */ ?>">-->
    <?php include "assets/common-includes/header_includes.php" ?>
</head>

<body class="background_body_image">
<?php
/*
echo $name;
die();*/

?>
<div class="end_sub_overlay">
    <div style="margin-top: 10%;text-align: center;"><!--class="bg-text"-->
        <img src="<?php echo FULL_DESKTOP_URL; ?>assets/images/sub.png" style="width: 40%">
    </div>
</div>

<section>
    <div class="digi-heading"></div>
    <div class="container">
        <div class="digi-web-main">
            <div>
                <?php  include "assets/common-includes/left_menu.php" ?><!--Left Menu-->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12 bhoechie-tab-container">
                            <div class=" col-md-2  bhoechie-tab-menu-custom">
                                <?php include "assets/common-includes/nav_tab.php" ?>
                            </div>
                            <div class=" col-md-10 bhoechie-tab margin-padding-remover">
                                <div class="bhoechie-tab-content margin-padding-remover"></div>
                                <?php
                                if (isset($_GET['custom_url'])) {
                                    if($parent_id !=""){
                                        $user_id = $parent_id;
                                        $custom_url = $getParentData['custom_url'];
                                    }else{
                                        $custom_url = $_GET['custom_url'];
                                    }
                                    /*
                                        $get_image_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);
                                        $get_modal_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);
                                        $get_column_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);*/
                                    /* $section_service_id = 1;
                                     $get_service_status = $manage->displayOnOffStatus($custom_url, $section_service_id);*/
                                    $section_image_id = 2;
                                    $get_image_status = $manage->displayOnOffStatus($custom_url, $section_image_id);
                                    $section_video_id = 3;
                                    $get_video_status = $manage->displayOnOffStatus($custom_url, $section_video_id);
                                    $section_client_id = 4;
                                    /* $get_client_status = $manage->displayOnOffStatus($custom_url, $section_client_id);
                                     $section_client_review_id = 5;
                                     $get_client_review_status = $manage->displayOnOffStatus($custom_url, $section_client_review_id);
                                     $section_our_team_id = 6;
                                     $get_our_team_status = $manage->displayOnOffStatus($custom_url, $section_our_team_id);
                                     $section_bank_id = 7;
                                     $get_bank_status = $manage->displayOnOffStatus($custom_url, $section_bank_id);*/
                                }
                                ?>

                                <div class="bhoechie-tab-content margin-padding-remover active">
                                    <section>
                                        <div class="content-main background-theme-cust">
                                            <div class="all-main-heading">
                                                <span class="text-color-p"><?php echo $gallery; ?></span>
                                                <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Gallery" class="add-icon-color fas fa-pencil-alt" href=<?php /*echo FULL_WEBSITE_URL; */?>."user/gallery.php">&nbsp;&nbsp;Edit</a>
                                                --><?php /*} */?>
                                            </div>
                                            <div class="cust-coverlay overlay-height">

                                                <div class="card">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <?php
                                                        /*                        if($get_image_status!=null){
                                                                                if(isset($_GET['custom_url']) && $get_image_status['digital_card']==1){ */?>
                                                        <li role="presentation" ><a href="<?php echo FULL_DESKTOP_URL."gallery".get_full_param();  ?>"><?php echo $images; ?></a>
                                                        </li>
                                                        <?php /*}} */?><!--
                        --><?php
                                                        /*                        if($get_video_status!=null){
                                                                                if(isset($_GET['custom_url']) && $get_video_status['digital_card']==1){ */?>
                                                        <li role="presentation" class="active" ><a href="#Video-tab" aria-controls="home"  role="tab" data-toggle="tab"><?php echo $videos; ?></a>
                                                        </li>
                                                        <?php /*}} */?>
                                                    </ul>
                                                    <div class="bank-up-div">
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane active" id="Video-tab">
                                                                <div class="col-md-12 margin_icon">
                                                                    <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Service" class="fas add-icon-color fa-plus-circle" href=FULL_WEBSITE_URL."user/video_gallery.php"></a>
                                    --><?php /*} */?>
                                                                </div>
                                                                <div class="video-main scrollbar style-11">
                                                                    <?php
                                                                    $get_data =$manage->mu_displayVideoDetailsByLimit($user_id,0,6);
                                                                    if ($get_data != null) {
                                                                        ?>
                                                                        <div class="post-wrapper">
                                                                            <!-- Loading overlay -->
                                                                            <div class="loading-overlay"><div class="overlay-content">Loading...</div></div>

                                                                            <!-- Post list container -->
                                                                            <div id="postContent">
                                                                                <?php
                                                                                // Include pagination library file
                                                                                include_once 'assets/common-includes/Pagination.php';

                                                                                // Set some useful configuration
                                                                                $baseURL = 'getData.php';
                                                                                $limit =6;
                                                                                $get_count = $manage->mdm_displayVideoCount($user_id);
                                                                                // $rowCount= $result['rowNum']; // video count

                                                                                // Initialize pagination class
                                                                                $pagConfig = array(
                                                                                    'user_id' => $user_id,
                                                                                    'baseURL' => $baseURL,
                                                                                    'totalRows' => $get_count,
                                                                                    'perPage' => $limit,
                                                                                    'contentDiv' => 'postContent'
                                                                                );
                                                                                $pagination =  new Pagination($pagConfig);

                                                                                ?>
                                                                                <ul class="video-main-ul">
                                                                                    <?php
                                                                                    while ($form_data = mysqli_fetch_array($get_data)) {
                                                                                        $video_link = str_replace("watch?v=","embed/",$form_data['video_link']); // &feature=youtu.be
                                                                                        $video_link = str_replace("&feature=youtu.be","",$video_link); // &feature=youtu.be
                                                                                        ?>
                                                                                        <li>
                                                                                            <div class="info-video">
                                                                                                <iframe src=<?php echo $video_link; ?>
                                                                                                        frameborder="0"
                                                                                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                                                        allowfullscreen></iframe>
                                                                                            </div>
                                                                                        </li>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </ul>
                                                                                <!-- Display pagination links -->

                                                                                <?php echo $pagination->createLinks(); ?>

                                                                            </div>
                                                                        </div>
                                                                    <?php }else{ ?>
                                                                        <div class="col-lg-12">
                                                                            <div class="col-lg-8 col-lg-offset-2">
                                                                                <div class="text-center no_data_found">

                                                                                    <img src="<?php echo $gallery_not_found; ?>">
                                                                                    <h5>No Video to Showcase in Gallery Section</h5>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </section>
                                </div>

                                <script>
                                    // Show loading overlay when ajax request starts
                                    $( document ).ajaxStart(function() {
                                        console.log('here');
                                        $('.loading-overlay').show();
                                    });

                                    // Hide loading overlay when ajax request completes
                                    $( document ).ajaxStop(function() {
                                        $('.loading-overlay').hide();
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</section>





<!--<script type="text/javascript">
    if (screen.width <= 768 || screen.height == 480) //if 1024x768
        window.location.replace("../<?php /*if(isset($_GET['custom_url'])) echo $_GET['custom_url'];*/ ?>")
</script>-->





<script>
    $('.list-group-item').on('click',function(){
        return true;
    });
</script>
<?php include "assets/common-includes/footer.php" ?>
<?php include "assets/common-includes/footer_includes.php" ?>



<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
