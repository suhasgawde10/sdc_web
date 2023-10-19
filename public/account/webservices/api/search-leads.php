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
if (isset($_GET['txt_search'])) {
    $search = $_GET['txt_search'];   
}

if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}
$getLeadsCount = $manage->getLeadsCount($userId);
$total_records_per_page = 10;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";
$total_records = $getLeadsCount;
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total page minus 1

$getLeads = $manage->getLeads($userId, $search, $offset, $total_records_per_page);
if ($getLeads != null) {
    $count = mysqli_num_rows($getLeads);
} else {
    $count = 0;
    $response = [
        'code'  => 404,
        'status'  => false,
        'message'  => 'Record not found.',
    ];
       
}

$data = [];
if($getLeads != null){
    while ($get_result = mysqli_fetch_array($getLeads)) {
         array_push($data,$get_result);
    }
    $response = [
        'code'  => 200,
        'status'  => true,
        'count' => $getLeadsCount,
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