<?php
include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
if (isset($_GET['custom_url'])) {
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($_GET['custom_url']);
    $parent_id = $get_data['parent_id'];
    if($parent_id !=""){
        $getParentData = $manage->getSpecificUserProfileById($parent_id);
        $custom_url = $getParentData['custom_url'];
    }else{
        $custom_url = $_GET['custom_url'];
    }

    $get_result = $manage->mdm_getDigitalCardDetails("team",$custom_url);
}else{
    header('location:../index.php');
}
include "assets/common-includes/count-includes.php";
$section_id = 6;

/*if($TeamSectionStatus != 1){
    $redirect = FULL_MOBILE_URL . "payment.php" . get_full_param();
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
    $get_section_theme = $manage->mdm_displaySectionTheme($user_id,$section_id);
    if($get_section_theme !=null){
        $section_theme = $get_section_theme['theme_id'];
    }else{
        $section_theme = 1;
    }


    ?>
</head>

<body data-swipeleft="payment.php<?php echo get_all_get(); ?>" data-swiperight="our-team.php<?php echo get_all_get(); ?>">
<div class="loader-overlay"></div>

<section>
    <div class="content-main">
        <div class="overlay overlay-height">
            <?php include "assets/common-includes/profile.php"; ?>
            <div class="our-team-heading"><h3><?php echo $our_team; ?></h3></div>
            <div class="container padding-right-scroll">
                <div class="row">
                    <div class=" padd-bot scrollbar style-11 ">
                        <?php
                        if ($get_result != null) {
                        ?>
                        <ul class="our-team-ul">
                            <?php
                                while ($result_data = mysqli_fetch_array($get_result)) {
                            $team_path = FULL_WEBSITE_URL."user/uploads/" . $result_data['email'] . "/our-team/" . $result_data['img_name'];
                            if($section_theme == 1) {
                                    ?>
                                    <li>
                                        <div class="our-team">
                                               <div class="pic_img">
                                                   <img
                                                       src="<?php if(check_url_exits($team_path) && $result_data['img_name']!=""){ echo FULL_WEBSITE_URL."user/uploads/" . $result_data['email'] . "/our-team/" . $result_data['img_name']; }else{ echo FULL_WEBSITE_URL."user/uploads/user.png";} ?>">
                                               </div>
                                            <div class="team-content">
                                                <h3 class="title"><?php echo $result_data['name']; ?></h3>
                                                <span class="post"><?php echo rep_escape($result_data['designation']); ?></span>
                                            </div>
                                      
                                       <?php
                                        if ($result_data['dg_link'] != '' OR $result_data['c_number'] != '' OR $result_data['w_number'] != '') {
                                        ?>
                                        <div class="btn-group" style="width: 100%">
                                            <?php
                                            if($result_data['c_number'] !='') {
                                                ?>
                                                <a href="tel:<?php
                                                echo $result_data['c_number'];
                                                ?>" <?php if($result_data['w_number'] =='') echo 'style=width:100%'; ?> class="btn our_team_opt_bn btn-primary"><i
                                                            class="fa fa-phone" aria-hidden="true"></i> Call</a>
                                                <?php
                                            }
                                            if($result_data['w_number'] !='') {
                                                ?>
                                                <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php
                                                echo $country_code.$result_data['w_number'];
                                                ?>" <?php if($result_data['c_number'] =='') echo 'style=width:100%'; ?> class="btn our_team_opt_bn btn-primary"><i
                                                            class="fab fa-whatsapp"></i> WhatsApp</a>

                                                <?php
                                            }
                                            if($result_data['dg_link'] !='') {
                                                ?>
                                                <a target="_blank" href="<?php echo $result_data['dg_link']; ?>"
                                                   class="btn form-control read_more_btn btn-primary"><i class="fas fa-external-link-alt"></i> View
                                                    Digital Card</a>
                                                <?php

                                            }
                                            ?>
                                        </div>
                                            <?php
                                        }
                                        ?>

                                        </div>
                                    </li>
                                <?php
                            }else{ ?>
                                <li>
                                    <div class="team-area team-items">
                                        <div class="single-item">
                                            <div class="item">
                                                <div class="thumb">
                                                    <img class="img-fluid" src="<?php if (check_url_exits($team_path) && $result_data['img_name'] != "") {
                                                        echo $team_path;
                                                    } else {
                                                        echo FULL_WEBSITE_URL."user/uploads/user.png";
                                                    } ?>" alt="Thumb">
                                                </div>
                                                <div class="theme2-info">
                                                    <?php
                                                    if ($result_data['dg_link'] != '' OR $result_data['c_number'] != '' OR $result_data['w_number'] != '') {
                                                        ?>
                                                        <?php
                                                        if ($result_data['c_number'] != '') {
                                                            ?>
                                                            <span class="theme2_call">
                                    <a href="tel:<?php
                                    echo $result_data['c_number'];
                                    ?>"><i class="fas fa-phone fa-flip-horizontal"></i></a>
                                </span>
                                                            <?php
                                                        }
                                                        if ($result_data['w_number'] != '') {
                                                            ?>
                                                            <span class="message">
                                    <a  target="_blank"
                                        href="https://api.whatsapp.com/send?phone=<?php
                                        echo $country_code . $result_data['w_number'];
                                        ?>"><i class="fab whastapp_theme2_icon fa-whatsapp"></i></a>
                                </span>
                                                            <?php
                                                        }
                                                        if ($result_data['dg_link'] != '') {
                                                            ?>
                                                            <span class="theme2_whatsapp">
                                    <a  target="_blank"
                                        href="<?php echo $result_data['dg_link']; ?>"><i class="fas fa-external-link-alt"></i></a>
                                </span>
                                                            <?php

                                                        }
                                                        ?>
                                                        <?php
                                                    }
                                                    ?>
                                                    <h4><?php echo $result_data['name']; ?></h4>
                                                    <span><?php echo $result_data['designation']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                                <?php
                            }}
                            ?>
                        </ul>
                        <?php  }else{ ?>
                        <div class="col-lg-12">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="text-center no_data_found">
                                    <img src="<?php echo FULL_MOBILE_URL; ?>assets/images/our team.png">
                                    <h5>Our Team Details will Appear Soon in this Section.</h5>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php include "assets/common-includes/footer.php" ?>
        </div>
    </div>
</section>

<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    var div_top = $('.our-team-heading').offset().top;

    $(window).scroll(function() {
        var window_top = $(window).scrollTop() - 0;
        if (window_top > div_top) {
            if (!$('.our-team-heading').is('.sticky')) {
                $('.our-team-heading').addClass('sticky');
            }
        } else {
            $('.our-team-heading').removeClass('sticky');
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