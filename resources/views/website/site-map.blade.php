@extends('layouts.web-main')
@section('main-container')
    <div class="innerpage-banner" id="home" style="background: url({{url('/')}}/dist/assets/img/bread/breadcrumbs.jpg) no-repeat center; background-size: cover;">
        <div class="inner-page-layer">
            <h5>Site <span>Map</span></h5>
            <h6><a href="{{url('/')}}">Home</a>&nbsp;/&nbsp;<span>Sitemap</span></h6>
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
                                <li><a href="{{url('/')}}" title="Home">Home</a></li>
                                <li><a href="{{url('/')}}/about-us" title="About us">About us</a></li>
                                <li><a href="{{url('/')}}/themes" title="Themes">Themes</a></li>
                                <li><a href="{{url('/')}}/pricing" title="Pricing">Pricing</a></li>
                                <li><a href="{{url('/')}}/contact-us" title="Contact">Contact</a></li>
                                <li><a href="{{url('/')}}/account/blogs.php" title="Blogs">Blogs</a></li>
                                <li><a href="{{url('/')}}/account/login.php" title="Login">Login</a></li>
                                <li><a href="{{url('/')}}/account/register.php" title="Registration">Registration</a></li>
                                <li><a href="{{url('/')}}/account/dealer-register.php" title="Register as a dealer">Register as a dealer</a></li>
                                <li><a href="{{url('/')}}/account/dealer-register.php?sign-in=true" title="Sign In as a dealer">Sign In as a dealer</a></li>
                            </ul>
                        </div>
                    </div>
                    <h4 class="sitemapheading">Top Keywords</h4>
                    <hr>
                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12 text-left">
                        <div class="sitemapkeyword">
                            <ul>
                                <li><a href="{{url('/')}}" title="Online visiting card maker">Online visiting card maker</a>
                                </li>
                                <li><a href="{{url('/')}}" title="digital business card">digital business card</a></li>
                                <li><a href="{{url('/')}}" title="digital card">digital card</a></li>
                                <li><a href="{{url('/')}}" title="create business card">create business card</a></li>
                                <li><a href="{{url('/')}}" title="free demo">free demo</a></li>
                                <li><a href="{{url('/')}}" title="mumbai">mumbai</a></li>
                                <li><a href="{{url('/')}}" title="india">india</a></li>
                                <li><a href="{{url('/')}}" title="verified and secure business card">verified and secure business card</a></li>
                                <li><a href="{{url('/')}}" title="create digital card">create digital card</a></li>
                                <li><a href="{{url('/')}}" title="digital business card">digital business card</a></li>
                                <li><a href="{{url('/')}}" title="digital card in mumbai">digital card in mumbai</a></li>
                                <li><a href="{{url('/')}}" title="digital visiting card">digital visiting card</a></li>
                                <li><a href="{{url('/')}}" title="online digital card maker">online digital card maker</a></li>
                                <li><a href="{{url('/')}}" title="digital business card free">digital business card free</a></li>
                                <li><a href="{{url('/')}}" title="digital marketing business cards">digital marketing business cards</a></li>
                                <li><a href="{{url('/')}}" title="digital anniversary card">digital anniversary card</a></li>
                                <li><a href="{{url('/')}}" title="digital visiting card free">digital visiting card free</a></li>
                                <li><a href="{{url('/')}}" title="digital birthday card maker">digital birthday card maker</a></li>
                                <li><a href="{{url('/')}}" title="best digital business card app">best digital business card app</a></li>
                                <li><a href="{{url('/')}}" title="free digital business card maker">free digital business card maker</a></li>
                                <li><a href="{{url('/')}}" title="smart digital business card">smart digital business card</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- terms and conditions area end -->
@endsection
