<?php
$error = false;
$errorMessage = "";
include "controller/ManageAdminApp.php";
$manage = new ManageAdminApp();
include "controller/validator.php";
$validate = new Validator();
include "controller/EncryptDecrypt.php";
$security = new EncryptDecrypt();
$controller = new Controller();
$con = $controller->connect();
$today_date = date("Y-m-d");

if (!isset($_SESSION['email'])) {
    header('location:index.php');
}

$getAllDealer = $manage->getDealer();

if (isset($_GET['publishData']) && (isset($_GET['action']))) {
    $action = $_GET['action'];
    $publishData = $security->decrypt($_GET['publishData']);

    if ($action == "Unpublish") {
        $condition = array('id' => $publishData);
        $insert_data = array('status' => 0);
        $result = $manage->update($manage->dealerTable, $insert_data,$condition);
    } else {
        $condition = array('id' => $publishData);
        $insert_data = array('status' => 1);
        $result = $manage->update($manage->dealerTable, $insert_data,$condition);
    }
    header('location:manage-dealer.php');
}

if (isset($_GET['delete_data']) && ($_GET['action'] == "delete")) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteData($manage->dealerTable, $delete_data);
    if ($status) {
        header('location:manage-dealer.php');
    }
}

if (isset($_GET['reset_password']) && ($_GET['action'] == "reset")) {
    $reset_data = $security->decrypt($_GET['reset_password']);
    $new_pass = $security->encrypt('12345678').'8523';
    $condition = array('id'=>$reset_data);
    $update_data = array('password'=>$new_pass);
    $update_pass = $manage->update($manage->dealerTable, $update_data,$condition);
    if ($update_pass) {
        header('location:manage-dealer.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage Dealer</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <!-- remove this if you use Modernizr -->

    <link rel="stylesheet" type="text/css" href="css/image-preview.css"/>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php include 'assets/common-includes/header.php' ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include 'assets/common-includes/left_menu.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">


            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <form action="" method="post">
                                <div class="card-header">
                                    <h3 class="card-title">Manage Dealer</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="col-md-12 table_scroll">
                                        <table id="example1" class="table table-bordered table-responsive table-striped">
                                            <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Customer Name</th>
                                                <th>Company Name</th>
                                                <th>Domain Name</th>
                                                <th>Email Id</th>
                                                <th>Contact No</th>
                                                <th>Expiry date</th>
                                                <th>Status</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if($getAllDealer != ""){
                                                while($getRows = mysqli_fetch_array($getAllDealer)){
//                                                    print_r($getRows);
                                                    $pass = str_replace('8523','',$getRows['password']);
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-default"
                                                                        type="button"
                                                                        data-toggle="dropdown"
                                                                        aria-expanded="false"><i
                                                                        class="fa fa-ellipsis-v"
                                                                        aria-hidden="true"></i>
                                                                </button>
                                                                <div
                                                                    class="dropdown-menu dropdown-menu-right"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                       href="update-dealer.php?edit_id=<?php echo $security->encrypt($getRows['id']);?>">
                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>

                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                       href="about-us.php?edit_id=<?php echo $security->encrypt($getRows['id']);?>">
                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit Website Content</a>

                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                       href="manage-dealer.php?delete_data=<?php echo $security->encrypt($getRows['id']); ?>&action=delete&edit_id=<?php echo $security->encrypt($getRows['id']); ?>"
                                                                       onclick="return confirm('Are You sure you want to delete?');">
                                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>


                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                       onclick="return confirm('Are You sure you want to <?php echo $getRows['status'] == 1 ? 'Inactive' : 'Active'; ?>?');"
                                                                       href="manage-dealer.php?publishData=<?php echo $security->encrypt($getRows['id']) ?>&action=<?php echo $getRows['status'] == 1 ? 'Unpublish' : 'Publish'; ?>"><i
                                                                            class="<?php echo $getRows['status'] == 0 ? "fa fa-unlock" : "fa fa-ban"; ?>"></i>
                                                                        &nbsp;&nbsp;<?php echo $getRows['status'] == 1 ? 'Inactive' : 'Active'; ?>
                                                                    </a>

                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                       href="manage-dealer.php?reset_password=<?php echo $security->encrypt($getRows['id']); ?>&action=reset&edit_id=<?php echo $security->encrypt($getRows['id']); ?>"
                                                                       onclick="return confirm('Are you really want to Reset User Password?');">
                                                                        <i class="fas fa-unlock-alt"></i>&nbsp;&nbsp;Reset password</a>


                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                       href="<?php echo "https://".$getRows['domain_name'];  ?>" target="_blank">
                                                                        <i class="fas fa-eye"></i>&nbsp;&nbsp;Preview</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $getRows['customer_name']; ?></td>
                                                        <td><?php echo $getRows['company_name']; ?></td>
                                                        <td><?php echo $getRows['domain_name']; ?></td>
                                                        <td><?php echo $getRows['email_id']."<br> <b>Password: </b>".$security->decrypt($pass); ?></td>
                                                        <td><?php echo $getRows['contact_no'];?>
                                                            <?php if($getRows['alter_contact_no'] != "") echo ' / '.$getRows['alter_contact_no']; ?></td>
                                                        <td><?php echo $getRows['expiry_date']; ?></td>
                                                        <td>
                                                            <?php
                                                            if($getRows['status'] == 1){
                                                                echo "<label class='badge badge-success'>Active</label>";
                                                            }else{
                                                                echo "<label class='badge badge-danger'>Inactive</label>";
                                                            }
                                                            ?>
                                                        </td>

                                                    </tr>
                                                <?php
                                                }
                                            }else{
                                                echo "<td colspan='9' style='text-align: center'><b>No record found..!</b></td>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <?php include 'assets/common-includes/footer.php' ?>
</div>
<!-- ./wrapper -->
<?php include 'assets/common-includes/footer_includes.php' ?>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


<script>

    $("#selectall").click(function () {

        $('.name').attr('checked', this.checked);

    });

    // INCLUDE JQUERY & JQUERY UI 1.12.1
    $(function () {
        $("#datepicker, #datepicker1").datepicker({
            dateFormat: "yy-mm-dd"
            , duration: "fast"
        });
    });
</script>


</body>
</html>
