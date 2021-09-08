<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<style>
    .custom-height-modal {
        width: 1000px

    }</style>
<!-- <script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script> -->
<script src="assets/js/ckeditor/ckeditor.js"></script>
<?php include 'public/add-promo-form.php'; ?>
<?php include 'public/footer.php'; ?>

<script>
    $("#srch_modal_articulo_promo").on('keyup',function(){
        var table = $('#tbl_modal_articulo_promo').DataTable();
        table.search(this.value).draw();
    });
    $( "#id_accion_add_articulo_promo" ).click(function() {
        $("#Id_Buscar_promo").val("");
        $('#ModalPromos').modal('show');
    });

    $('#tbl_modal_articulo_promo').DataTable({
        ajax: 'public/dt_modal_articulo.php',
        "destroy": true,
        "ordering": true,
        "info": false,
        "bPaginate": true,
        "bfilter": false,
        "searching": true,
        "pagingType": "full_numbers",
        "aaSorting": [
            [1, "asc"]
        ],
        "lengthMenu": [
            [5, 10, -1],
            [5, 30, "Todo"]
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
            { "data": "product_sku" },
            { "data": "product_name" },
            { "data": "product_image" }
        ],
        "fnInitComplete": function (dta) {
            $("#tbl_modal_articulo_promo_filter").hide();




        }
    });
    $('#tbl_modal_articulo_promo tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected_asign');
        var table   = $('#tbl_modal_articulo_promo').DataTable();
        table
            .rows( '.selected_asign' )
            .data()
            .each( function ( value, index ) {
                $("#Id_Buscar_promo").val(value.product_name);
                $("#id_sku_name").val(value.product_name);
                $("#id_product_sku").val(value.product_sku);

                $('#ModalPromos').modal('hide');

            } );
    } );


</script>
