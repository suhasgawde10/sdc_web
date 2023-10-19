<?php

ob_start();

include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
/*8523*/


if(isset($_POST['update'])){

    $encrypt = $security->encryptWebservice($_POST['encrypt']);
    echo $encrypt;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

<form action="" method="post">
<input type="text" name="encrypt"><br>
    <button name="update" type="submit">Update Password</button>
</form>

</body>
</html>