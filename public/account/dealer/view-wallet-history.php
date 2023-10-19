<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();


if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}

$maxsize = 10485760;

$error = false;
$errorMessage = "";
$error1 = false;
$errorMessage1 = "";
$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];     $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
    $id = $display_message['id'];
    $wallet_amount = $display_message['wallet_amount'];

}



if (isset($_GET['dealer_code'])) {
    $dealer_code = $security->decryptWebservice($_GET['dealer_code']);
}
$get_result = $manage->displayWallerHistoryByDealer($dealer_code);
if ($get_result != null) {
    $customerCount = mysqli_num_rows($get_result);
} else {
    $customerCount = 0;
}

if(isset($_POST['search'])){
    if(isset($_POST['drp_pay_status']) && $_POST['drp_pay_status'] !="All"){
        $get_result = $manage->displayWallerHistoryByDealerByPayStatus($dealer_code,$_POST['drp_pay_status']);
        if ($get_result != null) {
            $customerCount = mysqli_num_rows($get_result);
        } else {
            $customerCount = 0;
        }

    }
}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Basic Information</title>
    <?php include "assets/common-includes/header_includes.php" ?>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>

<section class="content">

    <div class="clearfix">
        <!--<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
            <div class="margin_div1">
                <div class="card">

                    <div class="body card_padding">
                        <form id="basic_user_profile" method="POST" action="" enctype="multipart/form-data">
                            <ul class="profile-left-ul">

                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>-->

        <div class="col-lg-12 col-md-5 col-sm-12 col-xs-12 padding_both">
            <div class="row margin_div_web">
                <div class="card">
                    <form method="post" action="">
                        <div class="header">

                                <div class="col-md-6">
                                    <div class="row">
                                        <h4>
                                            Manage Wallet History <span class="badge"><?php
                                                if (isset($customerCount)) echo $customerCount;
                                                ?></span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <div class="row">
                                       <form method="post" action="">
                                           <div class="col-md-8">
                                               <select class="form-control show-tick" name="drp_pay_status">
                                                   <option>All</option>
                                                   <option value="pending" <?php if(isset($_POST['drp_pay_status']) && $_POST['drp_pay_status'] == "pending") echo "selected"; ?>>Unpaid</option>
                                                   <option value="completed" <?php if(isset($_POST['drp_pay_status']) && $_POST['drp_pay_status'] == "completed") echo "selected"; ?>>Paid</option>
                                               </select>
                                           </div>
                                           <div class="col-md-4 text-left">
                                               <div class="row">
                                                   <button class="btn btn-primary" type="submit" name="search">Search</button>

                                                   <a class="btn btn-danger" href="">
                                                       Clear filter</a>
                                               </div>
                                           </div>
                                       </form>

                                   </div>
                                </div>
                                <!--<div class="col-md-2 text-right">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#addUser">Add User</button>
                                </div>-->

                        </div>
                        <div class="body">

                            <?php if ($error) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                </div>
                            <?php
                            } else if (!$error && $errorMessage != "") {
                                ?>
                                <div class="alert alert-success">
                                    <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                </div>
                            <?php
                            }
                            ?>
                            <table class="table table-striped table-bordered table-sm "
                                   cellspacing="0" id="dtHorizontalVerticalExample"
                                   width="100%"> <!--    -->
                                <thead>
                                <tr class="back-color">
                                    <th>Customer</th>
                                  <!--  <th>Beneficiary</th>-->
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Remark</th>
                                    <!--<th>Type</th>-->
                                    <th>Payment Status</th>
                                    <th>Paid Date</th>
                                    <th>Paid remark</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                if ($get_result != null) {
                                    while ($row = mysqli_fetch_array($get_result)) {
                                        ?>
                                        <tr>
                                            <td><?php
                                                $get_user = $manage->displayUserData($row['user_id']);
                                                echo $get_user['name']; ?></td>
                                          <!--  <td><?php
/*                                                if ($row['beneficiary_id'] != null) {
                                                    $get_user = $manage->displayUserData($row['beneficiary_id']);
                                                    echo $get_user['name'];
                                                } */?></td>-->
                                            <td class="nr"><?php echo $row['amount']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $row['remark']; ?></td>
                                           <!-- <td><?php /*echo $row['type']; */?></td>-->
                                            <td>
                                                <label
                                                    class="label <?php if ($row['payment_status'] == "pending") {
                                                        echo "label-danger";
                                                    } else {
                                                        echo "label-success";
                                                    } ?>"><?php echo $row['payment_status']; ?></label>
                                            </td>
                                            <td><?php echo $row['payment_date']; ?></td>
                                            <td><?php echo $row['payment_remark']; ?></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No data found!</td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<?php include "assets/common-includes/footer_includes.php" ?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog cust-model-width">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pay Amount</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <form id="upi_form_validation" method="POST" action="">
                        <?php if ($error1) {
                            ?>
                            <div class="alert alert-danger">
                                <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                            </div>
                        <?php
                        } else if (!$error1 && $errorMessage1 != "") {
                            ?>
                            <div class="alert alert-success">
                                <?php if (isset($errorMessage1)) echo $errorMessage1; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <input type="hidden" name="txt_id">
                        <div>
                            <label class="form-label">Payable amount</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input name="pending_amount" class="form-control"
                                           placeholder="Payable amount">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Select date</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input name="txt_date" class="form-control"
                                           type="date">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Remark</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <textarea class="form-control" name="txt_remark"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form_inline form-float">
                            <button class="btn btn-primary waves-effect form-control"
                                    name="btn_update" type="submit">
                                Pay Amount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $(".user_amount").click(function () {
        var $row = $(this).closest("tr");    // Find the row
        var amt = $row.find(".nr").text(); // Find the text

    });
    $(document).ready(function () {

        var count;

        $(".checkbox1").change(function () {
            //Create an Array.
            var selected = new Array();

            count = 0;
            $('input[type="checkbox"]:checked').each(function () {
                selected.push(this.value);
                var $row = $(this).closest("tr");    // Find the row
                var amt = $row.find(".nr").text(); // Find the text
                count += parseInt(amt, 10);
            });
            if (selected.length > 0) {
                $('input[name=txt_id]').val(selected.join(","));
            }
            $('input[name=pending_amount]').val(count);
        });

    });
</script>

<!--<script type="text/javascript">
    document.getElementById("b3").onclick = function () {
        swal("Good job!", "You clicked the button!", "success");
    };
</script>-->


</body>
</html>