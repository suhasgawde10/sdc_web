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

if (isset($_GET['edit_id'])) {
    $edit_plan = $security->decrypt($_GET['edit_id']);
    $form_data = $manage->getDealerByIdEdit($edit_plan);
//    print_r($form_data);
    if ($form_data != "") {
        $customer_name = $form_data['customer_name'];
        $company_name = $form_data['company_name'];
        $domain_name = $form_data['domain_name'];
        $email_id = $form_data['email_id'];
        $cont_no = $form_data['contact_no'];
        $alt_cont_no = $form_data['alter_contact_no'];
        $expiry_date = $form_data['expiry_date'];
        $domain_link = $form_data['domain_link_name'];
    }
}

if (isset($_GET['edit_id'])) {
    if (isset($_POST['btn_update'])) {

        if (isset($_POST['txt_cust_name']) && $_POST['txt_cust_name'] != "") {
            $up_txt_customer = $_POST['txt_cust_name'];
        } else {
            $error = true;
            $errorMessage .= "Enter customer name <br>";
        }
        if (isset($_POST['txt_company']) && $_POST['txt_company'] != "") {
            $up_txt_company = $_POST['txt_company'];
        } else {
            $error = true;
            $errorMessage .= "Enter company name<br>";
        }

        if (isset($_POST['txt_domain']) && $_POST['txt_domain'] != "") {
            $up_txt_domain = $_POST['txt_domain'];
        } else {
            $error = true;
            $errorMessage .= "Enter domain name<br>";
        }

        if (isset($_POST['txt_email']) && $_POST['txt_email'] != "") {
            $up_txt_email = $_POST['txt_email'];
        } else {
            $error = true;
            $errorMessage .= "Enter email id<br>";
        }

        if (isset($_POST['cont_number']) && $_POST['cont_number'] != "") {
            $up_txt_contact = $_POST['cont_number'];
        } else {
            $error = true;
            $errorMessage .= "Enter Contact Number<br>";
        }

        $up_txt_alt_contact = $_POST['alt_cont_number'];

        if (isset($_POST['expiry_date']) && $_POST['expiry_date'] != "") {
            $up_expiry_date = $_POST['expiry_date'];
        } else {
            $error = true;
            $errorMessage .= "Select Expiry date<br>";
        }
        if (isset($_POST['digital_domain_name']) && $_POST['digital_domain_name'] != "") {
            $up_digital_domain_name = $_POST['digital_domain_name'];
        } else {
            $error = true;
            $errorMessage .= "Enter Domain link<br>";
        }

        if (!$error) {
            $condition = array('id' => $security->decrypt($_GET['edit_id']));

            $insert_data = array('customer_name' => $up_txt_customer, 'company_name' => $up_txt_company, 'domain_name' => $up_txt_domain, 'email_id' => $up_txt_email, 'contact_no' => $up_txt_contact, 'alter_contact_no' => $up_txt_alt_contact, 'expiry_date' => $up_expiry_date, 'domain_link_name' => $up_digital_domain_name, 'updated_at' => $today_date, 'updated_by' => $_SESSION['email']);

            $update_dealer = $manage->update($manage->dealerTable, $insert_data, $condition);
            if ($update_dealer) {
                $errorMessage = "Data Update Successfully";
                header('location:manage-dealer.php');
            } else {
                $error = true;
                $errorMessage = "Issue while updating details, Please try again.";
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
    <title>Add Dealer</title>

    <?php include 'assets/common-includes/header_includes.php' ?>

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>

    <style>
        .error {
            color: red;
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
                                <h5>Update Dealer</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <form method="post" action="" id="form_register" enctype="multipart/form-data">
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
                                            <legend>Basic Info</legend>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="customer_name">Customer Name</label>
                                                    <input type="text" name="txt_cust_name" class="form-control"
                                                           id="customer_name" placeholder="Customer Name"
                                                           value="<?php if (isset($customer_name)) echo $customer_name ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="comapany_name">Company Name</label>
                                                    <input type="text" name="txt_company" class="form-control"
                                                           id="comapany_name" placeholder="Company Name"
                                                           value="<?php if (isset($company_name)) echo $company_name ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="domain">Domain Name</label>
                                                    <input type="text" name="txt_domain" class="form-control"
                                                           id="domain" placeholder="Domain Name"
                                                           value="<?php if (isset($domain_name)) echo $domain_name ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="email_id">Email Id</label>
                                                    <input type="text" name="txt_email" class="form-control"
                                                           id="email_id" placeholder="Email Id"
                                                           value="<?php if (isset($email_id)) echo $email_id ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="contact_no">Contact No</label>
                                                    <input type="text" name="cont_number" class="form-control"
                                                           id="contact_no" placeholder="Contact No"
                                                           value="<?php if (isset($cont_no)) echo $cont_no ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="email_id">Alternate Contact No</label>
                                                    <input type="text" name="alt_cont_number" class="form-control"
                                                           id="email_id" placeholder="Alternate Contact"
                                                           value="<?php if (isset($alt_cont_no)) echo $alt_cont_no ?>">
                                                </div>
                                                <!--<div class="form-group col-md-6">
                                                    <label for="image_upload">Logo Upload</label>
                                                    <input type="file" name="photos" class="form-control"
                                                           id="photo" accept="image/*">
                                                </div>-->
                                                <div class="form-group col-md-6">
                                                    <label for="Date">Expiry Date</label>
                                                    <input type="text" class="form-control" id="datepicker"
                                                           name="expiry_date"
                                                           placeholder="Expiry Date" autocomplete="off" required=""
                                                           value="<?php if (isset($expiry_date)) echo $expiry_date ?>">

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="domain_name">Digital card domain name</label>
                                                    <input type="text" class="form-control"
                                                           name="digital_domain_name" id="digital_domain_name"
                                                           placeholder="Digital card domain name" autocomplete="off"
                                                           required=""
                                                           value="<?php if (isset($domain_link)) echo $domain_link ?>">

                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="btn_update" class="btn btn-primary">Update
                                            </button>
                                            <a  href="manage-dealer.php" class="btn btn-danger">cancel</a>
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
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
    // INCLUDE JQUERY & JQUERY UI 1.12.1
    $(function () {
        $("#datepicker, #datepicker1").datepicker({
            dateFormat: "yy-mm-dd",
            duration: "fast",
            changeMonth: true,
            changeYear: true
        });
    });
</script>
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
