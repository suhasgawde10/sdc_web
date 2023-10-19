<?php



$host = parse_url('https://' . $_SERVER['HTTP_HOST'] . '/', PHP_URL_HOST);
$domains = explode('.', $host);
$url = $domains[count($domains) - 2];
// echo $url; exit;
// $url = 'sdigitalcard.com';
$today_date = date("Y-m-d");

$fetchDataFromDomain = $manage->getDealerFromDomain($url);

$id = $fetchDataFromDomain['id'];
$company_name = $fetchDataFromDomain['company_name'];
$logo = $fetchDataFromDomain['company_logo'];
$about_img = $fetchDataFromDomain['about_img'];
$about_desc = $fetchDataFromDomain['about_desc'];
$box1 = $fetchDataFromDomain['about_box_f'];
$box2 = $fetchDataFromDomain['about_box_s'];
$feature_img = $fetchDataFromDomain['feature_image'];
$slider_img = $fetchDataFromDomain['slider_image'];
$slider_title = $fetchDataFromDomain['slider_title'];
$slider_desc = $fetchDataFromDomain['slider_desc'];
$slider_color = $fetchDataFromDomain['slider_color'];
$customer_c = $fetchDataFromDomain['customer_count'];
$city_c = $fetchDataFromDomain['city_count'];
$theme_c = $fetchDataFromDomain['theme_count'];
$pertner_c = $fetchDataFromDomain['partner_count'];
$theme_font = $fetchDataFromDomain['theme_font'];
$theme_color = $fetchDataFromDomain['theme_color'];
$Addrs = strip_tags($fetchDataFromDomain['contact_addr']);
$call = strip_tags($fetchDataFromDomain['contact_call']);
$email_cont = strip_tags($fetchDataFromDomain['contact_email']);
$hours = $fetchDataFromDomain['contact_hours'];
$facebook = $fetchDataFromDomain['facebook'];
$linkdin = $fetchDataFromDomain['linkdin'];
$twitter = $fetchDataFromDomain['twitter'];
$instagram = $fetchDataFromDomain['instagram'];
$email = $fetchDataFromDomain['email_id'];
$youtube = $fetchDataFromDomain['youtube'];
$domain_link_name = $fetchDataFromDomain['domain_link_name'];
$franchise_status = $fetchDataFromDomain['franchise_status'];
$privacy_status = $fetchDataFromDomain['privacy_status'];
$cotactnum = $fetchDataFromDomain['contact_no'];
$cotactnum2 = $fetchDataFromDomain['contact_no'];
$whatsapp = $fetchDataFromDomain['alter_contact_no'];
$copy_right = $fetchDataFromDomain['copy_right'];
$logo_size = $fetchDataFromDomain['logo_width'];

$hosts = $fetchDataFromDomain['smtp_host'];
$username = $fetchDataFromDomain['smtp_username'];
$pawd = $fetchDataFromDomain['smtp_password'];
$port = $fetchDataFromDomain['smtp_port'];

if ($hosts == "" && $username == "" && $pawd == "" && $port == "") {
    $hosts = "smtp.gmail.com";
    $username = "kubic.testing2@gmail.com";
    $pawd = "lfwpircfbpblmjub";
    $port = "587";
}

$card_status = $fetchDataFromDomain['card_show_status'];
$services_status = $fetchDataFromDomain['other_service_status'];
$plan_status = $fetchDataFromDomain['plan_status'];

$theme_status= $fetchDataFromDomain['theme_status'];
$testimonial_status= $fetchDataFromDomain['testimonial_status'];
$team_status= $fetchDataFromDomain['team_status'];
$demo_status= $fetchDataFromDomain['demo_status'];

$specific_card_link = $fetchDataFromDomain['specific_link'];


define("THEME_COLORS", $theme_color);
define("HEADER_COLORS", $slider_color);


?>