<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();
$error = false;
$errorMessage = "";
// && isset($_GET['api_key'])
if (isset($_GET['user_id'])) {
    $id = $security->decryptWebservice($_GET['user_id']);
    $api_key = $security->decryptWebservice($_GET['api_key']);
    $validateId = $manage->validateUserIdAndAPIKey($id,$api_key);
}else{

}
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}
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
    $approve_status = $_POST['chk_status'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $drp_service = $_POST['drp_service'];
    $leadcount = $manage->mu_displayLeadResultWithLimitForFilterCountForAndroid($id,$approve_status,$from_date,$to_date,$drp_service);
    $total_records_per_page = 50;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $leadcount;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displaydata = $manage->mu_displayLeadResultWithLimitForAndroid($id,$offset, $total_records_per_page,$approve_status,$from_date,$to_date,$drp_service);
}else{
    $get_lead_result = $manage->mu_displayLeadResultWithLimitForFilterCountForAndroid($id);
    if ($get_lead_result != null) {
        $leadcount = mysqli_num_rows($get_lead_result);
    } else {
        $leadcount = 0;
    }

    $total_records_per_page = 50;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_records = $leadcount;
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1
    $displaydata = $manage->mu_displayLeadResultWithLimitForAndroid($id,$offset, $total_records_per_page);
}
function fetch_all_data($result)
{
    $all = array();
    while($thing = mysqli_fetch_array($result)) {
        $all[] = $thing;
    }
    return $all;
}



$get_service = $manage->displayServiceDetailsForAndroid($id);

?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "assets/common-includes/total_count.php" ?>
    <title>Follow Up Leads</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
     
        @media (max-width: 480px){
            .footer1_div {
                margin: 6px 0 0px -16px;
            }
        }
        .nav-tabs > li {
            width: 49%
        }
        .nav > li > a {
            padding: 10px 3px;
        }
        .collapsible {
            background-color: #1f91f3;
            color: white;
            cursor: pointer;
            padding: 12px 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

       .collapsible:hover {
            background-color: #1f91f3;
        }

        .collapsible:after {
           content: "\f107";
    color: white;
    font-weight: bold;
    float: right;
    margin-left: 5px;
    font-family: 'Font Awesome\ 5 Free';
        }

        .active:after {
            content: "\f106";
               font-family: 'Font Awesome\ 5 Free';
        }

        .content {
            padding: 0 10px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            background-color: #fff;
        }
        /*label start*/

        /*theme select*/
        .company_ul{
            list-style-type: none;
            margin: 0;
            padding: 0;
            padding-top: 30px;
        }
        .company_ul li.company_ul_li {
            display: inline-block;
            width: 49%;
            text-align: left;
            margin: 0;
        }

        .company_ul > li.company_ul_li > input[type="radio"][id^="myCheckbox"] {
            display: none;
        }

        .company_ul > li.company_ul_li > label {
            border: 1px solid #ddd;
            padding: 10px;
            display: block;
            position: relative;
            margin: 0 10px 10px;
            cursor: pointer;
        }

        .company_ul > li.company_ul_li > label:before {
            background-color: white !important;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid #13af13;
            position: absolute;
            top: 0;
            right: 0;
            left:unset;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
            z-index: 9;
        }

        .company_ul > li.company_ul_li > label img {
            height: auto;
            width: 100%;
            max-width: 100%;
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
            margin-bottom: 235px;
            border: 1px solid #ccc;
        }

        .company_ul > li.company_ul_li > :checked + label {
            border-color: #ddd;
        }

        .company_ul > li.company_ul_li > :checked + label:before {
            content: "✓";
            background-color: #13af13 !important;
            transform: scale(1);
            border: none !important;

        }
        .company_ul > li.company_ul_li > :checked + label:after {
            background-color: unset !important;
            border: none !important;

        }

        .company_ul > li.company_ul_li > :checked + label img {
            transform: scale(0.9);
            /* box-shadow: 0 0 5px #333; */
            z-index: -1;
        }
        [type="radio"]:not(:checked) + label,[type="radio"]:checked + label{
            height: auto;
        }

        .table tbody tr td, .table tbody tr th {
            border-top: none;
            border-bottom: none;
        }
        .card .body{
            padding: 0;
        }
        .filter{
            padding-top: 10px ;
            padding-bottom: 15px ;
        }
        .default_label_cust{
            padding: 6px 12px;
            border-radius: 5px;
        }
        .btn:not(.btn-link):not(.btn-circle) i{
            font-size: 16px;
        }
        .modal_margin {
            margin: 0%;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .modal_width {
            width: 100%;
            border-radius: 31px 31px 0px 0px;
        }
        .card{
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgb(0 0 0 / 10%);
        }
        /*label end*/
    </style>

</head>
<body>


<section class="user_contact">
    <div class="clearfix padding_bottom_46">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding_zero padding_zero_both">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-8 col-sm-12 col-xs-12">

                            <div >


                                <button class="collapsible">Filter <!--<i class="fa fa-angle-down" aria-hidden="true"></i>
                                    <i class="fa fa-angle-up" aria-hidden="true"></i>-->
                                </button>
                                <div class="content">
                                    <form method='post' action=''>
                                       <div class="filter">
                                           <ul class='ul_search'>
                                               <li class='ul_search_li'>
                                                   <div class='form-line'>
                                                       <label>From Date</label>
                                                       <input type='date' name='from_date' class='form-control'
                                                              value='<?php if(isset($_POST['from_date'])) echo $_POST['from_date']; ?>' >
                                                   </div>
                                               </li>
                                               <li class='ul_search_li'>
                                                   <div class='form-line'>
                                                       <label>To Date</label>
                                                       <input type='date' name='to_date' class='form-control'
                                                              value='<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; ?>'>
                                                   </div>
                                               </li>

                                           </ul>
                                           <h4>Status</h4>

                                           <div class="col-xs-12">
                                               <select name="drp_service" class="form-control">

                                                   <option value="">Select Service</option>
                                                   <?php
                                                   if($displaydata !=null) {
                                                       $get_service_data = fetch_all_data($displaydata);
                                                       $new_array = array();
                                                       foreach ($get_service_data as $row){
                                                           if (!in_array(trim($row['service_name']), $new_array) ) {
                                                               array_push($new_array,$row['service_name']);
                                                               ?>
                                                               <option <?php if (isset($_POST['drp_service']) && $_POST['drp_service'] == $row['service_name']) echo "selected"; ?>><?php echo substr(strip_tags($row['service_name']), 0, 20); ?></option>
                                                               <?php
                                                           }
                                                       }
                                                   }
                                                   ?>
                                               </select>
                                           </div><br>
                                           <ul class="company_ul">

                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox1" name="chk_status" value="Pending" <?php if(isset($_POST['chk_status']) && $_POST['chk_status']=='Pending') echo 'checked'; ?> />
                                                   <label for="myCheckbox1"><i class="fa fa-clock" aria-hidden="true"></i> <span>Pending</span></label>
                                               </li>
                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox2" name="chk_status" value="Follow Up" <?php if(isset($_POST['chk_status']) && $_POST['chk_status']=='Follow Up') echo 'checked'; ?> />
                                                   <label for="myCheckbox2"><i class="fas fa-user-clock"></i> <span>Follow Up</span></label>
                                               </li>
                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox3" name="chk_status" value="Converted" <?php if(isset($_POST['chk_status']) && $_POST['chk_status']=='Converted') echo 'checked'; ?> />
                                                   <label for="myCheckbox3"><i class="fas fa-thumbs-up"></i> <span>Converted</span></label>
                                               </li>
                                               <li class="company_ul_li">
                                                   <input type="radio" id="myCheckbox4" name="chk_status" value="Not Interested" <?php if(isset($_POST['chk_status']) && $_POST['chk_status']=='Not Interested') echo 'checked'; ?> />
                                                   <label for="myCheckbox4"><i class="fas fa-thumbs-down"></i> <span>Not Interested</span></label>
                                               </li>

                                           </ul>
                                           <div class="text-center">
                                                   <button type='submit' name='search' class='btn btn-primary'>Apply</button>
                                                   <a type='button' href="" class='btn btn-danger'>Cancel</a>
                                               </div>
                                       </div>
                                    </form>
                                </div>

                            </div>
                         <!--   <ul class="nav nav-tabs tab-nav-right marg-top-5" role="tablist">
                                <li role="presentation" class="active" ><a class="custom_nav_tab" href="#follow" data-toggle="tab"><i class="fa fa-flag"></i> Follow UP <span class="badge badge-success"><?php /*echo $followup_total_count; */?></span></a></li>
                                <li role="presentation" ><a class="custom_nav_tab" href="converted-leads.php?user_id=<?php /*echo $_GET['user_id']; */?>" ><i class="fa fa-check-square-o" aria-hidden="true"></i> Converted <span class="badge badge-success"><?php /*echo $converted_total_count */?></span></a></li>
                                <li role="presentation"><a class="custom_nav_tab" href="not-interested.php?user_id=<?php /*echo $_GET['user_id']; */?>" ><i class="fa fa-exclamation-triangle"></i> Not Interested <span class="badge badge-success"><?php /*echo $not_interest_total_count */?></span></a></li>
                            </ul>-->
                            <!-- Tab panes -->
                            <div style="padding-top: 10px">
                                <?php
                                if ($displaydata != null) {
                                    $i=1;
                                foreach ($get_service_data as $result_lead_data){
                                        ?>
                                        <div class="card">
                                            <div class="body">
                                                <table class="table table-borderless lead_table">
                                                    <tr class="lead_table_tr"><!-- data-toggle="modal" data-target="#shareModal" -->
                                                        <td><label onclick="showStatusModal(this,'<?php echo $result_lead_data['id']; ?>')" data-status="<?php echo $result_lead_data['approve_status']; ?>" id="rename_status<?php echo $i ?>" class="label label-<?php if($result_lead_data['approve_status'] == 'Pending') echo "warning";elseif ($result_lead_data['approve_status'] == 'Follow Up') echo "primary";elseif ($result_lead_data['approve_status'] == 'Converted')echo "success";elseif ($result_lead_data['approve_status'] == 'Not Interested')echo "danger"; ?> default_label_cust"><?php
if($result_lead_data['approve_status'] == 'Pending') echo '<i class="fa fa-clock" aria-hidden="true">';elseif ($result_lead_data['approve_status'] == 'Follow Up') echo '<i class="fas fa-user-clock"></i>';elseif ($result_lead_data['approve_status'] == 'Converted')echo '<i class="fas fa-thumbs-up"></i>';elseif ($result_lead_data['approve_status'] == 'Not Interested')echo '<i class="fas fa-thumbs-down"></i>';
                                                        ?> <?php echo $result_lead_data['approve_status']; ?></label></td>
                                                        <td class="text-right">
                                                            <label class=""><?php echo date('d-M-Y',strtotime($result_lead_data['created_date'])); ?></label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" >
                                                            <?php echo $result_lead_data['client_name'] . " | " . $result_lead_data['contact_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <?php echo "Enquiry For ". $result_lead_data['service_name']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class='form-inline'>
                                                                <?php
                                                                if(is_numeric($result_lead_data['contact_no'])) {
                                                                    ?>
                                                                    <a href="tel:<?php echo $result_lead_data['contact_no']; ?>"
                                                                       class='btn btn-default'><i
                                                                            class="fas fa-phone fa-flip-horizontal	"></i>
                                                                        Call Now</a>&nbsp;&nbsp;&nbsp;
                                                                    <a type='button'
                                                                       href="smsto:<?php echo $result_lead_data['contact_no']; ?>"
                                                                       class='btn btn-default'><i
                                                                            class="fas fa-comment-alt"></i> &nbsp;Message</a>
                                                                    <?php
                                                                }else{
                                                                   ?>
                                                                    <a type='button'
                                                                       href="mailto:<?php echo $result_lead_data['contact_no']; ?>"
                                                                       class='btn btn-default'><i
                                                                            class="fa fa-envelope"></i> &nbsp;Email</a>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-xs-12 no_contact_found">
                                        <img src="assets/img/notebook.png">

                                        <p>No Data Found</p>
                                    </div>
                                    <?php
                                }
                                if ($displaydata != null) {
                                    ?>

                                    <div>

                                        <ul class="pagination m-0">
                                            <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>

                                            <li <?php if ($page_no <= 1) {
                                                echo "class='disabled'";
                                            } ?>>
                                                <a <?php if ($page_no > 1) {
                                                    echo "href='?page_no=$previous_page'";
                                                } ?>>Previous</a>
                                            </li>

                                            <?php
                                            if ($total_no_of_pages <= 10) {
                                                for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                                    if ($counter == $page_no) {
                                                        echo "<li class='active'><a>$counter</a></li>";
                                                    } else {
                                                        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                    }
                                                }
                                            } elseif ($total_no_of_pages > 10) {

                                                if ($page_no <= 4) {
                                                    for ($counter = 1; $counter < 8; $counter++) {
                                                        if ($counter == $page_no) {
                                                            echo "<li class='active'><a>$counter</a></li>";
                                                        } else {
                                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                        }
                                                    }
                                                    echo "<li><a>...</a></li>";
                                                    echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                                    echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                                    echo "<li><a href='?page_no=1'>1</a></li>";
                                                    echo "<li><a href='?page_no=2'>2</a></li>";
                                                    echo "<li><a>...</a></li>";
                                                    for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                                        if ($counter == $page_no) {
                                                            echo "<li class='active'><a>$counter</a></li>";
                                                        } else {
                                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                        }
                                                    }
                                                    echo "<li><a>...</a></li>";
                                                    echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                                                    echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                } else {
                                                    echo "<li><a href='?page_no=1'>1</a></li>";
                                                    echo "<li><a href='?page_no=2'>2</a></li>";
                                                    echo "<li><a>...</a></li>";

                                                    for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                                        if ($counter == $page_no) {
                                                            echo "<li class='active'><a>$counter</a></li>";
                                                        } else {
                                                            echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                                                        }
                                                    }
                                                }
                                            }
                                            ?>

                                            <li <?php if ($page_no >= $total_no_of_pages) {
                                                echo "class='disabled'";
                                            } ?>>
                                                <a <?php if ($page_no < $total_no_of_pages) {
                                                    echo "href='?page_no=$next_page'";
                                                } ?>>Next</a>
                                            </li>
                                            <?php if ($page_no < $total_no_of_pages) {
                                                echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                                            } ?>
                                        </ul>
                                        <div style="padding-left: 10px;">
                                            <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                            </div>


                </div>
            </div>


        </div>
        </div>
</section>
<div class="modal modal_padding animated fadeInUpBig cust-model" id="shareModal" role="dialog">
    <div class="modal-dialog modal_margin">
        <div class="modal-content modal_width">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cust-model-heading">Change Status</h4>
            </div>
            <div class="modal-body">
                <div class="form-model">
                    <ul class="company_ul" id="modal_company_ul">

                    </ul>
                    <div class=" text-center">
                        <div class='form-inline'>
                            <input type="hidden" id="edit_id">
                            <input type="hidden" id="edit_status">

                            <button type='button' onclick="updateServiceRequest()" name='search' class='btn btn-primary'>Apply</button>
                            <a type='button' href="" class='btn btn-danger'>Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $(document).ready(function () {
        $("input[name=chk_status1]").click(function () {
            $('input[type=radio]').removeAttr('checked');
            $(this).find('input[type=radio]').attr('checked', 'checked');
        });
    })
</script>
<script>
    function showStatusModal(this_value,id) {
        $('#edit_id').val(id);
        $('#edit_status').val($(this_value).attr('id'));
        //
        var dataString="show_modal="+id+"&status="+$(this_value).attr('data-status');
        $.ajax({
            type: 'POST',
            url: 'lead_ajax.php',
            dataType: "json",
            data: dataString,
            // beforeSend: function () {
            //     $('#get_otp').text('Sending Otp...').attr("disabled", 'disabled');
            //     $('input[name=q_name]').attr("disabled", 'disabled');
            //     $('input[name=q_contact_no]').attr("disabled", 'disabled');
            // },
            success: function (response) {

                $('#modal_company_ul').html(response.data);
                $('#shareModal').modal('show');
            },
            error: function (err) {
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    }
    function updateServiceRequest(this_value) {
       var id = $('#edit_id').val();
       var statu =  $("input[name='chk_status1']:checked").val();
        var dataString="update_service_status="+id+"&status="+statu;
        console.log(dataString);
        $.ajax({
            type: 'POST',
            url: 'lead_ajax.php',
            dataType: "json",
            data: dataString,
            beforeSend: function () {
               // $(this_value).text('Applying...').attr("disabled", 'disabled');
            },
            success: function (response) {
                $renameclass = $('#edit_status').val();
                $('#'+$renameclass).text(statu).attr('data-status',statu);
              //  $(this_value).text('Applied').removeAttr("disabled");
                $('#shareModal').modal('hide');
            },
            error: function (err) {
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    }
</script>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
</script>

<script type="text/javascript">

    function SetStatus(i) {
        $('#submit_form'+i)[0].click();
    }
</script>
</body>
</html>