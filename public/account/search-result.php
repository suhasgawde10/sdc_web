<?php

include "controller/ManageApp.php";
$manage = new ManageApp();


if(isset($_GET['search']) && (isset($_GET['city']))){
    if(isset($_GET['state'])){
        $state = $_GET['state'];
        $search = $_GET['search'];
        $city = $_GET['city'];

        $displayUser = $manage->displayUser($search,$city,$state);
        if ($displayUser != null) {
            $count = mysqli_num_rows($displayUser);
        } else {
            $count = 0;
        }
    }else{
        $state="";
        $search = $_GET['search'];
        $city = $_GET['city'];

        $displayUser = $manage->displayUser($search,$city,$state);
        if ($displayUser != null) {
            $count = mysqli_num_rows($displayUser);
        } else {
            $count = 0;
        }
    }
}else{
    header('location:index.php');
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
    <meta property="og:title" content="Digital Card"/>
    <meta property="og:url" content="https://www.example.com/webpage/"/>
    <meta property="og:description" content="description of your website/webpage">
    <meta property="og:image" content="//cdn.example.com/uploads/images/webpage_300x200.png">
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>

<!-- preloader area start -->
<div id="preloader">
    <div class="spinner"></div>
</div>
<!-- preloader area end -->
<!-- header area start -->

<?php include "assets/common-includes/header.php" ?>
<div class="inner-bannerr" id="bread">
    <div class="container cust-bred">
        <h2 class="my-lg-4 mb-2"> Software Developer</h2>

        <h3 class="my-lg-4 mb-2"><a href="index.php">Home</a> <span>/ Search</span> <span>/ <?php echo $_GET['search'] ?></span>
        </h3>
    </div>
</div>


<section class="search-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="contact-form cont-div-pad">
                        <h3>Search Filter</h3>
                        <form action="" method="get">
                            <label>State</label>
                            <select name="state" required="required">
                                <option value="">Select State</option>
                                <option value="Maharshtra">Maharshtra</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Punjab">Punjab</option>
                            </select>
                            <label>City</label>
                            <select name="city" required="required">
                                <option value="">Select City</option>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Pune">Pune</option>
                                <option value="Nashik">Nashik</option>
                            </select>
                            <input type="text" name="search" placeholder="Search.." required="required">
                            <button class="btn btn-default" type="submit"  id="search">search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="container-fluid">
                    <div class="col-md-12 search-count">
                        <h2>Search Result <span class="badge"><?php echo $count; ?></span></h2>

                    </div>
                    <?php
                    if ($displayUser != null) {
                        ?>
                        <ul class="search-ul">
                            <?php
                            while ($get_result = mysqli_fetch_array($displayUser)) {
                                ?>
                                <li>
                                    <div class="col-md-4 text-center ">
                                        <div>
                                            <img src="<?php echo "user/uploads/".$get_result['email']. "/profile/".$get_result['img_name'] ?>">
                                        </div>
                                        <a href="http://localhost/Digital_card/m/index.php?custom_url=<?php echo $get_result['custom_url']; ?>" target="_blank" class="btnn cust-btn blue circular">View Profile</a>
                                    </div>
                                    <div class="col-md-8">
                                        <h5><i class="fa fa-user" aria-hidden="true"></i><?php echo $get_result['name']; ?></h5>
                                        <h6><i class="fa fa-briefcase"></i><?php echo $get_result['designation']; ?></h6>
                                        <p><i class="fa fa-mobile" aria-hidden="true"></i> <?php echo $get_result['contact_no']; ?></p>
                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $get_result['address']; ?></p>
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

                </div>

            </div>

        </div>

    </div>

</section>


<!--<section>
    <div class="container-fluid">
        <div class="search-btm">
            <div class="contact-form-btm ">
                <form action="" method="post">
                    <input type="text" name="name" placeholder="Enter Your Name">
                    <input type="text" name="email" placeholder="Enter Your Email">
                    <input type="submit" value="Send" id="btm-seach">
                </form>
            </div>

        </div>

    </div>
</section>-->

<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>