<?php

$activeCLass = basename($_SERVER['PHP_SELF']);
if (isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "dealer") {
        $dasboard_url = "dealer/dashboard.php";
    } elseif ($_SESSION['type'] == "editor" && (isset($_SESSION['dealer_id']))) {
        $dasboard_url = "dealer/dashboard.php";
    } elseif ($_SESSION['type'] == "Editor" && (isset($_SESSION['dealer_id']))) {
        $dasboard_url = "user/admin_dashboard.php";
    } elseif ($_SESSION['type'] == "Admin") {
        $dasboard_url = "user/admin_dashboard.php";
    } elseif ($_SESSION['type'] == "User") {
        $dasboard_url = "user/dashboard.php";
    }
}
function website_url_checker($page_name)
{


}


?>
<?php
// $main_site = true;

// if (strpos($_SERVER['HTTP_HOST'], 'sharedigitalcard.com') !== false) {
//     $main_site = true;
// }
// else if(strpos($_SERVER['HTTP_HOST'], 'localhost') !== false){
//     $main_site = true;
// }
// print_r($main_site);exit;
if ($main_site) {
    ?>
    <header id="header">
        <div class="header-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="menu-area">
                        <div class="col-md-2 col-sm-12 col-xs-12 text-center playstore_logo">


                            <div class="logo">
                                <a href="index"><img src="assets/img/logo/logo.png" alt="Digital Card logo"></a>
                            </div>

                            <a class="xyz hidden-lg hidden-md hidden-sm hidden-xs" href="#"><img
                                    class="playstore_logo_img" src="assets/img/google-play-badge.png"
                                    alt="digital card app"></a>

                            <main role="main">
                                <button class="popup-trigger btn hidden-lg hidden-md visible-sm visible-xs"
                                        id="popup-trigger"><i class="fa fa-search"
                                                              aria-hidden="true"></i></button>
                            </main>
                        </div>
                        <div class="col-md-10 hidden-xs hidden-sm">
                            <div class="main-menu">
                                <nav class="nav-menu">
                                    <ul>
                                        <li class="abc">
                                            <a class="xyz" href="<?php echo APP_URL;?>">Home</a></li>
                                        <li class="abc">
                                            <a class="xyz" href="<?php echo APP_URL;?>about-us">About</a></li>
                                       
                                        <li class="abc">
                                            <a class="xyz" href="<?php echo APP_URL;?>themes">Themes</a></li>
                                        <li class="abc">
                                            <a class="xyz" href="<?php echo APP_URL;?>pricing">Pricing</a></li>
                                        <li class="abc">
                                            <a class="xyz" href="<?php echo APP_URL;?>contact-us">Contact</a></li>
                                       
                                        <li class="abc">
                                            <a class="xyz" href="blogs<?php echo $extension;?>">Blogs</a></li>

                                        <?php if (isset($_SESSION['email'])) { ?>
                                            <li class="abc">
                                                <div class="dropdown" style="cursor: pointer;">
                                                    <a class="xyz hidden-sm hidden-xs" type="button"
                                                       data-toggle="dropdown">My
                                                        Account
                                                        <span class="caret"></span></a>
                                                    <ul class="dropdown-menu hidden-sm hidden-xs">
                                                        <li class="abcd"><a
                                                                href="<?php echo $dasboard_url; ?>">Dashboard</a></li>
                                                        <?php
                                                        if ($_SESSION['type'] == "User") {
                                                            ?>
                                                            <li class="abcd"><a href="user/basic-user-info.php">My
                                                                    Profile</a></li>
                                                            <li class="abcd"><a href="user/basic-user-info.php">Edit
                                                                    Digital
                                                                    Card</a></li>
                                                            <!--  <li class="abcd"><a href="user/theme.php">Edit Website</a></li>-->
                                                            <li class="abcd"><a href="user/reset-password.php">Change
                                                                    Password</a></li>
                                                            <li class="abcd"><a href="sign-out-all.php">Logout</a></li>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <li class="abcd"><a href="sign-out-all.php">Logout</a></li>
                                                        <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <a href="#collaps1" data-toggle="collapse"
                                                   class="  hidden-lg hiden-md hidden-sm ">My Account</a>
                                                <ul id="collaps1"
                                                    class="ul-margin-remover collapse hidden-lg hiden-md hidden-sm cust-ul">
                                                    <li class="abcd"><a
                                                            href="<?php echo $dasboard_url; ?>">Dashboard</a>
                                                    </li>
                                                    <?php
                                                    if ($_SESSION['type'] == "User") {
                                                        ?>
                                                        <li class="abcd"><a href="user/basic-user-info.php">My
                                                                Profile</a>
                                                        </li>
                                                        <li class="abcd"><a href="user/basic-user-info.php">Edit Digital
                                                                Card</a></li>
                                                        <!--  <li class="abcd"><a href="user/theme.php">Edit Website</a></li>-->
                                                        <li class="abcd"><a href="user/reset-password.php">Change
                                                                Password</a></li>
                                                        <li class="abcd"><a href="sign-out-all.php">Logout</a></li>
                                                    <?php
                                                    } else {
                                                        ?>
                                                        <li class="abcd"><a href="sign-out-all.php">Logout</a></li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                        <?php } else { ?>
                                            <li class="<?php if (isset($activeCLass) && $activeCLass == "login.php") echo "active"; ?> abc">
                                                <a class="xyz" href="login<?php echo $extension;?>">Login</a></li>
                                            <li class="<?php if (isset($activeCLass) && $activeCLass == "register.php") echo "active"; ?> abc">
                                                <a class="xyz" href="register<?php echo $extension;?>">Registration</a></li>
                                        <?php } ?>
                                        <li class="abc">
                                            <a class="digitalcard_demo" href="demo-cards" title="Share Digital Card">Demo
                                                Cards</a></li>
                                        <li class="abc"><a target="_blank"
                                                           class="visible-lg visible-md hidden-sm hidden-xs"
                                                           href="https://play.google.com/store/apps/details?id=sharedigitalcard.com.digitalcard"
                                                           style="padding: 0px"><img src="assets/img/playstore.png"
                                                                                     style="width: 150px"
                                                                                     alt="digital card app"></a></li>
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
<?php
}
?>

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
                            <input class="form-control search_input form_input_height" type="text" name="txt_search"
                                   placeholder="software engineer,tester,machanical,painter,"
                                   value="<?php if (isset($_GET['search']) && $_GET['search'] != "") {
                                       echo $_GET['search'];
                                   } ?>">
                        </div>
                        <div class="col-md-3 col-xs-2 form_padding">
                            <button type="submit" name="search_button"
                                    class="btn btn-primary search_button form_input_height"><i
                                    class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>