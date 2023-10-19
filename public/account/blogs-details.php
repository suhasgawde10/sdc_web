<?php

error_reporting(0);

include "controller/ManageApp.php";
$manage = new ManageApp();
$error = false;
$errorMessage = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $validateBlogId = $manage->validateBlogId($id);
    if ($validateBlogId) {
            $get_blog_details = $manage->getBlogDetails($id);
            if ($get_blog_details != null) {
                $title = $get_blog_details['title'];
                $description = $get_blog_details['description'];
                $img_name = $get_blog_details['img_file'];
                $facebook = $get_blog_details['facebook'];
                $instagram = $get_blog_details['instagram'];
                $keyword = $get_blog_details['keyword'];
                $created_date = $get_blog_details['created_date'];
                $video_file = $get_blog_details['video_file'];
                $get_recent_post = $manage->getRecentBlogDetails($id);
                $keyword_data = explode(",", $keyword);
            }
    } else {
        header('location:index.php');
    }
} else {
    header('location:index.php');
}
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else
    $link = "http";

// Here append the common URL characters.
$link .= "://";

// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
$link .= $_SERVER['REQUEST_URI'];
?>

<!doctype html>
<html lang="en">
<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title><?php if (isset($title)) echo $title; ?> | Online business and visiting card maker in India, Maharashtra,
        Mumbai. </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="<?php if (isset($description)) echo substr($description, 0, 160); ?>">
    <meta name="keywords" content="<?php if (isset($keyword)) echo substr($keyword, 0, 240); ?>">
    <meta property="og:title" content="<?php if (isset($title)) echo $title; ?>"/>
    <meta property="og:url" content="<?php echo $link . "&title=" . $title; ?>"/>
    <meta property="og:description"
          content="<?php if (isset($description)) echo substr($description, 0, 160); ?>">
    <meta property="og:image" itemprop="image" content="https://sharedigitalcard.com/assets/img/ajay300.jpg">
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

<div class="innerpage-banner" id="home" style="background: url(assets/img/bread/breadcrumbs-blog.jpg) no-repeat center; background-size: cover;">
    <div class="inner-page-layer">
        <h5>Digital Card Blog Details</h5>
        <h6><a href="index.php">Home</a>&nbsp;/<a
                href="blogs.php">Blogs</a>&nbsp;/&nbsp;<span>Digital Card Blog Details</span></h6>
    </div>
</div>

<!--<div class="ptb--30">-->
<!--</div>-->


<!--<div class="social_blog_left_menu" data-target="#sideModalTR" data-toggle="modal">
   <a href="#" class="blogs_social_icon"><i class="fa fa-facebook" aria-hidden="true"></i></a>
    <a href="#" class="blogs_social_icon"><i class="fa fa-instagram" aria-hidden="true"></i></a>
</div>-->
<div class="social_blog_left_menu">
    <div class="footer-social">
        <!--<li><span>share</span></li> -->
        <!--<a href="<?php /*if(isset($facebook)){ echo $facebook;} */ ?>" target="_blank"><i class="fa fa-facebook-square color-fb"></i></a>-->
        <a
            class="facebook animated fadeIn" href="<?php if (isset($facebook)) {
            echo $facebook;
        } ?>" target="_blank"
            data-toggle="tooltip" data-placement="right" title="Digital Card Facebook"
            data-original-title="Kubic Technology Facebook"><span
                class="fa fa-facebook"></span></a>
        <a class="instagram animated fadeIn"
           href="<?php if (isset($instagram)) {
               echo $instagram;
           } ?>"
           data-toggle="tooltip" data-placement="right"
           title="Digital Card Instagram" target="_blank"><span
                class="fa fa-instagram"></span></a>
        <!--<li><a href="<?php /*if(isset($instagram)){ echo $instagram;} */ ?>" target="_blank"><i class="fa fa-instagram color-insta"></i></a></li>-->
    </div>
</div>
<!-- Side Modal Top Right -->

<!-- about area start -->
<!-- blog post area start -->
<div class="blog-details ptb--60">
    <div class="container">
        <div class="row">
            <!-- blog details area start -->

            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="blog-info">
                    <div class="blog-thumbnail">
                        <img src="<?php echo "user/uploads/blog/" . $img_name; ?>" style="width: 100%"
                             alt="blog thumbnail">
                    </div>
                    <h2 class="blog-title"><a
                            href="blogs-details.php?id=<?php echo $_GET['id'] ?>"><?php if (isset($title)) {
                                echo $title;
                            } ?></a></h2>

                    <div class="blog-meta">
                        <ul>
                            <li><i class="fa fa-calendar"></i><?php if (isset($created_date)) {
                                    echo $created_date;
                                } ?> </li>
                            <!--                            <li><i class="fa fa-comment"></i>Comments</li>-->
                        </ul>
                    </div>
                    <div class="blog-summery">
                        <p><?php if (isset($description)) {
                                echo $description;
                            } ?></p>

                        <?php
                        if (isset($video_file) && $video_file != "") {
                            ?>
                            <div class="pt--10">

                            </div>
                            <div class="info-video">
                                <iframe
                                    src="<?php echo str_replace("watch?v=", "embed/", $video_file); ?>"
                                    frameborder="0" width="100%"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>

                        <?php
                        }

                        ?>

                        <div class="blog-single-tags">
                            <h2>Tag's</h2>
                            <ul>
                                <?php
                                foreach ($keyword_data as $key) {
                                    ?>
                                    <li>
                                        <a href="blogs-details.php?id=<?php echo $_GET['id'] ?>"><?php echo $key; ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- blog details area end -->
            <!-- sidebar area start -->
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="sidebar--area">
                    <div class="widget widget-recent-post">
                        <div class="widget-title">
                            <h2>Recent Post</h2>
                        </div>
                        <div class="recent--post-list">
                            <?php
                            if ($get_recent_post != null) {
                                while ($get_data = mysqli_fetch_array($get_recent_post)) {
                                    ?>
                                    <div class="rc-single-post">
                                        <div class="meta-thumb">
                                            <a href="blogs-details.php?id=<?php if (isset($get_data['id'])) echo $get_data['id']; ?>"><img
                                                    src="<?php echo "user/uploads/blog/" . $get_data['img_file']; ?>"
                                                    alt="post thumb"></a>
                                        </div>
                                        <div class="meta--content">
                                            <a href="blogs-details.php?id=<?php if (isset($get_data['id'])) echo $get_data['id']; ?>"
                                               title="<?php if (isset($get_data['title'])) echo $get_data['title']; ?>"><?php if (isset($get_data['title'])) echo $get_data['title']; ?>
                                                .</a>
                                            <span class="up-time"><?php if (isset($get_data['created_date'])) {
                                                    echo $get_data['created_date'];
                                                } ?> </span>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="widget widget-tags">
                        <div class="widget-title">
                            <h2>Tags</h2>
                        </div>
                        <div class="widget-tag-list">
                            <?php
                            foreach ($keyword_data as $key) {
                                ?>
                                <a href="blogs-details.php?id=<?php echo $_GET['id'] ?>"><?php echo $key; ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- widget tags area end -->
                </div>
            </div>
            <!-- sidebar area end -->
        </div>
    </div>
</div>

<!-- about area end -->
<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>