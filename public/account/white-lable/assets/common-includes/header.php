<?php
list($width, $height) = getimagesize("panel/uploads/logo/" . $logo);
if ($width > $height) {
    $size = "width:18%";
} else {
    $size = "width:3.5%";
}
//exit;
?>
<div class="topbar">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <div class="top-header-left">
                    <p>
                        <span class="fa fa-phone"></span>
                        <a target="_blank"
                            href="tel:+91<?php if (isset($cotactnum)) echo $cotactnum ?>">+91<?php if (isset($cotactnum)) echo $cotactnum ?></a>
                        &nbsp;&nbsp;
                        <span class="fa fa-envelope"></span>
                        <a target="_blank" href="mail:<?php if (isset($email) != "") {
                            echo $email;
                        } ?>"><?php if (isset($email) != "") {
                                echo $email;
                            } ?></a>
                    </p>
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="top-header-right">
                    <div class="login-signup-btn">
                        <?php
                        if ($franchise_status != 0) {
                            ?>
                        <p><a target="_blank" href="franchise.php">Franchise</a></p>
                        <?php
                        }
                        ?>

                    </div>

                    <ul class="social">
                        <li>
                            <?php
                            if (isset($facebook) && $facebook != "") { ?>
                            <a href="<?php echo $facebook ?>" class="facebook" target="_blank"><i
                                    class="bx bxl-facebook"></i></a>
                            <?php
                            } else { ?>
                            <a href="#" class="facebook" target="_blank"><i class="bx bxl-facebook"></i></a>
                            <?php }
                            ?>
                        </li>
                        <li>
                            <?php
                            if (isset($twitter) && $twitter != "") { ?>
                            <a href="<?php echo $twitter ?>" class="twitter" target="_blank"><i
                                    class="bx bxl-twitter"></i></a>
                            <?php
                            } else { ?>
                            <a href="#" class="twitter" target="_blank"><i class="bx bxl-twitter"></i></a>
                            <?php }
                            ?>
                        </li>
                        <li>
                            <?php
                            if (isset($linkdin) && $linkdin != "") { ?>
                            <a href="<?php echo $linkdin ?>" target="_blank" class="linkedin"><i
                                    class="bx bxl-linkedin"></i></a>
                            <?php
                            } else { ?>
                            <a href="#" class="linkedin" target="_blank"><i class="bx bxl-linkedin"></i></a>
                            <?php }
                            ?>
                        </li>
                        <li>
                            <?php
                            if (isset($instagram) && $instagram != "") { ?>
                            <a href="<?php echo $instagram ?>" class="instagram" target="_blank"><i
                                    class="bx bxl-instagram"></i></a>
                            <?php
                            } else { ?>
                            <a href="#" class="instagram" target="_blank"><i class="bx bxl-instagram"></i></a>
                            <?php }
                            ?>
                        </li>
                        <li>
                            <?php
                            if (isset($youtube) && $youtube != "") { ?>
                            <a href="<?php echo $youtube ?>" class="instagram" target="_blank"><i
                                    class="bx bxl-youtube"></i></a>
                            <?php
                            } else { ?>
                            <a href="#" class="instagram" target="_blank"><i class="bx bxl-youtube"></i></a>
                            <?php }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
<header id="header" class="fixed-top d-flex align-items-center header-transparent" style="overflow: hidden;">

    <div class="container d-flex align-items-center justify-content-between">
        <div class="logo" style="width: 100%">
            <h5>
                <a href="index.php">
                    <?php if ($logo != "") {
                        ?>
                    <img src="white-lable/panel/uploads/logo/<?php echo $logo ?>"
                        style="width: <?php echo $logo_size . "%"; ?>" />
                    <?php
                    } else {
                        echo $company_name;
                    } ?>

                </a>
            </h5>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li>
                    <a class="nav-link scrollto <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>"
                        href="index.php">Home</a></li>
                <li>
                    <a class="nav-link scrollto <?php if (basename($_SERVER['PHP_SELF']) == 'about-us.php') echo 'active'; ?> "
                        href="about-us.php">About Us</a></li>
                <?php
                if ($plan_status != 0) {
                    ?>
                <li>
                    <a class="nav-link scrollto <?php if (basename($_SERVER['PHP_SELF']) == 'pricing.php') echo 'active'; ?>"
                        href="pricing.php">Plans</a></li>
                <?php }
                       ?>
                <?php
                if ($services_status != 0) {
                    ?>
                <li>
                    <a class="nav-link scrollto <?php if (basename($_SERVER['PHP_SELF']) == 'our-services.php') echo 'active'; ?>"
                        href="our-services.php">Services</a>
                </li>
                <?php
                }
                ?>
                <li>
                    <a class="nav-link scrollto <?php if (basename($_SERVER['PHP_SELF']) == 'contact-us.php') echo 'active'; ?>"
                        href="contact-us.php">Contact Us</a>
                </li>
                <li>
                    <a class="nav-link scrollto" href="<?php echo $domain_link_name . "/login"; ?>"
                        target="_blank">Login</a>
                </li>
                <li>
                    <a class="getstarted scrollto" href="register.php">Create Card</a>
                </li>
                <li>
                    <a class="demo_cards scrollto" href="demo-cards.php">Demo Cards</a>
                </li>
                <!--<li>
                    <a class="getfranchise scrollto" href="franchise.php">Franchise</a>
                </li>-->
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <!-- .navbar -->

    </div>
</header>