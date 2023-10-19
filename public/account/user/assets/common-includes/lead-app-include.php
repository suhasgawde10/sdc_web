<?php

if(isset($_POST['change'])){
    if(isset($_POST['row_id']) && $_POST['row_id'] !=""){
        $row_id = $security->decryptWebservice($_POST['row_id']);
        $set_status = $_POST['set_status'];
        $condition = array('id'=>$row_id);
        $data = array('approve_status'=>$set_status);
        $update = $manage->update($manage->serviceRequestTable,$data,$condition);
        if($update){
            $error = false;
            $errorMessage = "Status has been changed";
        }else{
            $error = true;
            $errorMessage = "Issue while updating status please try after some time.";
        }
    }
}

$get_total_sub_leads_count = $manage->total_sub_leads_count($id);
$followup_total_count = $get_total_sub_leads_count['total_followup'];
$converted_total_count = $get_total_sub_leads_count['total_converted'];
$not_interest_total_count = $get_total_sub_leads_count['total_not_interest'];
$pending_total_count = $get_total_sub_leads_count['total_pending'];

if($pending_total_count ==null){
    $pending_total_count = 0;
}
if($converted_total_count == null){
    $converted_total_count = 0;
}
if($followup_total_count == null){
    $followup_total_count = 0;
}
if($not_interest_total_count ==null){
    $not_interest_total_count = 0;
}


if(isset($_POST['search'])){
    $txt_name = $_POST['txt_name'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $leadcount = $manage->displayLeadResultWithLimitForFilterCount($id,$approve_status,$offset, $total_records_per_page,$txt_name,$from_date,$to_date);
    $total_records_per_page = 25;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $leadcount;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displaydata = $manage->displayLeadResultWithLimitForFilter($id,$approve_status,$offset, $total_records_per_page,$txt_name,$from_date,$to_date);
}else{
    $get_lead_result = $manage->displayLeadResult($id,$approve_status);
    if ($get_lead_result != null) {
        $leadcount = mysqli_num_rows($get_lead_result);
    } else {
        $leadcount = 0;
    }

    $total_records_per_page = 25;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $leadcount;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displaydata = $manage->displayLeadResultWithLimit($id,$approve_status,$offset, $total_records_per_page);
}

if (isset($_POST["ExportType"])) {
    $get_result1 = $manage->displayLeadResultWithLimit($id,"Follow Up","0", "99999999897");
    $get_result2 = $manage->displayLeadResultWithLimit($id,"Converted","0", "99999999897");
    $get_result3 = $manage->displayLeadResultWithLimit($id,"Not Interested","0", "99999999897");
    $get_result4 = $manage->displayLeadResultWithLimit($id,"Pending","0", "99999999897");
    $i = 0;
    $data =",,Follow Up,\n";
    $data .=",,,,\n";
    $data .= "Date,Name,Contact,Service\n";
    if ($get_result1 != null) {
        while ($get = mysqli_fetch_array($get_result1)) {
            $data .= date('d-M-Y',strtotime($get['created_date'])) .",". $get['client_name'].",".$get['contact_no'].",". $get['service_name']."\n";
        }
    }
    $data .=",,,,\n";
    $data .=",,Converted leads,\n";
    $data .=",,,,\n";
    $data .= "Date,Name,Contact,Service\n";
    if ($get_result2 != null) {
        while ($get2 = mysqli_fetch_array($get_result2)) {
            $data .= date('d-M-Y',strtotime($get2['created_date'])) .",". $get2['client_name'].",".$get2['contact_no'].",". $get2['service_name']."\n";
        }
    }
    $data .=",,,,\n";
    $data .=",,Pending,\n";
    $data .=",,,,\n";
    $data .= "Date,Name,Contact,Service\n";
    if ($get_result4 != null) {
        while ($get4 = mysqli_fetch_array($get_result4)) {
            $data .= date('d-M-Y',strtotime($get4['created_date'])) .",". $get4['client_name'].",".$get4['contact_no'].",". $get4['service_name']."\n";
        }
    }
    $data .=",,,,\n";
    $data .=",,Not Interested,\n";
    $data .=",,,,\n";
    $data .= "Date,Name,Contact,Service\n";
    if ($get_result3 != null) {
        while ($get3 = mysqli_fetch_array($get_result3)) {
            $data .= date('d-M-Y',strtotime($get3['created_date'])) .",". $get3['client_name'].",".$get3['contact_no'].",". $get3['service_name']."\n";
        }
    }
    switch ($_POST["ExportType"]) {
        case "export-to-excel" :
            $file = "My_Leads.csv";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$file);
            echo $data;
            exit();
        default :
            die("Unknown action : " . $_POST["action"]);
            break;
    }
}