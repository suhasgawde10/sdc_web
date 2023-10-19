<?php
include_once '../sendMail/sendMail.php';
include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();
if(isset($_POST['background_image'])){
    $background_image = $_POST['background_image'];
    echo "<input class='form-control' id='amt' name='background_image1' type='hidden' value=" . $background_image . "><br>";
}


if(isset($_POST['email'])){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $link = $_POST['message'];
    $toEmail = $email;
    $toName = $name;
    $subject = "SHARE EMAIL";
    $message = "Hello guys,\n
Please click on below link to check Digital Card! :)\n" . SHARED_URL.$_GET['custom_url'];
    $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);

}

?>