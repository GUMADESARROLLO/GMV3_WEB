<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
    <link rel="stylesheet" type="text/css" href="assets/css/daterangepicker.min.css"/>
<style>
    .date-picker-wrapper {
        border-radius: 10px;
        background-color: #f1f5f8!important;
        padding: 15px!important;
        width: 250px;
    }


    .date-picker-wrapper .month-wrapper {

        width: 250px!important;

    }




</style>
<?php include 'public/table-order.php'; ?>

<?php include 'public/footer.php'; ?>

<script src="assets/js/moment.js"></script>
<script src="assets/js/daterangepicker.js"></script>
<script src="assets/js/jquery.daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        


        fechas = {};
        $('#dom-id').dateRangePicker({
            language: 'es',
            singleMonth: true,
            showShortcuts: false,
            startOfWeek: 'monday',
            separator : ' al ',
            showTopbar: false,
            autoClose: true,
            setValue: function(s,s1,s2) {
                setFechas(s1, s2)
            }
        });

        function setFechas(f1, f2) {

            $("#xls_f1").html(f1);
            $("#xls_f2").html(f2);

            $('#dtPedidos').DataTable({
                "ajax": {
                    'type': 'POST',
                    'url': 'public/dt_Pedidos_fecha.php',
                    'data': {
                        f1: f1,
                        f2: f2
                    }
                },
                "columnDefs": [
                    { "visible": false, "targets": 6 }
                ],

                async:'false',
                "destroy": true,
                "ordering": true,
                "info": false,
                "bPaginate": true,
                "bfilter": false,
                "searching": true,
                "pagingType": "full_numbers",
                "aaSorting": [
                    [0, "desc"]
                ],
                "lengthMenu": [
                    [10, 10, -1],
                    [10, 30, "Todo"]
                ],
                "language": {
                    "zeroRecords": "NO HAY RESULTADOS",
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Última ",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                    "lengthMenu": "_MENU_",
                    "emptyTable": "NO HAY DATOS DISPONIBLES",
                    "search":     "BUSCAR"
                },

                columns: [
                    { "data": "N" },
                    { "data": "name" },
                    { "data": "FullName" },
                    { "data": "order_total" },
                    { "data": "date_time" },
                    { "data": "status" },
                    { "data": "Estado" },
                    { "data": "permisos" }
                ],
                "fnInitComplete":  function(oSettings, json){

                    Pendiente = 0;
                    Procesado = 0;
                    Cancelado = 0;

                    $.each(json['data'], function(i, x) {
                        if (x['Estado'] == 0){
                            Pendiente++;
                        }else if(x['Estado'] == 1){
                            Procesado++;
                        }else{
                            Cancelado++;
                        }

                    });


                    $("#txt_procesado").html(Procesado);
                    $("#txt_pendiente").html(Pendiente);
                    $("#txt_cancelado").html(Cancelado);


                    $("#dtPedidos_filter").hide();
                    $("#dtPedidos_length").hide();
                }
            });


        }







    });

</script>
