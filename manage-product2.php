<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/footer.php'; ?>
<?php include 'public/formulario-PDF-clientes.php'; ?>
<?php header('Access-Control-Allow-Origin: *'); ?>

<script>
    $(document).ready(function() {
        loadLaboratorios();
        loadData();
        $('.content-des').children().each(function(i) {
            var item = $(this).val();
            console.log(item);
        });

        function addElement(parent, child) {
            parent.append(child);
        }

        function loadLaboratorios() {
            var lab = $('#lab').val();
            $.ajax({
                type: 'GET',
                url: 'http://186.1.15.166:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                //data: {},
                success: function(data) {

                    var result = data.filter(function(el, i, x) {
                        return x.some(function(obj, j) {
                            return obj.LAB === el.LAB && (x = j);
                        }) && i == x;
                    });

                    result.forEach(element => {
                        if ($.trim(element.LAB) == $.trim(lab)) {
                            addElement($("#laboratorios"),
                                $("<option selected></option>").text(element.LAB.toUpperCase()).attr({
                                    value: element.LAB
                                }));
                        } else if (element.LAB == null || element.LAB == "") {

                        } else {
                            addElement($("#laboratorios"),
                                $("<option></option>").text(element.LAB.toUpperCase()).attr({
                                    value: element.LAB
                                }));
                        }

                    });
                    // alert(JSON.stringify(result, null, 4));

                    // console.log(result);
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
            var lab = $('#lab').val();
            var i = 0;
            var num = Pages + 15;
            $.ajax({
                type: 'GET',
                url: 'http://186.1.15.166:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                data: {},
                success: function(data) {
                    // console.log(data);
                    count = Object.keys(data).length;
                    // console.log(count);
                    data.forEach(element => {
                        if (element.LAB !== null ) {
                            if ($.trim(element.LAB) == $.trim(lab)) {
                                i++;
                                if (i <= num && i > Pages) {
                                    const scriptHTML = `
                                <div class="col-lg-4 col-md-6 col-sm-10 col-12">
                                    <div class="card shadow mb-4 ">
                                        <div class="card-body size-body">
                                            <div class="row d-flex">
                                                <div class="col-md-6 p-0 m-0 d-flex ">
                                                    <div class=" p-0 m-0 justify-content-center align-self-center" id="content-img">
                                                        <img src="http://186.1.15.166:8448/gmv3/upload/product/` + element.product_image + `" class="img-content" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6  p-0 m-0">
                                                    <h6 class = "" id="tilte-medicament">` + element.product_name + `</h6>
                                                    <div class="container-fluid content-des   p-0 m-0" id="container-description">
                                                       ` + element.product_description + ` 
                                                       <button id="btn-modal` + i + `"  type="button" value = "` + element.product_id + `" class="btn-modal-pre btnModal bg-transparent b-0 p-0" data-toggle="modal" data-target=".bd-example-modal-lg">Ver mas</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                             </div>`;
                                    $("#content-products").append(scriptHTML);

                                    if ($(element.product_description).width() > $("#container-description").width()) {
                                        console.log('el texto es mas grande que el div')
                                    }
                                }
                                //document.getElementById("laboratorios").value = lab;
                            } else if ($.trim(lab) == "TODOS") {
                                i++;
                                if (i <= num && i > Pages) {
                                    const scriptHTML = `<div class="col-lg-4 col-md-6 col-sm-10 col-12">
                                    <div class="card shadow mb-4 ">
                                        <div class="card-body size-body">
                                            <div class="row d-flex">
                                                <div class="col-md-6 p-0 m-0 d-flex ">
                                                    <div class=" p-0 m-0 justify-content-center align-self-center" id="content-img">
                                                        <img src="http://186.1.15.166:8448/gmv3/upload/product/` + element.product_image + `" class="img-content" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6  p-0 m-0">
                                                    <h6   class ="" id="tilte-medicament">` + element.product_name + `</h6>
                                                    <div class="container-fluid   p-0 m-0" id="container-description">
                                                       ` + element.product_description + ` 
                                                            <button id="btn-modal` + i + `"  type="button" value = "` + element.product_id + `" class="btn-modal-pre btnModal bg-transparent b-0 p-0" data-toggle="modal" data-target=".bd-example-modal-lg">Ver mas</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                             </div>`;
                                    $("#content-products").append(scriptHTML);


                                }
                            }
                        }
                    });

                    $('div #container-description >p').each(function(i) {
                        /*** Calculo el tamaño para añadir el botón ver mas */
                        //calculando ancho y altura del div
                        var btn = $(this).next();
                        var ancho = $('#container-description').width();
                        var altodiv = $('#container-description').height();
                        //Calculando la altura y ancho de una etiqueta p
                        var altop = $(this).height();
                        if (altop > altodiv) {
                            $(this).addClass('block-ellipsis');
                            btn.removeClass('btn-modal-pre');
                            btn.addClass('btn-modal-next');
                            btn.show('fast');
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
            var text = $('#txt_buscar').val();
            var i = 0;
            $("#messages").empty();
            $("#content-products").empty();
            if (text == '') {
                loadData();
                $("#pagination-products").show();
                return;
            }

            // $("#pagination-products").empty();
            $.ajax({
                type: 'POST',
                url: 'public/ajaxPDF.php',
                dataType: "json",
                data: {
                    callback: 'Buscar',
                    text: text
                },
                success: function(data) {
                    if (data == "") {
                        const messages = `
                            <div class="card">
                                <h1>No se encontraron resultados</h1>
                            </div>`;
                        $("#pagination-products").hide();
                        $("#messages").append(messages);
                        return;
                    } else {
                        data.forEach(element => {
                            i++;
                            const scriptHTML = `<div class="col-lg-4 col-md-6 col-sm-10 col-12">
                                                     <div class="card shadow mb-4 ">
                                                         <div class="card-body size-body">
                                                             <div class="row d-flex">
                                                                 <div class="col-md-6 p-0 m-0 d-flex ">
                                                                     <div class=" p-0 m-0 justify-content-center align-self-center" id="content-img">
                                                                         <img src="http://186.1.15.166:8448/gmv3/upload/product/` + element.product_image + `" class="img-content" alt="">
                                                                     </div>
                                                                 </div>
                                                                 <div class="col-md-6  p-0 m-0">
                                                                     <h6   class = "" id="tilte-medicament">` + element.product_name + `</h6>
                                                                     <div class="container-fluid content-des  p-0 m-0" id="container-description">
                                                                        ` + element.product_description + ` 
                                                                        <button id="btn-modal` + i + `"  type="button" value = "` + element.product_id + `" class="btn-modal-pre btnModal bg-transparent b-0 p-0" data-toggle="modal" data-target=".bd-example-modal-lg">Ver mas</button>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </div>
                                                </div>`;
                            $("#content-products").append(scriptHTML);
                            $("#pagination-products").hide();
                        });

                        $('div #container-description >p').each(function(i) {
                            /*** Calculo el tamaño para añadir el botón ver mas */
                            //calculando ancho y altura del div
                            var btn = $(this).next();
                            var ancho = $('#container-description').width();
                            var altodiv = $('#container-description').height();
                            //Calculando la altura y ancho de una etiqueta p
                            var altop = $(this).height();
                            if (altop > altodiv) {
                                $(this).addClass('block-ellipsis');
                                btn.removeClass('btn-modal-pre');
                                btn.addClass('btn-modal-next');
                                btn.show('fast');
                            }
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + '\r\n' +
                        xhr.statusText + '\r\n' +
                        xhr.responseText + '\r\n' +
                        ajaxOptions);

                    console.log(thrownError + '\r\n' +
                        xhr.statusText + '\r\n' +
                        xhr.responseText + '\r\n' +
                        ajaxOptions);
                }
            });
        }

        $('#txt_buscar').on('keypress', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                Search();
            }
        });

        $(document).on('click', '.btnModal', function() {
            console.log('le distes click')
            var productID = $(this).val();
            console.log(productID);
            loadModal(productID);
        });

        $('#laboratorios').on('change', function(e) {
            var filtro = $('#laboratorios option:selected').val();
            var container = document.getElementById('content-products');
            //console.log(filtro);
            while (container.firstChild) {
                container.removeChild(container.firstChild)
            }
            var pagination_by_filtro = "manage-product2.php?page=1" + "&filtro=" + (filtro);
            $(location).attr('href', pagination_by_filtro);

        });

        function loadModal(valor) {
            var productId = valor;
            $.ajax({
                type: 'GET',
                url: 'http://186.1.15.166:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                data: {},
                success: function(data) {
                    // console.log(data);
                    count = Object.keys(data).length;
                    // console.log(count);
                    data.forEach(element => {
                        if (element.product_id === productId) {
                            $('#img-modal').attr('src', "http://186.1.15.166:8448/gmv3/upload/product/" + element.product_image);
                            $('#container-description-modal').children().remove();
                            $('#tilte-medicament-modal').text(element.product_name);
                            $('#container-description-modal').append(element.product_description);
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
    });
</script>