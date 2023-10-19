<?php

include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();

if ($_POST) {
    $drp_card = $_POST['drp_card'];
    $year = $_POST['drp_year'];

    if ($drp_card != '') {
        $result = $manage->getAmountRelatedToId($drp_card,$year);
        $amount = $result['amt'];
        echo "<input class='form-control' id='amt' name='amount' type='text' value=" . $amount . " readonly><br>";
    } else {
        echo '';
    }

}
?>