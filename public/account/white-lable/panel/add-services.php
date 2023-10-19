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
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $fetchId = $security->decrypt($id);
    $getService = $manage->getOtherServices($fetchId);
}

$getDealerData = $manage->getDealerByIdEdit($fetchId);
if ($getDealerData != "") {
    $service_status = $getDealerData['other_service_status'];
}

if (isset($_POST['btn-add'])) {
    if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $txt_title = $_POST['txt_title'];
    } else {
        $error = true;
        $errorMessage .= "Enter Service Title <br>";
    }
    if (isset($_POST['txt_desc']) && $_POST['txt_desc'] != "") {
        $txt_desc = mysqli_real_escape_string($con, $_POST['txt_desc']);
    } else {
        $error = true;
        $errorMessage .= "Enter Service Description <br>";
    }
    $whatsapp_check = $_POST['whatsapp_check'];
    $call_check = $_POST['call_check'];
    $txt_url = $_POST['txt_url'];

    if (!$error) {
        $directory_name = "uploads/other-service-img/";
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
                        }
                    } else {
                        $error = true;
                        $errorMessage .= "File Size should be less than 2mb.";
                    }
                }
            }
        }
        if (!$error) {
            $insertData = array('user_id' => $fetchId, 'title' => $txt_title, 'img_name' => $newimgname, 'description' => $txt_desc, 'url' => $txt_url, 'whatsapp_btn' => $whatsapp_check, 'call_btn' => $call_check);
            $insert_policy = $manage->insert($manage->otherServiceTable, $insertData);
            if ($insert_policy) {
                $error = false;
                $errorMessage = "Service Added Successfully";
            } else {
                $error = true;
                $errorMessage = "Issue while Adding details, Please try again.";
            }
        }
    }
}

if (isset($_GET['edit_data'])) {
    $getServiceById = $manage->getOtherServiceByUserId($fetchId);
    if ($getServiceById != "") {
        $e_title = $getServiceById['title'];
        $e_img = $getServiceById['img_name'];
        $e_desc = $getServiceById['description'];
        $e_url = $getServiceById['url'];
        $e_whatsapp = $getServiceById['whatsapp_btn'];
        $e_call = $getServiceById['call_btn'];
    }
}

if (isset($_GET['delete_data']) && ($_GET['action'] == "delete")) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    if ($getService != null) {
        while ($result_data = mysqli_fetch_array($getService)) {
            $data_id = $result_data['id'];
            $image_path = $image_path = "uploads/other-service-img/" . $result_data['img_name'];
            if (file_exists($image_path) && $delete_data == $data_id) {
                unlink($image_path);
                $status = $manage->deleteData($manage->otherServiceTable, $delete_data);
                header('location:add-services.php?edit_id='.$id);
            }
        }
    }
}

if (isset($_GET['edit_data'])) {

    if (isset($_POST['btn-update'])) {
        if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
            $up_txt_title = $_POST['txt_title'];
        } else {
            $error = true;
            $errorMessage .= "Enter Service Title <br>";
        }
        if (isset($_POST['txt_desc']) && $_POST['txt_desc'] != "") {
            $up_txt_desc = mysqli_real_escape_string($con, $_POST['txt_desc']);
        } else {
            $error = true;
            $errorMessage .= "Enter Service Description <br>";
        }
        $up_whatsapp_check = $_POST['whatsapp_check'];
        $up_call_check = $_POST['call_check'];
        $up_txt_url = $_POST['txt_url'];

        if (!$error) {
            $directory_name = "uploads/other-service-img/";
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
                                $image_path = "uploads/other-service-img/" . $e_img;
                                if (file_exists($image_path) && $e_img != '') {
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
                    $newimgname = $e_img;
                }
//                `user_id`, `title`, `img_name`, `description`, `url`, `whatsapp_btn`, `call_btn`
                $condition = array('id' => $security->decrypt($_GET['edit_data']));
                $insert_data = array('title' => $up_txt_title,'img_name'=>$newimgname,'description'=>$up_txt_desc, 'url' => $up_txt_url, 'whatsapp_btn' => $up_whatsapp_check,'call_btn'=>$up_call_check);
                $update_team = $manage->update($manage->otherServiceTable, $insert_data, $condition);
                if ($update_team) {
                    $error = false;
                    $errorMessage .= 'Service has been updated successfully';
//                    header('location:team-member.php?edit_id='.$id);

                } else {
                    $error = true;
                    $errorMessage .= 'Issue while updating please try after some time';
                }

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
    <title>Other Service</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <style>
        .dataTables_scrollBody {
            padding-bottom: 10px;
        }

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
                            <div class="card-body">

                                <div class="col-lg-12">
                                    <form method="post" action="" enctype="multipart/form-data">

                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Service
                                                Status</label>

                                            <div class="col-sm-6">
                                                <input id="toggle-event" type="checkbox"
                                                       data-toggle="toggle" <?php if ($service_status == 1) echo 'checked' ?>  >
                                            </div>
                                        </div>
                                        <fieldset>
                                            <legend>Other Services</legend>
                                            <div class="row">
                                                <div class="col-lg-5">
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

                                                    <div class="col-md-12">
                                                        <label for="name">Title</label>
                                                        <input type="text" name="txt_title" class="form-control"
                                                               id="name" placeholder="Service Title"
                                                               value="<?php if(isset($e_title)){ echo $e_title; }else{ echo $txt_title; } ?>">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="image_upload">Service Image</label>
                                                        <input type="file" name="upload[]"
                                                               class="form-control"
                                                               id="price">

                                                        <?php
                                                        if (isset($_GET['edit_data'])) {
                                                            $image_path = "uploads/other-service-img/" . $e_img;
                                                            if (file_exists($image_path) && $e_img != '') {
                                                                echo '<img src="' . $image_path . '" style="width:20%;margin-top:10px" >';
                                                            }
                                                        }
                                                        ?>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="exampleFormControlInput1" class="form-label">Service
                                                            Url link</label>
                                                        <input type="text" name="txt_url" class="form-control"
                                                               id="exampleFormControlInput1" placeholder="Service URL" value="<?php if(isset($e_url)){ echo $e_url; }else{ echo $txt_url; } ?>">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="image_upload">Service Description</label>
                                                        <textarea id="default" class="form-control" name="txt_desc"
                                                                  rows="10"><?php if(isset($e_desc)){ echo $e_desc; }else{ echo $txt_desc; } ?></textarea>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="whatsapp_check" value="1"
                                                                    <?php if($e_whatsapp == 1){ echo "checked"; }else{  echo "checked"; } ?> >
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                Whatsapp Button
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="call_check" value="2"
                                                                <?php if($e_call == 2){ echo "checked"; }else{  echo "checked"; } ?> >
                                                            <label class="form-check-label" for="flexCheckChecked">
                                                                Call Button
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <div class="col-md-12">
                                                        <?php
                                                        if (isset($_GET['edit_data'])) {
                                                            ?>
                                                            <button type="submit" class="btn btn-primary"
                                                                    name="btn-update">Update
                                                            </button>
                                                        <?php
                                                        } else {
                                                            ?>
                                                            <button type="submit" class="btn btn-primary"
                                                                    name="btn-add">Save
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>
                                                        <a href="add-services.php?edit_id=<?php echo $_GET['edit_id']; ?>" class="btn btn-danger">cancel</a>
                                                    </div>
                                                </div>
                                                <div class="col-sm-7">
                                                    <table id="example1"
                                                           class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Image</th>
                                                            <th>Title</th>
                                                            <th>Desc</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if ($getService != "") {
                                                            while ($rowData = mysqli_fetch_array($getService)) {
                                                                ?>
                                                                <tr>
                                                                    <td><img
                                                                            src="uploads/other-service-img/<?php echo $rowData['img_name'] ?>"
                                                                            style="width: 100px">
                                                                    </td>
                                                                    <td><?php echo $rowData['title'] ?></td>
                                                                    <td><?php echo substr($rowData['description'], 0, 80); ?></td>
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
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="add-services.php?edit_id=<?php echo $id; ?>&edit_data=<?php echo $security->encrypt($rowData['id']); ?>">
                                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="add-services.php?delete_data=<?php echo $security->encrypt($rowData['id']); ?>&action=delete&edit_id=<?php echo $id; ?>"
                                                                                       onclick="return confirm('Are You sure you want to delete?');">
                                                                                        <i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a>
                                                                                </div>
                                                                            <?php
                                                                            } else {

                                                                                ?>
                                                                                <div
                                                                                    class="dropdown-menu dropdown-menu-right"
                                                                                    aria-labelledby="dropdownMenuButton">
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="add-services.php?edit_id=<?php echo $security->encrypt($_SESSION['id']); ?>&edit_data=<?php echo $security->encrypt($rowData['id']); ?>">
                                                                                        <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                                                                    <a class="dropdown-item waves-light waves-effect"
                                                                                       href="add-services.php?delete_data=<?php echo $security->encrypt($rowData['id']); ?>&action=delete&edit_id=<?php echo $security->encrypt($_SESSION['id']); ?>"
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
                                                        }
                                                        ?>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>


    tinymce.init({
        selector: 'textarea#default,textarea#default1',
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        link_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.moxiecode.com'}
        ],
        image_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.moxiecode.com'}
        ],
        image_class_list: [
            {title: 'None', value: ''},
            {title: 'Some class', value: 'class-name'}
        ],
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', {text: 'My text'});
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', {alt: 'My alt text'});
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
                callback('movie.mp4', {source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg'});
            }
        },
        templates: [
            {
                title: 'New Table',
                description: 'creates a new table',
                content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
            },
            {title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...'},
            {
                title: 'New list with dates',
                description: 'New List with dates',
                content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
            }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 250,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image imagetools table',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script type="text/javascript">
    $("document").ready(function () {
        $('#toggle-event').change(function () {
            var data = $(this).prop('checked');
            var user_id = <?php echo $fetchId ?>;
            $.ajax({
                type: 'POST',
                url: "update_franchise_status.php",
                data: "servicestatus=" + data + "&user_id=" + user_id,
                success: function (result) {
                    console.log(result);
                }
            });
        })
    });
</script>


</body>
</html>
