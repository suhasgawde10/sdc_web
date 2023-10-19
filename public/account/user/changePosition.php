<?php
ob_start();
include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();



if(isset($_POST['service']) && $_POST['service'] !=""){

    $service = $_POST['service'];

    $i=1;
    foreach($service as $k=>$v){
        $condition = array('id'=>$security->decryptWebservice($v));
        $data = array('position_order'=>$i);
        $update_sorting = $manage->update($manage->serviceTable,$data,$condition);
        /*$sql = "Update sorting_items SET position_order=".$i." WHERE id=".$v;

        */
        $i++;
    }

}if(isset($_POST['client']) && $_POST['client'] !=""){

    $client = $_POST['client'];

    $i=1;
    foreach($client as $k=>$v){
        $condition = array('id'=>$security->decryptWebservice($v));
        $data = array('position_order'=>$i);
        $update_sorting = $manage->update($manage->clientTable,$data,$condition);
        /*$sql = "Update sorting_items SET position_order=".$i." WHERE id=".$security->decryptWebservice($v);

        */
        $i++;
    }

}
if(isset($_POST['team']) && $_POST['team'] !=""){

    $team = $_POST['team'];

    $i=1;
    foreach($team as $k=>$v){
        $condition = array('id'=>$security->decryptWebservice($v));
        $data = array('position_order'=>$i);
        $update_sorting = $manage->update($manage->ourTeamTable,$data,$condition);
        /*$sql = "Update sorting_items SET position_order=".$i." WHERE id=".$v;

        */
        $i++;
    }

}
if(isset($_POST['cover_profile']) && $_POST['cover_profile'] !=""){

    $cover_profile = $_POST['cover_profile'];

    $i=1;
    foreach($cover_profile as $k=>$v){
        $condition = array('id'=>$security->decryptWebservice($v));
        $data = array('position_order'=>$i);
        $update_sorting = $manage->update($manage->coverProfileTable,$data,$condition);
        $i++;
    }

}

if(isset($_POST['gallery']) && $_POST['gallery'] !=""){

    $gallery = $_POST['gallery'];

    $i=1;
    foreach($gallery as $k=>$v){
        $condition = array('id'=>$security->decryptWebservice($v));
        $data = array('position_order'=>$i);
        $update_sorting = $manage->update($manage->imageTable,$data,$condition);
        $i++;
    }

}

?>