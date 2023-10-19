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
    <title>About Us | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description" content="About us, About Digital card,About Digital business card,About trail,Digital card is online digital representation of your profile, includes your personal information, bank details, and many more">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, about, about us">
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
<!-- preloader area start -->
<!--<div id="preloader">
    <div class="spinner"></div>
</div>-->

<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<!-- header area end -->

<div class="innerpage-banner" id="home" style="background: url(assets/img/bread/breadcrumbs2.jpg) no-repeat center; background-size: cover;">
    <div class="inner-page-layer">
        <h5>About us</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>About us</span></h6>
    </div>
</div>
<!-- about area start -->
<?php include "about-digital-card-include.php" ?>
<!-- about area end -->



<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>