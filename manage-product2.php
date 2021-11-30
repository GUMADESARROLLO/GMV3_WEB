<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/footer.php'; ?>
<?php include 'public/formulario-PDF-clientes.php'; ?>

<script>
    $(document).ready(function() {

        loadLaboratorios();
        loadData();

        function addElement(parent, child) {
            parent.append(child);
        }

        function loadLaboratorios() {
            var lab = $('#lab').val();
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
                        if ($.trim(element.LAB) == $.trim(lab)) {
                            addElement($("#laboratorios"),
                                $("<option selected></option>").text(element.LAB).attr({
                                    value: element.LAB
                                }));
                        } else if (element.LAB == null || element.LAB == "") {

                        } else {
                            addElement($("#laboratorios"),
                                $("<option></option>").text(element.LAB).attr({
                                    value: element.LAB
                                }));
                        }

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
            var Pages = parseInt($("#offset").val());
            var lab = $('#lab').val();
            var i = 0;
            var num = Pages + 15;
            $.ajax({
                type: 'GET',
                url: 'http://192.168.1.15:8448/gmv3/api/api.php?get_recent',
                dataType: "json",
                data: {},
                success: function(data) {
                    console.log(data);
                    count = Object.keys(data).length;
                    console.log(count);
                    data.forEach(element => {
                        if ($.trim(element.LAB) == $.trim(lab)) {
                            i++;
                            if (i <= num && i > Pages) {
                                const scriptHTML = `<div class="col-md-4">
                                                                <div class="card shadow mb-4 ">
                                                                    <div class="card-body size-body">
                                                                        <div class="row">
                                                                    <div class="col-md-6 p-0 m-0">
                                                                        <div class="container-fluid p-0 m-0 text-center" id="content-img">
                                                                            <img src="upload/product/` + element.product_image + `" class="img-fluid" alt="">
                                                                        </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                        <h4 id="tilte-medicament">` + element.product_name + `</h4>
                                                                        <div class="container-fluid p-0 m-0 description" id="container-description">
                                                                        ` + element.product_description + `
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>`;
                                $("#content-products").append(scriptHTML);
                            }
                            //document.getElementById("laboratorios").value = lab;
                        } else if ($.trim(lab) == "TODOS") {
                            i++;
                            if (i <= num && i > Pages) {
                                const scriptHTML = `<div class="col-lg-4 col-md-6 col-sm-12">
                                 <div class="card shadow mb-4 ">
                                                <div class="card-body size-body">
                                                    <div class="row justify-content-center align-items-center">
                                                    <div class="col-md-6 d-flex justify-content-center"   >
                                                    <div class="container-fluid p-0 m-0 text-center" id="content-img">
                                                        <img src="upload/product/` + element.product_image + `" class="img-fluid" alt="">
                                                    </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <h4 id="tilte-medicament">` + element.product_name + `</h4>
                                                    <div class="container-fluid p-0 m-0" id="container-description">
                                                    ` + element.product_description + `
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                                $("#content-products").append(scriptHTML);
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
            var text = $('#txt_buscar').val();
            // console.log(texto);
            $("#content-products").empty();
            $.ajax({
                type: 'POST',
                url: 'public/ajaxPDF.php',
                dataType: "json",
                data: {
                    callback: 'Buscar',
                    text: text
                },
                success: function(data) {
                    data.forEach(element => {
                        const scriptHTML = `<div class="col-md-4">
                                                                <div class="card shadow mb-4 ">
                                                                    <div class="card-body size-body">
                                                                        <div class="row">
                                                                    <div class="col-md-6 p-0 m-0">
                                                                        <div class="container-fluid p-0 m-0 text-center" id="content-img">
                                                                            <img src="upload/product/` + element.product_image + `" class="img-fluid" alt="">
                                                                        </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                        <h4 id="tilte-medicament">` + element.product_name + `</h4>
                                                                        <div class="container-fluid p-0 m-0" id="container-description">
                                                                        ` + element.product_description + `
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>`;
                        $("#content-products").append(scriptHTML);
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


        /* $('#txt_buscar').on('keypress', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                Search();
            }
        });
        */
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
            var pagination_by_filtro = "manage-product2.php?page=1" + "&filtro=" + (filtro);
            $(location).attr('href', pagination_by_filtro);

        });


    });
</script>