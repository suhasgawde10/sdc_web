<?php

include_once '../sendMail/sendMail.php';
include "../controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();



if(isset($_POST['send_otp']) && $_POST['send_otp'] !=""){

    $contact_no = $_POST['send_otp'];
    $full_name = $_POST['full_name'];

    if($_POST['country'] == "101"){
        $message = substr_replace($random_sms,'-',3,0)." This is your One Time Password(OTP).";
        $sendSMS = $manage->sendSMS($contact_no,$message);
    }else{
        $message = "Your One Time Password(OTP) For Service Is".substr_replace($random_sms,'-',3,0);
        $sendSMS = $manage->sendMail(MAIL_FROM_NAME,$contact_no,"Send Enquiry OTP",$message);
    }
    /*$random_sms = 123456;
    $sendSMS = true;*/
    if ($sendSMS) {
        $_SESSION['prev_otp'] = $random_sms;
        $userData = array('status'=>'success');
        $returnData = array(
            'status' => 'ok',
            'msg' => 'User data has been updated successfully.',
            'data' => $userData
        );
    } else {
        $returnData = array(
            'status' => 'error',
            'msg' => 'Some problem occurred, please try again.',
            'data' => ''
        );
    }
    echo json_encode($returnData);

}

if(isset($_POST['verify_otp']) && $_POST['verify_otp'] !=""){

    $new_otp = str_replace(',','',$_POST['verify_otp']);
    $full_name = $_POST['full_name'];
    $contact_no = $_POST['contact_no'];
    $user_id = $_POST['user_id'];
    $service_name = $_POST['service_name'];
    $admin_email = $_POST['admin_email'];
    $verified_email_status= $_POST['verified_email_status'];
    $admin_contact = urldecode($_POST['admin_contact']);
    $_SESSION['client_name'] = $full_name;
    $_SESSION['client_contact'] = $contact_no;
    if($new_otp == $_SESSION['prev_otp']) {
        $subject = "Enquiry For service ".$service_name;

     $email_message = "You have a customer request for service ".$service_name."<br>Please contact with the customer Name: ".$full_name." & Contact Number: ".$contact_no;
        if($verified_email_status == 1){
        $sendmail = $manage->sendMail(MAIL_FROM_NAME,$admin_email,$subject,$email_message);
        }
        $date_time = date('Y-m-d h:i:a s');
        $sms_message = $full_name." " . $contact_no . " enquired for ".$service_name."\n(".date('d-M-Y').")\nsharedigitalcard";
        $sendsms = $manage->sendSMS($admin_contact,$sms_message);
        $update_count = $manage->updateUserLeadCount("Add",$user_id);
        $insert_data = array('user_id'=>$user_id,'client_name'=>$full_name,'contact_no'=>$contact_no,'service_name'=>$service_name,'created_date'=>date('Y-m-d'),'approve_status'=>"Pending");
        $insert = $manage->insert($manage->serviceRequestTable,$insert_data);
        $userData = array('status'=>'success');
        $returnData = array(
            'status' => 'ok',
            'msg' => 'User data has been updated successfully.',
            'data' => $userData
        );
    }else{
        $returnData = array(
            'status' => 'error',
            'msg' => 'Some problem occurred, please try again.',
            'data' => ''
        );
    }
    echo json_encode($returnData);
}
if(isset($_POST['submit_service']) && $_POST['submit_service'] !=""){
    $full_name = $_POST['full_name'];
    $contact_no = $_POST['contact_no'];
    $user_id = $_POST['user_id'];
    $service_name = $_POST['service_name'];
    $admin_email = $_POST['admin_email'];
    $verified_email_status= $_POST['verified_email_status'];
    $admin_contact = urldecode($_POST['admin_contact']);
    $_SESSION['client_name'] = $full_name;
    $_SESSION['client_contact'] = $contact_no;

    $subject = "Enquiry For service ".$service_name;

     $email_message = "You have a customer request for service ".$service_name."<br>Please contact with the customer Name: ".$full_name." & Contact Number: ".$contact_no;
        if($verified_email_status == 1){
        $sendmail = $manage->sendMail(MAIL_FROM_NAME,$admin_email,$subject,$email_message);
        }
        $date_time = date('Y-m-d h:i:a s');
        $sms_message = $full_name." " . $contact_no . " enquired for ".$service_name."\n(".date('d-M-Y').")\nsharedigitalcard";
        $sendsms = $manage->sendSMS($admin_contact,$sms_message);
        $update_count = $manage->updateUserLeadCount("Add",$user_id);
        $insert_data = array('user_id'=>$user_id,'client_name'=>$full_name,'contact_no'=>$contact_no,'service_name'=>$service_name,'created_date'=>date('Y-m-d'),'approve_status'=>"Pending");
        $insert = $manage->insert($manage->serviceRequestTable,$insert_data);
        $userData = array('status'=>'success');
        $returnData = array(
            'status' => 'ok',
            'msg' => 'User data has been updated successfully.',
            'data' => $userData
        );

    echo json_encode($returnData);
}

