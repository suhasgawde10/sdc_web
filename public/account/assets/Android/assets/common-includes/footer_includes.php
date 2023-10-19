<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<!-- Bootstrap Core Js -->
<script src="assets/plugins/bootstrap/js/bootstrap.js"></script>

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
<script src="https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<!-- MDBootstrap Datatables  -->
<script type="text/javascript" src="assets/js/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/commonValidation.js"></script>
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
            "scrollX": true
        });
        $('.dataTables_length').addClass('bs-select');
    });
    $(document).ready(function () {
        $('#dtHorizontalExample').DataTable({
            "scrollX": true
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>
