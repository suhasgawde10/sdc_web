<?php

include "../controller/ManageUser.php";
$manage = new ManageUser();
include "../controller/validator.php";
$validate = new Validator();
include "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include '../sendMail/sendMail.php';
$alert_status = false;
$failed_status = false;
if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}elseif(isset($_SESSION['email']) && isset($_SESSION['type']) && $_SESSION['type'] =='User'){
    header('location:../login.php');
}

$error = false;
$errorMessage = "";

if (isset($_GET['user_id'])) {
    $user_id = $security->decrypt($_GET['user_id']);
    $form_data = $manage->getSpecificUserProfileById($user_id);
    if ($form_data != null) {
        $name = $form_data['name'];
        $designation = $form_data['designation'];
        $gender = $form_data['gender'];
        $final_gender = $gender;
        $final_gender2 = $final_gender;
        $date_of_birth = $form_data['date_of_birth'];
        $contact = $form_data['contact_no'];
        $alter_contact_no = $form_data['altr_contact_no'];
        $email = $form_data['email'];
        $profilePaths = "uploads/" . $email . "/profile/" . $form_data['img_name'];
        $newprofilepath = $profilePaths;
        $newprofilepath2 = $newprofilepath;
    }
    $displaySubscription = $manage->displaySubscriptionDetailsById($user_id);
    if ($displaySubscription != null) {
        $countDescription = mysqli_num_rows($displaySubscription);
    } else {
        $countDescription = 0;
    }
    $displayLog = $manage->displayLogDetails($user_id);
    if ($displayLog != null) {
        $countLog = mysqli_num_rows($displayLog);
    } else {
        $countLog = 0;
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
    if (isset($_POST['send_log'])) {
        $toName = "Faheem";
        $toEmail = $email;
        $subject = "here";
        $message = "<!DOCTYPE html>
<html lang='en' xmlns=\"http://www.w3.org/1999/html\">
<head>
  <title>Bootstrap Example</title>
  <meta charset='utf-8'>
   <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script></head>
<body>";
        $message .= $_SESSION['log_table'];
        $message .= "</body></html>";

        $sendMail = $manage->sendMail($toName, $toEmail, $subject, $message);
        if ($sendMail) {
            $alert_status = true;
        }else{
            $failed_status = true;
        }
    }
}
if (isset($_GET['user_id'])) {
    $user_id = $security->decrypt($_GET['user_id']);
    if (isset($_POST['search'])) {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $displayLog = $manage->getCountBetweenDate($from_date, $to_date, $user_id);
        if ($displayLog != null) {
            $countLog = mysqli_num_rows($displayLog);
        } else {
            $countLog = 0;
        }
    }
}



?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $name; ?> - profile</title>
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
                                    src="<?php if (!file_exists($profilePaths) && $gender == "Male" or $form_data['img_name'] == "") {
                                        echo "uploads/male_user.png";
                                    } elseif (!file_exists($newprofilepath2) && $gender == "Female" or $form_data['img_name'] == "") {
                                        echo "uploads/female_user.png";
                                    } else {
                                        echo $newprofilepath;
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
                                               class="form-control"> <?php if (isset($final_gender)) echo $final_gender; ?></lable>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="width-prf">
                                <label><i class="fas fa-phone"></i></label>

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
                    Manage Subscription details  <span class="badge"><?php
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
                        <th>View Invoice</th>
                        <!--<th>Status</th>-->
                        <!--<th>ACTION</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($displaySubscription != null) {
                        while ($result_data = mysqli_fetch_array($displaySubscription)) {
                            ?>
                            <tr>
                                <!--<td><?php /*echo $result_data['name']; */?></td>
                                    <td><?php /*echo $result_data['contact']; */?></td>-->
                                <!--<td><?php /*if ($result_data['type'] == 1) {
                                            echo "Digital Card";
                                        } else {
                                            echo "Digital Card + Website (combo)";
                                        } */?></td>-->
                                <td><?php echo $result_data['year']; ?></td>
                                <td><?php echo $result_data['total_amount']; ?></td>
                                <td><?php echo $result_data['start_date']; ?></td>
                                <td><?php echo $result_data['end_date']; ?></td>
                                <td><a class="btn btn-success" <?php if ($result_data['invoice_no'] == "" or $result_data['invoice_no'] == 'NULL') {
                                        echo 'disabled';
                                        echo ' href="#"';
                                    } else {
                                        echo 'href="user-invoice.php?user_invoice_id=' . $security->encrypt($result_data['id']) . '"';
                                    } ?>>View Invoice</a></td>
                                <!--<td><label class="label <?php /*if ($result_data['status'] == "0") {
                                            echo "label-danger";
                                        } else {
                                            echo "label-success";
                                        } */?>"><?php /*if ($result_data['status'] == 0) {
                                                echo "Unpublished";
                                            } else {
                                                echo "Published";
                                            } */?></label></td>-->
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

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Manage Log details  <span class="badge"><?php
                        if (isset($countLog)) echo $countLog;
                        ?></span>
                </h2>
            </div>
            <div class="clearfix"></div>

            <?php
            $log_table = "<div style='padding: 10px;' class='col-md-12'>
                    <div class='col-md-9'>
                        <form method='post' action=''>";
            if ($alert_status) {
                $log_table .="<div class='alert alert-success'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Success!</strong> Message Sent Successfully!
                        </div>";
            }
            $log_table .="
                            <ul class='ul_search'>
                                <li class='ul_search_li'>
                                    <div class='form-line'>
                                        <label>From Date</label>
                                        <input type='date' name='from_date' class='form-control'
                                               value=''>
            </div>
            </li>
            <li class='ul_search_li'>
                <div class='form-line'>
                    <label>To Date</label>
                    <input type='date' name='to_date' class='form-control'
                           value=''>
                </div>
            </li>
            <li class='ul_search_li'>
                <div class='form-inline'>
                    <button type='submit' name='search' class='btn btn-primary'>Search</button>
                </div>
            </li>
            </ul>
            </form>
        </div>
        <div class='col-md-3 send_mail_padding'>
            <form method='post' action=''>
                <button type='submit' class='btn btn-primary' name='send_log'>send mail</button>
            </form>
        </div>
    </div>
    <div class='clearfix'></div>";
            $table = "<div class='body table-responsive table_scroll'>
                    <table class='table table-condensed table-bordered table-striped'>
                        <thead>
                        <tr class='back-color'>
                            <th>Page Type</th>
                            <th>Count</th>
                        </tr>
                        </thead>
                        <tbody>";
            if ($displayLog != null) {
                while ($result_data = mysqli_fetch_array($displayLog)) {
                    $page_type = $result_data['page_type'];
                    $user_count = $result_data['count'];
                    $table .= "<tr>
                                    <td>" . $page_type . "</td>
                                    <td>" . $user_count . "</td>
                                </tr>";
                }
            } else {
                $table .= "<tr><td colspan='10' class='text-center'>No data found!</td></tr>";
            }
            $table .= "</tbody></table></div>";

            $log_table .= $table;
            $_SESSION['log_table'] = $table;
            echo $log_table;
            ?>
        </div>
    </div>
</section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>