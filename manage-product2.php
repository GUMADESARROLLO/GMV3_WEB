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
        loadLaboratorios();

        /**** MOSTRAR DATOS ******/
        function addElement(parent, child) {
            parent.append(child);
        }

        function loadLaboratorios() {
            $.ajax({
                type: 'GET',
                url: 'http://localhost/GMV3_GP/api/api.php?get_recent',
                dataType: "json",
                //data: {},
                success: function(data) {

                    var result = data.filter(function(el, i, x) {
                        return x.some(function(obj, j) {
                            return obj.product_und === el.product_und && (x = j);
                        }) && i == x;
                    });

                    result.forEach(element => {
                        addElement($("#laboratorios"),
                            $("<option></option>").text(element.product_und));
                    });
                    // alert(JSON.stringify(result, null, 4));

                    console.log(result);
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
                url: 'http://localhost/GMV3_GP/api/api.php?get_recent',
                dataType: "json",
                //data: {},
                success: function(data) {
                    //console.log(data);
                    data.forEach(element => {
                        addElement($("#content-products"),
                            $('<div class="col-lg-4 col-md-6 col-sm-12 col-12">').append($('<div class="card shadow  mb-4">').append($('<div class="card-body bordes">').append($('<div class="container-fluid p-0 m-0 size-body">').append($('<div class="container-fluid p-0 m-0 text-center">').append($('<img class="size-image" alt="">').attr({
                                src: "upload/product/" + (element.product_image)
                            })).append($('<h4 class="font-weight-bold"></h4>').text(element.product_name)).append($(element.product_description)).append('</div>').append($('</div>')).append($('</div>')).append($('</div>')).append($('</div>')))))));


                    });
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