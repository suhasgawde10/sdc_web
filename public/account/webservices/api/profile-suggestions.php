
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


$getUserSuggestions = $manage->getUserSuggestions($search, $userId);
// dd($displayUser);exit;
if ($getUserSuggestions != null) {
    $count = mysqli_num_rows($getUserSuggestions);
} else {
    $count = 0;
    $response = [
        'code'  => 404,
        'status'  => false,
        'message'  => 'Record not found.',
    ];
       
}

$data = [];
if($getUserSuggestions != null){
    while ($get_result = mysqli_fetch_array($getUserSuggestions)) {
         array_push($data,$get_result);
    }
    $response = [
        'code'  => 200,
        'status'  => true,
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