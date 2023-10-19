

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<!--<script src="assets/plugins/jquery/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<!-- Bootstrap Core Js -->
<!--<script src="assets/plugins/bootstrap/js/bootstrap.js"></script>-->

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
           "scrollX": true,
           "ordering":false
       });
       $('#dtHorizontalVerticalExample1').DataTable({
           "scrollX": true,
           "ordering":false
       });
       $('.dataTables_length').addClass('bs-select');
   });
   $(document).ready(function () {
       $('#dtHorizontalExample').DataTable({
           "scrollX": true,
           "ordering":false
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
<div class="modal fade" id="supportModal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header cust-upi-madal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Support Team</h4>
            </div>
            <div class="modal-body">
                <form id="upi_form_validation" method="POST" action="">
                    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
                        <img src="assets/images/technical-support.png">
                    </div>
                    <table class="table">
                        <tr>
                            <td>
                                Support Team
                            </td>
                            <td>
                                +91 9321894076
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Ajay Chorge
                            </td>
                            <td>
                                +91 97689 04980
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Suhas Gawde
                            </td>
                            <td>
                                +91 97738 84631
                            </td>
                        </tr>

                    </table>
                </form>
            </div>
        </div>
    </div>
</div>