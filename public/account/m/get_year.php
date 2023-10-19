<?php
include "../controller/ManageMobileCard.php";
$manage = new ManageMobileCard();

if ($_POST) {
    $drp_card = $_POST['drp_card'];
    if ($drp_card != '') {
        /* echo $drp_card;
         die();*/
        $result = $manage->getYearRelatedToId($drp_card);
        echo "<select class='btn-group bootstrap-select form-control show-tick' name='drp_plan' id='myDropdown' onchange='get_amount();' required='required'>";
        echo "<option value=''>Select Year</option>";
        while ($row = mysqli_fetch_array($result)) {
            $value = "";
            echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
        }
        echo "</select>";
    } else {
        echo '';
    }

}
?>