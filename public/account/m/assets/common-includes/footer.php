<?php
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

//    print_r($statusUnhideCount);
    if ($statusUnhideCount != "") {
        $countShow = $statusUnhideCount['totalShow'];
        if ($countShow == 7) {
            $liWidth = 13;
        } elseif ($countShow == 6) {
            $liWidth = 15;
        } elseif ($countShow == 5) {
            $liWidth = 17;
        } elseif ($countShow == 4) {
            $liWidth = 17;
        } else {
            $liWidth = 17;
        }
    } else {
        $liWidth = 13;
    }

//    exit;
}

?>

<div class="cust-footer mobile_footer">
    <ul class="footer-ul">
        <?php
        if ($ProfileSectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;"><a
                    href="<?php echo get_url_param_for_mobile("index.php"); ?>">
                    <?php
                    if ($profileTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $profileTabIcon["section_img"] . " ";
                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo $mobile_profile_user; ?>"/>
                    <?php
                    } else {
                        ?>
                        <img src="<?php echo $mobile_profile_user; ?>">
                    <?php
                    }
                    ?>
                </a>
                <h6 <?php if ($baseName == "index.php") echo "class=color_headline" ?> ><?php echo $profile; ?></h6>
            </li>
        <?php
        }
        ?>
        <?php
        /*        if ($get_service_status != null) {
                if (isset($_GET['custom_url']) && $get_service_status['digital_card'] == 1) { */ ?>

        <?php
        if ($ServiceSectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;">
                <a href="<?php echo get_url_param_for_mobile('services.php'); ?>">
                    <?php
                    if ($ServiceTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $ServiceTabIcon["section_img"] . " ";
                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo $service_tab_icon; ?>"/>
                    <?php
                    } else {
                        ?>
                        <img src="<?php echo $service_tab_icon; ?>">
                    <?php
                    }
                    ?>
                </a>
                <h6 <?php if ($baseName == "services.php") echo "class=color_headline" ?> ><?php echo $services; ?></h6>
            </li>
        <?php
        }
        ?>
        <?php
        if ($ProductSectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;">
                <a href="<?php echo get_url_param_for_mobile('products.php'); ?>">
                    <?php
                    if ($ProductTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $ProductTabIcon["section_img"] . " ";
                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo FULL_MOBILE_URL ?>/assets/images/icon/cart.png"/>
                    <?php
                    } else {
                        ?>
                        <img src="<?php echo FULL_MOBILE_URL ?>/assets/images/icon/cart.png">
                    <?php
                    }
                    ?>
                </a>
                <h6 <?php if ($baseName == "products.php") echo "class=color_headline" ?> ><?php echo $product; ?></h6>
            </li>
        <?php
        }
        ?>


        <?php /*}} */ ?><!--
        --><?php
        /*        if ($get_image_status != null || $get_video_status = !null) {
                if (isset($_GET['custom_url']) && $get_image_status['digital_card'] == 1 || $get_video_status['digital_card'] == 1) { */ ?>
        <?php
        if ($gallerySectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;">
                <a href="<?php echo get_url_param_for_mobile('gallery.php'); ?>">
                    <?php
                    if ($galleryTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $galleryTabIcon["section_img"] . " ";
                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo $gallery_tab_icon ?>"/>

                    <?php
                    } else {
                        ?>
                        <img src="<?php echo $gallery_tab_icon; ?>">
                    <?php
                    }
                    ?>
                </a>
                <h6  <?php if ($baseName == "gallery.php") echo "class=color_headline" ?>><?php echo $gallery; ?></h6>
            </li>
        <?php
        }
        ?>

        <!-- <?php /*}} */ ?>
        --><?php
        /*        if ($get_client_status != null || $get_client_review_status = !null) {
                if (isset($_GET['custom_url']) && $get_client_status['digital_card'] == 1 || $get_client_review_status['digital_card'] == 1) { */ ?>

        <?php
        if ($ClientSectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;">
                <a href="<?php echo get_url_param_for_mobile('testimonial.php'); ?>">
                    <?php
                    if ($ClientTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $ClientTabIcon["section_img"] . " ";
                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo $client_tab_icon ?>"/>
                    <?php
                    } else {
                        ?>
                        <img src="<?php echo $client_tab_icon; ?>">
                    <?php
                    }
                    ?>
                </a>
                <h6 <?php if ($baseName == "testimonial.php") echo "class=color_headline" ?>><?php echo $clients ?></h6>
            </li>
        <?php
        }
        ?>

        <?php /*}} */ ?><!--
        --><?php
        /*        if ($get_our_team_status != null) {
                if (isset($_GET['custom_url']) && $get_our_team_status['digital_card'] == 1) { */ ?>
        <?php
        if ($TeamSectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;"><a
                    href="<?php echo get_url_param_for_mobile('our-team.php'); ?>">
                    <?php
                    if ($TeamTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $TeamTabIcon["section_img"] . " ";
                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo $our_team_tab_icon ?>"/>
                    <?php
                    } else {
                        ?>
                        <img src="<?php echo $our_team_tab_icon; ?>">
                    <?php
                    }
                    ?>
                    </i>
                </a>
                <h6 <?php if ($baseName == "our-team.php") echo "class=color_headline" ?>><?php echo $team; ?></h6>
            </li>
        <?php
        }
        ?>

        <?php /*}} */ ?><!--
        --><?php
        /*        if ($get_bank_status != null) {
                if (isset($_GET['custom_url']) && $get_bank_status['digital_card'] == 1) { */ ?>
        <!-- --><?php
        /*        if($validToken) {
                    */ ?>
        <?php
        if ($BankSectionStatus) {
            ?>
            <li class="load-mobile-redirect" style="width: <?php echo $liWidth; ?>%;">
                <a href="<?php echo FULL_MOBILE_URL . "payment.php" . get_full_param(); ?>">
                    <?php
                    if ($BankTabIcon != "") {
                        $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $BankTabIcon["section_img"] . " ";

                        ?>
                        <img src="<?php echo $pathImg; ?>"
                             onError="this.onerror=null;this.src='<?php echo $bank_tab_icon ?>"/>
                    <?php
                    } else {
                        ?>
                        <img src="<?php echo $bank_tab_icon; ?>">
                    <?php
                    }
                    ?>
                </a>
                <h6 <?php if ($baseName == "payment.php") echo "class=color_headline" ?>><?php echo $bank; ?></h6>
            </li>
        <?php
        }
        ?>
        <!-- --><?php
        /*        }
                */ ?>
        <?php /*}} */ ?>
    </ul>
</div>
