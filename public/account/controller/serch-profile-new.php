<?php

include "controller/ManageApp.php";
$manage = new ManageApp();


if(isset($_GET['search']) && (isset($_GET['city']))){
    if(isset($_GET['state'])){
        $state = $_GET['state'];
        $search = $_GET['search'];
        $city = $_GET['city'];
    }else{
        $state="";
        $search = $_GET['search'];
        $city = $_GET['city'];

    }
}else{
    $state="";
    $search = "";
    $city = "";
}


$displayUser = $manage->displayUser($search,$city,$state);
if ($displayUser != null) {
    $count = mysqli_num_rows($displayUser);
} else {
    $count = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Digital Card</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSS -->
    <meta property="og:title" content="Digital Card" />
    <meta property="og:url" content="https://www.example.com/webpage/" />
    <meta property="og:description" content="description of your website/webpage">
    <meta property="og:image" content="//cdn.example.com/uploads/images/webpage_300x200.png">

    <?php include "assets/common-includes/header_includes.php" ?>
</head>

<body>

<!-- header area start -->

<?php include "assets/common-includes/header.php" ?>
<div class="inner-bannerr" id="bread">
    <div class="container cust-bred">
        <?php
        if(isset($_GET['city']) && $_GET['city'] !="") {
            ?>
            <h2 class="my-lg-4 mb-2"> <?php echo $_GET['city'] ?></h2>
            <h3 class="my-lg-4 mb-2"><a href="index.php">Home</a> <span>/ Search</span>
                <span>/ <?php echo $_GET['search'] ?></span></h3>
        <?php
        }else{
            echo '<h2 class="my-lg-4 mb-2 mt-10"> Home </h2>';
        }
        ?>
    </div>
</div>


<section class="search-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12 ">
                <div class="contact-form cont-div-pad">
                        <h3>Search Filter</h3>
                    <form action="" method="get">
                        <label>State</label>
                        <input type="text" name="state" placeholder="Enter State Name" required="required">
                        <label>City</label>
                            <input type="text" name="city" placeholder="Enter City Name" required="required">
                        <input type="text" name="search" placeholder="Search.." required="required">
                        <button class="btn btn-default" type="submit"  id="search">search</button>
                    </form>
                    </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12 search-border">
                    <div class="col-md-12 search-count">
                        <h2>Search Result <span class="badge"><?php echo $count; ?></span></h2>

                    </div>

                <?php
                if ($displayUser != null) {
                ?>
                    <ul class="search-ul" >
                        <?php

                        while ($get_result = mysqli_fetch_array($displayUser)) {
                        $website = $get_result['website_url'];
                        $linked_in = $get_result['linked_in'];
                        $youtube = $get_result['youtube'];
                        $facebook = $get_result['facebook'];
                        $twitter = $get_result['twitter'];
                        $instagram = $get_result['instagram'];
                        $gender = $get_result['gender'];
                        $profilePath = "user/uploads/".$get_result['email']. "/profile/".$get_result['img_name'];
                        ?>
                        <li>
                            <div class="row border-btm">
                                <div class=" col-md-3 col-sm-4 col-xs-4">
                                </div>


                                <div class=" col-md-5 col-sm-5 col-xs-5 padding_right">
                                    <h5><i class="fa fa-user" aria-hidden="true"></i><?php echo $get_result['name']; ?></h5>
                                    <h6><i class="fa fa-briefcase"></i><?php echo $get_result['designation']; ?></h6>
                                </div>


                                <div class="padding_zero col-md-4 col-sm-3 col-xs-3" >
                                    <a href="http://sharedigitalcard.com/m/index.php?custom_url=<?php echo $get_result['custom_url']; ?>" target="_blank" class="btnn cust-btnn blue circular">View Profile</a>
                                </div>

                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-3 col-sm-3 col-xs-3 cust-pos-img">

                                    <img class="img-circle" src="<?php if (!file_exists($profilePath) && $gender == "Male" or $get_result['img_name']=="") {
                                        echo "user/uploads/male_user.png";
                                    } elseif (!file_exists($profilePath) && $gender == "Female" or $get_result['img_name']=="") {
                                        echo "user/uploads/female_user.png";
                                    } else {
                                        echo $profilePath;
                                    } ?>">
                                    <ul class="social">
                                       <!-- <li><a href="#" target="_blank"><i class="fa fa-facebook-square color-fb"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fa fa-instagram color-insta"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fa fa-youtube-square color-youtube"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>-->

                                        <li><a <?php if ($youtube == "") {
                                                echo "style='cursor: not-allowed;'" . "target='_self'";
                                            } else {
                                                echo "target='_blank'";
                                            } ?> href="<?php if (isset($youtube) && ($youtube) != "") {
                                                echo $youtube;
                                            } else {
                                                echo "#";
                                            } ?>"><i class="fa fa-youtube-square color-youtube"></i></a>
                                        </li>
                                        <li><a <?php if ($facebook == "") {
                                                echo "style='cursor: not-allowed;'" . "target='_self'";
                                            } else {
                                                echo "target='_blank'";
                                            } ?> href="<?php if (isset($facebook) && ($facebook) != "") {
                                                echo $facebook;
                                            } else {
                                                echo "#";
                                            } ?>" class="facebook"><i class="fa fa-facebook-square color-fb"></i></a>
                                        </li>
                                        <!--<li><a <?php /*if ($twitter == "") {
                                                echo "style='cursor: not-allowed;'" . "target='_self'";
                                            } else {
                                                echo "target='_blank'";
                                            } */?> href="<?php /*if (isset($twitter) && ($twitter) != "") {
                                                echo $twitter;
                                            } else {
                                                echo "#";
                                            } */?>" class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                        </li>-->
                                        <li><a <?php if ($instagram == "") {
                                                echo "style='cursor: not-allowed;'" . "target='_self'";
                                            } else {
                                                echo "target='_blank'";
                                            } ?> href="<?php if (isset($instagram) && ($instagram) != "") {
                                                echo $instagram;
                                            } else {
                                                echo "#";
                                            } ?>" class="instagram"><i class="fa fa-instagram color-insta"></i></a>
                                        </li>
                                        <!--<li><a <?php /*if ($linked_in == "") {
                                                echo "style='cursor: not-allowed;'" . "target='_self'";
                                            } else {
                                                echo "target='_blank'";
                                            } */?> href="<?php /*if (isset($linked_in) && ($linked_in) != "") {
                                                echo $linked_in;
                                            } else {
                                                echo "#";
                                            } */?>" class="linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>-->
                                    </ul>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 ">
                                    <p><i class="fa fa-mobile" aria-hidden="true"></i> <?php echo $get_result['contact_no']; ?></p>
                                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $get_result['address']; ?></p>
                                </div>
                            </div>
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


            </div>

        </div>

    </div>

</section>


<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>