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


if (isset($_POST['user_id']) && isset($_POST['counter_type']) && isset($_POST['counter_source']) && isset($_POST['location'])) {
    // Sanitize the data
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $counter_type = filter_var($_POST['counter_type'], FILTER_SANITIZE_SPECIAL_CHARS);
    $counter_source = filter_var($_POST['counter_source'], FILTER_SANITIZE_SPECIAL_CHARS);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_SPECIAL_CHARS);

    $data = [
        'user_id'           =>  $user_id,
        'counter_type'      =>  $counter_type,
        'counter_source'    =>  $counter_source,
        'location'          =>  $location,
        'created_at'        =>  date('Y-m-d G:i:s')
    ];

    $insertCounter = $manage->insert($manage->counterTable, $data);
    if($insertCounter){
        $response = [
            'code'  => 200,
            'status'  => true,
            'message'  => 'Data Updated successfully.',
        ];
    }else{
        $response = [
            'code'  => 500,
            'status'  => true,
            'data' => $data,
            'message'  => 'Oops.. Something went wrong.',
        ];
    }

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