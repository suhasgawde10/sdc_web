<?php

include "../controller/ManageUser.php";
$manage = new ManageUser();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

if (isset($_SESSION['id'])) {
    if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
        $session_email = $_SESSION['create_user_email'];
    } else {
        $session_email = $_SESSION['email'];
    }
}

//$form_data = $manage->getSpecificUserProfile();

if(isset($_POST['state_id'])){

    $state_id = $_POST['state_id'];
    $getCityData = $manage->getCityDataByStateID($state_id);
    echo '<select name="txt_city" class="form-control">';
    if($getCityData !=null){
        while ($row = mysqli_fetch_array($getCityData)){
            echo '<option ';
            if(isset($_POST['city_name']) && $_POST['city_name'] == $row['name']) {
                echo "selected";
            }
            echo  '>' .$row['name']. '</option>';
        }
    }else{
        echo '<option value="">select an option</option>';
    }
    echo '</select>';
}
if(isset($_POST['country_id'])){
    $country_id = $_POST['country_id'];
    $get_state = $manage->getStateCategory($country_id);
    echo '<select name="txt_state" class="form-control" onchange="getCityByStateId(this.value)">';
    if ($get_state != null) {
        while ($get_state_data = mysqli_fetch_array($get_state)) {
            ?>
            <option <?php if (isset($state) && $state == $get_state_data['id']) echo 'selected' ?>
                value="<?php echo $get_state_data['id']; ?>"><?php echo $get_state_data['name']; ?>
            </option>
            <?php
        }
    }else{
        echo '<option value="">select an option</option>';
    }
    echo '</select>';


}


?>