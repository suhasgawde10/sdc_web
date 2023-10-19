<?php
session_start();
include("controller.php");

class ManageApp
{

    public $loginTable = "tb_login";

    public $profileTable = "tb_user_profile";

    public $bankDetailsTable = "tb_bank_details";

    public $clientReviewTable = "tb_client_review";

    public $serviceTable = "tb_services";

    public $imageTable = "tb_image";

    public $videoTable = "tb_video";

    public $clientTable = "tb_clients";

    public $ourTeamTable = "tb_our_team";

    public $gatewayTable = "tb_gateway";

    public $sliderTable = "tb_image_slider";

    public $aboutUsTable = "tb_about_us";

    public $sectionTable = "tb_section";

    public $sectionStatusTable = "tb_section_status";

    public $logoTable = "tb_logo";

    public $mailSettingTable = "tb_mail_setting";

    public $blogTable = "tb_blog";

    public $planTable = "tb_subscription_plan";

    public $dealerProfileTable = "tb_dealer_profile";


    public function displayUser($search, $city, $start, $end)
    {
        $controller = new Controller();
        /*if ($state == "" && $city == "" && $state == "") {
            $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where lt.type='User' and pt.expiry_date > curdate()";
        } else {
            $sql_query = "select * from " . $this->profileTable . " as pt inner join " . $this->loginTable . " as lt on lt.user_id = pt.id where lt.type='User' and (name like '" . $search . "%' or designation like '" . $search . "%' or user_keyword like '%" . $search . "%') and (address like '%" . $city . "%' or address like '%" . $state . "%')";
        }*/
        $query = "call ma_displayActiveUser(?,?,?,?)";
        //  $query = "call ma_displayActiveUser('$search', '$city',$start,$end)";
        $type = "ssii";
        $params = array($search, $city, $start, $end);

        mysqli_set_charset($controller->connect(), 'utf8');
        $result = $controller->genericSelectToIterateUsingProcedure($query, $type, $params);
        return $result;
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

    function getCityCategory()
    {
        $controller = new Controller();
        $sql = "call ma_getCityCategoryCategory()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getSumOfRow($query)
    {
        $controller = new Controller();
        $sql_query = "SELECT COUNT(*) as `num` FROM {$query}";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query);
        return $result;
    }

    function sendSMS($contact, $message)
    {

        $username = "DGCARD";
        $password = "dgcard@123";
        $sendSmsUrl = SMS_URL . "?username=" . urlencode(SMS_USERNAME) . "&apikey=" . urlencode(SMS_APIKEY) . "&apirequest=Text&sender=" . urlencode(SMS_SENDER) . "&mobile=" . urlencode($contact) . "&message=" . urlencode($message) . "&route=TRANS&format=JSON";
        /*        $sendSmsUrl = SMS_URL . "?username=" . urlencode(SMS_USERNAME) . "&sender=" . urlencode($sender_id) . "&to=" . urlencode($contact) . "&message=" . urlencode($message) . "&reqid=1&format=json";*/
        $sendSmsUrl1 = str_replace(" ", "%20", $sendSmsUrl);
        $json = file_get_contents($sendSmsUrl1);
        $json = json_decode($json);
        if ($json->status == "success") {
            return true;
        } else {
            return false;
        }
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


    function displayBlogDetails()
    {
        $controller = new Controller();
        //  $sql_query = "SELECT * from " . $this->blogTable . " where status=1";
        $query = "call displayBlogDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function totalCustomer()
    {
        $controller = new Controller();
        //$sql_query = "SELECT COUNT(id) as total_customer from " . $this->profileTable . " where status=1";
        $sql_query = "call ma_totalCustomer()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($sql_query);
        if (isset($result['total_customer']) != null) {
            return $result['total_customer'];
        } else {
            return 1;
        }
    }

    function totalDealer()
    {
        $controller = new Controller();
        //$sql_query = "SELECT COUNT(id) as total_dealer from " . $this->dealerProfileTable . " where status=1";
        $query = "call ma_totalDealer()";
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query);
        if (isset($result['total_dealer']) != null) {
            return $result['total_dealer'];
        } else {
            return 1;
        }
    }


    function getBlogDetails($id)
    {
        $controller = new Controller();
        // $sql_query = "select * from " . $this->blogTable . " where id=" . $id . "";
        $query = "call ma_getBlogDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectAlreadyIteratedUsingProcedure($query, $type, $params);
        return $result;
    }

    function getRecentBlogDetails($id)
    {
        $controller = new Controller();
        //  $sql_query = "select * from " . $this->blogTable . " where id!=" . $id . " and status=1 ORDER BY RAND() LIMIT 8";
        $query = "call ma_getBlogDetails(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectToIterateUsingProcedure($query, $type, $params);
        return $result;
    }

    function validateBlogId($id)
    {
        $controller = new Controller();
        // $query = "select * from " . $this->blogTable . " where id=" . $id;
        $query = "call ma_validateBlogId(?)";
        $type = "i";
        $params = array($id);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    function validateBlogTitle($title)
    {
        $controller = new Controller();
        //    $query = "select * from " . $this->blogTable . " where title='" . $title . "'";
        $query = "call ma_validateBlogTitle(?)";
        $type = "s";
        $params = array($title);
        $result = $controller->genericSelectCountUsingProcedure($query, $type, $params);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }

    }

    function getCountOfRow($statement, $startpoint, $limit)
    {
        $controller = new Controller();
        $sql_query = "select * from {$statement} LIMIT {$startpoint} , {$limit}";
        $result = $controller->genericSelectToIterateUsingProcedure($sql_query);
        return $result;
    }


    function subscriptionPlan()
    {
        $controller = new Controller();
        //  $sql_query = "select * from " . $this->planTable . " order by id asc";
        $query = "call ma_subscriptionPlan()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function getDealerPriceDetails()
    {
        $controller = new Controller();
        //   $sql_query = "update " . $this->dealerProfileTable . " set status=1,approve_status='" . $approved_status . "',pay_status='$pay_status' where id ='" . $user_id . "'";
        $sql = "call md_getDealerPriceDetails()";
        $result = $controller->genericSelectToIterateUsingProcedure($sql);
        return $result;
    }

    function getUserSubscriptionPlan()
    {
        $controller = new Controller();
        //    $sql_query = "select * from " . $this->dealerPlanTable . " where year!='Free Trail (5 days)' order by year";
        $query = "call mu_subscriptionPlan()";
        $result = $controller->genericSelectToIterateUsingProcedure($query);
        return $result;
    }

    function getTodaysUserWithBlackListedKeywords($today_date)
    {
        $blacklist = array('paytm', 'care', 'helpline', 'customer','credit card','debit card','bank care','gpay','google pay','phone pe');
        $controller = new Controller();
        $sql = "";
        foreach ($blacklist as $item) {
            $sql .= "SELECT * FROM tb_user_profile where (name like '%$item%' or custom_url like '%$item%' or company_name like '%$item%') and created_date='$today_date' union ";
        }
        $finalSql = substr($sql, 0, strlen($sql) - 7);
        $result = $controller->genericSelectToIterate($finalSql);
        return $result;
    }

    function updateBlacklistUserStatus($user_id)
    {
        $controller = new Controller();
        $sql_query = "update tb_user_profile set status=0 where id=" . $user_id;
        $result = $controller->genericInsertUpdateDelete($sql_query);
        return $result;
    }

}