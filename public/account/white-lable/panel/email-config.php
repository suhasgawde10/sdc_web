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
$host = $fetAllData['smtp_host'];
$user = $fetAllData['smtp_username'];
$password = $fetAllData['smtp_password'];
$port = $fetAllData['smtp_port'];

if (isset($_POST['add-btn'])) {
    if (isset($_POST['txt_host']) && $_POST['txt_host'] != "") {
        $host_name = mysqli_real_escape_string($con, $_POST['txt_host']);
    } else {
        $error = true;
        $errorMessage .= "Enter host name.<br>";
    }
    if (isset($_POST['txt_username']) && $_POST['txt_username'] != "") {
        $user_name = mysqli_real_escape_string($con, $_POST['txt_username']);
    } else {
        $error = true;
        $errorMessage .= "Enter username.<br>";
    }
    if (isset($_POST['txt_pswd']) && $_POST['txt_pswd'] != "") {
        $user_pass = mysqli_real_escape_string($con, $_POST['txt_pswd']);
    } else {
        $error = true;
        $errorMessage .= "Enter Password.<br>";
    }
    if (isset($_POST['txt_port']) && $_POST['txt_port'] != "") {
        $hostPrt = is_numeric($_POST['txt_port']);
        if ($hostPrt) {
            $host_port = $_POST['txt_port'];
        } else {
            $error = true;
            $errorMessage .= "Enter Numeric Value<br>";
        }

    } else {
        $error = true;
        $errorMessage .= "Enter port number.<br>";
    }

    if (!$error) {
        $condition = array('id' => $security->decrypt($_GET['edit_id']));
        $insert_data = array('smtp_host' => $host_name, 'smtp_username' => $user_name, 'smtp_password' => $user_pass, 'smtp_port' => $host_port);
        $update_cont = $manage->update($manage->dealerTable, $insert_data, $condition);
        if ($update_cont) {
            $error = false;
            $errorMessage = "Email Data updated successfully";
            header("location:email-config.php?edit_id=" . $id);
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
    <title>Email Config</title>

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
                                    <form method="post" action="">
                                        <fieldset>
                                            <legend>Email Config</legend>
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">SMTP-Host</label>
                                                        <input type="text" class="form-control" name="txt_host"
                                                               placeholder="Host name"
                                                               value="<?php if (isset($host)) echo $host ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">User Name</label>
                                                        <input type="text" class="form-control" name="txt_username"
                                                               placeholder="User Name"
                                                               value="<?php if (isset($user)) echo $user ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">Password</label>
                                                        <input type="password" class="form-control" name="txt_pswd"
                                                               placeholder="Password"
                                                               value="<?php if (isset($password)) echo $password ?>">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="image_upload">Port</label>
                                                        <input type="text" class="form-control" name="txt_port"
                                                               placeholder="Port Number"
                                                               value="<?php if (isset($port)) echo $port ?>">
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="mt-20">
                                                    <button class="btn btn-primary" name="add-btn" type="submit">
                                                        Update
                                                    </button>
                                                </div>

                                            </div>
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
