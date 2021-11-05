<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<?php include 'public/footer.php'; ?>
<?php include 'public/formulario-PDF-clientes.php'; ?>

<script>
    $(document).ready(function() {

        loadLaboratorios();
        loadData();
        //pagination();
ñ
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

        function pagination() {
            var offset = parseInt($("#offset").val());
            var lab = $('#laboratorios option:selected').val();
            var i = 0;
            var num = offset + 15;
            $.ajax({
                type: 'POST',
                url: 'public/ajaxPDF.php',
                dataType: "json",
                data: {
                    callback: 'Pagination',
                    laboratorio: lab
                },
                success: function(data) {
                    console.log(data);
                    data.forEach(element => {
                        var page = element.page;
                        offset.attr('value',0);
                        $('#first-page');
                        $('#pag-previous');
                        $('#pag-next');
                        $('#pag-last');
                        var link = "manage-product2.php?rowCount=" + (element.rowCount) + "";
                        $('#page').attr('href', link);

                        var last = "manage-product2.php?page=" + (element.totalPage) + ""; // ultimo link
                        $('#pag-last').attr('href', last);
                        $('#total-paginas').text(element.totalPage);
                        $('#total-registros').text(element.rowCount);
                        console.log(link);
                    })
                    loadData();   
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

                            }
                        } else if (filtro == "TODOS") {
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
            pagination();
        });
        $('#btn-buscar').on('click', function(e) {
            Search();
        });
       /*$('#first-page').on('click', function(e) {
            loadData();
        });
        $('#pag-previous').on('click', function(e) {
            loadData();
        });
        $('#pag-next').on('click', function(e) {
            loadData();
        });
        $('#pag-last').on('click', function(e) {
            loadData();
        });*/

    });
</script>