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
include "controller/config data.php";

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


$fetAllData = $manage->FetchData($manage->dealerTable, $fetchId);
//print_r($fetAllData);
$slider_image = $fetAllData['slider_image'];
$slider_title = $fetAllData['slider_title'];
$slider_desc = $fetAllData['slider_desc'];
$slider_color = $fetAllData['slider_color'];
$cust_count = $fetAllData['customer_count'];
$city_count = $fetAllData['city_count'];
$theme_count = $fetAllData['theme_count'];
$partner_count = $fetAllData['partner_count'];


if (isset($_POST['btn_update'])) {
    if (isset($_POST['txt_title']) && $_POST['txt_title'] != "") {
        $title = mysqli_real_escape_string($con, $_POST['txt_title']);
    } else {
        $error = true;
        $errorMessage .= "Enter Title.<br>";
    }
    if (isset($_POST['txt_desc']) && $_POST['txt_desc'] != "") {
        $desc = mysqli_real_escape_string($con, $_POST['txt_desc']);
    } else {
        $error = true;
        $errorMessage .= "Enter Description.<br>";
    }
    if (isset($_POST['color_picker']) && $_POST['color_picker'] != "") {
        $color = mysqli_real_escape_string($con, $_POST['color_picker']);
    } else {
        $error = true;
        $errorMessage .= "choose slider Color <br>";
    }

    if (!$error) {
        $directory_name = "uploads/slider-image/";
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
                            $image_path = "uploads/slider-image/" . $fetAllData['slider_image'];
                            if (file_exists($image_path) && $fetAllData['slider_image'] != '') {
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
                $newimgname = $slider_image;
            }
//
            $condition = array('id' => $security->decrypt($_GET['edit_id']));
//            `slider_image`, `slider_title`, `slider_desc`, `slider_color`,

            $insert_data = array('slider_image' => $newimgname, 'slider_title' => $title, 'slider_desc' => $desc, 'slider_color' => $color);
            $update_slider = $manage->update($manage->dealerTable, $insert_data, $condition);
            if ($update_slider) {
                $error = false;
                $errorMessage .= 'Slider has been updated successfully';

            } else {
                $error = true;
                $errorMessage .= 'Issue while updating please try after some time';
            }

        }
    }
}


if (isset($_POST['save_count'])) {
    if (isset($_POST['customer_count']) && $_POST['customer_count'] != "") {
        $cust_up = mysqli_real_escape_string($con, $_POST['customer_count']);
    } else {
        $error = true;
        $errorMessage .= "Enter customer count.<br>";
    }
    if (isset($_POST['customer_city']) && $_POST['customer_city'] != "") {
        $city_up = mysqli_real_escape_string($con, $_POST['customer_city']);
    } else {
        $error = true;
        $errorMessage .= "Enter city count.<br>";
    }
    if (isset($_POST['customer_theme']) && $_POST['customer_theme'] != "") {
        $theme_up = mysqli_real_escape_string($con, $_POST['customer_theme']);
    } else {
        $error = true;
        $errorMessage .= "Enter Theme count <br>";
    }
    if (isset($_POST['customer_partner']) && $_POST['customer_partner'] != "") {
        $partner_up = mysqli_real_escape_string($con, $_POST['customer_partner']);
    } else {
        $error = true;
        $errorMessage .= "Enter Partner count <br>";
    }

    if (!$error) {
        $condition = array('id' => $security->decrypt($_GET['edit_id']));

        $insert_data = array('customer_count' => $cust_up, 'city_count' => $city_up, 'theme_count' => $theme_up, 'partner_count' => $partner_up);
        $update_counter = $manage->update($manage->dealerTable, $insert_data, $condition);
        if ($update_counter) {
            $error = false;
            $errorMessage .= 'Counter has been updated successfully';

        } else {
            $error = true;
            $errorMessage .= 'Issue while updating please try after some time';
        }

    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Slider Management</title>

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
                                        <fieldset>
                                            <legend>Slider Management</legend>

                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                    <label for="image_upload">Slider Image</label>
                                                    <input type="file" name="upload[]" class="form-control"
                                                           id="image_upload">
                                                    <?php
                                                    if (isset($_GET['edit_id'])) {
                                                        $image_path = "uploads/slider-image/" . $slider_image;
                                                        if (file_exists($image_path) && $slider_image != '') {
                                                            echo '<img src="' . $image_path . '" style="width:30%" >';
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="domain">Title</label>
                                                    <input type="text" name="txt_title" class="form-control"
                                                           id="domain" placeholder="Title"
                                                           value="<?php if (isset($slider_title)) echo $slider_title ?>">
                                                </div>

                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="comapany_name">Description</label>
                                                    <textarea id="default"
                                                              name="txt_desc"><?php if (isset($slider_desc)) echo $slider_desc ?></textarea>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="Date">Slider Color</label>
                                                    <input type="color" class="form-control" id="color_picker"
                                                           name="color_picker"
                                                           value="<?php if (isset($slider_color)) echo $slider_color ?>">

                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="btn_update" class="btn btn-primary">Update
                                            </button>
                                            <button type="reset" class="btn btn-danger">cancel</button>
                                        </fieldset>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <form method="post" action="">
                                        <fieldset>
                                            <legend>Counter</legend>

                                            <div class="form-row">

                                                <div class="form-group col-md-4">
                                                    <label for="image_upload">Customers</label>
                                                    <input type="number" name="customer_count" class="form-control"
                                                           placeholder="Customers"
                                                           value="<?php if (isset($cust_count)) echo $cust_count ?>">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="image_upload">Covered City</label>
                                                    <input type="number" name="customer_city" class="form-control"
                                                           placeholder="Covered City"
                                                           value="<?php if (isset($city_count)) echo $city_count ?>">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="image_upload">Themes</label>
                                                    <input type="text" name="customer_theme" class="form-control"
                                                           placeholder="Themes"
                                                           value="<?php if (isset($theme_count)) echo $theme_count ?>">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="image_upload">Partners</label>
                                                    <input type="number" name="customer_partner" class="form-control"
                                                           placeholder="Partners"
                                                           value="<?php if (isset($partner_count)) echo $partner_count ?>">
                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="save_count" class="btn btn-primary">Save
                                            </button>
                                            <button type="reset" class="btn btn-danger">cancel</button>
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


</body>
</html>
