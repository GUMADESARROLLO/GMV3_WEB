<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/footer.php'; ?>
<?php include 'public/formulario-PDF-clientes.php'; ?>



<script>
    $(document).ready(function() {
        var categoria, id, pagingOffset, page, texto, txt_buscar
        txt_buscar = $('#txt_buscar');
        categoria = $('#categorias');

        loadData();

        function loadCategorias() {
            $.ajax({
                type: "GET",
                url: "Ajax/loadCategorias.php",
                data: {
                    textoBusqueda: textoBusqueda,
                    callback: 'Cargar_Categorias'
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
                type: 'POST',
                url: 'public/functionsAjax.php',
                data: {
                    //categoria: categoria.val(),
                    //  callback: 'CargarData'
                },
                success: function(data) {
                    data.forEach(element =>{
                        
                    }) 
                    data.modalities.forEach(element => {
                        addElement(selectModalidades,
                            $("<option></option>").text(element.Descripcion)
                            .attr({
                                value: element.CodigoTurno
                            }));
                    });
                    //$('#container-products').html(data);
                    console.log(data);
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