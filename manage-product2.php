<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/footer.php'; ?>
<?php include 'public/formulario-PDF-clientes.php'; ?>
<?php header('Access-Control-Allow-Origin: *'); ?>


<script>
    $(document).ready(function() {

        loadData();
        loadLaboratorios();
        //loadData();
        /**** MOSTRAR DATOS ******/
        function addElement(parent, child) {
            parent.append(child);
        }

        function loadLaboratorios() {
            $.ajax({
                type: 'GET',
                url: 'http://192.168.1.15:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                //data: {},
                success: function(data) {

                    var result = data.filter(function(el, i, x) {
                        return x.some(function(obj, j) {
                            return obj.LAB === el.LAB && (x = j);
                        }) && i == x;
                    });

                    result.forEach(element => {
                        addElement($("#laboratorios"),
                            $("<option></option>").text(element.LAB).attr({
                                value: element.LAB
                            }));
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

        function getFiltros() {
            var Pages = parseInt($("#offset").val());
            var filtro = $('#laboratorios option:selected').val();
            var i = 0;
            var num = Pages + 15;
            $("div").remove(".load-products");
            $.ajax({
                type: 'GET',
                url: 'http://192.168.1.15:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                data: {},
                success: function(data) {
                    data.forEach(element => {
                        i++;
                        if (i <= num && i > Pages) {
                            if (element.LAB == filtro)
                                addElement($("#content-products"),
                                    $('<div class="col-lg-4 col-md-6 col-sm-12 col-12">').append($(
                                        '<div class="card shadow  mb-4">').append($(
                                        '<div class="card-body bordes">').append($(
                                        '<div class="container-fluid p-0 m-0 size-body">').append($(
                                        '<div class="container-fluid p-0 m-0 text-center">').append($(
                                        '<img class="size-image" alt="">').attr({
                                        src: "upload/product/" + (element.product_image)
                                    })).append($(
                                        '<h4 class="font-weight-bold"></h4>').text(element.product_name)).append($(element.product_description)).append(
                                        '</div>').append($(
                                        '</div>')).append($(
                                        '</div>')).append($(
                                        '</div>')).append($(
                                        '</div>')))))));
                        }
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

        function loadData() {
            var Pages = parseInt($("#offset").val());
            var filtro = $('#laboratorios option:selected').val();
            var i = 0;
            var num = Pages + 15;
            $.ajax({
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                type: 'GET',
                url: 'http://192.168.1.15:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                data: {},
                success: function(data) {
                    console.log(data);
                    count = Object.keys(data).length;
                    console.log(count);
                    data.forEach(element => {
                        if (element.LAB == filtro) {
                            i++;
                            if (i <= num && i > Pages) {
                                /*var clonModalFila3 = $('#col-clo').clone();
                                $('#col-clo').after(clonModalFila3);*/
                                const scriptHTML = `<div class="col-md-4">
                                 <div class="card shadow mb-4 ">
                                      <div class="card-body size-body">
                                        <div class="row">
                                       <div class="col-md-6 p-0 m-0">
                                        <div class="container-fluid p-0 m-0 text-center" id="content-img">
                                            <img src="upload/product/`+element.product_image+`" class="img-fluid" alt="">
                                        </div>
                                         </div>
                                        <div class="col-md-6">
                                        <h4 id="tilte-medicament">` + element.product_name+`</h4>
                                        <div class="container-fluid p-0 m-0" id="container-description">
                                        ` +element.product_description+`
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    $("#content-products").append(scriptHTML);
                    
                            }
                        } else if (filtro == "TODOS") {
                            i++;
                            if (i <= num && i > Pages) {
                                addElement($("#content-products"),
                                    $('<div class="col-lg-4 col-md-6 col-sm-12 col-12">').append($(
                                        '<div class="card shadow  mb-4">').append($(
                                        '<div class="card-body bordes">').append($(
                                        '<div class="container-fluid p-0 m-0 size-body">').append($(
                                        '<div class="container-fluid p-0 m-0 text-center">').append($(
                                        '<img class="size-image" alt="">').attr({
                                        src: "upload/product/" + (element.product_image)
                                    })).append($(
                                        '<h4 class="font-weight-bold"></h4>').text(element.product_name)).append($(element.product_description)).append(
                                        '</div>').append($(
                                        '</div>')).append($(
                                        '</div>')).append($(
                                        '</div>')).append($(
                                        '</div>')))))));
                            }
                        }
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

        function Search() {
            var texto = $('#txt_buscar').val();
            console.log(texto);
            $.ajax({
                type: 'GET',
                url: 'public/formulario-PDF-clientes.php',
                dataType: "json",
                data: {},
                success: function(data) {
                    console.log('data');
                    data.forEach(element => {
                        console.log(element);
                    });
                }
            });
        }

        $('#txt_buscar').on('keypress', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                Search();
            }
        });

        $('#btn-buscar').on('click', function(e) {
            Search();
        });

        $('#laboratorios').on('change', function(e) {
            var filtro = $('#laboratorios option:selected').val();
            var container = document.getElementById('content-products');
            console.log(filtro);
            while (container.firstChild) {
                container.removeChild(container.firstChild)
            }
            loadData();
        });

    });
    /* addElement($("#content-products"),
                                $('<div class="col-lg-4 col-md-6 col-sm-12 col-12">').append($('<div class="card shadow  mb-4">').append($('<div class="card-body bordes">').append($('<div class="container-fluid p-0 m-0 size-body">').append($('<div class="container-fluid p-0 m-0 text-center">').append($('<img class="size-image" alt="">').attr({
                                    src: "upload/product/" + (element.product_image)
                                })).append($('<h4 class="font-weight-bold"></h4>').text(element.product_name)).append($(element.product_description)).append('</div>').append($('</div>')).append($('</div>')).append($('</div>')).append($('</div>')))))));      
*/


    /**<p>
<div class="col-lg-4 col-md-6 col-sm-12 col-12">
    <div class="card shadow  mb-4">
        <div class="card-body bordes">
            <div class="container-fluid p-0 m-0 size-body">
                <div class="container-fluid p-0 m-0 text-center">
                    <img class="size-image" src="" alt="">
                    <h4 class="font-weight-bold"></h4>
                </div>
            </div>
        </div>
    </div>
</div>
</p> */

    /* $("'<div class='col-md-4'>", "<div class='card shadow mb-4'>", "<div class='card-body size-body'>",
         "<div class='row'><div class='col-md-6 p-0 m-0'>", "<div class='container-fluid p-0 m-0 text-center'>", "<img src='upload/product/1570551400_MONTAJE-BACTELID-300x154.png' class='img-fluid' alt=''>", "</div>", "</div>", "<div class='col-md-6'>",
         "<h1>Polvo Naturals</h1>",
         "<div class='container-fluid p-0 m-0'>", "p>Polvo Naturals para las manchas en la piel 100% natural</p>", "</div>", "</div>", "</div>", "</div>", "</div>", "</div>'")
    */
</script>