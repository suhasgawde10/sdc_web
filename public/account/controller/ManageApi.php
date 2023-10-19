<?php
session_start();
include("controller.php");
$root = "";
if (strpos($_SERVER['DOCUMENT_ROOT'],  '.com') !== false) {
    $root = $_SERVER['DOCUMENT_ROOT'];
} else {
    $root = $_SERVER['DOCUMENT_ROOT'] . '/sdc_aws_improved';
}

// echo  $root;
// die();
require_once   $root . '/vendor/autoload.php';

use \Firebase\JWT\JWT;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class ManageApi
{


    public $counterTable = "tb_counter";

    public $profileTable = "tb_user_profile";

    public function getContactCount($mobile)
    {
        $controller = new Controller();
        // $sql_query = "select * from tb_login as tbl inner join tb_user_profile as tbup on tbl.user_id=tbup.id  where tbl.contact_no='$mobile' and tbup.status=1 and tbup.expiry_date>=CURDATE()";
        $sql_query = "select * from tb_login where contact_no='$mobile'";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectCount($sql_query);
        return $result;
    }

    public function getOtpCount($otp)
    {
        $controller = new Controller();
        $sql_query = "select * from tb_user_profile where otp=" . $otp;
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectCount($sql_query);
        return $result;
    }

    public function getstatus($status, $mobile)
    {
        $controller = new Controller();
        $sql_query = "select * from tb_user_profile as tbup inner join tb_login as tbl on tbl.user_id=tbup.id where tbup.status=$status and tbl.contact_no='$mobile'";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectCount($sql_query);
        return $result;
    }


    public function getExpiryDate($today, $mobile)
    {
        $controller = new Controller();
        $sql_query = "select * from tb_user_profile as tbup inner join tb_login as tbl on tbl.user_id=tbup.id where tbup.expiry_date>='$today' and tbl.contact_no='$mobile'";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectCount($sql_query);
        return $result;
    }

    public function getLoginTable($mobile)
    {
        $controller = new Controller();
        $sql_query = "select * from tb_login  where contact_no='$mobile'";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    public function getLoginTableAndProfile($mobile)
    {
        $controller = new Controller();
        $sql_query = "select tbl.*,tbup.* from tb_login as tbl inner join tb_user_profile as tbup on tbl.user_id=tbup.id where tbl.contact_no='$mobile'";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    public function userDataUserProfile($mobile)
    {
        $controller = new Controller();
        $sql_query = "select tbup.* from tb_user_profile as tbup inner join tb_login as tbl on tbl.user_id=tbup.id  where tbl.contact_no='$mobile'";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    public function updateOtp($otp, $user_id)
    {
        $controller = new Controller();
        $sql_query = "update tb_user_profile set otp=$otp where id=" . $user_id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    public function updateUserData($adminData, $user_id)
    {
        // dd($adminData);
        // die();
        $controller = new Controller();
        $sql_query = "update tb_user_profile set current_app_version='$adminData[current_app_version]',android_version='$adminData[android_version]',devices_model='$adminData[devices_model]',fcm_token='$adminData[fcm_token]',longitude='$adminData[longitude]',latitude='$adminData[latitude]',app_locality='$adminData[app_locality]' where id=" . $user_id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    public function updateNotificationKey($adminData, $user_id)
    {
        $controller = new Controller();
        $sql_query = "update tb_login set user_notification='$adminData[fcm_token]' where user_id=" . $user_id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    public function getCustomerCount($user_id)
    {
        $controller = new Controller();
        $sql_query = "select * from tb_login  where user_id=" . $user_id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectCount($sql_query);
        return $result;
    }

    public function loginChangePassword($user_id)
    {
        $controller = new Controller();
        $sql_query = "select * from  tb_login where user_id=" . $user_id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    public function updatePassword($confirmPassword, $id)
    {
        $controller = new Controller();
        $sql_query = "update tb_login set password='$confirmPassword' where user_id=" . $id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

    public function getProfileUserData($user_id)
    {
        $controller = new Controller();
        $sql_query = "select tbup.*,tbl.email,tbl.contact_no,tbl.api_key,tbl.user_notification from tb_user_profile as tbup inner join tb_login as tbl on tbl.user_id=tbup.id where tbup.id=" . $user_id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }


    function sendMail($toName, $toEmail, $subject, $message)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMail($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    function sendMailWithAttachment($toName, $toEmail, $subject, $message, $attachment)
    {
        $sendMail = new sendMailSystem();
        $status = false;
        $sendMailStatus = $sendMail->sendMailWithAttachment($toName, $toEmail, MAIL_FROM_NAME, MAIL_FROM_EMAIL, $subject, $message, $attachment);
        if ($sendMailStatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }


    function updateEnquiryEmail($email, $id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->profileTable . " set email_count = 1 where id=" . $id . "";
        $query = "call mu_updateEnquiryEmail(?,?)";
        $type = "si";
        $params = array($email, $id);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }
    function updateSectionStatus($section_id, $digitalCard, $website, $id)
    {
        $controller = new Controller();
        $query = "call mu_updateSectionStatus(?,?,?,?)";
        $type = "iiii";
        $params = array($id, $website, $digitalCard, $section_id);
        /*print_r($params);
        exit;*/
        $status = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        /*print_r($status);
        exit;*/
        return $status;
    }

    function mu_updateUserReciever($status, $id)
    {
        $controller = new Controller();
        $query = "call mu_updateUserReciever(?,?)";
        $type = "is";
        $params = array($id, $status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }
    function updateUserOnlineSearch($online_search, $id)
    {
        $controller = new Controller();
        //    $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $user_id . "";
        $query = "call mu_updateUserOnlineSearch(?,?)";
        $type = "ii";
        $params = array($id, $online_search);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }

    public function getLoginTablePassword($id)
    {

        $controller = new Controller();
        $sql_query = "select * from  tb_login  where user_id=" . $id;
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        return $result;
    }

    function deactivateUserAccount($reason, $further, $account_status, $status, $id)
    {
        $controller = new Controller();
        // $sql_query = "update " . $this->userSubscriptionTable . " set active_plan='0' where user_id=" . $this->getUserSessionId() . "";
        $query = "call mu_deactivateUserAccount(?,?,?,?,?)";
        //$query = "call mu_deactivateUserAccount(86,'$reason','$further','$status')";
        $type = "isssi";
        $params = array($id, $reason, $further, $account_status, $status);
        $result = $controller->genericInsertUpdateDeleteUsingProcedure($query, $type, $params);
        return $result;
    }





    function sendSMSWithTemplateId($contact, $message, $template_id)
    {

        $username = "DGCARD";
        $password = "dgcard@123";
        /*        http://sms.bulksmsserviceproviders.com/api/send_http.php?authkey=8b7d2649d2239e549d4d0bbb66ef6ff5&mobiles=9768904980,9773884631&message=hello%0D%0Ahow+are+you&sender=DGCARD&route=4*/
        $sendSmsUrl = SMS_URL . "?authkey=" . trim(AUTH_KEY) . "&mobiles=" . urlencode($contact) . "&message=" . urlencode($message) . "&sender=" . trim(SMS_SENDER) . "&route=4&Template_ID=" . $template_id;
        $sendSmsUrl1 = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl1);
        /*echo $sendSmsUrl1;
        die();*/
        if (is_string($json)) {
            return true;
        } else {
            return false;
        }
    }

    public function generateJwtToken($user_id)
    {
        $secret_key = JWT_SECRET_KEY;
        $issuer_claim = JWT_THE_ISSUER; // this can be the servername
        $audience_claim = JWT_THE_AUDIENCE;
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 604800; // expire time in seconds
        $token = array(
            "user_id"   => $user_id,
            "iss"       => $issuer_claim,
            "aud"       => $audience_claim,
            "iat"       => $issuedat_claim,
            "nbf"       => $notbefore_claim,
            "exp"       => $expire_claim
        );
        $jwt = JWT::encode($token, $secret_key, 'HS256');
        return $jwt;
    }

    public function displayActiveUserCount($search, $city)
    {
        $controller = new Controller();
        $query = "call ma_displayActiveUserCount(?,?)";
        $type = "ss";
        $params = array($search, $city);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        if ($result['total_result'] != null) {
            return $result['total_result'];
        } else {
            return 0;
        }

    }

    public  function getActiveUserCount($search, $userId){

        $sql_query = "SELECT COUNT(pt.id) AS total_result
              FROM tb_user_profile AS pt
              INNER JOIN tb_login AS lt ON lt.user_id = pt.id
              INNER JOIN tb_user_subscription AS ust ON ust.user_id = pt.id
              WHERE ust.year NOT IN ('Free Trail (15 days)', 'Free Trail (5 days)')
                AND ust.active_plan = 1
                AND pt.online_search = 1
                AND pt.id != '".$userId."'
                AND pt.expiry_date > CURDATE()
                AND (
                  pt.name LIKE CONCAT('%', '" . $search . "', '%')
                  OR pt.designation LIKE CONCAT('%', '" . $search . "', '%')
                  OR pt.user_keyword LIKE CONCAT('%', '" . $search . "', '%')
                  OR pt.company_name LIKE CONCAT('%', '" . $search . "', '%')
                  OR pt.business_category LIKE CONCAT('%', '" . $search . "', '%')
                )";
        $controller = new Controller();
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        if ($result['total_result'] != null) {
            return $result['total_result'];
        } else {
            return 0;
        }
    }


    public function displayUser($search, $city, $start, $end)
    {
        $controller = new Controller();
        $query = "call ma_displayActiveUser(?,?,?,?)";
        //  $query = "call ma_displayActiveUser('$search', '$city',$start,$end)";
        $type = "ssii";
        $params = array($search, $city, $start, $end);
        mysqli_set_charset($controller->connect(), 'utf8');
        $result = $controller->genericSelectToIterateUsingProcedure($query, $type, $params);
        return $result;
    }

    function getActiveUser($p_search, $p_city, $userId, $start, $end){
        $sql_query = "SELECT pt.*, lt.email, lt.contact_no, lt.user_id, lt.password, ust.year, ust.end_date
              FROM tb_user_profile AS pt
              INNER JOIN tb_login AS lt ON lt.user_id = pt.id
              INNER JOIN tb_user_subscription AS ust ON ust.user_id = pt.id
              WHERE ust.year NOT IN ('Free Trail (15 days)', 'Free Trail (5 days)')
                AND ust.active_plan = 1 AND pt.id != '".$userId."'";
                if (!empty($p_search) && !empty($p_city)) {
                $sql_query .= " AND (pt.name LIKE '" . $p_search . "%' OR pt.designation LIKE '" . $p_search . "%' OR pt.user_keyword LIKE '%" . $p_search . "%')
                                AND (pt.address LIKE '%" . $p_city . "%' OR pt.city LIKE '%" . $p_city . "%')";
                } elseif (!empty($p_search) && empty($p_city)) {
                $sql_query .= " AND (pt.name LIKE '%" . $p_search . "%' OR pt.designation LIKE '%" . $p_search . "%' OR pt.user_keyword LIKE '%" . $p_search . "%' OR pt.company_name LIKE '%" . $p_search . "%' OR pt.business_category LIKE '%" . $p_search . "%')";
                } else {
                $sql_query .= " AND (pt.expiry_date > CURDATE() OR ust.year = 'Life Time')";
                }

            $sql_query .= " GROUP BY pt.id
                            ORDER BY pt.id DESC
                            LIMIT " . $start . ", " . $end;

    $controller = new Controller();
    $result = $controller->genericSelectToIterate($sql_query);
    return $result;
    }

    function getUserSuggestions($search, $userId) {
        $sql_query = "SELECT DISTINCT suggestion
        FROM (
          SELECT pt.name AS suggestion FROM tb_user_profile AS pt WHERE pt.name LIKE CONCAT('%', '".$search."', '%') AND pt.id != '".$userId."'
          UNION
          SELECT pt.designation AS suggestion FROM tb_user_profile AS pt WHERE pt.designation LIKE CONCAT('%', '".$search."', '%') AND pt.id != '".$userId."'
          UNION
          SELECT pt.user_keyword AS suggestion FROM tb_user_profile AS pt WHERE pt.user_keyword LIKE CONCAT('%', '".$search."', '%') AND pt.id != '".$userId."'
          UNION
          SELECT pt.company_name AS suggestion FROM tb_user_profile AS pt WHERE pt.company_name LIKE CONCAT('%', '".$search."', '%') AND pt.id != '".$userId."'
          UNION
          SELECT pt.business_category AS suggestion FROM tb_user_profile AS pt WHERE pt.business_category LIKE CONCAT('%', '".$search."', '%') AND pt.id != '".$userId."'
        ) AS suggestions
        LIMIT 10";
        // dd($sql_query);

        $controller = new Controller();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }



    function getLeadsCount($user_id) {
        $controller = new Controller();
        $sql_query = "SELECT COUNT(*) AS total_result FROM tb_service_request WHERE user_id = ".$user_id;
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        if ($result['total_result'] != null) {
            return $result['total_result'];
        } else {
            return 0;
        }
    }

    public function getLeads($user_id, $search, $start, $end)
    {
        $controller = new Controller();
        $sql_query = "SELECT * FROM tb_service_request WHERE user_id = ".$user_id;
        if (!empty($search)) {
          $sql_query .= " AND (`service_name` LIKE '%$search%')";
        }
        $sql_query .= " ORDER BY id DESC LIMIT $start , $end";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    


    function newcomersUser(){
        $controller = new Controller();
        $sql_query = "SELECT pt.id, pt.name, pt.custom_url, pt.img_name, pt.saved_email,ust.year,ust.active_plan FROM `tb_user_profile` as pt inner join tb_user_subscription as ust on ust.user_id = pt.id where ust.year not in('Free Trail (15 days)','Free Trail (5 days)') and ust.active_plan=1 ORDER BY pt.id DESC LIMIT 6";
        // print_r($sql_query);
        // die();
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }



    public function insert($table, $data)
    {
        $controller = new Controller();
        if (!empty($data) && is_array($data)) {
            $colname = '';
            $colval = '';
            $i = 0;
            /*    if(!array_key_exists('modified',$data)){
                    $data['modified'] = date("Y-m-d H:i:s");
                }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colname .= $pre . $key;
                $colval .= $pre . "'$val'";
                $i++;
            }

            $query = "insert into " . $table . " (" . $colname . ") values (" . $colval . ") ";
            $result = $controller->genericGetLastInsertedId($query);
            return $result;
        } else {
            return false;
        }
    }



    function getCounterCount($user_id, $counter_type){
        
        $controller = new Controller();
        $sql_query = "SELECT COUNT(*) AS total_result FROM ".$this->counterTable." WHERE user_id = ".$user_id." AND counter_type ='$counter_type'";
        $result = $controller->genericSelectAlreadyIterated($sql_query);
        if ($result['total_result'] != null) {
            return $result['total_result'];
        } else {
            return 0;
        }
    }

    function getCounterLastSixMonth($user_id, $counter_type) {
        $controller = new Controller();
        $sql_query = "SELECT DATE_FORMAT(created_at, '%b') AS month, COUNT(*) AS count FROM tb_counter WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) and user_id = '$user_id' and counter_type = '$counter_type' GROUP BY MONTH(created_at)";
        $result = $controller->genericSelectToIterate($sql_query);
        return $result;
    }

    

}