<?php
$host = parse_url('https://' . $_SERVER['HTTP_HOST'] . '/', PHP_URL_HOST);
$domains = explode('.', $host);
$url = $domains[count($domains) - 2];
// $url = 'sdigitalcard.com';
$footerDomain = $manage->getDealerFromDomain($url);
$cont = $footerDomain['contact_no'];
$whatsapp = $footerDomain['alter_contact_no'];
$copy_right = $footerDomain['copy_right'];

/*list($width, $height) = getimagesize("panel/uploads/logo/" . $logo);
if ($width > $height) {
    $size = "width:18%";
} else {
    $size = "width:3.5%";
}*/
$size = "width:18%";
?>

<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-6">
                    <div class="footer-info">
                        <p class="pb-3" style="font-style: italic  ">
                            <?php echo substr($slider_desc, 0, 200) . "..."; ?>
                        </p><br><br>
                        <a class="send_msg getstarted scrollto" href="index.php#about">Read more</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul class="ul_footer">
                        <li><i class="bx bx-chevron-right"></i><a href="index.php">Home</a></li>
                        <li><i class="bx bx-chevron-right"></i><a href="about-us.php">About us</a></li>
                        <li><i class="bx bx-chevron-right"></i><a href="index.php#features">Features</a></li>
                        <?php
                        if ($theme_status == 1) {
                            ?>
                        <li><i class="bx bx-chevron-right"></i><a href="index.php#gallery">Themes</a></li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($plan_status == 1) {
                            ?>
                        <li><i class="bx bx-chevron-right"></i><a href="pricing.php">Plans</a></li>
                        <?php
                        }
                        ?>
                        <li><i class="bx bx-chevron-right"></i><a href="contact">Contact Us</a></li>
                        <?php
                        if ($privacy_status == 1) {
                            ?>
                            <li><i class="bx bx-chevron-right"></i><a href="privacy-policy">Privacy policy</a></li>
                        <?php
                        } ?>
                        <?php
                        if ($services_status != 0) {
                            ?>
                            <li>
                                <i class="bx bx-chevron-right"></i><a href="our-services">Services</a>
                            </li>
                        <?php
                        }
                        ?>

                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 footer-newsletter">
                    <h4>Contact</h4>
                    <i class="fa fa-map-marker" aria-hidden="true"> </i> &nbsp;
                    <?php
                    if (isset($Addrs) && $Addrs != "") {
                        echo $Addrs;
                    } else { ?>
                        <p>Not Specified</p>
                    <?php }
                    ?>
                    <p>
                        <span class="fa fa-phone"></span><a href="tel:<?php if (isset($call)) echo $call ?>">
                            &nbsp;<?php if (isset($call)) echo $call ?></a><br>
                        <span class="fa fa-envelope"></span><a href="mailto:<?php if (isset($email_cont) != "") {
                            echo $email_cont;
                        } ?>">
                            &nbsp;<?php if (isset($email_cont) != "") {
                                echo $email_cont;
                            } else {
                                echo "Not Specified";
                            } ?></a><br>
                    </p>

                    <div class="social-links mt-3">
                        <?php
                        if (isset($facebook) && $facebook != "") { ?>
                            <a href="<?php echo $facebook ?>" class="facebook"><i class="bx bxl-facebook"></i></a>
                        <?php
                        } else {
                            ?>
                            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                        <?php
                        }
                        if (isset($linkdin) && $linkdin != "") { ?>
                            <a href="<?php echo $linkdin ?>" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        <?php
                        } else {
                            ?>
                            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        <?php
                        }
                        if (isset($twitter) && $twitter != "") { ?>
                            <a href="<?php echo $twitter ?>" class="twitter"><i class="bx bxl-twitter"></i></a>
                        <?php
                        } else {
                            ?>
                            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                        <?php
                        }

                        if (isset($instagram) && $instagram != "") { ?>
                            <a href="<?php echo $instagram ?>" class="instagram"><i class="bx bxl-instagram"></i></a>
                        <?php
                        } else {
                            ?>
                            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                        <?php
                        }
                        if (isset($youtube) && $youtube != "") { ?>
                            <a href="<?php echo $youtube ?>" class="instagram"><i class='bx bxl-youtube'></i></a>
                        <?php
                        } else {
                            ?>
                            <a href="#" class="instagram"><i class='bx bxl-youtube'></i></a>
                        <?php
                        }
                        ?>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            Copyright &copy; <?php echo $copy_right ?> - <?php echo date('Y') ?>
            <strong><span><?php echo $company_name ?></span></strong>. All Rights Reserved
        </div>
</footer>
<a href="tel:<?php echo $cont ?>" class="phone_call d-lg-none d-xl-none d-md-block" target="_blank">
    <i class="fa fa-phone my-float"></i>
</a>
<div class="pulse what_lbl hidden-sm hidden-xs">
    <p>Let's talk</p>
</div>
<a href="https://wa.me/91<?php if ($whatsapp != "") {
    echo $whatsapp;
} else {
    echo $cont;
} ?>?text=I%20am%20interested%20in%20Digital%20Card%20Franchise"
   class="whatsapp_icn" target="_blank">
    <i class="fa fa-whatsapp my-float"></i>
</a>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#preloader').hide();
        }, 3000);
    });
</script>

