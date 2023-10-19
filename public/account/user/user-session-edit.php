<?php
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (isset($_GET['id']) && (isset($_GET['name'])) && (isset($_GET['email'])) && (isset($_GET['contact'])) && (isset($_GET['custom_url']))) {

    $get_id = $_GET['id'];
    $email = $_GET['email'];
    $name = $_GET['name'];
    $contact = $_GET['contact'];
    $custom_url = $_GET['custom_url'];

    $_SESSION['create_user_id'] = $get_id;
    $_SESSION['create_user_email'] = $email;
    $_SESSION['create_user_name'] = $name;
    $_SESSION['create_user_contact'] = $contact;
    $_SESSION['create_user_custom_url'] = $custom_url;
    $_SESSION['create_user_status'] = true;
    if($_SESSION['type'] != "Admin"){
        $_SESSION['dealer_login_type'] = "editor_login";
    }
    $get_section = $manage->getSectionName();
    if ($get_section != null) {
        $_SESSION['menu'] = array('s_profile'=> $get_section['profile'],
            's_services'=> $get_section['services'],
            's_our_service'=> $get_section['our_service'],
            's_gallery'=> $get_section['gallery'],
            's_images'=> $get_section['images'],
            's_videos'=> $get_section['videos'],
            's_clients'=> $get_section['clients'],
            's_client_name'=> $get_section['client_name'],
            's_client_review_tab'=> $get_section['client_review'],
            's_team'=> $get_section['team'],
            's_our_team'=> $get_section['our_team'],
            's_bank'=> $get_section['bank'],
            's_payment'=> $get_section['payment'],
            's_basic_info'=> $get_section['basic_info'],
            's_company_info'=> $get_section['company_info']);
    } else {
        $_SESSION['menu'] = array(
            's_profile'=> "Profile",
            's_services'=> "Services",
            's_our_service'=> "Our Services",
            's_gallery'=> "Gallery",
            's_images'=> "Images",
            's_videos'=> "Videos",
            's_clients'=> "Clients",
            's_client_name'=> "Clients",
            's_client_review_tab'=> "Client's Reviews",
            's_team'=> "Team",
            's_our_team'=> "Our Team",
            's_bank'=> "Bank",
            's_payment'=> "Payment",
            's_basic_info' => "Basic Info",
            's_company_info' => "Company Info");
    }


    $_SESSION['total_percent'] = $manage->getUserProfilePercent();
    $pending_dot = $manage->getPendingFormCount();
    $_SESSION['red_dot'] = array();
    if ($pending_dot['company_name'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => true));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('company_name' => false ));
    }
    if ($pending_dot['service_name'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('service_name' => false ));
    }
    if ($pending_dot['image_name'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('image_name' => false ));
    }
    if ($pending_dot['video_link'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('video_link' => false ));
    }
    if ($pending_dot['client_name'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_name' => false ));
    }
    if ($pending_dot['client_review'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('client_review' => false ));
    }
    if ($pending_dot['our_team'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team'=> true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('our_team'=> false ));
    }
    if ($pending_dot['bank_name'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('bank_name' => false ));
    }
    if ($pending_dot['upi_id'] == "") {
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => true ));
    }else{
        $_SESSION['red_dot'] = array_merge($_SESSION['red_dot'], array('upi_id' => false));
    }
    header('location:basic-user-info.php');
}

?>