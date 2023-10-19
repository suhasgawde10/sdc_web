<?php
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";
include "common-file.php";


$getAllTheme = $manage->getAllByDealerId($manage->themeTable, $id);
$getAllTestimonial = $manage->getAllByDealerId($manage->testimonialTable, $id);
$getAllTeam = $manage->getAllByDealerId($manage->teamTable, $id);
$getAllPlan = $manage->getAllPriceByDealerId($manage->planTable, $id);
$getAllService = $manage->getAllServices($manage->otherServiceTable, $id);


?>
<!DOCTYPE html>
<html>

<head>
    <title>Create your Digital business card with 5 days free trial - <?php echo strtoupper($company_name); ?> </title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!--Header InClude-->
    <?php include "white-lable/assets/common-includes/header-includes.php"; ?>
</head>

<body>
    <!--Header-->
    <?php include "white-lable/assets/common-includes/header.php"; ?>
    <!-- ======= Hero Section ======= -->
    <section id="hero">

        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1 d-flex align-items-center">
                    <div data-aos="zoom-out">
                        <h1><?php if (isset($slider_title)) {
                            echo $slider_title;
                        } ?></h1>

                        <h2>
                            <?php if (isset($slider_desc)) {
                            echo $slider_desc;
                        } ?>
                        </h2>

                        <div class="text-center text-lg-start">
                            <a href="register" target="_blank" class="btn-get-started scrollto">Get Started</a>
                            <a target="_blank" href="demo-cards" class="btn-demo-card scrollto">Check Demo Cards</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="300">
                    <?php if (isset($slider_img) && $slider_img != "") { ?>
                    <img src="white-lable/panel/uploads/slider-image/<?php echo $slider_img; ?>"
                        class="img-fluid animated" alt="">
                    <?php } else { ?>
                    <img src="white-lable/panel/uploads/slider-image/details-2.png" class="img-fluid animated" alt="">
                    <?php } ?>

                </div>
            </div>
        </div>

        <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 24 150 28 " preserveAspectRatio="none">
            <defs>
                <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
            </defs>
            <g class="wave1">
                <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
            </g>
            <g class="wave2">
                <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
            </g>
            <g class="wave3">
                <use xlink:href="#wave-path" x="50" y="9" fill="#fff">
            </g>
        </svg>

    </section>
    <!-- End Hero -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-5 col-lg-6 video-box ">
                        <?php
                    if (isset($about_img) && $about_img != "") { ?>
                        <img src="white-lable/panel/uploads/about-img/<?php echo $about_img ?>">
                        <?php } else { ?>
                        <img src="white-lable/panel/uploads/about-img/hero-img.png">
                        <?php }
                    ?>
                    </div>

                    <div
                        class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
                        <h3>About Us</h3>
                        <?php if (isset($about_desc) && $about_desc != "") {
                        echo $about_desc;
                    } else {
                        echo ABOUT_DESC;
                    } ?>

                        <div class="box_icons">
                            <div class="icon-box" data-aos="zoom-in" data-aos-delay="100">
                                <div class="icon"><i class='bx bx-check'></i></div>
                                <h4 class="title">
                                    <?php
                                if (isset($box1)) {
                                    echo $box1;
                                }
                                ?>
                                </h4>
                            </div>
                            <div class="icon-box" data-aos="zoom-in" data-aos-delay="200">
                                <div class="icon"><i class='bx bx-check'></i></div>
                                <h4 class="title">
                                    <?php
                                if (isset($box2)) {
                                    echo $box2;
                                }
                                ?>
                                </h4>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <section id="features" class="features">
            <div class="container">
                <div class="section-title">
                    <h2>Features</h2>

                    <p>Check The Features</p>
                </div>
                <div class="row no-gutters">
                    <div class="col-xl-6 d-flex align-items-stretch order-2 order-lg-1">
                        <div class="content d-flex flex-column justify-content-center">
                            <div class="row">
                                <div class="col-md-6 icon-box">
                                    <i class="bx bx-rupee"></i>
                                    <h4>Fund Transfers</h4>

                                    <p>No need to ask for <b>UPI ID</b> . Just click on <b>fund transfer</b> and put the
                                        amount and send it via <b>wallet apps.</b></p>
                                </div>
                                <div class="col-md-6 icon-box">
                                    <i class="bx bx-cube-alt"></i>
                                    <h4>One Link Information</h4>

                                    <p>Share your <b>digital card link</b> with others to share your all your details
                                        under
                                        one link.</p>
                                </div>
                                <div class="col-md-6 icon-box">
                                    <i class="bx bx-user"></i>
                                    <h4>User Friendly</h4>

                                    <p>It is <b>very simple and easy to use</b> for all</p>
                                </div>
                                <div class="col-md-6 icon-box">
                                    <i class="bx bx-shield"></i>
                                    <h4>Banking</h4>

                                    <p>Add your bank details from <b>control panel</b> and easy to change bank details.
                                    </p>
                                </div>
                                <div class="col-md-6 icon-box">
                                    <i class="bx bx-atom"></i>
                                    <h4>Security</h4>

                                    <p>Make your <b>information more secure</b> as per your needs by changing visibility
                                        settings for your digital card.</p>
                                </div>
                                <div class="col-md-6 icon-box">
                                    <i class="bx bx-id-card"></i>
                                    <h4>Customize Card Design</h4>

                                    <p>Get access to many themes to <b>make your digital card more attractive.</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="image col-xl-6  order-1 order-lg-2">
                        <?php
                    if (isset($feature_img) && $feature_img != "") { ?>
                        <img src="white-lable/panel/uploads/feature_img/<?php echo $feature_img ?>" class="img-fluid"
                            alt="">
                        <?php } else { ?>
                        <img src="white-lable/panel/uploads/feature_img/details-4.png" class="img-fluid" alt="">
                        <?php }
                    ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Features Section -->
        <!-- ======= Counts Section ======= -->
        <section id="counts" class="counts">
            <div class="container">
                <div class="col-lg-12">
                    <div class="counts">
                        <ul class="ul_counts">
                            <li>
                                <div class="count-box">
                                    <i class="bi bi-emoji-smile"></i>
                                    <span data-purecounter-start="0"
                                        data-purecounter-end="<?php if (isset($customer_c)) echo $customer_c ?>"
                                        data-purecounter-duration="1" class="purecounter"></span>

                                    <p>Customers</p>
                                </div>
                            </li>
                            <li>
                                <div class="count-box">
                                    <i class="bi bi-journal-richtext"></i>
                                    <span data-purecounter-start="0"
                                        data-purecounter-end="<?php if (isset($city_c)) echo $city_c ?>"
                                        data-purecounter-duration="1" class="purecounter"></span>

                                    <p>Covered City</p>
                                </div>
                            </li>
                            <li>
                                <div class="count-box">
                                    <i class="bi bi-headset"></i>
                                    <span data-purecounter-start="0"
                                        data-purecounter-end="<?php if (isset($theme_c)) echo $theme_c ?>"
                                        data-purecounter-duration="1" class="purecounter"></span>

                                    <p>Themes</p>
                                </div>
                            </li>
                            <li>
                                <div class="count-box">
                                    <i class="bi bi-people"></i>
                                    <span data-purecounter-start="0"
                                        data-purecounter-end="<?php if (isset($pertner_c)) echo $pertner_c ?>"
                                        data-purecounter-duration="1" class="purecounter"></span>

                                    <p>Partners</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </section>
        <!-- End Counts Section -->
        <!-- ======= Details Section ======= -->

        <!-- End Details Section -->
        <!-- ======= Gallery Section ======= -->
        <?php
                if ($theme_status != 0) {
                    ?>
        <section id="gallery" class="gallery">
            <div class="container">

                <div class="section-title">
                    <h2>Themes</h2>

                    <p>Check our Theme</p>
                </div>
            </div>
            <div class="container-fluid">
                <div class="gallery-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                    if ($getAllTheme != "") {
                        while ($theme = mysqli_fetch_array($getAllTheme)) { ?>
                        <div class="swiper-slide">
                            <a href="white-lable/panel/uploads/theme-img/<?php echo $theme['theme_img']; ?>"
                                class="gallery-lightbox" data-gall="gallery-carousel">
                                <img src="white-lable/panel/uploads/theme-img/<?php echo $theme['theme_img']; ?>"
                                    class="img-fluid" alt="" style="width: 100%">
                            </a>
                        </div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </section>
        <?php 
                }
    ?>
        <!-- End Gallery Section -->
        <!-- ======= Testimonials Section ======= -->
        <?php
                if ($testimonial_status != 0) {
                    ?>
        <section id="testimonials" class="testimonials section-bg">
            <div class="container">

                <div class="section-title">
                    <h2>Testimonials</h2>

                    <p>What Our Clients Says</p>
                </div>

                <div class="testimonials-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                    if ($getAllTestimonial != "") {
                        while ($testimonial = mysqli_fetch_array($getAllTestimonial)) { ?>
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <p>
                                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                    <?php echo $testimonial['testimonail']; ?>
                                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                                </p>

                                <h3>~<?php echo $testimonial['name']; ?></h3>
                            </div>
                        </div>
                        <?php
                        }
                    }
                    ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </section>
        <?php 
                }
    ?>
        <!-- ======= Team Section ======= -->
        <?php
                if ($team_status != 0) {
                    ?>
        <section id="team" class="team">
            <div class="container">

                <div class="section-title">
                    <h2>Team</h2>

                    <p>Our Great Team</p>
                </div>

                <div>
                    <ul class="ul_team">
                        <?php
                    if ($getAllTeam != "") {
                        while ($team = mysqli_fetch_array($getAllTeam)) { ?>
                        <li>
                            <div class="member">
                                <div class="pic">
                                    <?php if ($team['image'] != "") { ?>
                                    <img src="white-lable/panel/uploads/team-img/<?php echo $team['image']; ?>"
                                        class="img-fluid" alt="" style="width: 100%">
                                    <?php } else { ?>
                                    <img src="white-lable/panel/uploads/team-img/default-team.jpg" class="img-fluid"
                                        alt="" style="width: 100%">
                                    <?php } ?>

                                </div>
                                <div class="member-info">
                                    <h4><?php echo $team['name']; ?></h4>
                                    <span><?php echo $team['designation']; ?></span>
                                </div>
                            </div>
                        </li>
                        <?php
                        }
                    } else {
                        ?>
                        <h2 style="text-align: center">No Team Added..!</h2>
                        <?php
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </section>
        <?php
                }
?>

        <!-- End Team Section -->
        <!-- ======= Pricing Section ======= -->
        <?php
                if ($plan_status != 0) {
                    ?>
        <section id="pricing" class="pricing">
            <div class="container">

                <div class="section-title">
                    <h2>Plans</h2>

                    <p>Check our Plans</p>
                </div>

                <div class="row">


                    <ul class="price-data">
                        <?php
                    if ($getAllPlan != "") {
                        while ($plan_price = mysqli_fetch_array($getAllPlan)) { ?>


                        <li class="price-data-li">
                            <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                                <div class="box featured">
                                    <h3><?php echo $plan_price['plan_name'] ?></h3>
                                    <h4>₹<?php echo $plan_price['price_price'] ?></h4>

                                    <div class="btn-wrap">
                                        <ul class="pricing-btn-ul">
                                            <li <?php if ($plan_price['payment_link'] == "") {
                                                    echo ' style="width: 100%;text-align: center;" ';
                                                } ?>><a href="registration.php" target="_blank" class="btn-buy">Get
                                                    Started</a></li>
                                            <?php if ($plan_price['payment_link'] != "") {
                                                    ?>
                                            <li><a href="<?php echo $plan_price['payment_link'] ?>" target="_blank"
                                                    name="buy_now" class="btn-buy-payment" style="margin-top:10px">Buy
                                                    now</a></li>
                                            <?php
                                                } ?>
                                        </ul>
                                    </div>
                                    <br>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                        <li><i class="fa fa-check"></i>Customize Menu</li>
                                        <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                        <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                        <li><i class="fa fa-check"></i>Create Employee cards</li>
                                        <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                        <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                        <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <?php
                        }
                        ?>
                        <?php } else{
                    ?>
                        <li>
                            <center>
                                <h3>No Plan Found</h3>
                            </center>
                        </li>
                        <?php 
                    }
                    ?>

                        <?php
                    /*} else {  */ ?>
                        <!-- 
                        <li class="price-data-li">
                            <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                                <div class="box featured">
                                    <h3>1 years)</h3>
                                    <h4>₹1499 </h4>

                                    <div class="btn-wrap">
                                        <a href="registration.php" class="btn-buy">Get Started</a>
                                    </div>
                                    <br>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                        <li><i class="fa fa-check"></i>Customize Menu</li>
                                        <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                        <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                        <li><i class="fa fa-check"></i>Create Employee cards</li>
                                        <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                        <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                        <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="price-data-li">
                            <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                                <div class="box featured">
                                    <h3>3 years (2 + 1 years FREE)</h3>
                                    <h4>₹2999 </h4>

                                    <div class="btn-wrap">
                                        <a href="registration.php" class="btn-buy">Get Started</a>
                                    </div>
                                    <br>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                        <li><i class="fa fa-check"></i>Customize Menu</li>
                                        <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                        <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                        <li><i class="fa fa-check"></i>Create Employee cards</li>
                                        <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                        <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                        <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="price-data-li">
                            <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                                <div class="box featured">
                                    <h3>5 years (3 + 2 years FREE)</h3>
                                    <h4>₹4499 </h4>

                                    <div class="btn-wrap">
                                        <a href="registration.php" class="btn-buy">Get Started</a>
                                    </div>
                                    <br>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                        <li><i class="fa fa-check"></i>Customize Menu</li>
                                        <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                        <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                        <li><i class="fa fa-check"></i>Create Employee cards</li>
                                        <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                        <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                        <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="price-data-li">
                            <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                                <div class="box featured">
                                    <h3>Life Time</h3>
                                    <h4>₹7999 </h4>

                                    <div class="btn-wrap">
                                        <a href="registration.php" class="btn-buy">Get Started</a>
                                    </div>
                                    <br>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                        <li><i class="fa fa-check"></i>Customize Menu</li>
                                        <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                        <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                        <li><i class="fa fa-check"></i>Create Employee cards</li>
                                        <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                        <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                        <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>

                                </div>
                            </div>
                        </li> -->


                        <?php /*}*/
                    ?>
                    </ul>
                </div>
            </div>
        </section>
        <?php
                }
?>

        <?php
        if ($services_status != 0 && $getAllService != "") {
        ?>
        <section id="" class="Services">
            <div class="container">
                <div class="section-title">
                    <h2>Services</h2>

                    <p>Check our other Services</p>
                </div>
                <?php include 'white-lable/assets/common-includes/services-includes.php'; ?>
            </div>
        </section>
        <?php
    }
    ?>

        <!-- End Contact Section -->

    </main>
    <!-- End #main -->
    <?php include "white-lable/assets/common-includes/footer.php"; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <div id="preloader">
        <div class="loder-img">
        </div>
    </div>

    <div class="modal fade" id="myModal" runat="server">
        <div class="modal-dialog">
            <div class="modal-content modal_content">
                <div class="modal-header modal_head" runat="server">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body modal_body">
                    <div class="modal_img" style="width: 100%">
                        <img style="width: 100%" src="white-lable/assets/img/dg_india/IMG-20210521-WA0056%20(1).jpg" />
                    </div>

                </div>

            </div>
        </div>
    </div>

    <?php
include "white-lable/assets/common-includes/footer-includes.php";
?>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZG5Y9ZEJ2V"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-ZG5Y9ZEJ2V');
    </script>
    <script>
        /*$(document).ready(function () {
     $('#myModal').modal('show');
     });*/
    </script>
    <script type="text/javascript">
        $(function () {
            /*$(".close").click(function () {
             $("#myModal").modal("hide");
             });*/
        });
    </script>
</body>

</html>