<?php
/*header('location:index.php');*/include 'whitelist.php';
require_once "controller/ManageApp.php";
$manage = new ManageApp();
if (isset($_POST['search_button'])) {
    $city = $_POST['city'];
    $search = $_POST['search'];
    header('location:search-profile.php?city=' . $city . '&search=' . $search);
}

$sub_plan = $manage->subscriptionPlan();

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$ip = getRealIpAddr(); // your ip address here
$ip_query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

if($ip_query && $ip_query['status'] == 'success'){
    $countryName = $ip_query['country'];
}else{
    $countryName = '';
}


?>
<!doctype html>
<html lang="en">
<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Special Offers | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description" content="we offer digital business card service for professional and business with exciting price and attractive feature.including website for business">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, price, offer, special">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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


<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<!-- header area end -->


<div class="innerpage-banner " id="home" style="background: url(assets/img/bread/breadcrumbs%20price.jpg) no-repeat center;background-size: cover;">
    <div class="inner-page-layer">
        <h5>Pricing</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Pricing</span></h6>
    </div>
</div>

<section class="ptb--30" style="background: #f5f5f5">




<!-- pricing area start -->
<?php include "pricing-include.php" ?>
<!-- pricing area end -->
</section>


<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    // Change option selected
    const label = document.querySelector('.dropdown__filter-selected');
    const options = Array.from(document.querySelectorAll('.dropdown__select-option'));

    options.forEach(option => {
        option.addEventListener('click', () => {
            label.textContent = option.textContent;
        });
    });

    // Close dropdown onclick outside
    document.addEventListener('click', e => {
        const toggle = document.querySelector('.dropdown__switch');
        const element = e.target;

        if (element == toggle) return;

        const isDropdownChild = element.closest('.dropdown__filter');

        if (!isDropdownChild) {
            toggle.checked = false;
        }
    });
</script>
</body>
</html>