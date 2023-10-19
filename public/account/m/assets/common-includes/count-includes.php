<?php

$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


$page_type = basename($_SERVER['PHP_SELF']);
$date = date("Y-m-d");

if (isset($_GET['custom_url'])) {
    $custom_url = $_GET['custom_url'];
    $getUserId = $manage->mdm_getDigitalCardDetailsOFUser($custom_url);
    if ($getUserId != null) {
        $user_id = $getUserId['user_id'];
        $section_user_id = $getUserId['user_id'];
    }
    $ip_aadr = (string) $xml->geoplugin_request;

    $city = (string) $xml->geoplugin_city;
    $region = (string) $xml->geoplugin_region;
    $countryName = (string) $xml->geoplugin_countryName;
    $getCountOfUser = $manage->countUserdId($user_id, $page_type,$ip_aadr);
    if ($getCountOfUser == 0) {
        $count = 1;
        $insertUserCount = $manage->insertUserCount($user_id, $page_type, $count,$ip_aadr,$city,$region,$countryName);
    } else {
        /*$getUserCount = $manage->getUserId($user_id, $page_type);
        if ($getUserCount != null) {
            $countUser = $getUserCount['count'];
        }
        $count = $countUser + 1;
        $updateCount = $manage->updateUserCount($count, $user_id, $page_type,$ip_aadr,$city,$region,$countryName);*/
    }

    $ProfileSectionStatus = $manage->getSectionStatus($section_user_id, 10);
    $ServiceSectionStatus = $manage->getSectionStatus($section_user_id, 1);
    $ProductSectionStatus = $manage->getSectionStatus($section_user_id, 11);
    $gallerySectionStatus = $manage->getSectionStatus($section_user_id, 2);
    $ClientSectionStatus = $manage->getSectionStatus($section_user_id, 4);
    $TeamSectionStatus = $manage->getSectionStatus($section_user_id, 6);
    $BankSectionStatus = $manage->getSectionStatus($section_user_id, 7);


    $profileTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 10);
    $ServiceTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 1);
    $ProductTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 11);
    $galleryTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 2);
    $ClientTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 4);
    $TeamTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 6);
    $BankTabIcon = $manage->getSectionImageDataByCustom($section_user_id, 7);

    $statusUnhideCount = $manage->getSectionStatusCountUnhide($section_user_id);


}


?>