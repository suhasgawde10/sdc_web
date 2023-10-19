<?php
session_start();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

$n1=rand(1,6); //Generate First number between 1 and 6
$n2=rand(5,9); //Generate Second number between 5 and 9
$answer=$n1+$n2;

$math = $n1." + ".$n2." = ";
$_SESSION['vercode'] = $security->encryptWebservice($answer);


$height = 22; //CAPTCHA image height
$width = 75; //CAPTCHA image width

$image_p = imagecreate($width, $height);
$black = imagecolorallocate($image_p, 219, 94, 165);
$white = imagecolorallocate($image_p, 255, 255, 255);
$font_size = 10;
imagestring($image_p, $font_size, 5, 3, $math, $white);
imagejpeg($image_p, null, 80);
?>