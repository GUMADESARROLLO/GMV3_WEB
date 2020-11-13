<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/table-product.php'; ?>
<?php include 'public/footer.php'; ?>
<script>
    $('#dtArticulos').DataTable({
        ajax: 'public/dt_Articulos.php',
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
            { "data": "product_sku" },
            { "data": "product_name" },
            { "data": "product_image" },
            { "data": "product_status" },
            { "data": "product_permiso" }
        ],
        "fnInitComplete": function (dta) {
            $("#dtArticulos_filter").hide();
        }
    });
</script>
