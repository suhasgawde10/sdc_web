<?php
$page_name = basename($_SERVER['PHP_SELF']);
$path = "../user/uploads/<?php echo $email ?>/section_icon/ "

?>
<div class="list-group-custom">
    <?php
    /*                                    $alreadyActiveSet = false;
                                        $alreadyActiveContent = false;
                                        */
    ?>
    <?php
    if ($ProfileSectionStatus) {
        ?>
        <a href="<?php echo get_url_param("home"); ?>"
           onclick="window.open(this.href,'_self');return false;"
           class="list-group-item <?php if ($ProfileSectionStatus and $page_name == "index.php") echo "active"; ?> text-center">
            <h4>
                <?php
                if ($profileTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $profileTabIcon . " ";
                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/building.png';"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/building.png">
                <?php
                }
                ?>
            </h4><br/>

            <p>Company Info</p>
        </a>
    <?php
    }
    ?>

    <!--  --><?php
    /*
                                        if ($get_service_status != null) {
                                            if (isset($_GET['custom_url']) && $get_service_status['digital_card'] == 1) {
                                                $alreadyActiveSet = true; */

    if ($ServiceSectionStatus) {
        ?>
        <a href="<?php echo FULL_DESKTOP_URL . "services" . get_full_param(); ?>"
           onclick="window.open(this.href,'_self');return false;" class="list-group-item <?php /*if (!$alreadyActiveSet) {
                                                $alreadyActiveSet = true;
                                                echo "active";
                                            } */
        ?> text-center <?php if ($ServiceSectionStatus and $page_name == "services.php") echo "active"; ?>">
            <h4>
                <?php
                if ($ServiceTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $ServiceTabIcon . " ";
                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/clipboard.png';"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/clipboard.png">
                <?php
                }
                ?>
            </h4>
            <br/>

            <p><?php echo $services; ?></p>
        </a>
    <?php
    }
    ?>
    <?php
    if ($ProductSectionStatus) {
        ?>
        <a href="<?php echo FULL_DESKTOP_URL . "products" . get_full_param(); ?>"
           onclick="window.open(this.href,'_self');return false;" class="list-group-item <?php /*if (!$alreadyActiveSet) {
                                                $alreadyActiveSet = true;
                                                echo "active";
                                            } */
        ?> text-center <?php if ($ServiceSectionStatus and $page_name == "products.php") echo "active"; ?>">
            <h4>
                <?php
                if ($ProductTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $ProductTabIcon . " ";

                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/cart.png';"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/cart.png">
                <?php
                }
                ?>
            </h4><br/>

            <p><?php echo $product; ?></p>
        </a>
    <?php
    }
    ?>

    <!-- <?php /*}
                                    } */
    ?>
    --><?php
    /*                                    if ($get_image_status != null || $get_video_status = !null) {
                                            if (isset($_GET['custom_url']) && $get_image_status['digital_card'] == 1 || $get_video_status['digital_card'] == 1) { */
    ?>
    <?php
    if ($gallerySectionStatus) {
        ?>
        <a href="<?php echo FULL_DESKTOP_URL . "gallery" . get_full_param(); ?>"
           onclick="window.open(this.href,'_self');return false;"
           class="list-group-item <?php if ($gallerySectionStatus and $page_name == "gallery.php" OR $page_name == "video_gallery.php") echo "active"; ?> <?php /*if (!$alreadyActiveSet) {
                                                $alreadyActiveSet = true;
                                                echo "active";
                                            } */
           ?>  text-center">
            <h4>
                <?php
                if ($galleryTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $galleryTabIcon . " ";
                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/gallery.png'"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/gallery.png">
                <?php
                }
                ?>
            </h4>
            <br/>

            <p><?php echo $gallery; ?></p>
        </a>
    <?php
    }
    ?>

    <?php /*}
                                    } */
    ?><!--
                                    --><?php
    /*                                    if ($get_client_status != null || $get_client_review_status = !null) {
                                            if (isset($_GET['custom_url']) && $get_client_status['digital_card'] == 1 || $get_client_review_status['digital_card'] == 1) { */
    ?>
    <?php
    if ($ClientSectionStatus) {
        ?>
        <a href="<?php echo FULL_DESKTOP_URL . "testimonial" . get_full_param(); ?>"
           onclick="window.open(this.href,'_self');return false;"
           class="list-group-item <?php if ($ClientSectionStatus and $page_name == "testimonial.php") echo "active"; ?>
                                             <?php /*if (!$alreadyActiveSet) {
                                                $alreadyActiveSet = true;
                                                echo "active";
                                            } */
           ?>
                                             text-center">
            <h4>
                <?php
                if ($ClientTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $ClientTabIcon . " ";
                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/review.png';"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/review.png">
                <?php
                }
                ?>

            </h4><br/>

            <p><?php echo $clients; ?></p>
        </a>
    <?php
    }
    ?>

    <?php /*}
                                    } */
    ?><!--
                                    --><?php
    /*                                    if ($get_our_team_status != null) {
                                            if (isset($_GET['custom_url']) && $get_our_team_status['digital_card'] == 1) { */
    ?>
    <?php
    if ($TeamSectionStatus) {
        ?>
        <a href="<?php echo FULL_DESKTOP_URL . "our-team" . get_full_param(); ?>"
           onclick="window.open(this.href,'_self');return false;"
           class="list-group-item <?php if ($TeamSectionStatus and $page_name == "our-team.php") echo "active"; ?> <?php /*if (!$alreadyActiveSet) {
                                                $alreadyActiveSet = true;
                                                echo "active";
                                            } */
           ?>  text-center">
            <h4>
                <?php
                if ($TeamTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $TeamTabIcon . " ";
                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/teamwork.png';"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/teamwork.png">
                <?php
                }
                ?>
            </h4><br/>

            <p><?php echo $team; ?></p>
        </a>
    <?php
    }
    ?>

    <?php /*}
                                    } */
    ?><!--
                                    --><?php
    /*                                    if ($get_bank_status != null) {
                                            if (isset($_GET['custom_url']) && $get_bank_status['digital_card'] == 1) { */
    ?>
    <!-- --><?php
    /*    if($validToken) {
            */
    ?><!-- FULL_DESKTOP_URL."payment.php".get_full_param(); -->
    <?php
    if ($BankSectionStatus) {
        ?>
        <a href="<?php echo FULL_DESKTOP_URL . "payment.php" . get_full_param(); ?>"
           onclick="window.open(this.href,'_self');return false;"
           class="list-group-item <?php if ($BankSectionStatus and $page_name == "payment.php") echo "active"; ?> <?php /*if (!$alreadyActiveSet) {
                                                $alreadyActiveSet = true;
                                                echo "active";
                                            } */
           ?>  text-center">
            <h4>
                <?php
                if ($BankTabIcon != "") {
                    $pathImg = "../../user/uploads/" . $email . "/section_icon/" . $BankTabIcon . " ";
                    ?>
                    <img src="<?php echo $pathImg; ?>"
                         onError="this.onerror=null;this.src='<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/point-of-service.png';"/>
                <?php
                } else {
                    ?>
                    <img src="<?php echo FULL_DESKTOP_URL ?>/assets/images/icon/point-of-service.png">
                <?php
                }
                ?>
            </h4>
            <br/>

            <p><?php echo $bank; ?></p>
        </a>
    <?php
    }
    ?>
    <!-- --><?php
    /*    }
        */
    ?>
    <?php /*}
 } */
    ?>
</div>