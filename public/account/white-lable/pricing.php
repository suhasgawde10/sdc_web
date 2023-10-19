<?php
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
include "controller/config data.php";

include "common-file.php";

$getAllPlan = $manage->getAllPriceByDealerId($manage->planTable, $id);
//$getAllPlan ="";
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create digital card - <?php echo strtoupper($company_name); ?></title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <?php include "white-lable/assets/common-includes/header-includes.php"; ?>

</head>
<body>
<?php include "white-lable/assets/common-includes/header.php"; ?>
<section class="breadcrumbs" style="">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Pricing & plan</h2>
            <ol>
                <li><a href="index.php">Home</a></li>
                <li>Pricing & plan</li>
            </ol>
        </div>

    </div>
</section>
<section id="" class="pricing">
    <div class="container">
        <div class="row">
            <ul class="price-data">
                <?php
                if ($getAllPlan != "") {
                    while ($plan_price = mysqli_fetch_array($getAllPlan)) { ?>
                        <li class="price-data-li">
                            <div class="col-lg-12 col-md-12 mt-4 mt-md-0 p-0">
                                <div class="box featured">
                                    <h3><?php echo $plan_price['plan_name'] ?></h3>
                                    <h4>₹<?php echo $plan_price['price_price'] ?></h4>

                                    <div class="btn-wrap">
                                        <ul class="pricing-btn-ul">
                                            <li <?php if ($plan_price['payment_link'] == "") {
                                                echo ' style="width: 100%;text-align: center;" ';
                                            } ?> >
                                                <a href="registration.php" target="_blank" class="btn-buy"
                                                   style="padding:8px 8px 8px 8px;">Get Started</a>
                                            </li>
                                            <?php if ($plan_price['payment_link'] != "") {
                                                ?>
                                                <li>
                                                    <a href="<?php echo $plan_price['payment_link'] ?>" target="_blank"
                                                       name="buy_now" class="btn-buy-payment"
                                                       style="margin-top:10px;padding:8px 15px 8px 15px;">Buy now</a>
                                                </li>
                                            <?php
                                            } ?>
                                        </ul>
                                    </div>
                                    <br>
                                    <br>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                        <li><i class="fa fa-check"></i>Customize Menu</li>
                                        <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                        <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                        <li><i class="fa fa-check"></i>Create Employee cards</li>
                                        <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                        <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                        <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                <?php
                } else {
                ?>
                <li>
                    <center><h3>No Plans Found</h3></center>
                </li>
                <?php 
                }
                ?>

                <?php /*else {  */ ?>

                    <!-- <li class="price-data-li">
                        <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                            <div class="box featured">
                                <h3>1 years)</h3>
                                <h4>₹1499 </h4>

                                <div class="btn-wrap">
                                    <a href="registration.php" class="btn-buy">Get Started</a>
                                </div>
                                <br>
                                <br>
                                <ul>
                                    <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                    <li><i class="fa fa-check"></i>Customize Menu</li>
                                    <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                    <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                    <li><i class="fa fa-check"></i>Create Employee cards</li>
                                    <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                    <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                    <li><i class="fa fa-check"></i>Share via any application</li>
                                    </ul>
                            </div>
                        </div>
                    </li>
                    <li class="price-data-li">
                        <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                            <div class="box featured">
                                <h3>3 years (2 + 1 years FREE)</h3>
                                <h4>₹2999 </h4>

                                <div class="btn-wrap">
                                    <a href="registration.php" class="btn-buy">Get Started</a>
                                </div>
                                <br>
                                <br>
                                <ul>
                                    <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                    <li><i class="fa fa-check"></i>Customize Menu</li>
                                    <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                    <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                    <li><i class="fa fa-check"></i>Create Employee cards</li>
                                    <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                    <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                    <li><i class="fa fa-check"></i>Share via any application</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="price-data-li">
                        <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                            <div class="box featured">
                                <h3>5 years (3 + 2 years FREE)</h3>
                                <h4>₹4499 </h4>

                                <div class="btn-wrap">
                                    <a href="registration.php" class="btn-buy">Get Started</a>
                                </div>
                                <br>
                                <br>
                                <ul>
                                    <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                    <li><i class="fa fa-check"></i>Customize Menu</li>
                                    <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                    <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                    <li><i class="fa fa-check"></i>Create Employee cards</li>
                                    <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                    <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                    <li><i class="fa fa-check"></i>Share via any application</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="price-data-li">
                        <div class="col-lg-12 col-md-12 mt-4 mt-md-0">
                            <div class="box featured">
                                <h3>Life Time</h3>
                                <h4>₹7999 </h4>

                                <div class="btn-wrap">
                                    <a href="registration.php" class="btn-buy">Get Started</a>
                                </div>
                                <br>
                                <br>
                                <ul>
                                    <li><i class="fa fa-check"></i>Customize Theme Options</li>
                                    <li><i class="fa fa-check"></i>Customize Menu</li>
                                    <li><i class="fa fa-check"></i>Product & Services with Buy Now option</li>
                                    <li><i class="fa fa-check"></i>Easy To transfer amount</li>
                                    <li><i class="fa fa-check"></i>Create Employee cards</li>
                                    <li><i class="fa fa-check"></i>Lead Generation Panel</li>
                                    <li><i class="fa fa-check"></i>Visitors Statistics</li>
                                    <li><i class="fa fa-check"></i>Share via any application</li>
                                </ul>
                            </div>
                        </div>
                    </li> -->


                <?php /*} */
                ?>
                
            </ul>

        </div>
    </div>
</section>
<?php include "white-lable/assets/common-includes/footer.php"; ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i>
</a>

<div id="preloader">
    <div class="loder-img">
    </div>
</div>

<?php
include "white-lable/assets/common-includes/footer-includes.php";
?>
</body>
</html>
