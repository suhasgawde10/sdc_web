<?php
include "controller/ManageApp.php";
$manage = new ManageApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();

//$form_data = $manage->getSpecificUserProfile();

if(isset($_POST['state_id'])){

    $state_id = $_POST['state_id'];
    $getCityData = $manage->getCityDataByStateID($state_id);
    echo '<select name="city" required="" id="city" class="form-control">';
    if($getCityData !=null){
        while ($row = mysqli_fetch_array($getCityData)){
            ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?>
            </option>
        <?php
        }
    }else{
        echo '<option value="">select an option</option>';
    }
    echo '</select>';
}
if(isset($_POST['country_id'])){
    $country_id = $_POST['country_id'];
    $get_state = $manage->getStateCategory($country_id);
    echo '<select name="state" required="" id="state" class="form-control" onchange="getCityByStateId(this.value)">';
    if ($get_state != null) {
        while ($get_state_data = mysqli_fetch_array($get_state)) {
            ?>
            <option value="<?php echo $get_state_data['id']; ?>"><?php echo $get_state_data['name']; ?>
            </option>
        <?php
        }
    }else{
        echo '<option value="">select an option</option>';
    }
    echo '</select>';


}

?>