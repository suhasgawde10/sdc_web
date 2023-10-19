<?php
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
    <title>Features | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="feature,feature of digital card, features of visiting card,feature of digital card,digital card provides feature to connect end user , secure fund transfer customized business and visting card design and many more">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, features, feature of digital bsuiness card">

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


<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<!-- header area end -->

<div class="innerpage-banner" id="home" style="background: url(assets/img/bread/breadcrumbs.jpg) no-repeat center; background-size: cover;">
    <div class="inner-page-layer">
        <h5>Features</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Features</span></h6>
    </div>
</div>
<div class="ptb--30">

</div>
<!--<section class="feature-area process_padding" id="feature">
    <div class="container">

    </div>
</section>-->


<div class="ptb--10">

</div>
<div class="col-md-12 feature_margin_bottom">
    <?php include "feature-include.php" ?>
</div>

<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>