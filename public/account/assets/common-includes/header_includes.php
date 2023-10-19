<?php
$version = 84;
if (!defined("FULL_WEBSITE_URL")) {
    define('FULL_WEBSITE_URL','https://sharedigitalcard.com/');
}
//$FULL_WEBSITE_URL = "https://sharedigitalcard.com/";
?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144581468-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-144581468-1');
    </script>
    <script data-ad-client="ca-pub-5659625226794667" async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.12/jquery.bxslider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--<link rel="stylesheet" href="assets/css/jquery-ui.css">-->

    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">

    <!--<link rel="stylesheet" href="assets/css/magnificpopup.css">-->
    <!--<link rel="stylesheet" href="assets/css/jquery.mb.YTPlayer.min.css">-->

    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/style.css?version=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/responsive.css?version=<?php echo $version; ?>">
    <!-- Favicon -->
<?php

if ($_SERVER['HTTP_HOST'] == "sharedigitalcard.com") {
    ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo FULL_WEBSITE_URL; ?>assets/img/logo/favicon.png">
    <?php
} else {
    ?>
    <link rel="shortcut icon" type="image/png"
          href="https://freepngimg.com/download/logo/81920-world-globe-computer-silhouette-icons-hq-image-free-png.png">
    <?php
}
?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    <link href="user/assets/plugins/node-waves/waves.css" rel="stylesheet"/>
    <link href="assets/css/form.css?version=<?php echo $version; ?>" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->
<?php
if ($main_site) {
    ?>
    <div id="preloader">
        <div class="spinner">
            <img class="spinner" src="assets/img/logo-loading.gif" style="width: auto; height: 180px;">
        </div>
    </div>
    <?php
}
?>