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

if($gallerySectionStatus != 1){
    $redirect = FULL_DESKTOP_URL . "testimonial" . get_full_param();
    header('Location: '.$redirect);
    die();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Gallery - <?php echo $name; ?> - <?php echo $designation; ?> -<?php echo $_SERVER['HTTP_HOST']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                    $get_image_result = $manage->mu_displayGalleryDetailsByLimit($user_id,0,20);
                                    $get_modal_result = $manage->mu_displayGalleryDetailsByLimit($user_id,0,20);
                                    $get_column_result = $manage->mu_displayGalleryDetailsByLimit($user_id,0,20);
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
                                                <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Gallery" class="add-icon-color fas fa-pencil-alt" href="<?php /*echo FULL_WEBSITE_URL; */?>user/gallery.php">&nbsp;&nbsp;Edit</a>
                                                --><?php /*} */?>
                                            </div>
                                            <div class="cust-coverlay overlay-height">

                                                <div class="card">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <?php
                                                        /*                        if($get_image_status!=null){
                                                                                if(isset($_GET['custom_url']) && $get_image_status['digital_card']==1){ */?>
                                                        <li role="presentation" class="active"><a href="#Images"
                                                                                                  aria-controls="home"
                                                                                                  role="tab"
                                                                                                  data-toggle="tab"><?php echo $images; ?></a>
                                                        </li>
                                                        <?php /*}} */?><!--
                        --><?php
                                                        /*                        if($get_video_status!=null){
                                                                                if(isset($_GET['custom_url']) && $get_video_status['digital_card']==1){ */?>
                                                        <li role="presentation" <?php if(isset($_GET['custom_url']) && $get_image_status['digital_card']==0){ ?> class="active" <?php } ?> ><a href="<?php echo FULL_DESKTOP_URL."video_gallery".get_full_param(); ?>"><?php echo $videos; ?></a>
                                                        </li>
                                                        <?php /*}} */?>
                                                    </ul>
                                                    <div class="bank-up-div">
                                                        <div class="tab-content">
                                                            <div role="tabpanel" class="tab-pane active" id="Images">
                                                                <div class="col-md-12 margin_icon">
                                                                    <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Service" class="fas add-icon-color fa-plus-circle" href=FULL_WEBSITE_URL."user/gallery.php"></a>
                                    --><?php /*} */?>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <ul class="gallery-part-ul">
                                                                        <li><a  href="javascript:void(0)"><img onclick="changeSize('24%','110px',this)" id="first_li_img" src="<?php echo FULL_DESKTOP_URL; ?>assets/images/4.png"></a></li>
                                                                        <li><a  href="javascript:void(0)" ><img onclick="changeSize('32%','180px',this)" src="<?php echo FULL_DESKTOP_URL; ?>assets/images/3.png"></a></li>
                                                                        <li><a  href="javascript:void(0)"><img onclick="changeSize('49%','200px',this)" src="<?php echo FULL_DESKTOP_URL; ?>assets/images/2.png"></a></li>
                                                                    </ul>
                                                                </div>

                                                                <div class="img-ul-container scrollbar style-11">

                                                                    <?php

                                                                    if ($get_image_result != null) {
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
                                                                                $baseURL = 'getImgData.php';
                                                                                $limit = 20;
                                                                                $get_count = $manage->mdm_displayGalleryCount($user_id);
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
                                                                                <ul>
                                                                                    <?php
                                                                                    $count = 1;
                                                                                    while ($result_image_data = mysqli_fetch_array($get_image_result)) {
                                                                                        ?>
                                                                                        <li>
                                                                                            <div class="gallery-div">
                                                                                                <img src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_image_data['email'] . "/images/" . $result_image_data['img_name']; ?>"
                                                                                                     onclick="openModal();currentSlide(<?php echo $count; ?>)"
                                                                                                     class="hover-shadow cursor img-cust-gall">
                                                                                            </div>
                                                                                        </li>
                                                                                        <?php
                                                                                        $count++;
                                                                                    }
                                                                                    ?>
                                                                                </ul>
                                                                                <!-- Display pagination links -->
                                                                                <?php echo $pagination->createLinks(); ?>

                                                                            </div>
                                                                        </div>
                                                                        <div id="myModalImage" class="modal">
                                                                            <span class="close cursor cust-close-img-gall" onclick="closeModal()">&times;</span>

                                                                            <div class="modal-content model-img">
                                                                                <?php
                                                                                if ($get_modal_result != null) {
                                                                                    $total = mysqli_num_rows($get_modal_result);
                                                                                    $count = 1;
                                                                                    while ($result_modal_data = mysqli_fetch_array($get_modal_result)) {
                                                                                        ?>

                                                                                        <div class="mySlides">
                                                                                            <div class="numbertext"><?php echo $count; ?> / <?php echo $total; ?></div>
                                                                                            <img
                                                                                                    src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_modal_data['email'] . "/images/" . $result_modal_data['img_name']; ?>"
                                                                                                    style="width:100%">
                                                                                        </div>
                                                                                        <?php
                                                                                        $count++;
                                                                                    }
                                                                                }
                                                                                ?>

                                                                                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                                                                                <a class="next" onclick="plusSlides(1)">&#10095;</a>

                                                                                <div class="caption-container">
                                                                                    <p id="caption"></p>
                                                                                </div>

                                                                                <?php
                                                                                if ($get_column_result != null) {
                                                                                    $count = 1;
                                                                                    while ($result_column_data = mysqli_fetch_array($get_column_result)) {
                                                                                        ?>
                                                                                        <div class="column img-slider">
                                                                                            <img class="demo cursor" src="<?php echo FULL_WEBSITE_URL."user/uploads/" . $result_column_data['email'] . "/images/" . $result_column_data['img_name']; ?>"
                                                                                                 style="width:100%"
                                                                                                 onclick="currentSlide(<?php echo $count; ?>)" alt="<?php echo $result_column_data['image_name']; ?>">
                                                                                        </div>
                                                                                        <?php
                                                                                        $count++;
                                                                                    }
                                                                                }?>


                                                                            </div>
                                                                        </div>
                                                                    <?php }else{ ?>
                                                                        <div class="col-lg-12">
                                                                            <div class="col-lg-8 col-lg-offset-2">
                                                                                <div class="text-center no_data_found">
                                                                                    <img src="<?php echo $gallery_not_found; ?>">
                                                                                    <h5>No Image to Showcase in Gallery Section</h5>
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

<script>

    $(document).ready(function () {
        $('#first_li_img').css({'background': 'white',
            'box-shadow': 'rgba(0, 0, 0, 0.14) 0px 0px 11px 1px'});
    });
    function changeSize(width,height,this_val) {
        $('.img-ul-container ul li').css('width',width);
        $('.img-ul-container ul li .gallery-div').css('height',height);
        // console.log(this_val);
        $('.gallery-part-ul li a img').removeAttr("style");
        $(this_val).css({'background': 'white',
        'box-shadow': '0 0 11px 1px rgb(0 0 0 / 14%)'});
    }
</script>





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

<script>
    function openModal() {
        document.getElementById("myModalImage").style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModalImage").style.display = "none";
    }

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        var captionText = document.getElementById("caption");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
        captionText.innerHTML = dots[slideIndex - 1].alt;
    }
</script>

<?php /*include "../assets/common-includes/mobile-desktop-url-changer.php" */ ?>
</body>
</html>
