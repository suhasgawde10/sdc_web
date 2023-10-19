<?php
include 'whitelist.php';
if (isset($_POST['search_button'])) {
    $city = $_POST['city'];
    $search = $_POST['search'];
    header('location:search-profile.php?city=' . $city . '&search=' . $search);
}


?>
<!doctype html>
<html lang="en">
<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Site Map | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Site map,Site map of digital card,Site map of digital business card,Digital card is online digital representation of your profile, includes your personal information, bank details, and many more">
    <meta name="keywords"
          content="digital business card,Site Map, online visiting card, affordable, attractive business and visiting card design maker in india, maharshatra, mumbai, modern solution for visiting card, business card application for android, share digital card , best digital card, customized , about us">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSS -->
    <?php include "assets/common-includes/header_includes.php" ?>
</head>

<body>
<div class="visible-lg visible-md visible-sm hidden-xs">
    <?php include "request-to-call-include.php"; ?>
</div>

<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<!-- header area end -->

<div class="innerpage-banner" id="home"
     style="background: url(assets/img/bread/breadcrumbs.jpg) no-repeat center; background-size: cover;">
    <div class="inner-page-layer">
        <h5>Site <span>Map</span></h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Sitemap</span></h6>
    </div>
</div>

<!-- terms and conditions area start -->

<div class="about-area ptb--60">
    <div class="container">
        <div class="row display_about_img d-flex">
            <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12 text-center">
                <h4 class="sitemapheading">Main</h4>
                <hr>
                <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">

                    <div class="sitemap">
                        <ul>
                            <li><a href="index.php" title="Home">Home</a></li>
                            <li><a href="about-us.php" title="About us">About us</a></li>
                            <li><a href="screenshot.php" title="Screenshot">Screenshot</a></li>
                            <li><a href="pricing.php" title="Pricing">Pricing</a></li>
                            <li><a href="contact.php" title="Contact">Contact</a></li>
                            <li><a href="blogs.php" title="Blogs">Blogs</a></li>
                            <li><a href="login.php" title="Login">Login</a></li>
                            <li><a href="register.php" title="Registration">Registration</a></li>
                            <li><a href="dealer-register.php" title="Register as a dealer">Register as a dealer</a></li>
                            <li><a href="dealer-register.php?sign-in=true" title="Sign In as a dealer">Sign In as a
                                    dealer</a></li>
                        </ul>
                    </div>
                </div>
                <h4 class="sitemapheading">Top Keywords</h4>
                <hr>
                <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12 text-left">
                    <div class="sitemapkeyword">
                        <ul>
                            <li><a href="index.php" title="Online visiting card maker">Online visiting card maker</a>
                            </li>
                            <li><a href="index.php" title="digital business card">digital business card</a></li>
                            <li><a href="index.php"
                                   title="digital card">digital card</a></li>
                            <li><a href="index.php" title="create business card">create business card</a></li>
                            <li><a href="index.php"
                                   title="free demo">free demo</a></li>
                            <li><a href="index.php"
                                   title="mumbai">mumbai</a></li>
                            <li><a href="index.php" title="india">india</a>
                            </li>
                            <li><a href="index.php"
                                   title="verified and secure business card">verified and secure business card</a>
                            </li>
                            <li><a href="index.php" title="create digital card">create digital card</a></li>
                            <li><a href="index.php" title="digital business card">digital business card</a></li>
                            <li><a href="index.php" title="digital card in mumbai">digital card in mumbai</a></li>
                            <li><a href="index.php" title="digital visiting card">digital visiting card</a></li>
                            <li><a href="index.php" title="online digital card maker">online digital card maker</a></li>
                            <li><a href="index.php" title="digital business card free">digital business card free</a>
                            </li>
                            <li><a href="index.php" title="digital marketing business cards">digital marketing business
                                    cards</a></li>
                            <li><a href="index.php" title="digital anniversary card">digital anniversary card</a></li>
                            <li><a href="index.php" title="digital visiting card free">digital visiting card free</a>
                            </li>
                            <li><a href="index.php" title="digital
                            birthday card maker">digital
                                    birthday card maker</a></li>
                            <li><a href="index.php" title="best digital business card app">best digital business card
                                    app</a></li>
                            <li><a href="index.php" title="free digital business card maker">free digital business card
                                    maker</a></li>
                            <li><a href="index.php" title="smart digital
                            business card">smart digital
                                    business card</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- terms and conditions area end -->


<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>