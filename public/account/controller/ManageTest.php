<?php
session_start();
include("controller.php");

class ManageTest
{
    function updateDealerPanelExpiryDate($user_id)
    {
        $controller = new Controller();
        $sql_query = "update tb_dealer_profile set expiry_date=(SELECT DATE_ADD((select created_date from tb_dealer_profile where id=$user_id), INTERVAL 5 year)) where id=$user_id";
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    public function getDealerData()
    {
        $controller = new Controller();
        $sql_query = "select * from tb_dealer_profile";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }
}