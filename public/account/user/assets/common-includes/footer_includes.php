<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- Select Plugin Js -->
<script src="assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<!-- Slimscroll Plugin Js -->
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="assets/plugins/node-waves/waves.js"></script>

<!-- Jquery CountTo Plugin Js -->
<script src="assets/plugins/jquery-countto/jquery.countTo.js"></script>

<!-- Morris Plugin Js -->
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/morrisjs/morris.js"></script>

<!-- ChartJs -->
<script src="assets/plugins/chartjs/Chart.bundle.js"></script>

<!-- Sparkline Chart Plugin Js -->
<script src="assets/plugins/jquery-sparkline/jquery.sparkline.js"></script>

<!-- Custom Js -->
<script src="assets/js/admin.js"></script>
<!--<script src="http://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js" type="text/javascript"></script>-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<!-- MDBootstrap Datatables  -->
<script type="text/javascript" src="assets/js/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/commonValidation.js"></script>
<script type="text/javascript" src="assets/js/important.js"></script>
<?php
if(isset($_GET['show_payment_modal']) && $_GET['show_payment_modal'] !=""){
    ?>
<script>
    function successMessage(){
        Swal.fire({
            showConfirmButton: false,
            title: '<strong>Warning!</strong>',
            icon: 'warning',
            html:
            '<p>Bank details are only editable and visible for Original Card User. Please ask your customer to add bank details from his/her site.</p>',
            showCloseButton: true,
            focusConfirm: false
        })
    }
    $(document).ready(function(){
       successMessage();
    });
</script>
<?php
}
?>
<script>
    function updateNotification(){
        var dataString = "update_notification=true";
        $.ajax({
            type: "POST",
            url: "upload.php",
            data: dataString,
            success: function (html) {
                $("#notifiy_count").remove();
                var leadcount = $('#pending_lead_count').text();
                if(leadcount > 0){
                    $('.title_count').text('('+leadcount + ') Notification');
                }else {
                    $('.title_count').remove();
                }
            }
        });
    }
</script>
<?php
if(isset($_SESSION['type']) && $_SESSION['type'] == "User") {
    ?>
    <script>
        (function ($) {
            var $p = $('.progress');
            var val = parseInt(<?php echo $_SESSION['total_percent'] ?>, 10);
            if (val <= 100 && val > 0) {
                $p.css({
                    width: val + '%',
                    backgroundPosition: val + '%'
                });
            }

        })(jQuery);
    </script>
<?php
}
?>
<script>
   /* $('.open_custom_bar').on('click',function(){
        $('.custom_right_side_bar').removeClass('open_custom');
       $('.custom_right_side_bar').addClass('open_custom');
    });*/
</script>
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
<script>
    function setClipboard(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    }
</script>
<script>
    function copyPrivateLink() {
            var dataString = "insert_token=true";
            $.ajax({
                type:"POST",
                url:"upload.php",
                data:dataString,
                success: function (html) {
                    var tempInput = document.createElement("input");
                    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
                    tempInput.value = html;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempInput);
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                }
            });
    }
</script>
<script>
    function copyClipbaord(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar1");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    }
</script>
<script>
    function copyClipboard(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    }
</script>
<script>
    jQuery(document).ready(function($){

        if (sessionStorage.getItem('splash') !== 'true') {
            $("#bkgOverlay").show().fadeIn(400);
            //Fade in delay for the popup (control timing here)
            $("#delayedPopup").show().fadeIn(400);
            //Hide dialouge and background when the user clicks the close button
            $("#btnClose").click(function (e) {
                HideDialog();
                e.preventDefault();
            });
            sessionStorage.setItem('splash','true');
        }
        window.onbeforeunload = function() {
            localStorage.removeItem("splash");
        };
    });

</script>

<script>
    $(document).ready(function () {
        //Fade in delay for the background overlay (control timing here)

    });
    //Controls how the modal popup is closed with the close button
    function HideDialog() {
        $("#bkgOverlay").fadeOut(400);
        $("#delayedPopup").fadeOut(300);
    }
</script>

<script>
    jQuery(document).ready(function ($) {
        $('.help-button').on('click', function (e) {
            e.preventDefault();
            $(this).siblings('.info-box-url').show();
        });

        $('.close-button').on('click', function (e) {
            e.preventDefault();
            $(this).parents('.info-box-url').hide();
        });
    });

</script>

<script>
    function RestrictSpace() {
        if (event.keyCode == 32) {
            return false;
        }
    }
</script>

<!--<script type="text/javascript">
    $(function () {
        $(document).keydown(function (e) {
            return (e.which || e.keyCode) != 116;
        });
    });
</script>-->
<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
            return false;
        return true;
    }
</script>
<script>
    function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        document.execCommand("copy");

        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copied: " + copyText.value;
    }

    function outFunc() {
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copy to clipboard";
    }
</script>
<script>
   /* $(document).ready(function() {
        $('#manageTable').DataTable();
    } );*/
  /* $(document).ready(function () {
       $('#dtBasicExample').DataTable();
       $('.dataTables_length').addClass('bs-select');
   });*/
   $(document).ready(function () {
       $('#dtHorizontalVerticalExample').DataTable({
           "scrollX": true,
           "ordering": false,
           "lengthMenu": [[-1], ["All"]]
       });
       $('.dataTables_length').addClass('bs-select');
   });

   $(document).ready(function () {
       $('#dtHorizontalExample').DataTable({
           "scrollX": true,
           "ordering": false,
           "lengthMenu": [[-1], ["All"]]
       });
       $('.dataTables_length').addClass('bs-select');
   });
</script>
<script type="text/javascript">
    $(document).bind("contextmenu",function(e) {
        e.preventDefault();
    });
    $(document).keydown(function(e){
        if(e.which === 123){
            return false;
        }
    });

</script>
<script>
    /*$(document).ready(function() {

        $(window).keydown(function(event){

            if(event.keyCode == 116) {

                event.preventDefault();

                return false;

            }

        });

    });*/
</script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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
