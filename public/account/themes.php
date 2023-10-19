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
    <title>Screenshots | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="detail view of digital business card for user include basic information page, payment page gallery page, and many more..">
    <meta name="keywords"
          content="digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, screenshots, preview">
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

<div class="innerpage-banner" id="home" style="background: url(assets/img/bread/breadcrumbs4.jpg) no-repeat center;background-size: cover;">
    <div class="inner-page-layer">
        <h5>Themes</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Themes</span></h6>
    </div>
</div>


    <div class="container process_padding screen-area ptb--30">
        <div class="section-title">
        </div>
        <?php include "screenshot-include.php" ?>

    </div>
<!-- screen slider area end -->


<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->

<?php include "assets/common-includes/footer_includes.php" ?>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<script>
    $(document).ready(function () {
        var slider = $('.slider').bxSlider({
            mode: 'horizontal', //mode: 'fade',
            speed: 300,
            //不要自動
            auto: false,
            /*autoControls: true,
            stopAutoOnClick: true,
            pager: true,
            infiniteLoop: true,*/
            hideControlOnEnd: true,
            useCSS: false,
            onSliderLoad: function (currentIndex) {
                //初始是第一張
                $('.carousel-indicators a').filter('[data-picindex="' + (currentIndex + 1) + '"]').addClass('active').siblings().removeClass('active');
                $('.carousel-indicators2 a').filter('[data-picindex="' + (currentIndex + 1) + '"]').addClass('active').siblings().removeClass('active');

            },
            onSlideAfter: function ($slideElement, oldIndex, newIndex) {
                //切換後
                $('.carousel-indicators a').filter('[data-picindex="' + (newIndex + 1) + '"]').addClass('active').siblings().removeClass('active');
                $('.carousel-indicators2 a').filter('[data-picindex="' + (newIndex + 1) + '"]').addClass('active').siblings().removeClass('active');

            }
        });

        $('.carousel-indicators a').click(function (event) {
            event.preventDefault();
            var $this = $(this);
            slider.goToSlide($this.data('picindex') - 1);
            $('.carousel-indicators2 a').removeClass('active');
        });
        $('.carousel-indicators2 a').click(function (event) {
            event.preventDefault();
            var $this = $(this);
            slider.goToSlide($this.data('picindex') - 1);
            $('.carousel-indicators a').removeClass('active');
        });


    });
</script>


</body>
</html>