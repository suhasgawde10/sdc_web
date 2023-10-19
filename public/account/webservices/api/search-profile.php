<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../../controller/ManageApi.php';
$manage = new ManageAPI();
include '../../controller/EncryptDecrypt.php';
$security = new EncryptDecrypt;
date_default_timezone_set('Asia/Calcutta');
require "protected.php";
$jwtPayloadArray = json_decode(json_encode($decoded), true);
$userId = $jwtPayloadArray['user_id'];

$search = '';
$city = '';
if (isset($_GET['txt_search'])) {
    $search = $_GET['txt_search'];   
}
// dd($search);exit;

if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}
$total_customer = $manage->getActiveUserCount($search, $userId);
dd($total_customer);
$total_records_per_page = 10;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";
$total_records = $total_customer;
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total page minus 1

$displayUser = $manage->getActiveUser($search, $city, $userId, $offset, $total_records_per_page);
// dd($displayUser);exit;
if ($displayUser != null) {
    $count = mysqli_num_rows($displayUser);
} else {
    $count = 0;
    $response = [
        'code'  => 404,
        'status'  => false,
        'message'  => 'Record not found.',
    ];
       
}

$data = [];
if($displayUser != null){
    while ($get_result = mysqli_fetch_array($displayUser)) {
         array_push($data,$get_result);
    }
    $response = [
        'code'  => 200,
        'status'  => true,
        'count' => $count,
        'users' => $data,
        'message'  => 'Record found.',
    ];
}else{
    $response = [
        'code'  => 404,
        'status'  => false,
        'message'  => 'Record not found.',
    ];
}

echo json_encode($response);
exit; 

?>