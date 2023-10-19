<?php


include '../controller/Constants.php';

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
$link = "https";
else
$link = "http";
$link .= "://";

$link .= $_SERVER['HTTP_HOST'];

$link .= $_SERVER['REQUEST_URI'];
$final_link = FULL_WEBSITE_URL.$_GET['custom_url'];

$data = isset($_GET['data']) ? $_GET['data'] : $final_link;
$size = isset($_GET['size']) ? $_GET['size'] : '350x350';
$logo = isset($_GET['logo']) ? $_GET['logo'] : FULL_DESKTOP_URL.'ic_launcher_round.png';

header('Content-type: image/png');
$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
if($logo !== FALSE){
$logo = imagecreatefromstring(file_get_contents($logo));
$QR_width = imagesx($QR);
$QR_height = imagesy($QR);

$logo_width = imagesx($logo);
$logo_height = imagesy($logo);

// Scale logo to fit in the QR Code
$logo_qr_width = $QR_width/3;
$scale = $logo_width/$logo_qr_width;
$logo_qr_height = $logo_height/$scale;

imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
}
imagepng($QR);
imagedestroy($QR);