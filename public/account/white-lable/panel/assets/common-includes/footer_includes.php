<!-- jQuery -->
<script src="assets/jquery/jquery.min.js"></script>
<script src="assets/datatables/jquery.dataTables.js"></script>
<script src="assets/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script>
    document.multiselect('#testSelect1');
</script>
<script>
    $(function () {
        $("#example1").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "scrollX": true
        });
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<!-- Bootstrap 4 -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="assets/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="assets/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="assets/moment/moment.min.js"></script>
<script src="assets/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- date-range-picker -->
<script src="assets/daterangepicker/daterangepicker.js"></script>
<script src="assets/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="assets/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="assets/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="assets/dist/js/demo.js"></script>
<script>
    document.multiselect('#allSelect1');
    document.multiselect('#allSelect2');
    document.multiselect('#allSelect3');
    document.multiselect('#shift_employee');
</script>
<!-- Page script -->

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'})
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'})
        //Money Euro
        $('[data-mask]').inputmask()

        //Date picker
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-M-yyyy'
        })
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

        //Timepicker
        $('.timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function (event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        });

        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

    })
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('focus', ':input', function () {
            $(this).attr('autocomplete', 'off');
        });
    });

    /*    var today = new Date();
     var dd = String(today.getDate()).padStart(2, '0');
     var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
     var yyyy = today.getFullYear();

     today = yyyy + "-" + mm + '-' + dd;


     $('input[type=date]').val(today);*/


    /*$("input[type=date]").on("change", function() {
     this.setAttribute(
     "data-date",
     moment(this.value, "YYYY-MM-DD")
     .format( this.getAttribute("data-date-format") )
     )
     }).trigger("change");*/


    /*$("input[type=date]").click(function(){
     this.setAttribute(
     "data-date",
     moment(this.value, "YYYY-MM-DD")
     .format( this.getAttribute("data-date-format") )
     )
     }).trigger("change");*/
</script>
