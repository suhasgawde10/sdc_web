<?php
include 'whitelist.php';
require_once "controller/ManageApp.php";
$manage = new ManageApp();
$error = false;
$errorMessage = "";


/*$get_blog_details = $manage->displayBlogDetails();*/

function pagination($query, $per_page = 6,$page = 1, $url = '?'){
    global $manage;
    $row = $manage->getSumOfRow($query);
    $total = $row['num'];
    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total/$per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";
    if($lastpage > 1)
    {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details' style='margin-top:2px'>Page $page of $lastpage</li>";
        if ($lastpage < 7 + ($adjacents * 2))
        {
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= "<li><a class='current'>$counter</a></li>";
                else
                    $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2))
        {
            if($page < 1 + ($adjacents * 2))
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>...</li>";
                $pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";
            }
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                $pagination.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
                $pagination.= "<li class='dot'>..</li>";
                $pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";
            }
            else
            {
                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                $pagination.= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1){
            $pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
            $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
        }else{
            $pagination.= "<li><a class='current'>Next</a></li>";
            $pagination.= "<li><a class='current'>Last</a></li>";
        }
        $pagination.= "</ul>\n";
    }


    return $pagination;
}

?>

<!doctype html>
<html lang="en">
<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Blogs | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
          content="Blogs ,Blogs of digital card,Blogs of the visiting card,Blogs about digital card, digital card provides feature to connect end user , secure fund transfer customized business and visting card design and many more">
    <meta name="keywords"
          content="digital business card, online visiting card, affordable, attractive business and visiting card design maker in india, maharshatra, mumbai, modern solution for visiting card, business card application for android, share digital card , best digital card, customized, feature digital card ,feature">

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

<div class="innerpage-banner" id="home" style="background: url(assets/img/bread/breadcrumbs-blog.jpg) no-repeat center; background-size: cover;">
    <div class="inner-page-layer">
        <h5>Blogs</h5>
        <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Blogs</span></h6>
    </div>
</div>

<?php

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 6; //if you want to dispaly 10 records per page then you have to change here
$startpoint = ($page * $limit) - $limit;
$statement = "tb_blog WHERE status=1 order by id desc"; //you have to pass your query over here
$get_blog_details=$manage->getCountOfRow($statement,$startpoint,$limit);

/*while($row=mysqli_fetch_array($res))
{
    echo $row["title"];
    echo "<br>";
}*/

?>




<!--<div class="ptb--30">-->
<!--</div>-->
<!-- about area start -->
<div class="blog-post-area">
    <div class="container">
        <div class="row">
            <div class="blog-list">
                <?php
                if ($get_blog_details != null) {
                    ?>

                    <ul class="blog_ul">
                        <?php
                        while ($result_data = mysqli_fetch_array($get_blog_details)) {
                            ?>
                            <li class="blog_li">
                                <div class="list-item">
                                    <div class="blog-thumbnail">
                                        <a href="blogs-details.php?id=<?php echo $result_data['id']; ?>"><img
                                                src="<?php echo "user/uploads/blog/" . $result_data['img_file']; ?>"
                                                alt="blog thumbnail"></a>
                                    </div>
                                    <h2 class="blog-title"><a
                                            href="blogs-details.php?id=<?php echo $result_data['id']; ?>"><?php if (isset($result_data['title'])) echo $result_data['title']; ?></a>
                                    </h2>

                                    <div class="blog-meta">
                                        <ul>
                                            <li>
                                                <i class="fa fa-calendar"></i><?php if (isset($result_data['created_date'])) {
                                                    echo $result_data['created_date'];
                                                } ?></li>
                                        </ul>
                                    </div>
                                    <div class="blog-summery">
                                        <p><?php if (isset($result_data['description'])) echo substr($result_data['description'], 0, 100) . "..."; ?></p>
                                    </div>
                                    <div class="widget-tag-list">
                                        <?php
                                        $keyword_data = explode(",", $result_data['keyword']);
                                        $i = 1;
                                        foreach ($keyword_data as $key) {
                                            if ($i++ >= 4) {
                                                break;
                                            }
                                            ?>
                                            <a href="blogs-details.php?id=<?php echo $result_data['id'] ?>"><?php
                                                echo $key;
                                                ?>
                                            </a>
                                        <?php } ?>


                                    </div>
                                    <div class="ptb--10">

                                    </div>
                                    <div class="blog-meta text-center">
                                        <ul>
                                            <li>
                                                <div class="blogs_buttons">
                                                    <a href="register.php" class="read-more">Get 5 days trial</a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="blogs_buttons_read_more">
                                                    <a href="blogs-details.php?id=<?php echo $result_data['id']; ?>"
                                                       class="read-more">Read More</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php
                } ?>


            </div>
        </div>
    </div>
</div>
<?php
echo "<div id='pagingg' >";
echo pagination($statement,$limit,$page);
echo "</div>";
?>
<!-- blog post area end -->
<!-- pagination area start -->
<!--<div class="pagination-area">-->
<!--    <div class="container">-->
<!--        <div class="pagination">-->
<!--            <ul>-->
<!--                <li><a href="#">OLDER POSTS</a></li>-->
<!--                <li><a href="#">1</a></li>-->
<!--                <li><a href="#">2</a></li>-->
<!--                <li><span>3</span></li>-->
<!--                <li><a href="#">4</a></li>-->
<!--                <li><a href="#">NEWER POSTS</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- pagination area end -->
<div class="ptb--10">

</div>
<!-- about area end -->





<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>