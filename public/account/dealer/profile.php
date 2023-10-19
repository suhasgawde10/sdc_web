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
$errorFile = false;
$errorMessageFile = "";
$emailError = false;
$emailErrorMessage = "";
$contactError = false;
$contactErrorMessage = "";
/*$randomEmail = rand(100, 10000);*/
$randomEmail = 1234;
/*$randomSMS = rand(100, 10000);*/
$randomSMS = 1234;
/*@session_start() ;
session_destroy() ;*/

$id = 0;
include("session_includes.php");

if ($id != 0) {
    $form_data = $manage->getSpecificDealerProfile($id);
    if ($form_data != null) {
        $name = $form_data['name'];
        $gender = $form_data['gender'];
        $date_of_birth = $form_data['date_of_birth'];
        $alter_contact_no = $form_data['altr_contact_no'];
        $state = $form_data['state'];
        $city = $form_data['city'];
        $id_proof = $form_data['id_proof'];
        $light_bill = $form_data['light_bill'];
        $c_name = $form_data['c_name'];
        $c_registered = $form_data['c_registered'];
        $gstin_no = $form_data['gstin_no'];
        $pan_no = $form_data['pan_no'];
        $address = $form_data['address'];
        $landline_no = $form_data['landline_no'];
        $office_address = $form_data['office_address'];
        $website = $form_data['website'];
        $b_email_id = $form_data['b_email_id'];
        $img_name = $form_data['img_name'];
        $category = $form_data['category'];
        $profilePath = "uploads/" . $session_email . "/profile/" . $form_data['img_name'];
    }
}

$display_message = $manage->displayDealerProfile();
if ($display_message != null) {
    $message_status = $display_message['message_status'];
      $dealer_status = $display_message['status'];     $pay_status = $display_message['pay_status'];
    $deal_code = $display_message['dealer_code'];
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
        <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
            <div class="row margin_div1">
                <div class="card">
                    <div class="body card_padding">
                        <form id="basic_user_profile" method="POST" action="" enctype="multipart/form-data">
                            <ul class="profile-left-ul">
                                <li>
                                    <div class="form-group form-float text-align-profile" style="position: relative">
                                        <!----><?php /*echo '<img src="" style="width: 15%;border-radius: 50%;" /><br />'; */ ?>
                                        <img
                                            src="<?php if (!file_exists($profilePath) && $gender == "Male" or $form_data['img_name'] == "") {
                                                echo "uploads/male_user.png";
                                            } elseif (!file_exists($profilePath) && $gender == "Female" or $form_data['img_name'] == "") {
                                                echo "uploads/female_user.png";
                                            } else {
                                                echo $profilePath;
                                            } ?>" style="width: 50%;border-radius: 50%;">
                                        <!--<div class="upload_camera">
                                            <a href="#edit_photo" data-toggle='modal' class="photo"><img
                                                    src="assets/images/camera.png"></a>
                                        </div>-->
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
                                        <label class="form-label"><i class="fa fa-phone"></i></label>

                                        <div class="form-group form-group-left form-float">
                                            <div class="">
                                                <lable name=label_txt_name"
                                                       class="form-control"> <?php echo $session_contact_no; ?>
                                                </lable>
                                            </div>
                                        </div>
                                        <!--  <a title="Edit Contact" class="add-icon-color fas fa-pencil-alt"
                                           href="basic-user-info.php?change_contact=<?php /*echo $session_contact_no; */ ?>"></a>-->
                                </li>
                                <li>
                                    <div class="width-prf">
                                        <label class="form-label"><i class="fas fa-envelope"></i></label>

                                        <div class="form-group form-group-left form-float">
                                            <div class="">
                                                <lable name=label_txt_email"
                                                       class="form-control"><?php echo $session_email; ?>
                                                </lable>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 padding_both">
            <div class="row margin_div_web">
                <div class="card">
                    <div class="body">
                        <form id="basic_user_info" method="POST" action="" enctype="multipart/form-data">
                            <fieldset>
                                <legend class="legend_font_size" align="left">Basic Information</legend>
                                <ul class="profile-ul">
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Name</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input name="txt_name" class="form-control"
                                                           value="<?php if (isset($name)) echo $name; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label>Gender</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select id="gender" name="gender" class="form-control gender_li">
                                                        <option name="">Select an option</option>
                                                        <option <?php if ($gender == 'Male') echo 'selected' ?>
                                                            name="male">Male
                                                        </option>
                                                        <option <?php if ($gender == 'Female') echo 'selected' ?>
                                                            name="female">Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">Date of birth</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="date" class="form-control" id="dob" name="dob"
                                                           value="<?php if (isset($date_of_birth)) echo $date_of_birth; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Alternet Contact Number</label>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                    <input type="text" name="txt_alt_contact" class="form-control"
                                                           onkeypress="return isNumberKey(event)"
                                                           placeholder="Alternet Contact Number"
                                                           value="<?php if (isset($alter_contact_no)) echo $alter_contact_no; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">State</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="dob" name="txt_state"
                                                           placeholder="Enter State"
                                                           value="<?php if (isset($state)) echo $state; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label class="control-label">City</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="dob" name="txt_city"
                                                           value="<?php if (isset($city)) echo $city; ?>"
                                                           placeholder="Enter State">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <label>Id proof</label>

                                            <div class="form-group form-float">
                                                <!--<input type="file" id="upload-file" name="upload-file[]"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"
                                                           value="<?php /*if (isset($filename)) echo $filename; */ ?>">-->
                                                <br>
                                                <a target="_blank"
                                                   href="<?php if (isset($form_data['id_proof']) && $form_data['id_proof'] != "") echo '../dealer/uploads/' . $_SESSION['dealer_email'] . '/id-proof/' . $form_data['id_proof'] . '"'; ?>">view
                                                    id proof</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <label class="form-label">Last Month Light bill</label>

                                            <div class="form-group form-float">
                                                <br> <a
                                                    href="<?php if (isset($form_data['light_bill']) && $form_data['light_bill'] != "") echo '../dealer/uploads/' . $_SESSION['dealer_email'] . '/light-bill/' . $form_data['light_bill'] . '"'; ?>"
                                                    target="_blank">view light bill</a>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="width-prf">
                                            <label class="form-label">Address</label> <span>*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                    <textarea name="txt_address" class="form-control"
                                              placeholder="Address"><?php if (isset($address)) echo $address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </fieldset>
                            <div class="altenet-div">
                                <fieldset>
                                    <legend class="legend_font_size" align="left">Company Info</legend>
                                    <ul class="profile-ul">
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Company Name</label> <span>*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" name="txt_c_name" class="form-control"
                                                               placeholder="Enter Company Name"
                                                               value="<?php if (isset($c_name)) echo $c_name; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label>Type</label> <span>*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select id="gender" name="drp_type"
                                                                class="form-control gender_li">
                                                            <option name="">Select Type</option>
                                                            <option <?php if ($c_registered == 'Registered') echo 'selected' ?>
                                                                name="registered">Registered
                                                            </option>
                                                            <option <?php if ($c_registered == 'Unregistered') echo 'selected' ?>
                                                                name="Unregistered">Unregistered
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Landline No</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_landline" class="form-control"
                                                               placeholder="Landline Number"
                                                               value="<?php if (isset($landline_no)) echo $landline_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">GSTIN No</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input type="text" name="txt_gstin" class="form-control"
                                                               placeholder="GSTIN number"
                                                               value="<?php if (isset($gstin_no)) echo $gstin_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">PAN NO</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="pan_no" class="form-control"
                                                               placeholder="PAN number"
                                                               value="<?php if (isset($pan_no)) echo $pan_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Office Address</label> <span>*</span>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="office_address" class="form-control"
                                                               placeholder="Office address"
                                                               value="<?php if (isset($office_address)) echo $office_address; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Website</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input name="txt_website" type="url" class="form-control"
                                                               placeholder="Enter Website"
                                                               value="<?php if (isset($website)) echo $website; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Business Email Id</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="b_email_id" class="form-control"
                                                               placeholder="Business email id"
                                                               value="<?php if (isset($b_email_id)) echo $b_email_id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="width-prf">
                                                <label class="form-label">Category</label>

                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                                        <input name="txt_category" class="form-control"
                                                               placeholder="Category"
                                                               value="<?php if (isset($category)) echo $category; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </fieldset>
                            </div>
                            <!-- <div class="btn-up-div">
                                 <div class="form-group form_inline">
                                     <div class="example">

                                         <button name="btn_update" type="submit"
                                                 class="btn btn-primary waves-effect">Update
                                         </button>
                                     </div>
                                     &nbsp;&nbsp;
                                     <div>
                                         <input type="reset" class="btn btn-default" value="reset">
                                     </div>
                                 </div>
                             </div>-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="edit_photo">
    <div class="modal-dialog dialog_width">
        <div class="modal-content">
            <div class="modal-header header_bottom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b><?php echo $session_name; ?></span></b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    <?php if ($errorFile) {
                        ?>
                        <div class="alert alert-danger">
                            <?php if (isset($errorMessageFile)) echo $errorMessageFile; ?>
                        </div>
                    <?php
                    } else if (!$errorFile && $errorMessageFile != "") {
                        ?>
                        <div class="alert alert-success">
                            <?php if (isset($errorMessageFile)) echo $errorMessageFile; ?>
                        </div>
                    <?php
                    }
                    ?>
                    <!--<input type="hidden" class="userid" name="id">-->

                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo</label>

                        <div class="col-sm-9">
                            <input class="form-control upload" type="file" id="upload" name="upload[]"
                                   multiple="multiple" accept=".png, .jpg, .jpeg,.JPG,.PNG"
                                   value="<?php if (isset($filename)) echo $filename; ?>" required="required">
                        </div>
                    </div>
            </div>
            <div class="modal-footer footer_bottom">
                <button type="submit" class="btn btn-success btn-flat" name="upload_photo"><i class="fas fa-check"></i>
                    &nbsp;Update
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "assets/common-includes/footer_includes.php" ?>


<!--<script type="text/javascript">
    document.getElementById("b3").onclick = function () {
        swal("Good job!", "You clicked the button!", "success");
    };
</script>-->


</body>
</html>