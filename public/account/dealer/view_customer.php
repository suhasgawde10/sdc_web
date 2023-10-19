<?php
ob_start();
include "../controller/ManageDealer.php";
$manage = new ManageDealer();
include "../controller/validator.php";
$validate = new Validator();
include '../sendMail/sendMail.php';
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$alert_status = false;
$failed_status = false;


if (!isset($_SESSION['dealer_email'])) {
    header('location:../dealer-register.php');
}

$error = false;
$errorMessage = "";

if (isset($_GET['user_id'])) {
    $user_id = $security->decrypt($_GET['user_id']);
    $form_data = $manage->getSpecificUserProfileById($user_id);
    if ($form_data != null) {
//        print_r($form_data);
        $name = $form_data['name'];
        $designation = $form_data['designation'];
        $gender = $form_data['gender'];
        $date_of_birth = $form_data['date_of_birth'];
        $contact = $form_data['contact_no'];
        $alter_contact_no = $form_data['altr_contact_no'];
        $email = $form_data['email'];
//        $profilePath = "../user/uploads/" . $email . "/profile/" . $form_data['img_name'];
        $profilePaths = "../user/uploads/" . $email . "/profile/" . $form_data['img_name'];
       /* echo $profilePath;
        exit;*/

    }
    $displaySubscription = $manage->displaySubscriptionDetails($user_id);
    if ($displaySubscription != null) {
        $countDescription = mysqli_num_rows($displaySubscription);
    } else {
        $countDescription = 0;
    }

    /*if($displaySubscription!=null){
        $userName = $displaySubscription['name'];
        $userContact = $displaySubscription['contact'];
        $type = $displaySubscription['type'];
        if($type==1){
            echo "Digital Card";
        }else{
            echo "Digital Card + Website (combo)";
        }
        $year = $displaySubscription['year'];
        $amount = $displaySubscription['amount'];
        $startDate = $displaySubscription['start_date'];
        $endDate = $displaySubscription['end_date'];
    }*/

}
$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
      $dealer_status = $display_message['status'];
    $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];


}


/*echo $profilePath;
echo $gender;
echo $form_data['img_name'];
exit;*/
?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>User Mangement</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">

    <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">

        <div class="card">
            <div class="body card_padding">
                <form id="basic_user_profile" method="POST" action="" enctype="multipart/form-data">
                    <ul class="profile-left-ul">
                        <li>
                            <div class="form-group form-float text-align-profile" style="position: relative">
                                <img
                                    src="<?php if ($profilePaths && $gender == "Male" && $form_data['img_name'] == "") {
                                        echo "uploads/male_user.png";
                                    } elseif ($profilePaths && $gender == "Female" && $form_data['img_name'] == "") {
                                        echo "uploads/female_user.png";
                                    } else {
                                        echo $profilePaths;
                                    } ?>" style="width: 50%;border-radius: 50%;">

                            </div>
                        </li>
                        <li>
                            <div class="width-prf">
                                <label class="form-label"><i class="fas fa-user"></i></label>

                                <div class="form-group form-group-left form-float">
                                    <div class="">
                                        <lable name=label_txt_name"
                                               class="form-control"> <?php if (isset($name)) echo $name; ?></lable>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="width-prf">
                                <label><i class="fas fa-restroom"></i></label>

                                <div class="form-group form-group-left form-float">
                                    <div class="">
                                        <lable name=label_txt_gender"
                                               class="form-control"> <?php if (isset($gender)) echo $gender; ?></lable>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="width-prf">
                                <label><i class="fa fa-phone"></i></label>

                                <div class="form-group form-group-left form-float">
                                    <div class="">
                                        <lable name=label_txt_gender"
                                               class="form-control"> <?php if (isset($contact)) echo $contact; ?></lable>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="width-prf">
                                <label><i class="fas fa-envelope"></i></label>

                                <div class="form-group form-group-left form-float">
                                    <div class="">
                                        <lable name=label_txt_gender"
                                               class="form-control"> <?php if (isset($email)) echo $email; ?></lable>
                                    </div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Manage Subscription details <span class="badge"><?php
                        if (isset($countDescription)) echo $countDescription;
                        ?></span>
                </h2>
            </div>
            <div class="body table-responsive table_scroll">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                    <tr class="back-color">
                        <!--<th>Name</th>
                        <th>Contact</th>-->
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($displaySubscription != null) {
                        while ($result_data = mysqli_fetch_array($displaySubscription)) {
                            ?>
                            <tr <?php if ($result_data['status'] == "success") {
                                echo 'style="background-color: #B2DFDB"';
                            } elseif ($result_data['status'] == "failed") {
                                echo 'style="background-color: #FFCDD2"';
                            }; ?>>
                                <!--<td><?php /*echo $result_data['name']; */ ?></td>
                                    <td><?php /*echo $result_data['contact']; */ ?></td>-->
                                <!--<td><?php /*if ($result_data['type'] == 1) {
                                            echo "Digital Card";
                                        } else {
                                            echo "Digital Card + Website (combo)";
                                        } */ ?></td>-->
                                <td><?php echo $result_data['year']; ?></td>
                                <td><?php echo $result_data['total_amount']; ?></td>
                                <td><?php echo $result_data['start_date']; ?></td>
                                <td><?php echo $result_data['end_date']; ?></td>
                                <td><label class="label <?php if ($result_data['active_plan'] == "0") {
                                        echo "label-danger";
                                    } else {
                                        echo "label-success";
                                    } ?>"><?php if ($result_data['active_plan'] == 0) {
                                            echo "Expired";
                                        } else {
                                            echo "Active";
                                        } ?></label></td>
                                <td>
                                    <a class="btn btn-default" <?php if ($result_data['invoice_no'] == "" or $result_data['invoice_no'] == 'NULL') {
                                        echo 'disabled';
                                        echo ' href="#"';
                                    } else {
                                        echo 'href="user-invoice.php?user_invoice_id=' . $security->encrypt($result_data['id']) . '"';
                                    } ?>>View Invoice</a></td>
                                <!--<td><label class="label <?php /*if ($result_data['status'] == "0") {
                                            echo "label-danger";
                                        } else {
                                            echo "label-success";
                                        } */ ?>"><?php /*if ($result_data['status'] == 0) {
                                                echo "Unpublished";
                                            } else {
                                                echo "Published";
                                            } */ ?></label></td>-->

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
        </div>
    </div>

</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>