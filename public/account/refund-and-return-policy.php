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
    <title>Refund and Return Policy | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description"
          content="Refund and return policy of Digital card,Refund and return policy of Digital business card, Digital card is online digital representation of your profile, includes your personal information, bank details, and many more">
    <meta name="keywords"
          content="digital business card, online visiting card, affordable, attractive business and visiting card design maker in india, maharshatra, mumbai, modern solution for visiting card, business card application for android, share digital card , best digital card, customized , about us">
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
        <h5>Refund and Return Policy</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Refund and Return Policy</span></h6>
    </div>
</div>

<!-- terms and conditions area start -->

<div class="about-area ptb--60">
    <div class="container">
        <div class="row display_about_img d-flex">
            <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                <div class="about-heading refund_heading">
                    <h3><strong>Refund Policy</strong></h3>
                </div>
                <div class="about-content ptb--20 terms_and_condition_content_point">
                    <p>Share digital card as a digital business card services provider we give our user
                        5 days of an extensive free trial of our service.
                        Where users can use all services
                        related to share digital card.
                        We give a 5-day trial to the user for the reason if
                        the user was happy with our service they can buy it. If the user buys this service
                        after the end of 5 days of the free trial period, it is taken into account as an
                        informed choice and thereby refund is not applicable at all. </p>
					<p>
						Before Purchasing you must read all terms and condition, return policy properly.
						</p>
					<p>
						If user directly purchase Digital card without the Free trial, he/she must read the return policy and terms and condition properly Before further purchasing the digital card, as there won't be any return of money once the service is purchased.
						</p>
					<p>
						After purchasing the digital card, if the user found any issues in the existing digital card then user can report the issue on "support@sharedigitalcard.com". So our technical team will try to resolve the issues as soon as possible but the amount will not be refundable.
						</p>

                    <p>Share digital card not responsible for any refund on cancellation of the subscription.
                    </p>
                </div>
                <br>
                <br>

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