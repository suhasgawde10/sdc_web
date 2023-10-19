<?php
ob_start();
include_once "../controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
include "../controller/ManageUser.php";
$manage = new ManageUser();


$error = false;
$errorMessage = "";


if (!isset($_SESSION['email'])) {
    header('location:../login.php');
}
function deleteDirectory($dirPath)
{
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        reset($objects);
        rmdir($dirPath);
    }
}
if(isset($_POST['btn_delete'])){

    if(isset($_POST['check']) && $_POST['check'] !=null) {
        $data = $_POST['check'];
    }else{
        $error = true;
        $errorMessage = "Please select checkbox";
    }
    if(!$error){
        if(count($data) >= 1){
            foreach ($data as $key){
                $seprate_key = explode(',',$key);

                $dirPath = "uploads/$seprate_key[1]";

                if($seprate_key[1] !=''){
                    deleteDirectory($dirPath);
                }
                $status = $manage->deleteUser($seprate_key[0]);
            }
            if ($status) {
                $error = true;
                $errorMessage = "Data has been successfully deleted";
            }

        }else{
            $error = true;
            $errorMessage = "Please select checkbox";
        }
    }

}

$remainingUser = $manage->displayDefaultUser();
if ($remainingUser != null) {
    $newCustomer = mysqli_num_rows($remainingUser);
} else {
    $newCustomer = 0;
}



?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>New Customer</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>New User</h2>
        </div>
        <div class="clearfix">
            <div class="col-lg-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 padding_zero_both col-md-12 col-sm-12 col-xs-12">
                       <form method="post" action="">
                           <div class="card">
                               <div class="header">
                                   <div class="col-md-6">
                                       <h4>
                                           Manage New User Listing <span class="badge"><?php
                                               if (isset($newCustomer)) echo $newCustomer;
                                               ?></span>
                                       </h4>

                                   </div>
                                   <div class="col-md-6 text-right">
                                       <button class="btn btn-danger" name="btn_delete" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                   </div>

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
                                   <table id="dtHorizontalExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                                          width="100%">
                                       <thead>
                                       <tr class="back-color">
                                           <th><input type="checkbox" id="checkAl"></th>
                                           <th>Name</th>
                                           <th>Contact No</th>
                                           <th>Email ID</th>
                                           <th>Auto Login</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                       <?php
                                       if ($remainingUser != null) {
                                           while ($result_data = mysqli_fetch_array($remainingUser)) {
                                               ?>
                                               <tr>
                                                   <td><input type="checkbox" id="checkItem" name="check[]" class="checkbox1"
                                                              value="<?php echo $result_data["id"].",".$result_data['email']; ?>"></td>
                                                   <td><?php echo $result_data['name']; ?></td>
                                                   <td><?php echo $result_data['contact_no']; ?></td>
                                                   <td class="data-email"><?php echo $result_data['email']; ?></td>
                                                   <td><a href="user-session-edit.php?id=<?php echo $security->encrypt($result_data['id']) ?>&name=<?php echo $result_data['name'] ?>&contact=<?php echo $result_data['contact_no'] ?>&email=<?php echo $result_data['email'] ?>&custom_url=<?php echo $result_data['custom_url'] ?>">
                                                           <i class="fa fa-sign-in"></i>&nbsp;&nbsp;log in</a>
                                                   </td>
                                               </tr>
                                               <?php
                                           }
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
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
</body>
</html>