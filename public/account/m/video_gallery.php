<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();

include "assets/common-includes/count-includes.php";

if (isset($_GET['custom_url'])) {
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
    $parent_id = $get_data['parent_id'];
    if($parent_id !=""){
        $getParentData = $manage->getSpecificUserProfileById($parent_id);
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

   /* $get_image_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);
    $get_modal_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);
    $get_column_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);
    $get_video_data = $manage->mdm_getDigitalCardDetails("video",$custom_url);*/
    $section_image_id = 2;
    $get_image_status = $manage->displayOnOffStatus($custom_url, $section_image_id);
    $section_video_id = 3;
    $get_video_status = $manage->displayOnOffStatus($custom_url, $section_video_id);
}else{
    header('location:../index.php');
}
?>


<!DOCTYPE html>
<html>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body data-swipeleft="testimonial.php<?php echo get_all_get(); ?>" data-swiperight="service.php<?php echo get_all_get(); ?>">

<section>

    <div class="content-main">
        <div class="overlay overlay-height">
            <?php include "assets/common-includes/profile.php"; ?>
            <!--<div class="gallery-heading">
            </div>-->

            <div class="card">
                <ul class="nav sticky_tab nav-tabs" role="tablist">
                    <?php
                    /*                        if($get_image_status!=null){
                                            if(isset($_GET['custom_url']) && $get_image_status['digital_card']==1){ */?>
                    <li role="presentation" ><a href="<?php echo get_url_param_for_mobile('gallery.php'); ?>"><?php echo $images; ?></a>
                    </li>
                    <?php /*}} */?><!--
                        --><?php
                    /*                        if($get_video_status!=null){
                                            if(isset($_GET['custom_url']) && $get_video_status['digital_card']==1){ */?>
                    <li role="presentation" class="active" ><a href="#Video-tab" aria-controls="home"  role="tab"     data-toggle="tab"><?php echo $videos; ?></a>
                    </li>
                    <?php /*}} */?>
                </ul>
                <div class="bank-up-div">
                    <div class="tab-content" style="overflow: hidden">

                        <div role="tabpanel" class="tab-pane active" id="Video-tab">
                            <div class="col-md-12 margin_icon">
                                <?php /*if (isset($_SESSION['email'])) { */?><!-- <a title="Add Service" class="fas add-icon-color fa-plus-circle" href="../user/video_gallery.php"></a>
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

            <?php include "assets/common-includes/footer.php" ?>
        </div>
    </div>
</section>
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
<script>
    var div_top = $('.sticky_tab').offset().top;

    $(window).scroll(function() {
        var window_top = $(window).scrollTop() - 0;
        if (window_top > div_top) {
            if (!$('.sticky_tab').is('.sticky')) {
                $('.sticky_tab').addClass('sticky');
            }
        } else {
            $('.sticky_tab').removeClass('sticky');
        }
    });
</script>
</body>
</html>