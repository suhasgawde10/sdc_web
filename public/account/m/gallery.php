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

/*    $get_image_result = $manage->mdm_getDigitalCardDetails("image",$custom_url);
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

/*if($gallerySectionStatus != 1){
    $redirect = get_url_param_for_mobile('testimonial.php');
    header('Location: '.$redirect);
    die();
}*/
?>


<!DOCTYPE html>
<html>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "assets/common-includes/header_includes.php" ?>
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
            bottom:0;
            position: fixed;
        }
    </style>
    <?php
    $get_image_result = $manage->mu_displayGalleryDetailsByLimit($user_id,0,21);
    $get_modal_result = $manage->mu_displayGalleryDetailsByLimit($user_id,0,21);
    $get_column_result = $manage->mu_displayGalleryDetailsByLimit($user_id,0,21);
    ?>
</head>
<body data-swipeleft="testimonial.php<?php echo get_all_get(); ?>" data-swiperight="service.php<?php echo get_all_get(); ?>">
<div class="loader-overlay"></div>

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
                    <li role="presentation" class="active"><a href="#Images"
                                                              aria-controls="home"
                                                              role="tab"
                                                              data-toggle="tab"><?php echo $images; ?></a>
                    </li>
                    <?php /*}} */?><!--
                        --><?php
                    /*                        if($get_video_status!=null){
                                            if(isset($_GET['custom_url']) && $get_video_status['digital_card']==1){ */?>
                    <li role="presentation" <?php if(isset($_GET['custom_url']) && $get_image_status['digital_card']==0){ ?> class="active" <?php } ?> ><a href="<?php echo get_url_param_for_mobile('video_gallery.php') ?>"><?php echo $videos; ?></a>
                    </li>
                    <?php /*}} */?>
                </ul>
                <div class="bank-up-div">
                    <div class="tab-content" style="overflow: hidden">
                        <div role="tabpanel" class="tab-pane active" id="Images">

                            <div class="col-md-12">
                                <ul class="gallery-part-ul">
                                    <li><a  href="javascript:void(0)" ><img onclick="changeSize('31%','108px',this)" id="first_li_img" src="<?php echo FULL_DESKTOP_URL ?>assets/images/3.png"></a></li>
                                    <li><a  href="javascript:void(0)" ><img onclick="changeSize('48%','170px',this)" src="<?php echo FULL_DESKTOP_URL ?>assets/images/2.png"></a></li>
                                    <li><a  href="javascript:void(0)"><img onclick="changeSize('100%','auto',this)" src="<?php echo FULL_DESKTOP_URL ?>assets/images/1.png"></a></li>
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
                                            $limit =21;
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
                                            <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/gallary.png">
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

            <?php include "assets/common-includes/footer.php" ?>
        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    function changeSize(width,height,this_val) {
        $('.img-ul-container ul li').css('width',width);
        $('.img-ul-container ul li').css('height',height);
        $('.gallery-part-ul li a img').removeAttr("style");
        $(this_val).css({'background': 'white',
            'box-shadow': '0 0 11px 1px rgb(0 0 0 / 14%)'});
    }
    $(document).ready(function () {
        $('#first_li_img').css({'background': 'white',
            'box-shadow': '0 0 11px 1px rgb(0 0 0 / 14%)'});
    });
</script>
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
<script>
//    $('.load-mobile-redirect').click(function () {
//        $(".loader-overlay").css("display", "block");
//        $('.loader-overlay').html('<img src="<?php //echo FULL_MOBILE_URL ?>//assets/images/loader-below.gif" style="width: 100%;height: 60vh;"/>');
////        return false
//    });
</script>
</body>
</html>