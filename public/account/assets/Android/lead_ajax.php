<?php
include "../controller/ManageUser.php";
$manage = new ManageUser();

$error = false;
$errorMessage = "";



if(isset($_POST['show_modal']) && $_POST['show_modal'] !=''){
    $show_modal = $_POST['show_modal'];
    $status = $_POST['status'];
    $data = '    <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox11" name="chk_status1" value="Pending" '; if($status=='Pending') $data .='checked';$data .=' />
                                                   <label for="myCheckbox11"><i class="fa fa-clock" aria-hidden="true"></i> <span>Pending</span></label>
                                               </li>
                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox12" name="chk_status1" value="Follow Up" '; if($status=='Follow Up') $data .='checked';$data .=' />
                                                   <label for="myCheckbox12"><i class="fas fa-user-clock"></i> <span>Follow Up</span></label>
                                               </li>
                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox13" name="chk_status1" value="Converted" '; if($status=='Converted') $data .='checked';$data .=' />
                                                   <label for="myCheckbox13"><i class="fas fa-thumbs-up"></i> <span>Converted</span></label>
                                               </li>
                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox14" name="chk_status1" value="Not Interested" '; if($status=='Not Interested') $data .='checked';$data .='  />
                                                   <label for="myCheckbox14"><i class="fas fa-thumbs-down"></i> <span>Not Interested</span></label>
                                               </li>';
    $returnData = array(
        'status' => 'ok',
        'msg' => 'ok',
        'data' => $data
    );
echo json_encode($returnData);
}

if(isset($_POST['update_service_status'])){
    $status = $_POST['status'];
    $update_service_status = $_POST['update_service_status'];
    $condition = array('id'=>$update_service_status);
    $data = array('approve_status'=>$status);
    $update = $manage->update($manage->serviceRequestTable,$data,$condition);
    $returnData = array(
        'status' => 'ok',
        'msg' => 'ok',
        'data' => ''
    );
    echo json_encode($returnData);
}

?>

