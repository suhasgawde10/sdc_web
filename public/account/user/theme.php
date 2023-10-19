<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
include("session_includes.php");
$get_theme = $manage->selectTheme();

if(isset($_POST['theme1'])){
    $theme_id = "theme1";
    $update_theme1 = $manage->updateTheme($theme_id);
    if($update_theme1){
        /*echo "<script>alert('Theme one has been set')</script>";*/
        $url = $_SERVER['PHP_SELF'];
        header('location:'.$url);
    }
}
if(isset($_POST['theme2'])){
    $theme2 = "theme2";
    $update_theme2 = $manage->updateTheme($theme2);
    if($update_theme2){
        /*echo "<script>alert('Theme two has been set')</script>";*/
        $url = $_SERVER['PHP_SELF'];
        header('location:'.$url);
    }
}
if(isset($_POST['theme3'])){
    $theme3 = "theme3";
    $update_theme3 = $manage->updateTheme($theme3);
    if($update_theme3){
        /*echo "<script>alert('Theme three has been set')</script>";*/
        $url = $_SERVER['PHP_SELF'];
        header('location:'.$url);
    }
}
if(isset($_POST['theme4'])){
    $theme4 = "theme4";
    $update_theme4 = $manage->updateTheme($theme4);
    if($update_theme4){
        /*echo "<script>alert('Theme four has been set')</script>";*/
        $url = $_SERVER['PHP_SELF'];
        header('location:'.$url);
    }
}
if(isset($_POST['theme5'])){
    $theme5 = "theme5";
    $update_theme5 = $manage->updateTheme($theme5);
    if($update_theme5){
        /*echo "<script>alert('Theme five has been set')</script>";*/
        $url = $_SERVER['PHP_SELF'];
        header('location:'.$url);
    }
}
if(isset($_POST['theme6'])){
    $theme6 = "theme6";
    $update_theme6 = $manage->updateTheme($theme6);
    if($update_theme6){
        /*echo "<script>alert('Theme six has been set')</script>";*/
        $url = $_SERVER['PHP_SELF'];
        header('location:'.$url);
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Theme</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
    <?php
    if(isset($_SESSION['create_user_status']) && $_SESSION['create_user_status']==true){
        include "assets/common-includes/session_button_includes.php" ;
    }
    ?>
    <?php include "assets/common-includes/website-preview.php" ?>
    <div class="up-nav visible-lg visible-md visible-sm hidden-xs">
        <main>
            <div class="page-content" id="applyPage">
                <ul class="breadcrumbs">
                    <li class="tab-link breadcrumb-item breadcrumb_width active visited">
                        <a href="theme.php">
                            <span class="number"><i class="far fa-list-alt"></i></span>
                            <span class="label">Theme</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width animated infinite pulse">
                        <a href="image-slider.php">
                            <span class="number"><i class="fas fa-user"></i></span>
                            <span class="label">Image Slider</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width">
                        <a href="about-us.php">
                            <span class="number"><i class="fas fa-images"></i></span>
                            <span class="label">About Us</span>
                        </a>
                    </li>
                    <li class="tab-link breadcrumb-item breadcrumb_width">
                        <a href="website_setting.php">
                            <span class="number"><i class="fas fa-user"></i></span>
                            <span class="label">Website Setting</span>
                        </a>
                    </li>
                </ul>

            </div>

        </main>
    </div>




    <div class="clearfix">
        <div class="col-md-12 padding_zero padding_zero_both">
            <div class="row">
                <ul class="theme_ul">
                    <!--<li>
                        <div class="theme_main_div">
                            <div class="overlay1"></div>
                            </div>
                    </li>-->
                    <li>
                        <div class="theme_main_div">
                            <div>
                                <img src="../website/assets/img/default-theme/banner3.jpg" style="width: 100%">
                            </div>
                            <div class="theme_name">
                                <h3>Theme 1</h3>
                            </div>
                            <?php if($get_theme!=null){
                                if(isset($get_theme['theme_id']) && $get_theme['theme_id']=="theme1"){
                            ?>
                                <div class="demo_button">
                                    <div class="live_demo">
                                        <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme1"><b>Preview</b></a>
                                    </div>
                                    <div class="set_default_selected">
                                            <button style="cursor: not-allowed"><b>Selected</b></button>
                                    </div>
                                </div>
                                <?php
                                }else{
                                    ?>
                                    <div class="demo_button">
                                        <div class="live_demo">
                                            <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme1"><b>Live Demo</b></a>
                                        </div>
                                        <div class="set_default">
                                            <form method="post" action="">
                                                <button type="submit" name="theme1"><b>Set as Default</b></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                } ?>

                        </div>

                    </li>
                    <li>
                        <div class="theme_main_div">
                            <div>
                                <img src="../website/assets/img/default-theme/banner5.jpg" style="width: 100%">
                            </div>
                            <div class="theme_name">
                                <h3>Theme 2</h3>
                            </div>
                            <?php if($get_theme!=null){
                            if(isset($get_theme['theme_id']) && $get_theme['theme_id']=="theme2"){
                            ?>
                                <div class="demo_button">
                                    <div class="live_demo">
                                        <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme2"><b>Preview</b></a>
                                    </div>
                                    <div class="set_default_selected">
                                        <button style="cursor: not-allowed"><b>Selected</b></button>
                                    </div>
                                </div>
                                <?php
                                }else{
                                    ?>
                                    <div class="demo_button">
                                        <div class="live_demo">
                                            <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme2"><b>Live Demo</b></a>
                                        </div>
                                        <div class="set_default">
                                            <form method="post" action="">
                                                <button type="submit" name="theme2"><b>Set as Default</b></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                } ?>

                        </div>

                    </li>
                    <li>
                        <div class="theme_main_div">
                            <div>
                                <img src="../website/assets/img/default-theme/banner4.jpg" style="width: 100%">
                            </div>
                            <div class="theme_name">
                                <h3>Theme 3</h3>
                            </div>
                            <?php if($get_theme!=null){
                            if(isset($get_theme['theme_id']) && $get_theme['theme_id']=="theme3"){
                            ?>
                                <div class="demo_button">
                                    <div class="live_demo">
                                        <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme3"><b>Preview</b></a>
                                    </div>
                                    <div class="set_default_selected">
                                        <button style="cursor: not-allowed"><b>Selected</b></button>
                                    </div>
                                </div>
                                <?php
                                }else{
                                    ?>
                                    <div class="demo_button">
                                        <div class="live_demo">
                                            <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme3"><b>Live Demo</b></a>
                                        </div>
                                        <div class="set_default">
                                            <form method="post" action="">
                                                <button type="submit" name="theme3"><b>Set as Default</b></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                } ?>

                        </div>

                    </li>
                    <li>
                        <div class="theme_main_div">
                            <div>
                                <img src="../website/assets/img/default-theme/banner3.jpg" style="width: 100%">
                            </div>
                            <div class="theme_name">
                                <h3>Theme 4</h3>
                            </div>
                            <?php if($get_theme!=null){
                            if(isset($get_theme['theme_id']) && $get_theme['theme_id']=="theme4"){
                            ?>
                                <div class="demo_button">
                                    <div class="live_demo">
                                        <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme4"><b>Preview</b></a>
                                    </div>
                                    <div class="set_default_selected">
                                        <button style="cursor: not-allowed"><b>Selected</b></button>
                                    </div>
                                </div>
                                <?php
                                }else{
                                    ?>
                                    <div class="demo_button">
                                        <div class="live_demo">
                                            <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme4"><b>Live Demo</b></a>
                                        </div>
                                        <div class="set_default">
                                            <form method="post" action="">
                                                <button type="submit" name="theme4"><b>Set as Default</b></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                } ?>
                        </div>

                    </li>
                    <li>
                        <div class="theme_main_div">
                            <div>
                                <img src="../website/assets/img/default-theme/banner3.jpg" style="width: 100%">
                            </div>
                            <div class="theme_name">
                                <h3>Theme 5</h3>
                            </div>
                            <?php if($get_theme!=null){
                            if(isset($get_theme['theme_id']) && $get_theme['theme_id']=="theme5"){
                            ?>
                                <div class="demo_button">
                                    <div class="live_demo">
                                        <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme5"><b>Preview</b></a>
                                    </div>
                                    <div class="set_default_selected">
                                        <button style="cursor: not-allowed"><b>Selected</b></button>
                                    </div>
                                </div>
                                <?php
                                }else{
                                    ?>
                                    <div class="demo_button">
                                        <div class="live_demo">
                                            <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme5"><b>Live Demo</b></a>
                                        </div>
                                        <div class="set_default">
                                            <form method="post" action="">
                                                <button type="submit" name="theme5"><b>Set as Default</b></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                } ?>
                        </div>

                    </li>
                    <li>
                        <div class="theme_main_div">
                            <div>
                                <img src="../website/assets/img/default-theme/banner3.jpg" style="width: 100%">
                            </div>
                            <div class="theme_name">
                                <h3>Theme 6</h3>
                            </div>
                            <?php if($get_theme!=null){
                            if(isset($get_theme['theme_id']) && $get_theme['theme_id']=="theme6"){
                            ?>
                                <div class="demo_button">
                                    <div class="live_demo">
                                        <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme6"><b>Preview</b></a>
                                    </div>
                                    <div class="set_default_selected">
                                        <button style="cursor: not-allowed"><b>Selected</b></button>
                                    </div>
                                </div>
                                <?php
                                }else{
                                    ?>
                                    <div class="demo_button">
                                        <div class="live_demo">
                                            <a target="_blank" href="../website/index.php?custom_url=<?php echo $get_theme['custom_url']; ?>&theme=theme6"><b>Live Demo</b></a>
                                        </div>
                                        <div class="set_default">
                                            <form method="post" action="">
                                                <button type="submit" name="theme6"><b>Set as Default</b></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                } ?>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>