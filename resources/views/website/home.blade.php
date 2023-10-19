@extends('layouts.web-main')
@section('main-container')
<div class="phone_img hidden-lg hidden-md hidden-sm visible-xs">
    <a href="tel:" title="contact us:-"><img
            src="{{url('/')}}/dist/assets/img/phone.png" alt="digital card contact"></a>
</div>

<!-- header area end -->
<!-- slider area start -->
<!--<section class="slider-area" id="home">
    <div class="container">
        <div class="col-md-6 col-sm-6 hidden-xs">
            <div class="row">
                <div class="slider-img">
                    <img src="{{url('/')}}/dist/assets/img/mobile/slider-left-img.png" alt="slider image">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="slider-inner text-right">
                    <h2>Who Else Wants To User</h2>
                    <h5>And Use Our Zeed App !</h5>
                    <a href="#">View More</a>
                    <a href="#">Purchase Now</a>

                </div>
            </div>
        </div>
    </div>
</section>-->
<section class="slider-area" id="home">
    <div class="container-fluid">
        <div class="row">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <!--<ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>-->
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                <div class="item active">
                        <img src="{{url('/')}}/dist/assets/img/sidebar/new-slider/slider_combo.png"
                             class="visible-lg visible-md visible-sm hidden-xs"
                             alt="digital visiting card website combo offer" title="digital visiting card website combo offer" style="width:100%;">
                        <!--<img src="{{url('/')}}/dist/assets/img/sidebar/4.jpg" class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="Los Angeles" style="width:100%;">-->
                        <img src="{{url('/')}}/dist/assets/img/sidebar/slider_combo.jpeg"
                             class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="digital visiting card website combo offer" style="width:100%;" title="digital visiting card website combo offer">
                    </div>
                
                <div class="item">
                        <img src="{{url('/')}}/dist/assets/img/sidebar/new-slider/slider2.png"
                             class="visible-lg visible-md visible-sm hidden-xs"
                             alt="digital visiting card" title="digital visiting card" style="width:100%;">
                        <!--<img src="{{url('/')}}/dist/assets/img/sidebar/4.jpg" class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="Los Angeles" style="width:100%;">-->
                        <img src="{{url('/')}}/dist/assets/img/sidebar/secondslideimage.jpg"
                             class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="digital visiting card" style="width:100%;" title="digital visiting card">
                    </div>

                    <div class="item">
                        <img src="{{url('/')}}/dist/assets/img/sidebar/new-slider/slider1.png"
                             class="visible-lg visible-md visible-sm hidden-xs"
                             alt="online digital card maker" style="width:100%;" title="online digital card maker">
                        <img src="{{url('/')}}/dist/assets/img/sidebar/secondslideimage3.jpg"
                             class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="online digital card maker" title="online digital card maker" style="width:100%;">


                        <div class="carousel-caption">

                        </div>
                    </div>
                    <!--  <div class="item">
                          <img src="{{url('/')}}/dist/assets/img/sidebar/fgthffgh.jpeg"
                               class="visible-lg visible-md visible-sm hidden-xs"
                               alt="digital card in mumbai" style="width:100%;" title="digital card in mumbai">
                          <img src="{{url('/')}}/dist/assets/img/sidebar/secondslideimage1.jpg"
                               class="hidden-sm visible-xs hidden-lg hidden-md"
                               alt="digital card in mumbai" title="digital card in mumbai" style="width:100%;">

                          <div class="carousel-caption">
                          </div>
                      </div>-->
                </div>

                <a title="digital business card" class="left carousel-control carousel-width" href="#myCarousel"
                   data-slide="prev">
                    <div id="slider_left">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </div>
                    <span class="sr-only">Previous</span>
                </a>
                <a title="digital business card" class="right carousel-control carousel-width" href="#myCarousel"
                   data-slide="next">
                    <div id="slider_right">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </div>
                    <span class="sr-only">Next</span>
                </a>

            </div>
        </div>
    </div>
</section>
<div id="projectFacts" class="sectionClass">
    <div class="eight columns">

        <div class="projectFactsWrap">
            <div class="explore_now">
                <a href="search-profile.php" class="explore_now_btn form-control btn btn-primary">Explore Now</a>
                <a href="register.php" class="explore_now_btn form-control btn btn-success" style="margin: 0">Get
                    Started</a>
            </div>
            <div class="item wow fadeInUpBig animated animated" data-number=""
                 style="visibility: visible;background-color: #ffffff; ">
                <div class="counter-icon customr_count_img">
                    <img src="{{url('/')}}/dist/assets/img/website-counter/customer.png" alt="Online visiting card maker">
                </div>
                <div class="customer_count">
                    <p id="number1" class="number"></p><i class="fa fa-plus" aria-hidden="true"></i>
                    <span></span>

                    <p>Customers</p>
                </div>
            </div>
            <div class="item wow fadeInUpBig animated animated" data-number="55"
                 style="visibility: visible;background-color: #f8f8f8; ">
                <div class="counter-icon customr_count_img">
                    <img src="{{url('/')}}/dist/assets/img/website-counter/city.png" alt="smart digital business card">
                </div>
                <div class="customer_count">
                    <p id="number2" class="number">55</p><i class="fa fa-plus" aria-hidden="true"></i>
                    <span></span>

                    <p>Covered City</p>
                </div>
            </div>
            <div class="item wow fadeInUpBig animated animated" data-number="359"
                 style="visibility: visible;background-color: #efefef;">
                <div class="counter-icon customr_count_img">
                    <img src="{{url('/')}}/dist/assets/img/website-counter/artist.svg" alt="verified and secure business card">
                </div>

                <div class="customer_count">
                    <p id="number3" class="number">359</p><i class="fa fa-plus" aria-hidden="true"></i>
                    <span></span>

                    <p>Themes</p>
                </div>
            </div>
            <div class="item wow fadeInUpBig animated animated" data-number="500"
                 style="visibility: visible;background: #e8e8e8;">
                <div class="counter-icon customr_count_img">
                    <img src="{{url('/')}}/dist/assets/img/website-counter/handshake.svg" alt="digital card free demo">
                </div>
                <div class="customer_count">

                    <p id="number4" class="number">246</p><i class="fa fa-plus" aria-hidden="true"></i>
                    <span></span>

                    <p>Partners</p>
                </div>
            </div>
            <div class="explore_now explore-cust-respon">
                <a href="search-profile.php" class="explore_now_btn form-control btn btn-primary">Explore Now</a>
                <a href="register.php" class="explore_now_btn form-control btn btn-success"
                   style="margin: 0;margin-right: 5px;">Get Started</a>
            </div>

        </div>
    </div>
</div>


<!--search area start-->
<section class="visible-lg visible-md visible-sm hidden-xs">
    <div class="container">
        <div class="col-md-12 contact-form form_padding search_form">
            <div class="col-md-8 col-md-offset-1 search_form1">
                <div class="col-md-12 search-title form_padding">
                    <h4>Search for anything</h4>
                </div>
                <div class="row search_form_margin">
                    <form class="form-horizontal" method="post">
                        <!--<div class="col-md-3 col-sm-3 search_bar_starting form_padding">
                            <input class="form-control search_input form_input_height search_text_city" name="txt_city" value="<?php /*echo $current_city; */ ?>" placeholder="Enter City">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>-->
                        <div class="col-md-10 col-sm-10 form_padding">
                            <input class="form-control search_input_text" type="text" name="txt_search"
                                   style="border-radius: 0;border-left: 1px solid #ccc;"
                                   value="<?php if (isset($_GET['search']) && $_GET['search'] != "") {
                                       echo $_GET['search'];
                                   } ?>"
                                   placeholder="Search For software engineer,tester,machanical,painter,etc."
                                   required="required">
                        </div>
                        <div class="col-md-2 col-sm-2 form_padding">
                            <button type="submit" name="search_button"
                                    class="btn btn-primary search_button form_input_height"><i
                                    class="fa fa-search"></i>&nbsp;&nbsp;search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <img src="{{url('/')}}/dist/assets/img/search.png" class="img img-fluid img-responsive search-img" alt="">
            </div>
        </div>
    </div>
</section>
<!--search area end-->
<!-- slider area end -->
<!-- service area start -->
<!--<div class="service-area">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="service-single">
                    <img src="{{url('/')}}/dist/assets/img/service/service-img1.png" alt="service image">

                    <h2>Direct Chat</h2>

                    <p>Connect With A Click</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12 col-6">
                <div class="service-single">
                    <img src="{{url('/')}}/dist/assets/img/service/service-img2.png" alt="service image">

                    <h2>Share Links</h2>

                    <p>Share Card Links On Social Media</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12 col-6">
                <div class="service-single">
                    <img src="{{url('/')}}/dist/assets/img/service/service-img3.png" alt="service image">

                    <h2>Save Contact</h2>

                    <p>Save Contact Hazzle Free</p>
                </div>
            </div>
        </div>
    </div>
</div>-->
<!-- service area end -->

<!-- about area start -->
<?php /*include "about-digital-card-include.php" */ ?>
<!-- about area end -->
<!--Counter area start-->
<!--<div class="sectiontitle">
    <h2>Projects statistics</h2>
    <span class="headerLine"></span>
</div>-->


<!--Counter area end-->

<!-- feature area start -->
<section class="feature-area about_back_color ptb--30" id="feature">
    <div class="container">
        <div class="section-title">
            <h2>Digital Business Card</h2>

            <p>Great Features</p>
        </div>
        @include('website.feature-include')
    </div>
</section>


<!-- feature area end -->
<!-- video area start -->
<div class="video-area ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                <h2 class="video-title">Watch Our Video</h2>

                <p>Digital Card VS Visiting Card</p>

                <p>Why we need a professional digital business card? Check the video to see the difference between the
                    normal business card and digital business card.</p>
                <a title="digital business card" class="expand-video" target="_blank"
                   href="https://www.youtube.com/watch?v=s9I8gIrvwEc"><i
                        class="fa fa-play"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- video area start -->

<?php if (!isset($_SESSION['email'])) { ?>
    <div>
        <div id="bkgOverlay" class="backgroundOverlay"></div>

        <div id="delayedPopup" class="delayedPopupWindow">
            <!-- This is the close button -->
            <a href="#" id="btnClose" title="Click here to close this deal box.">
                <i class="fa fa-times-circle"></i>
            </a>
            <!-- This is the left side of the popup for the description -->
            <!-- Begin MailChimp Signup Form -->
            <div id="mc_embed_signup">
                <form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                      class="validate"
                      target="_blank" novalidate="">
                    <a href="register.php" title="digital business card"><img src="{{url('/')}}/dist/assets/img/warningcards.png"
                                                                              style="width: 100%"
                                                                              alt="digital card registeration"></a>
                    <!--<div class="clear text-center">
                        <a href="register.php" name="subscribe" id="mc-embedded-subscribe" class="button">Try for free!</a>
                    </div>-->
                </form>
            </div>
            <!-- End MailChimp Signup Form -->
        </div>
    </div>
<?php } ?>

<!-- video area end -->
<!-- screen slider area start -->
<!--<section class="screen-area ptb--30" id="screenshot">
    <div class="container">
        <div class="section-title">
            <h2>Screenshots</h2>

            <p>Go Paperless, Go Digital</p>
        </div>
        <?php /*include "screenshot-include.php" */ ?>
    </div>
</section>-->
<!-- Theme Section Start -->
<section class="pricing-area ptb--40" id="pricing">
    <div class="container">
        <div class="row">
            <div class="section-title">
                <h2>Our Satisfied Customers</h2>

                <p>Go Paperless, Go Digital</p>
            </div>
            @include('website.screenshot-include')

        </div>
    </div>
</section>

<!-- Theme Section End -->

<!--our process area start-->

<section class="feature-area process_padding steps_to_create_sec" id="feature">
    <div class="container">
        <div class="section-title">
            <h1 class="main_heading">Steps To Create Digital Business Card</h1>

            <p>Go Paperless, Go Digital</p>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="timeline">
                    <li>
                        <div class="timeline-image">
                            <img class="img-circle img-responsive process-img" src="{{url('/')}}/dist/assets/img/process/register.png"
                                 alt="digital card in mumbai" title="digital card in mumbai">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Step One</h4>
                                <a href="register.php" title="register to complete your profile" target="_blank"><h3
                                        class="subheading">Registration</h3></a>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">
                                    Register yourself with us and fill in all the details to complete your <b>digital
                                        profile</b>.
                                </p>
                            </div>
                        </div>
                        <div class="line"></div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image">
                            <img class="img-circle img-responsive process-img" src="{{url('/')}}/dist/assets/img/process/ready.png"
                                 alt="digital business card" title="digital business card">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Step Two</h4>
                                <h4 class="subheading">Digital Card Ready!</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">
                                    Congratulations your own <b>personalised Digital Card</b> is created and ready to
                                    share.
                                </p>
                            </div>
                        </div>
                        <div class="line"></div>
                    </li>
                    <li>
                        <div class="timeline-image">
                            <img class="img-circle img-responsive process-img" src="{{url('/')}}/dist/assets/img/process/share.png"
                                 alt="digital card" title="digital card">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Step Three</h4>
                                <h4 class="subheading">Easy To Share</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">
                                    You can now share your digital card across all <b>social media platforms</b> and
                                    make yourself more visible to the world in a better way.
                                </p>
                            </div>
                        </div>
                        <div class="line"></div>
                    </li>
                    <li class="timeline-inverted process-padding">
                        <div class="timeline-image process_margin_img">
                            <img class="img-circle img-responsive process-img" src="{{url('/')}}/dist/assets/img/process/transaction.png"
                                 alt="digital business card" title="digital business card">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Step Four</h4>
                                <h4 class="subheading">Secure Transactions</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">
                                    Make money transactions more easy and secure by sharing your <b>Digital Card for
                                        business</b> and personal use.
                                </p>
                            </div>
                        </div>
                        <!-- <div class="line"></div>-->
                    </li>
                    <!--  <li>
                          <div class="timeline-image">
                              <img class="img-circle img-responsive" src="http://lorempixel.com/250/250/cats/5" alt="">
                          </div>
                          <div class="timeline-panel">
                              <div class="timeline-heading">
                                  <h4>Bonus Step</h4>
                                  <h4 class="subheading">Subtitle</h4>
                              </div>
                              <div class="timeline-body">
                                  <p class="text-muted">
                                      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                      incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                      exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                  </p>
                              </div>
                          </div>
                      </li>-->
                </ul>
            </div>
        </div>
    </div>
</section>
<!--our process area end-->
<!-- pricing area start -->
<!--<section class="pricing-area ptb--30 about_back_color" id="pricing">
    <div class="container">
        <div class="section-title">
            <h2>Special Offers</h2>

        </div>
        <?php /*include "pricing-include.php" */ ?>
    </div>
</section>-->
<!-- pricing area end -->

@endsection
@section('custom-js')
<script>
    $.fn.jQuerySimpleCounter = function (options) {
        var settings = $.extend(
            {
                start: 0,
                end: 100,
                easing: "swing",
                duration: 400,
                complete: ""
            },
            options
        );

        var thisElement = $(this);

        $({count: settings.start}).animate(
            {count: settings.end},
            {
                duration: settings.duration,
                easing: settings.easing,
                step: function () {
                    var mathCount = Math.ceil(this.count);
                    thisElement.text(mathCount);
                },
                complete: settings.complete
            }
        );
    };

    $("#number1").jQuerySimpleCounter({end: 500, duration: 5000});
    $("#number2").jQuerySimpleCounter({end: 70, duration: 5000});
    $("#number3").jQuerySimpleCounter({end: 16, duration: 5000});
    $("#number4").jQuerySimpleCounter({end: 500, duration: 5000});

    $('.number').append("+");
    /* AUTHOR LINK */
    $(".about-me-img").hover(
        function () {
            $(".authorWindowWrapper")
                .stop()
                .fadeIn("fast")
                .find("p")
                .addClass("trans");
        },
        function () {
            $(".authorWindowWrapper")
                .stop()
                .fadeOut("fast")
                .find("p")
                .removeClass("trans");
        }
    );

</script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

<script>
    $(document).ready(function () {

        var slider = $('.slider').bxSlider({
            mode: 'horizontal', //mode: 'fade',
            speed: 300,
            //不要自動
            auto: false,
            infiniteLoop: true,
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

<script src="user/assets/plugins/node-waves/waves.js"></script>
<script src="user/assets/js/admin.js"></script>

<script>
    $(window).scroll(function () {

        if ($(this).scrollTop() > 3300) {
            $('.search_form').fadeOut();
        } else if ($(this).scrollTop() > 200) {
            $('.search_form').fadeIn();
        } else if ($(this).scrollTop() < 200) {
            $('.search_form').fadeOut();
        }
    });
</script>

@endsection