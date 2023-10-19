<?php
ini_set('memory_limit', '-1');
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

/*$maxsize = 4194304;*/
$maxsize = 4194304;
$imgUploadStatus = false;
$id = 0;
if(isset($_GET['edit_id'])){
    $id = $_GET['edit_id'];
    $fetchId = $security->decrypt($id);
}


$getDealerData = $manage->getDealerByIdEdit($fetchId);
if ($getDealerData != "") {
    $team_status = $getDealerData['team_status'];
}

$getAllTeam = $manage->getTeamMember($fetchId);

if (isset($_POST['btn-add'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Enter team name.<br>";
    }
    $designation = mysqli_real_escape_string($con, $_POST['txt_designation']);


    if (!$error) {
        $directory_name = "uploads/team-img/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $digits = 8;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newthumname = "";

        if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4 ) {
            $total = count($_FILES['upload']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                } else {
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    $newimgname = $randomNum . '.' . $file_extension;

                    $newPath = $directory_name . $newimgname;
                    if (($_FILES['upload']['size'][$i] <= $maxsize)) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        }
                    } else {
                        $error = true;
                        $errorMessage .= "File Size should be less than 2mb.";
                    }
                }
            }
        }
        if (!$error) {
            $insert_data = array('dealer_id'=>$fetchId,'name' => $name, 'image' => $newimgname, 'designation' => $designation, 'created_at' => $today_date, 'created_by' => $_SESSION['email']);
            $insert_team = $manage->insert($manage->teamTable, $insert_data);
//            print_r($insert_data);
//            exit;
            if ($insert_team) {
                $error = false;
                $errorMessage = "Team Added successfully";
                header('location:team-member.php?edit_id='.$id);
            } else {
                $error = true;
                $errorMessage = "Issue while adding details, Please try again.";
            }
        }
    }
}

if (isset($_GET['edit_data'])) {
    $edit_team = $security->decrypt($_GET['edit_data']);
    $form_data = $manage->getTeamByIdEdit($edit_team);
    if ($form_data != "") {
        $name = $form_data['name'];
        $image = $form_data['image'];
        $designation = $form_data['designation'];
    }
}

if (isset($_POST['btn_update'])) {

    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $nameup = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Enter team name.<br>";
    }
    $designationup = mysqli_real_escape_string($con, $_POST['txt_designation']);

    if (!$error) {
        $directory_name = "uploads/team-img/";
        $extension = array('.jpg', '.JPG', '.jpeg', 'png', 'PNG', '.png', '.PNG', '.jpeg', 'jpeg', 'JPEG', '.JPEG');
        $digits = 8;
        $randomNum = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $newimgname = "";
        if (isset($_FILES['upload']) && $_FILES['upload']['error'][0] != 4) {
            $total = count($_FILES['upload']['name']);
            for ($i = 0; $i < $total; $i++) {
                $filename = $_FILES['upload']['name'][$i];
                $extensionStatus = $validate->validateFileExtension($filename, $extension);
                if (!$extensionStatus) {
                    $error = true;
                    $errorMessage .= "Please select valid file extension";
                } else {
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    $file_original_name = substr($_FILES['upload']['name'][$i], 0, strrpos($_FILES['upload']['name'][$i], '.'));
                    $file_extension = substr($_FILES['upload']['name'][$i], (strrpos($_FILES['upload']['name'][$i], '.') + 1));
                    $newimgname = $randomNum . '.' . $file_extension;

                    $newPath = $directory_name . $newimgname;
                    if (($_FILES['upload']['size'][$i] <= $maxsize)) {
                        if (!move_uploaded_file($tmpFilePath, $newPath)) {
                            $error = true;
                            $errorMessage .= "Failed to upload file";
                        } else {
                            $image_path = "uploads/team-img/" . $form_data['image'];
                            if (file_exists($image_path) && $form_data['image'] != '') {
                                unlink($image_path);
                            }
                        }
                    } else {
                        $error = true;
                        $errorMessage .= "File Size should be less than 2mb.";
                    }
                }
            }
        }
        if (!$error) {
            if ($newimgname == "") {
                $newimgname = $image;
            }
//
            $condition = array('id' => $security->decrypt($_GET['edit_data']));
            $insert_data = array('name' => $nameup,'image'=>$newimgname,'designation'=>$designationup, 'updated_at' => $today_date, 'updated_by' => $_SESSION['email']);
            $update_team = $manage->update($manage->teamTable, $insert_data, $condition);
            if ($update_team) {
                $error = false;
                $errorMessage .= 'Team member has been updated successfully';
                header('location:team-member.php?edit_id='.$id);

            } else {
                $error = true;
                $errorMessage .= 'Issue while updating please try after some time';
            }

        }
    }
}


if (isset($_GET['delete_data']) && ($_GET['action'] == "delete")) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    if ($getAllTeam != null) {
       /* echo "jhds";
        exit;*/
        while ($result_data = mysqli_fetch_array($getAllTeam)) {
            $data_id = $result_data['id'];
            $image_path = $image_path = "uploads/team-img/" . $result_data['image'];
            if (file_exists($image_path) && $delete_data == $data_id) {
                unlink($image_path);
                $status = $manage->deleteData($manage->teamTable, $delete_data);
                header('location:team-member.php?edit_id='.$id);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Team Member</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <style>
        /*.active{
            background-color: #0000ff;
        }*/

        legend {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }

        .img-input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        img {
            max-width: 180px;
        }

        #more {
            display: none;
        }
    </style>

    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- remove this if you use Modernizr -->
    <script>(function (e, t, n) {
            var r = e.querySelectorAll("html")[0];
            r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
        })(document, window, 0);</script>
    <link rel="stylesheet" type="text/css" href="css/image-preview.css"/>
    <link rel="stylesheet" href="assets/summernote/summernote-bs4.css">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
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
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header table-card-header">
                                <?php include "commom-menu.php" ?>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <?php if ($error && $errorMessage != "") {
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
                                        <form method="post" action="" enctype="multipart/form-data">

                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Team
                                                Status</label>

                                            <div class="col-sm-6">
                                                <input id="toggle-event" type="checkbox"
                                                       data-toggle="toggle" <?php if ($team_status == 1) echo 'checked' ?>  >
                                            </div>
                                        </div>

                                            <fieldset>
                                                <legend>Team Member</legend>
                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <form>
                                                            <div class="col-md-12">
                                                                <label for="name">Image</label>
                                                                <input type="file" name="upload[]" class="form-control">
                                                                <?php
                                                                if (isset($_GET['edit_id'])) {
                                                                    $image_path = "uploads/team-img/" . $form_data['image'];
                                                                    if (file_exists($image_path) && $form_data['image'] != '') {
                                                                        echo '<img src="' . $image_path . '" style="width:30%" >';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="name">Name</label>
                                                                <input type="text" name="txt_name" class="form-control"
                                                                       id="name" placeholder="Name" value="<?php if(isset($name)) echo $name ?>">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="image_upload">Designation</label>
                                                                <input type="text" name="txt_designation"
                                                                       class="form-control"
                                                                       id="designation" placeholder="Designation" value="<?php if(isset($designation)) echo $designation ?>">
                                                            </div>
                                                            <br>

                                                            <div class="col-md-12">
                                                                <?php
                                                                if(isset($_GET['edit_data'])){
                                                                    ?>
                                                                    <button type="submit" name="btn_update" class="btn btn-primary">Update</button>
                                                                <?php
                                                                }else{
                                                                    ?>
                                                                    <button type="submit" name="btn-add" class="btn btn-primary">Save</button>
                                                                <?php
                                                                }
                                                                ?>

                                                                <button type="reset" class="btn btn-danger">cancel
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <table id="example1"
                                                               class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Name</th>
                                                                <th>Designation</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if($getAllTeam != ""){
                                                                while($row = mysqli_fetch_array($getAllTeam)){
//                                                                    print_r($row);
                                                                    ?>
                                                                    <tr>
                                                                        <td><img src="uploads/team-img/<?php echo $row['image']; ?>"
                                                                                 style="width: 100px"></td>
                                                                        <td><?php echo $row['name']; ?></td>
                                                                        <td><?php echo $row['designation']; ?></td>
                                                                        <td>
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-default"
                                                                                        type="button"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false"><i
                                                                                        class="fa fa-ellipsis-v"
                                                                                        aria-hidden="true"></i>
                                                                                </button>
                                                                                <?php if ($_SESSION["type"] == "admin") {
                                                                                    ?>
                                                                                    <div class="dropdown-menu dropdown-menu-right"
                                                                                         aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item waves-light waves-effect"
                                                                                           href="team-member.php?edit_id=<?php echo $id ;?>&edit_data=<?php echo $security->encrypt($row['id']);?>">
                                                                                            <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                        <a class="dropdown-item waves-light waves-effect"
                                                                                           href="team-member.php?delete_data=<?php echo $security->encrypt($row['id']); ?>&action=delete&edit_id=<?php echo $id; ?>"
                                                                                           onclick="return confirm('Are You sure you want to delete?');">
                                                                                            <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                    </div>
                                                                                <?php

                                                                                }else{
                                                                                    ?>
                                                                                    <div class="dropdown-menu dropdown-menu-right"
                                                                                         aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item waves-light waves-effect"
                                                                                           href="team-member.php?edit_id=<?php echo $security->encrypt($_SESSION['id']);?>&edit_data=<?php echo $security->encrypt($row['id']);?>">
                                                                                            <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                        <a class="dropdown-item waves-light waves-effect"
                                                                                           href="team-member.php?delete_data=<?php echo $security->encrypt($row['id']); ?>&action=delete&edit_id=<?php echo $security->encrypt($_SESSION['id']); ?>"
                                                                                           onclick="return confirm('Are You sure you want to delete?');">
                                                                                            <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                    </div>
                                                                                <?php

                                                                                } ?>

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                            } ?>


                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <?php include 'assets/common-includes/footer.php' ?>
</div>
<!-- ./wrapper -->
<?php include 'assets/common-includes/footer_includes.php' ?>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script type="text/javascript">
    $("document").ready(function () {
        $('#toggle-event').change(function () {
            var data = $(this).prop('checked');
            var user_id = <?php echo $fetchId ?>;
            $.ajax({
                type: 'POST',
                url: "update_franchise_status.php",
                data: "teamstatus=" + data + "&user_id=" + user_id,
                success: function (result) {
                    console.log(result);
                }
            });
        })
    });
</script>
</body>
</html>
