<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/footer.php'; ?>
<?php include 'public/formulario-PDF-clientes.php'; ?>



<script>
    $(document).ready(function() {
        var categoria, id, pagingOffset, page, texto, txt_buscar, dta
        txt_buscar = $('#txt_buscar');
        categoria = $('#categorias');
        containerProducts = ('#info-products')

        loadData();

        function loadCategorias() {
            $.ajax({
                type: "GET",
                url: "Ajax/loadCategorias.php",
                data: {
                    textoBusqueda: textoBusqueda,
                    callback: 'get_recent'
                },
                success: function(data) {
                    $('#container-products').html(data);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + '\r\n' +
                        xhr.statusText + '\r\n' +
                        xhr.responseText + '\r\n' +
                        ajaxOptions);
                }
            });
        }

        function loadData() {
            $("div").remove(".load-products");
            $.ajax({
                type: 'GET',
                url: 'http://localhost/GMV3_WEB/api/api.php?get_recent',
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    for (var i = 0; i < response.length; i++) {
                        $('#info-products').append('<li><img src="' + 
                        respose[i].product_image + '" product_name="' + data[i].product_name + '"/>' + data[i].product_description + '</li>')
                    }

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + '\r\n' +
                        xhr.statusText + '\r\n' +
                        xhr.responseText + '\r\n' +
                        ajaxOptions);
                }
            });
        }

        function search() {
            var textoBusqueda = txt_buscar.val();
            $("div").remove(".load-products");

            $.ajax({
                type: 'POST',
                url: 'public/functionsAjax.php',
                data: {
                    ///textoBusqueda: textoBusqueda,
                    callback: 'Cargar_productos'
                },
                success: function(data) {
                    $('#container-products').html(data);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + '\r\n' +
                        xhr.statusText + '\r\n' +
                        xhr.responseText + '\r\n' +
                        ajaxOptions);
                }
            });
        }
        txt_buscar.on('keypress', function() {
            search();
        })


    });
</script>