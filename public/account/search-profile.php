<?php

include "controller/ManageApp.php";
$manage = new ManageApp();

$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$ip_aadr = (string)$xml->geoplugin_request;

$current_city = (string)$xml->geoplugin_city;
$current_region = (string)$xml->geoplugin_region;
$countryName = (string)$xml->geoplugin_countryName;

if (isset($_POST['search_button'])) {
    $search = $_POST['txt_search'];
    $city = $_POST['txt_city'];
    if (trim($city) != "") {
        header('location:search-profile.php?city=' . $city . '&search=' . $search);
    } else {
        header('location:search-profile.php?search=' . $search);
    }
}

if (isset($_GET['city'])) {
    $city = $_GET['city'];
} else {
    $city = "";
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = "";

}

if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}
$total_customer = $manage->displayActiveUserCount($search, $city);
$total_records_per_page = 18;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";
$total_records = $total_customer;
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total page minus 1


$displayUser = $manage->displayUser($search, $city, $offset, $total_records_per_page);
if ($displayUser != null) {
    $count = mysqli_num_rows($displayUser);
} else {
    $count = 0;
}
function urlChecker($url)
{
    $status = preg_replace('/^(?!https?:\/\/)/', 'http://', $url);
    return $status;
}

$get_city = $manage->getCityCategory();
include "data-uri-image.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--- Basic Page Needs  -->
    <meta charset="UTF-8">
    <title><?php
        if (isset($_GET['search'])) {
            echo $_GET['search'];
        }
        if (isset($_GET['city']) && $_GET['city'] != "") {
            echo " - " . $_GET['city'];
        }
        ?> Digital Card</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSS -->
    <meta property="og:title" content="Digital Card"/>
    <meta property="og:url" content="https://www.example.com/webpage/"/>
    <meta property="og:description" content="description of your website/webpage">
    <meta property="og:image" content="//cdn.example.com/uploads/images/webpage_300x200.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css"/>

    <?php include "assets/common-includes/header_includes.php" ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .select2-container {
            width: 100% !important;
        }

        .blog-single-tags ul li a {
            padding: 10px;
            margin-bottom: 5px;
            margin-right: 6px;
            border-radius: 16px;
            background: #c7c7c7;
            color: #1f1f1f;
            border: 1px solid #b7b7b7;
        }
    </style>

</head>

<body>

<!-- header area start -->

<?php include "assets/common-includes/header.php" ?>
<!--<div class="inner-bannerr" id="bread">
    <div class="container cust-bred">
        <?php
/*        if(isset($_GET['city']) && $_GET['city'] !="") {
            */ ?>
            <h2 class="my-lg-4 mb-2"> <?php /*echo $_GET['city'] */ ?></h2>
            <h3 class="my-lg-4 mb-2"><a href="index.php">Home</a> <span>/ Search</span>
                <span>/ <?php /*echo $_GET['search'] */ ?></span></h3>
        <?php
/*        }else{
            echo '<h2 class="my-lg-4 mb-2 mt-10"> Home </h2>';
        }
        */ ?>
    </div>
</div>-->
<section class="slider-area" id="home">
    <div class="container-fluid" style="position: relative">


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
                        <img src="assets/img/website-counter/template.png"
                             alt="digital visiting card" title="digital visiting card" style="width:100%;">
                        <!-- class="visible-lg visible-md visible-sm hidden-xs" -->
                        <!--<img src="assets/img/sidebar/4.jpg" class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="Los Angeles" style="width:100%;">-->
                        <!--<img src="assets/img/sidebar/secondslideimage.jpg"
                             class="hidden-sm visible-xs hidden-lg hidden-md"
                             alt="digital visiting card" style="width:100%;" title="digital visiting card">-->
                    </div>

                    <!--<div class="item">
                        <img src="assets/img/website-counter/1%20(2).png"
                             alt="online digital card maker" style="width:100%;" title="online digital card maker">

                        <div class="carousel-caption">

                        </div>
                    </div>-->
                    <!--  <div class="item">
                          <img src="assets/img/sidebar/fgthffgh.jpeg"
                               class="visible-lg visible-md visible-sm hidden-xs"
                               alt="digital card in mumbai" style="width:100%;" title="digital card in mumbai">
                          <img src="assets/img/sidebar/secondslideimage1.jpg"
                               class="hidden-sm visible-xs hidden-lg hidden-md"
                               alt="digital card in mumbai" title="digital card in mumbai" style="width:100%;">

                          <div class="carousel-caption">
                          </div>
                      </div>-->
                </div>
                <!--
                                <a title="digital business card" class="left carousel-control carousel-width" href="#myCarousel"
                                   data-slide="prev">
                                    <div id="slider_left">
                                        <span class="glyphicon glyphicon-chevron-left cust-chevoron-icon"></span>
                                    </div>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a title="digital business card" class="right carousel-control carousel-width" href="#myCarousel"
                                   data-slide="next">
                                    <div id="slider_right">
                                        <span class="glyphicon glyphicon-chevron-right cust-chevoron-icon"></span>
                                    </div>
                                    <span class="sr-only">Next</span>
                                </a>-->

            </div>
            <div class="search_bar_div visible-lg visible-md visible-sm hidden-xs">
                <form class="form-horizontal search_bar_sub" method="post">
                    <div class="search-heading">
                        <h2>Search Digital Card Profile As per Your Need</h2>
                    </div>
                   <!-- <div class="col-md-3 col-sm-3 search_bar_starting form_padding">
                        <select class="js-select2 form_input_height form-control search_input search_text_city"
                                placeholder="select city">
                            <option value="">Global</option>
                            <?php
/*
                            if ($get_city != null) {
                                while ($get_data = mysqli_fetch_array($get_city)) {
                                    */?>
                                    <option <?php /*if (isset($current_city) && $current_city == $get_data['name']) echo 'selected' */?>><?php /*echo $get_data['name']; */?>
                                    </option>
                                    <?php
/*                                }
                            }
                            */?>
                        </select>-->
                        <!--<input class="form-control search_input form_input_height search_text_city" required name="txt_city" value="<?php /*if(isset($_GET['city']) && $_GET['city'] !=""){ echo $_GET['city']; }else{ echo $current_city; }  */ ?>" placeholder="Enter City">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>-->
                   <!-- </div>-->
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
    </div>
</section>


<section class="search-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-9 col-xs-12 search-border">
                <div class="col-md-12 search-count">
                    <h2>Search Result <span class="badge"><?php echo $total_customer; ?></span></h2>

                </div>

                <?php
                if ($displayUser != null) {
                    ?>
                    <ul class="search-ul">
                        <?php
                        while ($get_result = mysqli_fetch_array($displayUser)) {
                            $contact_no = $get_result['contact_no'];
                            $altr_contact_no = $get_result['altr_contact_no'];
                            $email = $get_result['email'];
                            $saved_email = $get_result['saved_email'];
                            $website = $get_result['website_url'];
                            $linked_in = $get_result['linked_in'];
                            $youtube = $get_result['youtube'];
                            $facebook = $get_result['facebook'];
                            $twitter = $get_result['twitter'];
                            $instagram = $get_result['instagram'];
                            $gender = $get_result['gender'];
                            $keyword_data = explode(',', $get_result['user_keyword']);
                            $profilePath = "user/uploads/" . $get_result['email'] . "/profile/" . $get_result['img_name'];
                            $playstore = $get_result['playstore_url'];
                            ?>
                            <li>
                                <div class="display">
                                    <div class="display-item">
                                        <div class="business-card">
                                            <a href="<?php echo FULL_WEBSITE_URL.$get_result['custom_url']; ?>"
                                               target="_blank">
                                                <div class="profile">
                                                    <div class="image_profile">
                                                        <div class="profile-image"
                                                             style="background-image: url('<?php if (!file_exists($profilePath) && $gender == "Male" or $get_result['img_name'] == "") {
                                                                 echo "user/uploads/male_user.png";
                                                             } elseif (!file_exists($profilePath) && $gender == "Female" or $get_result['img_name'] == "") {
                                                                 echo "user/uploads/female_user.png";
                                                             } else {
                                                                 echo $profilePath;
                                                             } ?>')"></div>
                                                    </div>
                                                    <div class="profile-title">
                                                        <h5><?php echo $get_result['name']; ?></h5>
                                                        <h4><?php echo $get_result['designation'];
                                                            if ($get_result['company_name'] != "") {
                                                                echo " - " . $get_result['company_name'];
                                                            }
                                                            ?></h4>
                                                        <?php echo "<span>" . $get_result['business_category'] . "</span>"; ?>
                                                        <div class="social-logo">
                                                            <ul class="social-ul">
                                                                <?php

                                                                ?>
                                                                <li><a <?php if ($youtube == "") {
                                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                                    } else {
                                                                        echo "target='_blank'";
                                                                    } ?> href="<?php if (isset($youtube) && ($youtube) != "") {
                                                                        echo urlChecker($youtube);
                                                                    } else {
                                                                        echo "#";
                                                                    } ?>" class="linkedin"><img
                                                                                src="<?php echo $youtube_icon; ?>"></a>
                                                                </li>
                                                                <li><a <?php if ($facebook == "") {
                                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                                    } else {
                                                                        echo "target='_blank'";
                                                                    } ?> href="<?php if (isset($facebook) && ($facebook) != "") {
                                                                        echo $facebook;
                                                                    } else {
                                                                        echo "#";
                                                                    } ?>" class="facebook"> <img
                                                                                src="<?php echo $facebook_share_con; ?>"></a>
                                                                </li>
                                                                <li><a <?php if ($twitter == "") {
                                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                                    } else {
                                                                        echo "target='_blank'";
                                                                    } ?> href="<?php if (isset($twitter) && ($twitter) != "") {
                                                                        echo $twitter;
                                                                    } else {
                                                                        echo "#";
                                                                    } ?>" class="twitter"><img
                                                                                src="<?php echo $twitter_icon; ?>"></a>
                                                                </li>
                                                                <li><a <?php if ($instagram == "") {
                                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                                    } else {
                                                                        echo "target='_blank'";
                                                                    } ?> href="<?php if (isset($instagram) && ($instagram) != "") {
                                                                        echo $instagram;
                                                                    } else {
                                                                        echo "#";
                                                                    } ?>" class="instagram"><img
                                                                                src="<?php echo $instagram_icon; ?>"></a>
                                                                </li>
                                                                <li><a <?php if ($linked_in == "") {
                                                                        echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                                    } else {
                                                                        echo "target='_blank'";
                                                                    } ?> href="<?php if (isset($linked_in) && ($linked_in) != "") {
                                                                        echo $linked_in;
                                                                    } else {
                                                                        echo "#";
                                                                    } ?>" class="linkedin"><img
                                                                                src="<?php echo $linked_in_icon; ?>"></a>
                                                                </li>
                                                                <?php
                                                                if ($playstore != "") {
                                                                    ?>
                                                                    <li><a <?php if ($playstore == "") {
                                                                            echo "style='cursor: not-allowed;filter: grayscale(100%);'" . "target='_self'";
                                                                        } else {
                                                                            echo "target='_blank'";
                                                                        } ?> href="<?php if (isset($playstore) && ($playstore) != "") {
                                                                            echo $playstore;
                                                                        } else {
                                                                            echo "#";
                                                                        } ?>" class="playstore"><img
                                                                                    src="d/assets/images/icon/playstore.png"></a>
                                                                    </li>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="info">
                                                <div class="info-contact">
                                                    <span><i class="fa fa-phone"></i> <a
                                                                href="tel:<?php if (isset($contact_no)) echo $contact_no; ?>"><?php if (isset($contact_no)) echo $contact_no; ?></a><a
                                                                href="tel:<?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "&nbsp;/&nbsp;" . $altr_contact_no; ?>"><?php if (isset($altr_contact_no) && $altr_contact_no != "") echo "&nbsp;/&nbsp;" . $altr_contact_no; ?></a></span>
                                                    <span><i class="fa fa-at"></i> <?php
                                                        if (isset($saved_email) && $saved_email != "") {
                                                            ?>
                                                            <a href="mailto:<?php if (isset($saved_email)) echo $saved_email; ?>"><?php if (isset($saved_email) && strlen($saved_email) <= 27) {
                                                                    echo $saved_email;
                                                                } else {
                                                                    echo substr($saved_email, 0, 27) . "...";
                                                                }; ?></a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a href="mailto:<?php if (isset($email)) echo $email; ?>"><?php if (isset($email) && strlen($email) <= 27) {
                                                                    echo $email;
                                                                } else {
                                                                    echo substr($email, 0, 27) . "...";
                                                                }; ?></a>
                                                            <?php
                                                        }
                                                        ?></span>
                                                    <span><i class="fa fa-globe"></i> <a
                                                                href="<?php if (isset($website)) echo urlChecker($website); ?>"
                                                                target="_blank"><?php if ($website != null) {
                                                                if (strlen($website) <= 26) {
                                                                    echo $website;
                                                                } else {
                                                                    echo substr($website, 0, 26) . '..';
                                                                }
                                                            } else {
                                                                echo "Website not available";
                                                            } ?></a></span>
                                                </div>
                                                <div class="info-bio">
                                                    <span><i class="fa fa-home" aria-hidden="true"></i> <a
                                                                href="<?php echo FULL_WEBSITE_URL.$get_result['custom_url']; ?>"
                                                                target="_blank">About US</a></span>
                                                    <span><i class="fa fa-eye" aria-hidden="true"></i> <a
                                                                href="<?php echo FULL_WEBSITE_URL.$get_result['custom_url']; ?>"
                                                                target="_blank">Our Mission</a></span>
                                                    <span><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <a
                                                                href="<?php echo FULL_WEBSITE_URL.$get_result['custom_url']; ?>"
                                                                target="_blank">Company Profile</a></span>
                                                    <span><i class="fa fa-cog" aria-hidden="true"></i> <a
                                                                href="<?php echo FULL_WEBSITE_URL."services".$get_result['custom_url']; ?>"
                                                                target="_blank">Our Services</a></span>
                                                </div>
                                                <?php
                                                if($keyword_data !='') {
                                                    ?>
                                                    <div class="cut-padding-left-5 cata-sub-nav">
                                                        <div class="nav-prev arrow" style="display: none;"><i
                                                                    class="fa fa-angle-left" aria-hidden="true"></i>
                                                        </div>

                                                        <ul class="tags">
                                                            <?php foreach ($keyword_data as $key) {
                                                                if ($key != "") {
                                                                    ?>
                                                                    <li>
                                                                        <a onclick="searchKey('<?php echo $key; ?>')"
                                                                           href="javascript:void(0)"><?php echo $key; ?></a>
                                                                    </li>

                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </ul>

                                                        <div class="nav-next arrow" style=""><i
                                                                    class="fa fa-angle-right"
                                                                    aria-hidden="true"></i>
                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>


                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                } else {
                    ?>
                    <div class="col-md-12 text-center">
                        <h3>No data found</h3>
                    </div>
                    <?php
                }
                ?>
                <div>

                    <ul class="pagination m-0">
                        <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

                        <li <?php if ($page_no <= 1) {
                            echo "class='disabled'";
                        } ?>>
                            <a <?php if ($page_no > 1) {
                                echo "href='?page_no=$previous_page'";
                            } ?>>Previous</a>
                        </li>

                        <?php
                        if ($total_no_of_pages <= 10) {
                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='active'><a>$counter</a></li>";
                                } else {
                                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                }
                            }
                        } elseif ($total_no_of_pages > 10) {

                            if ($page_no <= 4) {
                                for ($counter = 1; $counter < 8; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<li class='active'><a>$counter</a></li>";
                                    } else {
                                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                    }
                                }
                                echo "<li><a>...</a></li>";
                                echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                            } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                echo "<li><a href='?page_no=1'>1</a></li>";
                                echo "<li><a href='?page_no=2'>2</a></li>";
                                echo "<li><a>...</a></li>";
                                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<li class='active'><a>$counter</a></li>";
                                    } else {
                                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                    }
                                }
                                echo "<li><a>...</a></li>";
                                echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                            } else {
                                echo "<li><a href='?page_no=1'>1</a></li>";
                                echo "<li><a href='?page_no=2'>2</a></li>";
                                echo "<li><a>...</a></li>";

                                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<li class='active'><a>$counter</a></li>";
                                    } else {
                                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                    }
                                }
                            }
                        }
                        ?>

                        <li <?php if ($page_no >= $total_no_of_pages) {
                            echo "class='disabled'";
                        } ?>>
                            <a <?php if ($page_no < $total_no_of_pages) {
                                echo "href='?page_no=$next_page'";
                            } ?>>Next</a>
                        </li>
                        <?php if ($page_no < $total_no_of_pages) {
                            echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                        } ?>
                    </ul>
                    <div style="padding-left: 10px;">
                        <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>


<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    function searchKey(val) {
   /*     var city = $('.search_text_city').val();
        if (city.trim() != '') {
            window.location.href = 'search-profile.php?city=' + city + '&search=' + val;
        } else {*/
            window.location.href = 'search-profile.php?search=' + val;
        /*}*/
    }
</script>

<script>
    (function ($) {
        // console.log( 'init-scroll: ' + $(".nav-next").scrollLeft() );
        $(".nav-next").on("click", function () {
            $(this).closest('div.cata-sub-nav').animate({scrollLeft: '+=460'}, 200);
            $val = $(this).closest('div.cata-sub-nav').scrollLeft();
            if ($(this).closest('div.cata-sub-nav').scrollLeft() + $(this).closest('div.cata-sub-nav').innerWidth() >= $(this).closest('div.cata-sub-nav')[0].scrollWidth) {
                $(this).hide();
            } else {
                $(this).show();
            }

            if ($val == 0) {
                $('.nav-prev').hide();
            } else {
                $('.nav-prev').show();
            }

        });
        $(".nav-prev").on("click", function () {
            $(this).closest('div.cata-sub-nav').animate({scrollLeft: '-=460'}, 200)
            $val = $(this).closest('div.cata-sub-nav').scrollLeft();

            if ($(this).closest('div.cata-sub-nav').scrollLeft() + $(this).closest('div.cata-sub-nav').innerWidth() >= $(this).closest('div.cata-sub-nav')[0].scrollWidth) {
                $(this).hide();
            } else {
                $(this).show();
            }

            if ($val == 0) {
                $('.nav-next').hide();
            } else {
                $('.nav-next').show();
            }

        });

    })(jQuery);
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".js-select2").select2();
    });
</script>
</body>
</html>