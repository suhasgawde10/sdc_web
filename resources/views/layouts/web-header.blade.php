
<header id="header">
    <div class="header-area">
        <div class="container-fluid">
            <div class="row">
                <div class="menu-area">
                    <div class="col-md-2 col-sm-12 col-xs-12 text-center playstore_logo">


                        <div class="logo">
                            <a href="index"><img src="{{ url('/') }}/dist/assets/img/logo/logo.png" alt="Digital Card logo"></a>
                        </div>

                        <a class="xyz hidden-lg hidden-md hidden-sm hidden-xs" href="#"><img class="playstore_logo_img" src="{{ asset('dist/assets/img/google-play-badge.png') }}" alt="digital card app"></a>

                        <main role="main">
                            <button class="popup-trigger btn hidden-lg hidden-md visible-sm visible-xs" id="popup-trigger"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </main>
                    </div>
                    <div class="col-md-10 hidden-xs hidden-sm">
                        <div class="main-menu">
                            <nav class="nav-menu">
                                <ul>
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/">Home</a>
                                    </li>
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/about-us">About</a>
                                    </li>
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/themes">Themes</a>
                                    </li>
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/pricing">Pricing</a>
                                    </li>
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/contact-us">Contact</a>
                                    </li>
                                    
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/account/blogs.php">Blogs</a>
                                    </li>

                                    
                                    <li class="abc">
                                        <a class="xyz" href="{{url('/')}}/account/login.php">Login</a>
                                    </li>
                                    <li class=" abc">
                                        <a class="xyz" href="{{url('/')}}/account/register.php">Registration</a>
                                    </li>
                                    
                                    <li class="abc">
                                        <a class="digitalcard_demo" href="{{url('/')}}/account/demo-cards.php" title="Share Digital Card">Demo Cards</a>
                                    </li>
                                    <li class="abc"><a target="_blank" class="visible-lg visible-md hidden-sm hidden-xs" href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard" style="padding: 0px"><img src="{{ url('/') }}/dist/assets/img/playstore.png" style="width: 150px" alt="digital card app"></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12 visible-sm visible-xs">
                        <div class="row" style="background: #eee">
                            <div class="mobile_menu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="hidden-lg hidden-sm">
    <div class="overlay" id="overlay">
        <div class="overlay-background" id="overlay-background"></div>
        <div class="overlay-content" id="overlay-content">
            <div class="fa fa-times fa-lg overlay-close" id="overlay-close"></div>
            <h3 class="main-heading">Search anything</h3>

            <div class="col-xs-12 padding_bottom_search">
                <div class="row">
                    <form class="form-horizontal" method="post">
                        <div class="col-md-9 col-xs-9 form_padding">
                            <input class="form-control search_input form_input_height" type="text" name="txt_search" placeholder="software engineer,tester,machanical,painter," value="<?php if (isset($_GET['search']) && $_GET['search'] != '') {
                                echo $_GET['search'];
                            } ?>">
                        </div>
                        <div class="col-md-3 col-xs-2 form_padding">
                            <button type="submit" name="search_button" class="btn btn-primary search_button form_input_height"><i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
