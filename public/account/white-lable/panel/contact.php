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

$fetAllData = $manage->FetchData($manage->dealerTable, $fetchId);
$address = $fetAllData['contact_addr'];
$cont_call = $fetAllData['contact_call'];
$cont_email = $fetAllData['contact_email'];
$cont_hour = $fetAllData['contact_hours'];

if (isset($_POST['add-btn'])) {
    if (isset($_POST['txt_addrs']) && $_POST['txt_addrs'] != "") {
        $addrs = mysqli_real_escape_string($con, $_POST['txt_addrs']);
    } else {
        $error = true;
        $errorMessage .= "Enter address.<br>";
    }
    if (isset($_POST['txt_call']) && $_POST['txt_call'] != "") {
        $call = mysqli_real_escape_string($con, $_POST['txt_call']);
    } else {
        $error = true;
        $errorMessage .= "Enter call deatils.<br>";
    }
    if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
        $email = mysqli_real_escape_string($con, $_POST['txt_email']);
    } else {
        $error = true;
        $errorMessage .= "Enter email id.<br>";
    }
    if (isset($_POST['txt_work']) && $_POST['txt_work'] != "") {
        $work = mysqli_real_escape_string($con, $_POST['txt_work']);
    } else {
        $error = true;
        $errorMessage .= "Enter working hours.<br>";
    }
    if (!$error) {
        $condition = array('id' => $security->decrypt($_GET['edit_id']));
        $insert_data = array('contact_addr' => $addrs, 'contact_call' => $call, 'contact_email' =>$email, 'contact_hours'=>$work);
        $update_cont = $manage->update($manage->dealerTable, $insert_data, $condition);
        if ($update_cont) {
            $error = false;
            $errorMessage = "Contact Added successfully";
            header("location:contact.php?edit_id=".$id);
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }

}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Contact</title>

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
                                    <form method="post" action="">
                                        <fieldset>
                                            <legend>Contact</legend>
                                            <form method="post" action="">
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
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="image_upload">Address</label>
                                                                <textarea id="default"
                                                                          name="txt_addrs"><?php if(isset($address)) echo $address ?></textarea>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="image_upload">Call Us</label>
                                                                <textarea id="default"
                                                                          name="txt_call"><?php if(isset($cont_call)) echo $cont_call ?></textarea>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="image_upload">Email Us</label>
                                                                <textarea id="default"
                                                                          name="txt_email"><?php if(isset($cont_email)) echo $cont_email ?></textarea>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="image_upload">Working Hours</label>
                                                                <textarea id="default"
                                                                          name="txt_work"><?php if(isset($cont_hour)) echo $cont_hour ?></textarea>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <div class="mt-20">
                                                        <div class="col-lg-4">
                                                            <button type="submit" name="add-btn" class="btn btn-primary">Save</button>
                                                            <button type="reset" class="btn btn-danger">cancel</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </form>
                                            <br><br><br>

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
