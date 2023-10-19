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
}
$fetchTheme = $manage->FetchThemeData($manage->themeTable, $fetchId);
$getAllTheme = $manage->getTheme();

$getDealerData = $manage->getDealerByIdEdit($fetchId);
if ($getDealerData != "") {
    $theme_status = $getDealerData['theme_status'];
}


if (isset($_POST['save_theme'])) {
    $directory_name = "uploads/theme-img/";
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
        $insert_data = array('dealer_id'=>$fetchId,'theme_img'=>$newimgname,'created_at'=>$today_date,'created_by'=>$_SESSION["email"]);
        $update_team = $manage->insert($manage->themeTable,$insert_data);
        if ($update_team) {
            $error = false;
            $errorMessage .= 'Theme image save successfully';
            header('location:add-theme.php?edit_id='.$id);

        } else {
            $error = true;
            $errorMessage .= 'Issue while updating please try after some time';
        }

    }
}

if (isset($_GET['delete_data']) && ($_GET['action'] == "delete")) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    if ($getAllTheme != null) {
        while ($result_data = mysqli_fetch_array($getAllTheme)) {
            $data_id = $result_data['id'];
            $image_path = $image_path = "uploads/theme-img/" . $result_data['theme_img'];
            if (file_exists($image_path)) {
                unlink($image_path);
                $status = $manage->deleteData($manage->themeTable, $delete_data);
                header('location:add-theme.php?edit_id='.$id);
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
    <title>Add Theme</title>

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
                            <div class="card-body">
                                <br>

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
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Theme
                                                Status</label>

                                            <div class="col-sm-6">
                                                <input id="toggle-event" type="checkbox"
                                                       data-toggle="toggle" <?php if ($theme_status == 1) echo 'checked' ?>  >
                                            </div>
                                        </div>

                                        <fieldset>
                                            <legend>Theme</legend>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModalCenter" style="float: right">
                                                Add Image
                                            </button>
                                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Add
                                                                Image</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <label for="image_upload">Image</label>
                                                                <input type="file" name="upload[]" class="form-control"
                                                                       id="image_upload" required="">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"
                                                                    name="save_theme">Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br><br><br>

                                            <div class="row">
                                                <?php
                                                if($fetchTheme != ""){
                                                    while($rowData = mysqli_fetch_array($fetchTheme)){
                                                        ?>
                                                        <div class="col-lg-3">
                                                            <div class="image-area">
                                                                <img src="uploads/theme-img/<?php echo $rowData['theme_img']; ?>" alt="Preview" style="">
                                                                <a class="remove-image" href="add-theme.php?delete_data=<?php echo $security->encrypt($rowData['id']); ?>&action=delete&edit_id=<?php echo $id; ?>"
                                                                   onclick="return confirm('Are You sure you want to delete?');" style="display: inline;"><i
                                                                        class="fa fa-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                }
                                                ?>
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
        height: 200,
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
                data: "themestatus=" + data + "&user_id=" + user_id,
                success: function (result) {
                    console.log(result);
                }
            });
        })
    });
</script>

</body>
</html>
