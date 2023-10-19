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

?>


<!DOCTYPE html>
<html>
<head>
    <title>Create digital card - <?php echo strtoupper($company_name); ?></title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="DGINDIA" name="keywords">
    <!-- Favicons -->
    <!-- Google Fonts -->
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
            <h2>About Us</h2>
            <ol>
                <li><a href="index.php">Home</a></li>
                <li>About Us</li>
            </ol>
        </div>

    </div>
</section>
<section id="" class="about">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
                <?php if (isset($about_desc) && $about_desc != "") {
                    echo $about_desc;
                } else {
                    echo ABOUT_DESC;
                } ?>
            </div>
        </div>

    </div>
</section>
<?php include "white-lable/assets/common-includes/footer.php"; ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<div id="preloader">
    <div class="loder-img">
    </div>
</div>

<?php
include "white-lable/assets/common-includes/footer-includes.php";
?>
</body>
</html>
