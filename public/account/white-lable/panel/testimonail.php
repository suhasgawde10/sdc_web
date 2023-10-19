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

$getDealerData = $manage->getDealerByIdEdit($fetchId);
if ($getDealerData != "") {
    $testimonial_status = $getDealerData['testimonial_status'];
}

$getAllTestimonial = $manage->FetchThemeData($manage->testimonialTable, $fetchId);

if (isset($_POST['btn-add'])) {
    if (isset($_POST['txt_name']) && $_POST['txt_name'] != "") {
        $name = mysqli_real_escape_string($con, $_POST['txt_name']);
    } else {
        $error = true;
        $errorMessage .= "Enter team name.<br>";
    }
    if (isset($_POST['txt_desc']) && $_POST['txt_desc'] != "") {
        $testimonial = mysqli_real_escape_string($con, $_POST['txt_desc']);
    } else {
        $error = true;
        $errorMessage .= "Enter Testimonial<br>";
    }
    if (!$error) {
        $insert_data = array('dealer_id' => $fetchId, 'name' => $name, 'testimonail' => $testimonial, 'created_at' => $today_date, 'created_by' => $_SESSION['email']);
        $insert_team = $manage->insert($manage->testimonialTable, $insert_data);
        if ($insert_team) {
            $error = false;
            $errorMessage = "Testimonial Added successfully";
            header("location:testimonail.php?edit_id=" . $id);
        } else {
            $error = true;
            $errorMessage = "Issue while adding details, Please try again.";
        }
    }
}

if (isset($_GET['delete_data']) && ($_GET['action'] == "delete")) {
    $delete_data = $security->decrypt($_GET['delete_data']);
    $status = $manage->deleteData($manage->testimonialTable, $delete_data);
    if ($status) {
        header("location:testimonail.php?edit_id=" . $id);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add testimonial</title>

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
                                    <fieldset>
                                        <legend>Client Testimonial</legend>

                                        <form action="" method="post">
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Testimonial
                                                Status</label>

                                            <div class="col-sm-6">
                                                <input id="toggle-event" type="checkbox"
                                                       data-toggle="toggle" <?php if ($testimonial_status == 1) echo 'checked' ?>  >
                                            </div>
                                        </div>
                                        </form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModalCenter1" style="float: right">
                                            Add testimonial
                                        </button>
                                        <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Add
                                                            Testimonial</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="" method="post">
                                                        <div class="modal-body">
                                                            <div class="col-md-12">
                                                                <label for="name">Name</label>
                                                                <input type="text" name="txt_name"
                                                                       class="form-control"
                                                                       id="name" placeholder="Name" required="">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="default">Testimonial</label>
                                                                <textarea id="default"
                                                                          name="txt_desc"
                                                                          class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="btn-add"
                                                                    class="btn btn-primary">Save
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br><br>

                                        <div class="row">
                                            <?php
                                            if ($getAllTestimonial != "") {
                                                while ($row = mysqli_fetch_array($getAllTestimonial)) {
//                                                    print_r($row);
                                                    ?>
                                                    <div class="col-lg-3">

                                                        <div class="image-area">
                                                            <?php echo $row['testimonail']; ?>
                                                            <a class="remove-testimonial"
                                                               href="testimonail.php?delete_data=<?php echo $security->encrypt($row['id']); ?>&action=delete&edit_id=<?php echo $id; ?>"
                                                               style="display: inline;"><i
                                                                    class="fa fa-trash"></i></a><?php echo $row['name']; ?>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } else {
                                                echo "Not Found!";
                                            }
                                            ?>
                                        </div>
                                    </fieldset>
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
        selector: 'textarea#default',
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
                data: "testimonialstatus=" + data + "&user_id=" + user_id,
                success: function (result) {
                    console.log(result);
                }
            });
        })
    });
</script>

</body>
</html>
