<?php
error_reporting(0);
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}

$error = false;
$errorMessage = "";
include("session_includes.php");


if (isset($_POST['change_profile'])) {
    $profile_name = $_POST['change_profile'];
    $txt_profile = $manage->updateSectionProfile($profile_name);
    if ($txt_profile) {
        $_SESSION['menu']['s_profile'] = $profile_name;
        echo 'profile name updated successfully';
    } else {
        echo 'Profile name update failed';
    }
}
if (isset($_POST['change_service']) && $_POST['service_header']) {
    $change_service = $_POST['change_service'];
    $service_header = $_POST['service_header'];
    $update_service = $manage->updateSectionService($change_service, $service_header);
    if ($update_service) {
        $_SESSION['menu']['s_services'] = $change_service;
        $_SESSION['menu']['s_our_service'] = $service_header;
        echo 'Service updated successfully';
    } else {
        echo 'Service update failed';
    }
}
if (isset($_POST['change_product']) && $_POST['product_header']) {
    $change_product = $_POST['change_product'];
    $product_header = $_POST['product_header'];
    $update_product = $manage->updateSectionProduct($change_product, $product_header);
    if ($update_product) {
        $_SESSION['menu']['s_products'] = $change_product;
        $_SESSION['menu']['s_our_products'] = $product_header;
        echo 'Products updated successfully';
    } else {
        echo 'Products update failed';
    }
}

if (isset($_POST['change_gallery']) && (isset($_POST['images']) && (isset($_POST['videos'])))) {
    $gallery = $_POST['change_gallery'];
    $images = $_POST['images'];
    $videos = $_POST['videos'];
    $gallery_update = $manage->updateSectionGallery($gallery, $images, $videos);
    if ($gallery_update) {
        $_SESSION['menu']['s_gallery'] = $gallery;
        $_SESSION['menu']['s_images'] = $images;
        $_SESSION['menu']['s_videos'] = $videos;
        echo 'gallery updated successfully';
    } else {
        echo 'gallery update failed';
    }
}
if (isset($_POST['change_clients']) && (isset($_POST['client_name']) && (isset($_POST['client_review'])))) {
    $clients = $_POST['change_clients'];
    $client_name = $_POST['client_name'];
    $client_review = $_POST['client_review'];
    $update_clients = $manage->updateSectionClients($clients, $client_name, $client_review);
    if ($update_clients) {
        $_SESSION['menu']['s_clients'] = $clients;
        $_SESSION['menu']['s_client_name'] = $client_name;
        $_SESSION['menu']['s_client_review_tab'] = $client_review;
        echo 'Clients updated successfully';
    } else {
        echo 'Clients update failed';
    }
}
if (isset($_POST['change_team']) && (isset($_POST['our_team']))) {
    $team = $_POST['change_team'];
    $our_team = $_POST['our_team'];
    $update_team = $manage->updateSectionTeam($team, $our_team);
    if ($update_team) {
        $_SESSION['menu']['s_team'] = $team;
        $_SESSION['menu']['s_our_team'] = $our_team;
        echo 'Our team updated successfully';
    } else {
        echo 'Our team update failed';
    }
}
if (isset($_POST['change_bank']) && $_POST['payment']) {
    $bank = $_POST['change_bank'];
    $payment = $_POST['payment'];
    $UpdateBank = $manage->updateSectionBank($bank, $payment);
    if ($UpdateBank) {
        $_SESSION['menu']['s_bank'] = $bank;
        $_SESSION['menu']['s_payment'] = $payment;
        echo 'Bank updated successfully';
    } else {
        echo 'Bank update failed';
    }
}

if (isset($_POST['change_basic_info']) && $_POST['company_info']) {
    $basic_info = $_POST['change_basic_info'];
    $company_info = $_POST['company_info'];
    $UpdateBank = $manage->updateSectionNavbar($basic_info, $company_info);
    if ($UpdateBank) {
        $_SESSION['menu']['s_basic_info'] = $basic_info;
        $_SESSION['menu']['s_company_info'] = $company_info;
        echo 'Navbar updated successfully';
    } else {
        echo 'Navbar update failed';
    }
}

if (isset($_POST['profile_toggle']) && isset($_POST['mode'])) {
    $profile_status = $_POST['profile_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(10, $profile_status, $profile_status);
    if ($txt_profile_staus == 1) {
        echo 'profile status updated successfully';
    } else {
        echo 'profile status updated successfully';
    }
}

if (isset($_POST['service_toggle']) && isset($_POST['mode'])) {
    $service_status = $_POST['service_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(1, $service_status, $service_status);
    if ($txt_profile_staus == 1) {
        echo 'service status updated successfully';
    } else {
        echo 'service status updated successfully';
    }
}

if (isset($_POST['product_toggle']) && isset($_POST['mode'])) {
    $product_status = $_POST['product_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(11, $product_status, $product_status);
    if ($txt_profile_staus == 1) {
        echo 'Product status updated successfully';
    } else {
        echo 'service status updated successfully';
    }
}

if (isset($_POST['gellery_toggle']) && isset($_POST['mode'])) {
    $gallery_status = $_POST['gellery_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(2, $gallery_status, $gallery_status);
    if ($txt_profile_staus == 1) {
        echo 'gellery status updated successfully';
    } else {
        echo 'gellery status updated successfully';
    }
}
if (isset($_POST['client_toggle']) && isset($_POST['mode'])) {
    $client_status = $_POST['client_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(4, $client_status, $client_status);
    if ($txt_profile_staus == 1) {
        echo 'Client status updated successfully';
    } else {
        echo 'Client status updated successfully';
    }
}

if (isset($_POST['team_toggle']) && isset($_POST['mode'])) {
    $team_status = $_POST['team_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(6, $team_status, $team_status);
    if ($txt_profile_staus == 1) {
        echo 'Team status updated successfully';
    } else {
        echo 'Team status updated successfully';
    }
}

if (isset($_POST['bank_toggle']) && isset($_POST['mode'])) {
    $bank_status = $_POST['bank_toggle'];
    $status = $_POST['mode'];
    $txt_profile_staus = $manage->updateSectionStatus(7, $bank_status, $bank_status);
    if ($txt_profile_staus == 1) {
        echo 'Bank status updated successfully';
    } else {
        echo 'Bank status updated successfully';
    }
}
?>