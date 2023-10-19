<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
if (isset($_GET['token']) && isset($_GET['type']) && $_GET['type'] == "android") {
    $token = $security->decryptWebservice($_GET['token']);
    $seperate_token = explode('+', $token);
    $validateUserId = $manage->validAPIKEYId($seperate_token[0], $seperate_token[1]);
    if ($validateUserId) {
        $userSpecificResult = $manage->getUserProfile($seperate_token[0]);
        if ($userSpecificResult != null) {
            $android_name = $userSpecificResult["name"];
            $android_email = $userSpecificResult["email"];
            $android_custom_url = $userSpecificResult["custom_url"];
            $android_contact = $userSpecificResult['contact_no'];
            $android_type = $userSpecificResult['type'];
        }
        $_SESSION['type'] = $android_type;
        $_SESSION['email'] = $android_email;
        $_SESSION['name'] = $android_name;
        $_SESSION['contact'] = $android_contact;
        $_SESSION['custom_url'] = $android_custom_url;
        $_SESSION['id'] = $security->encrypt($seperate_token[0]);
    } else {
        header('location:404-not-found.php?' . $android_url);
    }
} elseif (!isset($_SESSION['email'])) {
    header('location:../login.php');
} else {
    $android_url = "";
}
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}

$error = false;
$errorMessage = "";
include("session_includes.php");
include "validate-page.php";

if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Please enter service name.<br>";
    }
    if (isset($_POST['txt_des']) && $_POST['txt_des'] != "") {
        $description = mysqli_real_escape_string($con, $_POST['txt_des']);
    } else {
        $error = true;
        $errorMessage .= "Please enter description.<br>";
    }


    if (!$error) {

        // $status = $manage->updateService($name, $description, $cover_name, $security->decrypt($_GET['display_data']));
        /* if ($status) {
             $errorMessage = "details Updated successfully";
             header('location:manage-section.php');
         } else {
             $error = true;
             $errorMessage = "Issue while updating details, Please try again.";
         }*/


    }

}


$get_section = $manage->getSectionName();
if ($get_section != null) {
    $profile = $get_section['profile'];
    $services = $get_section['services'];
    $our_service = $get_section['our_service'];
    $product = $get_section['products'];
    $our_product = $get_section['our_product'];
    $gallery = $get_section['gallery'];
    $images = $get_section['images'];
    $videos = $get_section['videos'];
    $clients = $get_section['clients'];
    $client_name = $get_section['client_name'];
    $client_review_tab = $get_section['client_review'];
    $team = $get_section['team'];
    $our_team = $get_section['our_team'];
    $bank = $get_section['bank'];
    $payment = $get_section['payment'];
    $basic_info = $get_section['basic_info'];
    $company_info = $get_section['company_info'];
} else {
    $profile = "Profile";
    $services = "Services";
    $our_service = "Our Services";
    $product = "Products";
    $our_product = "Our Products";
    $gallery = "Gallery";
    $images = "Images";
    $videos = "Videos";
    $clients = "Clients";
    $client_name = "Clients";
    $client_review_tab = "Client's Reviews";
    $team = "Team";
    $our_team = "Our Team";
    $bank = "Bank";
    $payment = "Payment";
    $basic_info = "Basic Info";
    $company_info = "Company Info";
}

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Manage Section</title>
    <style>
        #profile-message {
            display: none;
        }

        #profile-message-validate {
            display: none;
        }

        #service-message {
            display: none;
        }

        #service-message-validate {
            display: none;
        }

        #product-message {
            display: none;
        }

        #product-message-validate {
            display: none;
        }

        #gallery-message {
            display: none;
        }

        #gallery-message-validate {
            display: none;
        }

        #client-message {
            display: none;
        }

        #client-message-validate {
            display: none;
        }

        #team-message {
            display: none;
        }

        #team-message-validate {
            display: none;
        }

        #bank-message {
            display: none;
        }

        #bank-message-validate {
            display: none;
        }

        #navbar-message {
            display: none;
        }

        #navbar-message-validate {
            display: none;
        }

        .form-label {
            color: #666;
        }
    </style>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .d-block-imp {
            display: block !important;
        }
    </style>
</head>
<body>
<?php
if (!isset($_GET['token']) && (!isset($_GET['type']))) {
?>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">
<?php
if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
    include "assets/common-includes/session_button_includes.php";
}
?>
<?php include "assets/common-includes/preview.php" ?>
<?php
}elseif (isset($_GET['token']) && (isset($_GET['type']) && $_GET['type'] == "android")) {
?>
<section class="androidSection">
<?php
}
?>

<?php
if (!isset($_GET['token']) && !isset($_GET['type'])) {
?>
<div class="clearfix">

<div class="update_menu_heading">
    <h2>UPDATE MENU BAR</h2>
</div>
<?php
} else {
    echo '   <div class="row">';
}
?>
<div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 bhoechie-tab-container">
<div class="col-lg-4 col-md-3 col-sm-4 col-xs-3 bhoechie-tab-menu">
    <div class="list-group menu_bar_img">
        <a href="#" class="list-group-item active">
            <img src="assets/images/user1.png">
                        <span class="list_group_title">
                        <?php echo $profile; ?></span>
        </a>
        <a href="#" class="list-group-item">
            <img src="assets/images/clipboard.png">
            <span class="list_group_title"><?php echo $services; ?></span>
        </a>
        <a href="#" class="list-group-item">
            <img src="assets/images/clipboard.png">
            <span class="list_group_title"><?php echo $our_product; ?></span>
        </a>
        <a href="#" class="list-group-item">
            <img src="assets/images/gallery.png">
                        <span class="list_group_title">
                        <?php echo $gallery; ?></span>
        </a>
        <a href="#" class="list-group-item">
            <img src="assets/images/review.png">
            <span class="list_group_title"><?php echo $clients ?></span>
        </a>
        <a href="#" class="list-group-item">
            <img src="assets/images/teamwork.png">
                        <span class="list_group_title">
                        <?php echo $team; ?></span>
        </a>
        <a href="#" class="list-group-item">
            <img src="assets/images/point-of-service.png">
                        <span class="list_group_title">
                        <?php echo $bank; ?></span>
        </a>
    </div>
</div>
<form id="section_form" method="post" action="">
<div class="col-lg-8 col-md-8 col-sm-9 col-xs-9 bhoechie-tab">
<!-- flight section -->
<div class="bhoechie-tab-content active">
    <div id="profile-message" class="alert alert-success">
    </div>
    <div id="profile-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12 mb-20">
        <div class="row">
            <label class="form-label">Menu Name</label>

            <div class="">
                <input name="txt_name" id="txt_profile" class="form-control"
                       value="<?php if (isset($profile)) echo $profile; ?>">
            </div>
            <div class="menu_update_button">
                <button class="btn btn-primary d-block-imp" type="button" onclick="updateProfile()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div id="navbar-message" class="alert alert-success">
            </div>
            <div id="navbar-message-validate" class="alert alert-danger">
            </div>
            <label class="form-label">Nav Bar Name</label>

            <div class="mb-10">
                <input name="txt_name" id="txt_basic" class="form-control" placeholder="basic info"
                       value="<?php if (isset($basic_info)) echo $basic_info; ?>">
            </div>
            <div class="">
                <input name="txt_name" id="txt_company" class="form-control" placeholder="company name"
                       value="<?php if (isset($company_info)) echo $company_info; ?>">
            </div>
            <div class="menu_update_button">
                <button class="btn btn-primary" type="button" onclick="updateNavBar()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>
    <!--  <div class="col-md-6">
          <img src="assets/images/menu_profile.JPG">
      </div>-->
</div>
<!-- train section -->
<div class="bhoechie-tab-content">
    <div id="service-message" class="alert alert-success">
    </div>
    <div id="service-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12">
        <div class="row">
            <label class="form-label">Menu Name</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="service_name"
                       class="form-control"
                       value="<?php if (isset($services)) echo $services; ?>">
            </div>


            <label class="form-label">Header Name (Service)</label>


            <input name="txt_name" id="service_header"
                   class="form-control"
                   value="<?php if (isset($our_service)) echo $our_service; ?>">

            <div class="menu_update_button">
                <button class="btn btn-primary" type="button" onclick="updateService()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>

        </div>

    </div>
    <!--<div class="col-md-6">

    </div>-->


</div>


<div class="bhoechie-tab-content">
    <div id="product-message" class="alert alert-success">
    </div>
    <div id="product-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12">
        <div class="row">
            <label class="form-label">Menu Name</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="product_name"
                       class="form-control"
                       value="<?php if (isset($product)) echo $product;  ?>">
            </div>


            <label class="form-label">Header Name (Product)</label>


            <input name="txt_name" id="product_header"
                   class="form-control"
                   value="<?php if (isset($our_product)) echo $our_product; ?>">

            <div class="menu_update_button">
                <button class="btn btn-primary" type="button" onclick="updateProduct()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>

        </div>

    </div>

</div>

<!-- hotel search -->
<div class="bhoechie-tab-content">
    <div id="gallery-message" class="alert alert-success">
    </div>
    <div id="gallery-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12">
        <div class="row">

            <label class="form-label">Menu Name</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="txt_gallery" class="form-control"
                       value="<?php if (isset($gallery)) echo $gallery; ?>">
            </div>


            <label class="form-label">Header Name (images)</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="txt_images" class="form-control"
                       value="<?php if (isset($images)) echo $images; ?>">
            </div>


            <label class="form-label">Header Name (videos)</label>
            <input name="txt_name" id="videos" class="form-control"
                   value="<?php if (isset($videos)) echo $videos; ?>">

            <div class="menu_update_button">
                <button class="btn btn-primary" type="button" onclick="updateGallery()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>


        </div>
    </div>
    <!--<div class="col-md-6">

    </div>-->
</div>
<div class="bhoechie-tab-content">

    <div id="client-message" class="alert alert-success">
    </div>
    <div id="client-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12">
        <div class="row">
            <label class="form-label">Menu Name</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="txt_clients" class="form-control"
                       value="<?php if (isset($clients)) echo $clients; ?>">
            </div>

            <label class="form-label">Header Name (Client)</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="client_name" class="form-control"
                       value="<?php if (isset($client_name)) echo $client_name; ?>">
            </div>
            <label class="form-label">Header Name (Client's review)</label>
            <input name="txt_name" id="client_review"
                   class="form-control"
                   value="<?php if (isset($client_review_tab)) echo $client_review_tab; ?>">

            <div class="menu_update_button">
                <button class="btn btn-primary" onclick="updateClients()" type="button"><i
                        class="far fa-save"></i> Update
                </button>
            </div>

        </div>
    </div>


    <!--<div class="col-md-6">

    </div>-->
</div>
<div class="bhoechie-tab-content">
    <div id="team-message" class="alert alert-success">
    </div>
    <div id="team-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12">
        <div class="row">
            <label class="form-label">Menu Name</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="team" class="form-control"
                       value="<?php if (isset($team)) echo $team; ?>">
            </div>
            <label class="form-label">Header Name (Our Team)</label>
            <input name="txt_name" id="our_team" class="form-control"
                   value="<?php if (isset($our_team)) echo $our_team; ?>">

            <div class="menu_update_button">
                <button class="btn btn-primary" type="button" onclick="updateTeam()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-6">

     </div>-->
</div>
<div class="bhoechie-tab-content">
    <div id="bank-message" class="alert alert-success">
    </div>
    <div id="bank-message-validate" class="alert alert-danger">
    </div>
    <div class="col-md-12">
        <div class="row">
            <label class="form-label">Menu Name</label>

            <div class="menu_input_margin">
                <input name="txt_name" id="bank" class="form-control"
                       value="<?php if (isset($bank)) echo $bank; ?>">
            </div>
            <label class="form-label">Header Name (Payment)</label>
            <input name="txt_name" id="payment" class="form-control"
                   value="<?php if (isset($payment)) echo $payment; ?>">

            <div class="menu_update_button">
                <button class="btn btn-primary" type="button" onclick="updateBank()"><i
                        class="far fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>
    <!--<div class="col-md-6">

    </div>-->
</div>

</div>
</form>
</div>
<div class="col-md-5 hidden-sm hidden-xs">
    <img src="assets/images/menu.png" style="width: 100%">
</div>
</div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $(document).ready(function () {
        $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $(".list-group,.bhoechie-tab").sortable({
            placeholder: "ui-state-highlight",
            update: function (event, ui) {
                var page_id_array = new Array();
                $('.list-group a.list-group-item').each(function () {
                    page_id_array.push($(this).attr("id"));
                });
            }
        });

    });
</script>

<script>
    function updateProfile() {
        var profile = $('#txt_profile').val();
        if (profile.trim() == '') {
            $("#profile-message-validate").css({"display": "block"});
            $("#profile-message-validate").html('please enter some value');
        }
        else if (profile.length >= 15) {
            $("#profile-message-validate").css({"display": "block"});
            $("#profile-message-validate").html('length should be less than 10');
        }
        else {
            var dataString = "change_profile=" + encodeURIComponent(profile);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#profile-message-validate").css({"display": "none"});
                    $("#profile-message").css({"display": "block"});
                    $("#profile-message").html(html);
                    $(".list-group a.active .list_group_title").html(profile);

                }
            });
        }

    }
</script>
<script>
    function updateService() {
        var service_name = $('#service_name').val();
        var service_header = $('#service_header').val();
        if (service_name.trim() == '' || service_header.trim() == '') {
            $("#service-message-validate").css({"display": "block"});
            $("#service-message-validate").html('please enter some value');
        } else if (service_name.length >= 15) {
            $("#service-message-validate").css({"display": "block"});
            $("#service-message-validate").html('service name length should be 10 or less than');
        } else if (service_header.length >= 21) {
            $("#service-message-validate").css({"display": "block"});
            $("#service-message-validate").html('header length should be less than 20 or less than');
        } else {
            var dataString = "change_service=" + encodeURIComponent(service_name) + "&service_header=" + encodeURIComponent(service_header);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#service-message-validate").css({"display": "none"});
                    $("#service-message").css({"display": "block"});
                    $("#service-message").html(html);
                    $(".list-group a.active .list_group_title").html(service_name);
                }
            });
        }

    }


    function updateProduct() {
        var product_name = $('#product_name').val();
        var product_header = $('#product_header').val();
        if (product_name.trim() == '' || product_header.trim() == '') {
            $("#product-message-validate").css({"display": "block"});
            $("#product-message-validate").html('please enter some value');
        } else if (product_name.length >= 15) {
            $("#product-message-validate").css({"display": "block"});
            $("#product-message-validate").html('product name length should be 10 or less than');
        } else if (product_header.length >= 21) {
            $("#product-message-validate").css({"display": "block"});
            $("#product-message-validate").html('header length should be less than 20 or less than');
        } else {
            var dataString = "change_product=" + encodeURIComponent(product_name) + "&product_header=" + encodeURIComponent(product_header);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#product-message-validate").css({"display": "none"});
                    $("#product-message").css({"display": "block"});
                    $("#product-message").html(html);
                    $(".list-group a.active .list_group_title").html(product_name);
                }
            });
        }

    }
</script>
<script>
    function updateGallery() {
        var gallery = $('#txt_gallery').val();
        var images = $('#txt_images').val();
        var videos = $('#videos').val();
        if (gallery.trim() == '' || images.trim() == '' || videos.trim() == '') {
            $("#gallery-message-validate").css({"display": "block"});
            $("#gallery-message-validate").html('please enter some value');
        } else if (gallery.length >= 15) {
            $("#gallery-message-validate").css({"display": "block"});
            $("#gallery-message-validate").html('gallery length should be less than 10');
        } else if (images.length >= 21 || videos.length >= 21) {
            $("#gallery-message-validate").css({"display": "block"});
            $("#gallery-message-validate").html('Image and Video Length should be less than 20');
        } else {
            var dataString = "change_gallery=" + encodeURIComponent(gallery) + "&images=" + encodeURIComponent(images) + "&videos=" + encodeURIComponent(videos);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#gallery-message-validate").css({"display": "none"});
                    $("#gallery-message").css({"display": "block"});
                    $("#gallery-message").html(html);
                    $(".list-group a.active .list_group_title").html(gallery);
                }
            });
        }
    }
</script>
<script>
    function updateClients() {
        var clients = $('#txt_clients').val();
        var client_name = $('#client_name').val();
        var client_review = $('#client_review').val();

        if (clients.trim() == '' || client_name.trim() == '' || client_review.trim() == '') {
            $("#client-message-validate").css({"display": "block"});
            $("#client-message-validate").html('please enter some value');
        } else if (clients.length >= 15) {
            $("#client-message-validate").css({"display": "block"});
            $("#client-message-validate").html('Client length should be 10 or less than');
        } else if (client_name.length >= 21 || client_review.length >= 21) {
            $("#client-message-validate").css({"display": "block"});
            $("#client-message-validate").html('client name and client review length should be 20 or less than');
        } else {
            var dataString = "change_clients=" + encodeURIComponent(clients) + "&client_name=" + encodeURIComponent(client_name) + "&client_review=" + encodeURIComponent(client_review);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#client-message-validate").css({"display": "none"});
                    $("#client-message").css({"display": "block"});
                    $("#client-message").html(html);
                    $(".list-group a.active .list_group_title").html(clients);
                }
            });
        }


    }
</script>
<script>
    function updateTeam() {
        var team = $('#team').val();
        var our_team = $('#our_team').val();

        if (team.trim() == '' || our_team.trim() == '') {
            $("#team-message-validate").css({"display": "block"});
            $("#team-message-validate").html('Enter some value');
        } else if (team.length >= 15) {
            $("#team-message-validate").css({"display": "block"});
            $("#team-message-validate").html('team length should be 10 or less than 10');
        } else if (our_team.length >= 21) {
            $("#team-message-validate").css({"display": "block"});
            $("#team-message-validate").html('our team length should be less than 20 ');
        } else {
            var dataString = "change_team=" + encodeURIComponent(team) + "&our_team=" + encodeURIComponent(our_team);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#team-message-validate").css({"display": "none"});
                    $("#team-message").css({"display": "block"});
                    $("#team-message").html(html);
                    $(".list-group a.active .list_group_title").html(team);

                }
            });
        }


    }
</script>
<script>
    function updateBank() {
        var bank = $('#bank').val();
        var payment = $('#payment').val();

        if (bank.trim() == '' || payment.trim() == '') {
            $("#bank-message-validate").css({"display": "block"});
            $("#bank-message-validate").html('Enter some value');
        } else if (bank.length >= 15) {
            $("#bank-message-validate").css({"display": "block"});
            $("#bank-message-validate").html('bank length should be less than 10');
        } else if (payment.length >= 21) {
            $("#bank-message-validate").css({"display": "block"});
            $("#bank-message-validate").html('payment length should be or less than 20');
        } else {
            var dataString = "change_bank=" + encodeURIComponent(bank) + "&payment=" + encodeURIComponent(payment);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#bank-message-validate").css({"display": "none"});
                    $("#bank-message").css({"display": "block"});
                    $("#bank-message").html(html);
                    $(".list-group a.active .list_group_title").html(bank);

                }
            });
        }
    }
</script>
<script>
    function updateNavBar() {
        var basic_info = $('#txt_basic').val();
        var company_info = $('#txt_company').val();

        if (basic_info.trim() == '' || company_info.trim() == '') {
            $("#navbar-message-validate").css({"display": "block"});
            $("#navbar-message-validate").html('Enter some value ');
        } else if (basic_info.length >= 15) {
            $("#navbar-message-validate").css({"display": "block"});
            $("#navbar-message-validate").html('bank length should be less than 10');
        } else if (company_info.length >= 20) {
            $("#navbar-message-validate").css({"display": "block"});
            $("#navbar-message-validate").html('payment length should be or less than 20');
        } else {
            var dataString = "change_basic_info=" + encodeURIComponent(basic_info) + "&company_info=" + encodeURIComponent(company_info);
            $.ajax({
                type: "POST",
                url: "manage_section_ajax.php",
                data: dataString,
                success: function (html) {
                    $("#navbar-message-validate").css({"display": "none"});
                    $("#navbar-message").css({"display": "block"});
                    $("#navbar-message").html(html);

                }
            });
        }
    }
</script>
</body>
</html>