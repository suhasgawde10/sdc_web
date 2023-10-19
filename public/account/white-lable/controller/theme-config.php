<?php
include "ManageAdminApp.php";
$manage = new ManageAdminApp();

$host = parse_url('https://'.$_SERVER['HTTP_HOST'].'/',PHP_URL_HOST);
$domains = explode('.',$host);
$url = $domains[count($domains)-2];
// $url = 'sdigitalcard.com';
//$url = "dgindia.website";
//$url = "atultech.com";


$fetchDataFromDomain = $manage->getDealerFromDomain($url);
//var_dump($fetchDataFromDomain);
//exit;
$slider_color = $fetchDataFromDomain['slider_color'];
$theme_color = $fetchDataFromDomain['theme_color'];
$icon_color = $fetchDataFromDomain['icon_color'];
$host = $fetchDataFromDomain['smtp_host'];
$username = $fetchDataFromDomain['smtp_username'];
$pawd = $fetchDataFromDomain['smtp_password'];
$port = $fetchDataFromDomain['smtp_port'];


define("THEME_COLORS",$theme_color);
define("ICON_COLORS",$icon_color);
define("HEADER_COLORS",$slider_color);

define("SMTP_HOST",$host);
define("SMTP_USERNAME",$username);
define("SMTP_PASSWORD",$pawd);
define("SMTP_PORT",$port);

/*echo SMTP_HOST."<br>";
echo SMTP_USERNAME."<br>";
echo SMTP_PASSWORD."<br>";
echo SMTP_PORT."<br>";
exit;*/

?>