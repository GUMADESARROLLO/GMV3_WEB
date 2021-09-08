<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/table-banner.php'; ?>
<?php include 'public/footer.php'; ?>
<script>
    $("#Id_Buscar_banner").on('keyup',function(){
        var table = $('#dtBanner').DataTable();
        table.search(this.value).draw();
    });
    $('#dtBanner').DataTable({
        ajax: 'public/dt_Banner.php',
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
            { "data": "banner_status" },
            { "data": "banner_image" },
            { "data": "banner_name" },
            { "data": "banner_permiso" }
        ],
        "fnInitComplete": function (dta) {
            $("#dtBanner_filter").hide();
        }
    });
</script>
