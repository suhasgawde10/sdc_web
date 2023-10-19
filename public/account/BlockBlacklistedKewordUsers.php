<?php

include "controller/ManageApp.php";
$manage = new ManageApp();

$today_date = date('Y-m-d');
$getResult = $manage->getTodaysUserWithBlackListedKeywords($today_date);

if($getResult!=null){
    while($row = mysqli_fetch_array($getResult)){
        $user_id = $row["id"];
        if($row["status"]!="0"){
            $update = $manage->updateBlacklistUserStatus($user_id);
            if($update){
                echo $user_id."Updated Successfully <br>";
            }
        }
    }
}
?>