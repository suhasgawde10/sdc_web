<?php
include "controller/ManageDesktopCard.php";
$manage = new ManageDesktopCard();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();

include_once 'sendMail/sendMail.php';

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

if(isset($_POST['send_otp']) && $_POST['send_otp'] !=""){

    $contact_no = $_POST['send_otp'];
    $full_name = $_POST['full_name'];
    $message = substr_replace($random_sms,'-',3,0)." This is your One Time Password(OTP).";
    if($_POST['country'] == "101"){
        $sendSMS = $manage->sendSMS($contact_no,$message);
    }else{
        $sendSMS = $manage->sendMail(MAIL_FROM_NAME,$contact_no,"Sharedigitalcard.com - One Time Password(OTP)",$message);
    }
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
    $full_name = urldecode($_POST['full_name']);
    $rating_number = $_POST['ratingNum'];
    $contact_no = $_POST['contact_no'];
    $user_id = $_POST['user_id'];
    $admin_email = urldecode($_POST['admin_email']);
    $description = urldecode($_POST['description']);
    $admin_contact = urldecode($_POST['admin_contact']);
    $_SESSION['client_name'] = $full_name;
    $_SESSION['client_contact'] = $contact_no;
    $date_time_ago = time_elapsed_string(date('Y-m-d H:i:s'));
    $time = date('Y-m-d H:i:s');
    if($new_otp == $_SESSION['prev_otp']) {
        /*start*/
        if(empty($_FILES)){
            $status = $manage->addClientsReviewWithRating($full_name, $description, "",$user_id,$rating_number,$time);
            if($status){
                $get_data = $manage->getSpecificUserProfileById($user_id);
                $name = $get_data['name'];
                $custom_url = $get_data['custom_url'];
                $url = FULL_WEBSITE_URL.$custom_url;
                $message = "Dear ".$name." ,\n" . $full_name. " has given review and rating to your business.\nClick on below link to check\n".$url;
                $send = $manage->sendSMS($admin_contact,$message);
                if($rating_number == "5") {
                    $rating = "75px";
                }elseif ($rating_number == "4") {
                    $rating = "60px";
                }elseif ($rating_number == "3") {
                    $rating = "45px";
                }elseif ($rating_number == "2") {
                    $rating = "30px";
                }elseif ($rating_number == "1") {
                    $rating = "15px";
                }


                $userData = ' <li>
                                                <div id="DIV_8">
                                                    <a href="#"><img alt="' . $name . '" src="'.FULL_WEBSITE_URL.'user/uploads/user.png" id="IMG_10" /></a>
                                                    <div id="DIV_11">
                                                        <div id="DIV_12">
                                                            <a href="#" id="A_13">' . $full_name . '</a>
                                                        </div>
                                                        <div id="DIV_14">
                                                            <span id="SPAN_15">' . $date_time_ago . '</span>
                                                        </div>
                                                        <div id="DIV_20">
                                                            
                                                                <g id="G-REVIEW-STARS_21">
                                                                    <span id="SPAN_22"><span id="SPAN_23" style="width: ' . $rating . ';"></span></span>
                                                                </g>
                                                                
                                                            <div id="DIV_24">
                                                                <span id="SPAN_27"> ' . $description . '</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div></li>';

                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'User data has been updated successfully.',
                    'full_name' => $full_name,
                    'data' => $userData
                );
                echo json_encode($returnData);

            }
        }else{
            $filetype = array('jpeg','jpg','png','gif','PNG','JPEG','JPG');
            foreach ($_FILES as $key ) {
                $name = time().str_replace([' ', '_'], '-',$key['name']);
                $path = "user/uploads/$admin_email/testimonials/client_review/$name";

                $file_ext = pathinfo($name, PATHINFO_EXTENSION);
                if (in_array(strtolower($file_ext), $filetype)) {
                    if ($key['name'] < 1000000) {
                        $upload = compressImage($key['tmp_name'], $path,60);
                        if($upload){
                            $status = $manage->addClientsReviewWithRating($full_name, $description, $name,$user_id,$rating_number,$time);
                            if($status){
                                $get_data = $manage->getSpecificUserProfileById($user_id);
                                $name = $get_data['name'];
                                $custom_url = $get_data['custom_url'];
                                $url = FULL_WEBSITE_URL.$custom_url;
                                $message = "Dear ".$name." ,\n" . $full_name. " has given review and rating to your business.\nClick on below link to check\n".$url;
                                $send = $manage->sendSMS($admin_contact,$message);
                                if($rating_number == "5") {
                                    $rating = "75px";
                                }elseif ($rating_number == "4") {
                                    $rating = "60px";
                                }elseif ($rating_number == "3") {
                                    $rating = "45px";
                                }elseif ($rating_number == "2") {
                                    $rating = "30px";
                                }elseif ($rating_number == "1") {
                                    $rating = "15px";
                                }
                                $date_time_ago = time_elapsed_string(date('Y-m-d h:i:s'));
                                $userData = ' <li>
                                                <div id="DIV_8">
                                                    <a href="#"><img alt="' . $name . '" src="'.FULL_WEBSITE_URL . $path .'" id="IMG_10" /></a>
                                                    <div id="DIV_11">
                                                        <div id="DIV_12">
                                                            <a href="#" id="A_13">' . $full_name . '</a>
                                                        </div>
                                                        <div id="DIV_14">
                                                            <span id="SPAN_15">' . $date_time_ago . '</span>
                                                        </div>
                                                        <div id="DIV_20">
                                                            
                                                                <g id="G-REVIEW-STARS_21">
                                                                    <span id="SPAN_22"><span id="SPAN_23" style="width: ' . $rating . ';"></span></span>
                                                                </g>
                                                                
                                                            <div id="DIV_24">
                                                                <span id="SPAN_27"> ' . $description . '</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div></li>';
                                $returnData = array(
                                    'status' => 'ok',
                                    'msg' => 'User data has been updated successfully.',
                                    'full_name' => $full_name,
                                    'data' => $userData
                                );
                                echo json_encode($returnData);

                            }
                        }else{
                            $returnData = array(
                                'status' => 'error',
                                'msg' => 'Issue while uploading please try after some time.',
                                'data' => ''
                            );
                            echo json_encode($returnData);

                        }
                    } else {
                        $returnData = array(
                            'status' => 'error',
                            'msg' => 'File must be less then 1 mb.',
                            'data' => ''
                        );
                        echo json_encode($returnData);

                    }
                } else {
                    $returnData = array(
                        'status' => 'error',
                        'msg' => 'Invalid File type please upload image only.',
                        'data' => ''
                    );
                    echo json_encode($returnData);

                }
            }
        }
    }else {
        $returnData = array(
            'status' => 'error',
            'msg' => 'OTP mismatch',
            'data' => ''
        );
        echo json_encode($returnData);

    }

}

if(isset($_POST['submit_review']) && $_POST['submit_review'] !=""){
    $full_name = urldecode($_POST['full_name']);
    $rating_number = $_POST['ratingNum'];
    $contact_no = $_POST['contact_no'];
    $user_id = $_POST['user_id'];
    $admin_email = urldecode($_POST['admin_email']);
    $description = urldecode($_POST['description']);
    $admin_contact = urldecode($_POST['admin_contact']);
    $_SESSION['client_name'] = $full_name;
    $_SESSION['client_contact'] = $contact_no;
    $date_time_ago = time_elapsed_string(date('Y-m-d H:i:s'));
    $time = date('Y-m-d H:i:s');
    if(empty($_FILES)){
            $status = $manage->addClientsReviewWithRating($full_name, $description, "",$user_id,$rating_number,$time);
            if($status){
                $get_data = $manage->getSpecificUserProfileById($user_id);
                $name = $get_data['name'];
                $custom_url = $get_data['custom_url'];
                $url = FULL_WEBSITE_URL.$custom_url;
                $message = "Dear ".$name." ,\n" . $full_name. " has given review and rating to your business.\nClick on below link to check\n".$url;
                $send = $manage->sendSMS($admin_contact,$message);
                if($rating_number == "5") {
                    $rating = "75px";
                }elseif ($rating_number == "4") {
                    $rating = "60px";
                }elseif ($rating_number == "3") {
                    $rating = "45px";
                }elseif ($rating_number == "2") {
                    $rating = "30px";
                }elseif ($rating_number == "1") {
                    $rating = "15px";
                }


                $userData = ' <li>
                                                <div id="DIV_8">
                                                    <a href="#"><img alt="' . $name . '" src="'.FULL_WEBSITE_URL.'user/uploads/user.png" id="IMG_10" /></a>
                                                    <div id="DIV_11">
                                                        <div id="DIV_12">
                                                            <a href="#" id="A_13">' . $full_name . '</a>
                                                        </div>
                                                        <div id="DIV_14">
                                                            <span id="SPAN_15">' . $date_time_ago . '</span>
                                                        </div>
                                                        <div id="DIV_20">

                                                                <g id="G-REVIEW-STARS_21">
                                                                    <span id="SPAN_22"><span id="SPAN_23" style="width: ' . $rating . ';"></span></span>
                                                                </g>

                                                            <div id="DIV_24">
                                                                <span id="SPAN_27"> ' . $description . '</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div></li>';

                $returnData = array(
                    'status' => 'ok',
                    'msg' => 'User data has been updated successfully.',
                    'full_name' => $full_name,
                    'data' => $userData
                );
                echo json_encode($returnData);

            }
        }else{
            $filetype = array('jpeg','jpg','png','gif','PNG','JPEG','JPG');
            foreach ($_FILES as $key ) {
                $name = time().str_replace([' ', '_'], '-',$key['name']);
                $path = "user/uploads/$admin_email/testimonials/client_review/$name";

                $file_ext = pathinfo($name, PATHINFO_EXTENSION);
                if (in_array(strtolower($file_ext), $filetype)) {
                    if ($key['name'] < 1000000) {
                        $upload = compressImage($key['tmp_name'], $path,60);
                        if($upload){
                            $status = $manage->addClientsReviewWithRating($full_name, $description, $name,$user_id,$rating_number,$time);
                            if($status){
                                $get_data = $manage->getSpecificUserProfileById($user_id);
                                $name = $get_data['name'];
                                $custom_url = $get_data['custom_url'];
                                $url = FULL_WEBSITE_URL.$custom_url;
                                $message = "Dear ".$name." ,\n" . $full_name. " has given review and rating to your business.\nClick on below link to check\n".$url;
                                $send = $manage->sendSMS($admin_contact,$message);
                                if($rating_number == "5") {
                                    $rating = "75px";
                                }elseif ($rating_number == "4") {
                                    $rating = "60px";
                                }elseif ($rating_number == "3") {
                                    $rating = "45px";
                                }elseif ($rating_number == "2") {
                                    $rating = "30px";
                                }elseif ($rating_number == "1") {
                                    $rating = "15px";
                                }
                                $date_time_ago = time_elapsed_string(date('Y-m-d h:i:s'));
                                $userData = ' <li>
                                                <div id="DIV_8">
                                                    <a href="#"><img alt="' . $name . '" src="'.FULL_WEBSITE_URL . $path .'" id="IMG_10" /></a>
                                                    <div id="DIV_11">
                                                        <div id="DIV_12">
                                                            <a href="#" id="A_13">' . $full_name . '</a>
                                                        </div>
                                                        <div id="DIV_14">
                                                            <span id="SPAN_15">' . $date_time_ago . '</span>
                                                        </div>
                                                        <div id="DIV_20">

                                                                <g id="G-REVIEW-STARS_21">
                                                                    <span id="SPAN_22"><span id="SPAN_23" style="width: ' . $rating . ';"></span></span>
                                                                </g>

                                                            <div id="DIV_24">
                                                                <span id="SPAN_27"> ' . $description . '</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div></li>';
                                $returnData = array(
                                    'status' => 'ok',
                                    'msg' => 'User data has been updated successfully.',
                                    'full_name' => $full_name,
                                    'data' => $userData
                                );
                                echo json_encode($returnData);

                            }
                        }else{
                            $returnData = array(
                                'status' => 'error',
                                'msg' => 'Issue while uploading please try after some time.',
                                'data' => ''
                            );
                            echo json_encode($returnData);

                        }
                    } else {
                        $returnData = array(
                            'status' => 'error',
                            'msg' => 'File must be less then 1 mb.',
                            'data' => ''
                        );
                        echo json_encode($returnData);

                    }
                } else {
                    $returnData = array(
                        'status' => 'error',
                        'msg' => 'Invalid File type please upload image only.',
                        'data' => ''
                    );
                    echo json_encode($returnData);

                }
            }
        }

}



if(isset($_POST['get_link']) && $_POST['get_link'] == "true"){

    $custom_url = urldecode($_POST['custom_url']);
    $get_data = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
    $user_id = $get_data['user_id'];
    $contact_no = $get_data['contact_no'];
    $email = $get_data['email'];
    $verified_email_status = $get_data['verified_email_status'];
    $name = $get_data['name'];
    $token = $security->encryptWebservice($random_sms);
    $date=date_create(date("Y-m-d"));
    date_add($date,date_interval_create_from_date_string("7 days"));
    $update = $manage->mu_insertPrivateLinkToken($user_id,$token,date_format($date,"Y-m-d"));
    $url = SHARED_URL.$custom_url.'&token='.$token;
    if($update){
        $sms_message = "Hi " . $name . ",\nYour Private URL valid for next 7 days\n".$url."\nYour bank/UPI details will be visible using this above url. Only share with your trusted people.";
        if($get_data['recieve_service'] == "sms") {
            $send = $manage->sendSMS($contact_no, $sms_message);
            $return_msg= "Private Link has been sent to ".$contact_no;
        }elseif ($get_data['recieve_service'] == "email"){
            if($verified_email_status == 1){
                $snd_email = $manage->sendMail($name, $email, "Request For Private Link - sharedigitalcard.com", $sms_message);
                $return_msg= "Private Link has been sent to ".$email;
            }else{
                $return_msg= "Please verify your email address";
            }

        }else{
            $send = $manage->sendSMS($contact_no, $sms_message);
            if($verified_email_status == 1){
                $snd_email = $manage->sendMail($name, $email, "Request For Private Link - sharedigitalcard.com", $sms_message);
                $return_msg= "Private Link has been sent to ".$contact_no." / ".$email;
            }else{
                $return_msg= "Private Link has been sent to ".$contact_no."\nplease verify your email for getting url on email";
            }

        }
        $returnData = array(
            'status' => 'ok',
            'msg' => $return_msg,
            'data' => ''
        );
        echo json_encode($returnData);
        exit();

    }else{
        $returnData = array(
            'status' => 'error',
            'msg' => 'Issue',
            'data' => ''
        );

        echo json_encode($returnData);
        exit();
    }

}

if(isset($_POST['text_color']) && $_POST['text_color'] !=""){
    $text_color = $_POST['text_color'];
    $user_id = $_POST['user_id'];
    $update = $manage->mdm_updateUserTextColor($user_id,$text_color);
    if($update){
        echo true;
    }
}
if(isset($_POST['back_color']) && $_POST['back_color'] !=""){
    $back_color = $_POST['back_color'];
    $custom_url= $_POST['custom_url'];
    $update = $manage->updateUserTheme($back_color,$custom_url);
    if($update){
        echo true;
    }
}
if(isset($_POST['change_font_family']) && $_POST['change_font_family'] !=''){
    $change_font_family = $_POST['change_font_family'];
    $custom_url= $_POST['custom_url'];
    $update = $manage->mdm_updateBackgroundTheme($change_font_family,$custom_url);
    if($update){
        echo true;
    }


}