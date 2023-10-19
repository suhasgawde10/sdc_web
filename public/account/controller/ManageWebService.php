<?php
session_start();
include("controller.php");

class ManageWebService
{
    public function getDealerInfo($site_name)
    {
        $controller = new Controller();
        $sql_query = "select dealer_code,dg_card_site_link,name from tb_dealer_profile where website like '%" . $site_name . "%'";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    public function getDealerCardDetails($dealer_id)
    {
        $controller = new Controller();
        $sql_query = "call ma_displayActiveUserOfDealer('$dealer_id')";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    function validateUserContact($contact_no)
    {
        $result = false;
        $controller = new Controller();
        // $sql = "select * from " . $this->loginTable . " where contact_no='" . $contact_no . "'";
        $sql = "call mu_validateUserContact(?)";
        $type = "s";
        $param = array($contact_no);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateCustomUrl($url)
    {
        $result = false;
        $controller = new Controller();
        //$sql = "select * from " . $this->customUrlLogTable . " as clt inner join " . $this->profileTable . " as pt where clt.custom_url='" . $url . "' or pt.custom_url='" . $url . "'";
        $sql = "call mu_validateCustomUrl(?)";
        $type = "s";
        $param = array($url);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function validateUserRegisterEmail($email)
    {
        $result = false;
        $controller = new Controller();
        $sql = "call mu_validateRegisterEmail(?)";
        $type = "s";
        $param = array($email);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function addUserDetails($name, $custom_url, $gender, $sell_ref, $dealer_id, $online_search, $country, $state, $city, $txt_company_name, $dealer_name)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->profileTable . " (theme_id,subscription_id,name,custom_url,gender,status,referer_code,created_by,created_date,update_user_count,email_count,verify_number,sell_ref,dealer_id,country) VALUES ('theme1','0','$name','$custom_url','$gender',1,'" . $_SESSION['dealer_code'] . "','" . $_SESSION['dealer_name'] . "',NOW(),0,0,0,'$sell_ref','$dealer_id','India')";
        /* $query = "call md_addUserDetails(?,?,?,?,?,?)";
         $type = "ssssss";
         $params = array($name, $custom_url, $gender,$sell_ref,$dealer_id,$_SESSION['dealer_name']);*/

        $procedure = "CALL md_addUserDetails('$name', '$custom_url', '$gender','$sell_ref','$dealer_id','$dealer_id','$dealer_name','$online_search','$country','$state','$city','$txt_company_name',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;
        /*$result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        return $result;*/
    }

    function updateUserCode($id, $user_referral_code)
    {
        $controller = new Controller();
        //$query = "update " . $this->profileTable . " set user_referer_code = '" . $_SESSION['user_code'] . "' where id = " . $id . "";
        $query = "call mu_updateUserCode(?,?)";
        $type = "is";
        $param = array($id, $user_referral_code);
        $id = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $param);
        return $id;
    }

    function addMenuBar($id)
    {
        $controller = new Controller();
        //$sql_query = "insert into " . $this->sectionNameTable . " (user_id,profile,services,our_service,gallery,images,videos,clients,client_name,client_review,team,our_team,bank,payment) values('$id','Profile','Services','Our Services','Gallery','Images','Videos','Clients','Clients','Clients Reviews','Team','Our Team','Bank','Payment')";
        $query = "call mu_addMenuBar(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function addUserLoginDetails($user_id, $type, $email, $contact, $password, $api_key)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->loginTable . " (user_id,type,email,contact_no,password,api_key) VALUES ('$user_id','" . $type . "', '$email','$contact','$password','$api_key')";
        $query = "call mu_addUserLogin(?,?,?,?,?,?)";
        $data_type = "isssss";
        $params = array($user_id, $type, $email, $contact, $password, $api_key);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $data_type, $params);
        return $result;
    }

    function getSectionDetails()
    {
        $controller = new Controller();
        //$sql_query = "select * from " . $this->sectionTable;
        $query = "call mu_getSectionDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function insertUserData($getUserId,$year, $plan_amount, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type, $for_bill, $for_email, $user_gstno, $for_pan, $from_bill, $from_gstno, $from_pan, $sac_code)
    {
        $controller = new Controller();
        // $query = "insert into " . $this->userSubscriptionTable . " (user_id,type,year,taxable_amount,start_date,end_date,status,referral_code,referenced_by,active_plan,invoice_no,discount,tax,total_amount,payment_brand,payment_mode,custBankId,timestamp,gstn_no) values (" . $this->getUserSessionId() . ",1,'" . $year . "','" . $taxable_amount . "','" . $sDate . "','" . $endDate . "','" . $status . "','" . $referral_code . "','" . $refrenced_by . "','" . $active_plan . "','" . $invoice_no . "','" . $discount . "','" . $tax . "','" . $total_amount . "','" . $payment_brand . "','" . $payment_mode . "','" . $bankId . "','" . $timestamp . "','" . $user_gstno . "')";
        $query = "call mu_insertUserData(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $type = "sssssssssssssssssi";
        // $params = array($year, $taxable_amount, $sDate, $endDate, $status, $referral_code, $refrenced_by, $active_plan, $invoice_no, $discount, $tax, $total_amount, $payment_brand, $payment_mode, $bankId, $timestamp, $payment_type,$for_bill,$for_email,$user_gstno,$for_pan,$from_bill,$from_gstno,$from_pan,$sac_code, $this->getUserSessionId());
        $procedure = "CALL mu_insertUserData('$year','$plan_amount', '$taxable_amount', '$sDate', '$endDate', '$status', '$referral_code', '$refrenced_by', '$active_plan', '$invoice_no', '$discount', '$tax', '$total_amount', '$payment_brand', '$payment_mode', '$bankId', '$timestamp','$payment_type','$for_bill','$for_email','$user_gstno','$for_pan','$from_bill','$from_gstno','$from_pan','$sac_code', '" . $getUserId . "',@p_out_param)";
        $mysqli = $controller->connect();
        $results1 = $mysqli->query($procedure);
        $results2 = $mysqli->query("SELECT @p_out_param as last_id");
        $row = $results2->fetch_object();
        return $row->last_id;

        // $result = $controller->genericGetLastInsertedIdUsingProcedure($query,$type,$params);
        // return $result;
    }

    function updateUserExpiryDate($getUserId,$expiry_date)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set expiry_date='" . $expiry_date . "',update_user_count=1,user_start_date=CURDATE() where id=" . $this->getUserSessionId() . "";
        $query = "call mu_updateUserExpiryDate(?,?)";
        $type = "is";
        $params = array($getUserId, $expiry_date);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    function insertDefaultUserSectionEntry($user_id, $section_id, $p_dg_status)
    {
        $controller = new Controller();
        //$query = "insert into " . $this->sectionStatusTable . " (user_id,section_id,website,digital_card) VALUES (" . $user_id . "," . $section_id . ",1,1)";
        $query = "call mu_insertDefaultUserSectionEntry(?,?,?)";
        $type = "iii";
        $params = array($user_id, $section_id, $p_dg_status);
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $status;
    }

    function getUserSessionId()
    {
        $security = new EncryptDecrypt();
        if (isset($_SESSION['create_user_status']) && $_SESSION['create_user_status'] == true) {
            $id = $security->decrypt($_SESSION["create_user_id"]);
        } else {
            $id = $security->decrypt($_SESSION["id"]);
        }
        return $id;
    }

    function getUserData($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->loginTable . " as lt inner join " . $this->profileTable . " as pt on pt.id=lt.user_id where pt.id=" . $this->getUserSessionId();
        $sql = "call mu_getUserData(?)";
        $type = "i";
        $param = array($id);
//        echo
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql, $type, $param);
        return $result;
    }

    function update_user_email_count($id) // update_email_count
    {
        $controller = new Controller();
        //  $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $id;
        $query = "call mu_update_email_count(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

}