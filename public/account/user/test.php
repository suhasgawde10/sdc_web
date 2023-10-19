<?php

include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$controller = new Controller();
$con = $controller->connect();
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
$send = $manage->sendSMS('8070139237',"here");
if($send){
    echo "hear";
}else{
    echo "dear";
}
die();
$query = "SELECT * FROM `tb_user_subscription` WHERE year NOT IN ('Free Trail (5 days)','Free Trail (15 days)') and referral_code LIKE 'dealer%' AND status = 'success' and dealer_by_pay=1 ORDER BY `id` DESC";
$result = $con->query($query);
$i = 1;
while ($row = mysqli_fetch_array($result)){

    $invoice_id = $row['id'];
    $referral_code = $row['referral_code'];
    $get_user_data_query = "SELECT * FROM tb_dealer_profile WHERE dealer_code='$referral_code' limit 1";
    $result_data = $con->query($get_user_data_query);
    $form_data = $result_data->fetch_array(MYSQLI_ASSOC);
    $invoice_name = $form_data['c_name'];
    $invoice_email = $form_data['b_email_id'];
    if($invoice_name ==""){
        $invoice_name= $form_data['name'];
    }
    if($invoice_email  == ""){
        $invoice_email= $form_data['email'];
    }
    $dealer_gstn_no = $form_data['gstin_no'];
    $dealer_pan_no = $form_data['pan_no'];
    $update = "UPDATE tb_user_subscription SET for_bill='$invoice_name', for_email='$invoice_email', for_gstno='$dealer_gstn_no', for_pan='$dealer_pan_no' where id=".$invoice_id;
    echo $update.'<br>';
    $con->query($update);
    $i++;
}

$query_u = "SELECT * FROM `tb_user_subscription` WHERE year NOT IN ('Free Trail (5 days)','Free Trail (15 days)') and status = 'success' and dealer_by_pay != 1 ORDER BY `id` DESC";
$result_u = $con->query($query_u);
$i = 1;
while ($row_u = mysqli_fetch_array($result_u)){

    $invoice_id = $row_u['id'];
    $user_id = $row_u['user_id'];
    $get_user_data_query_u = "SELECT * FROM tb_user_profile WHERE id=" . $user_id. " limit 1";
    $result_data = $con->query($get_user_data_query_u);
    $form_data_u = $result_data->fetch_array(MYSQLI_ASSOC);
    $invoice_name = $form_data_u['company_name'];
    if($invoice_name == ''){
        $invoice_name = $form_data_u['name'];
    }
    $email = $form_data_u['email'];

    $user_contact = $form_data_u['contact_no'];
    $user_gstno = $form_data_u['gst_no'];
    $user_pan_no = $form_data_u['pan_no'];
    $update_u = "UPDATE tb_user_subscription SET for_bill='$invoice_name' , for_email='$invoice_email' , for_gstno='$user_gstno' , for_pan='$user_pan_no' where id=".$invoice_id;
    echo $update_u;
    $con->query($update_u);
    $i++;
}

?>