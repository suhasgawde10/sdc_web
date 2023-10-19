<?php


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
    <script data-ad-client="ca-pub-5659625226794667" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.12/jquery.bxslider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('dist/assets/css/bootstrap.min.css')}}">

    <!--<link rel="stylesheet" href="assets/css/jquery-ui.css">-->

    <link rel="stylesheet" href="{{ asset('dist/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{url('/')}}/dist/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('dist/assets/css/slicknav.min.css')}}">

    <!--<link rel="stylesheet" href="assets/css/magnificpopup.css">-->
    <!--<link rel="stylesheet" href="assets/css/jquery.mb.YTPlayer.min.css">-->

    <link rel="stylesheet" href="{{asset('dist/assets/css/typography.css')}}">
    <link rel="stylesheet" href="{{asset('dist/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('dist/assets/css/responsive.css')}}">
    <!-- Favicon -->
<?php

if ($_SERVER['HTTP_HOST'] == "sharedigitalcard.com") {
    ?>
    <link rel="shortcut icon" type="image/png" href="{{asset('dist/assets/img/logo/favicon.png')}}">
    <?php
} else {
    ?>
    <link rel="shortcut icon" type="image/png" href="https://freepngimg.com/download/logo/81920-world-globe-computer-silhouette-icons-hq-image-free-png.png">
    <?php
}
?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <!-- Waves Effect Css -->
    {{-- public\account\user\assets\plugins --}}
    <link href="{{asset('account/user/assets/plugins/node-waves/waves.css')}}" rel="stylesheet"/>
    <link href="{{asset('dist/assets/css/form.css')}}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>-->

    <div id="preloader">
        <div class="spinner">
            <img class="spinner" src="{{url('/')}}/dist/assets/img/logo-loading.gif" style="width: auto; height: 180px;">
        </div>
    </div>
