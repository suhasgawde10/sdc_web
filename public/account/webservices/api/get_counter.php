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


if (isset($_POST['user_id']) && isset($_POST['counter_type'])) {
    // Sanitize the data
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $counter_type = filter_var($_POST['counter_type'], FILTER_SANITIZE_SPECIAL_CHARS);
   


    $getCounterCount = $manage->getCounterCount($user_id,$counter_type);
    $getCounterLastSixMonth = $manage->getCounterLastSixMonth($user_id,$counter_type);
    $data = [];
    if($getCounterLastSixMonth != null){
        while ($get_result = mysqli_fetch_array($getCounterLastSixMonth)) {
            array_push($data,$get_result);
        }
        $response = [
            'code'  => 200,
            'status'  => true,
            'count' => $getCounterCount,
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




    // if($getCounterCount){
    //     $response = [
    //         'code'  => 200,
    //         'status'  => true,
    //         'count' => $getCounterCount,
    //         'message'  => 'Data Updated successfully.',
    //     ];
    // }else{
    //     $response = [
    //         'code'  => 500,
    //         'status'  => true,
    //         'data' => $data,
    //         'message'  => 'Oops.. Something went wrong.',
    //     ];
    // }

} else {
    $response = [
        'code'  => 403,
        'status'  => false,
        'message'  => 'One or more fields are missing.',
    ];

}
   
echo json_encode($response);
exit; 

?>