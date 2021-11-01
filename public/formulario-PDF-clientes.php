<?php
include 'functions.php';
include 'sql-query.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="content" class="pmd-content content-area dashboard">
        <div class="container-fluid full-width-container">
            <h1 class="section-title" id="services">FORMULARIO</h1>
        </div>
        <div class="section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 mb-4">
                        <div class="card border-left-primary shadow h-100">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="input-group col-sm-8">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2" id="txt_buscar">
                                        <button class="btn btn-primary sm" type="button" id="btn-buscar">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                    <div class="input-group col-sm-2">
                                        <select name="laboratorio" id="laboratorios" class="form-control">
                                            <option value="">TODOS</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-sm-2">
                                        <div class="container text-right">
                                            <a id="" href="#!" class="btn btn-light text-danger  float-right">
                                                <i class="fas fa-file-pdf">PDF</i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid p-0 m-0 " id="load-products-default">
                    <div class="row" id="content-products">
                    </div>
                </div>

            </div>


            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    </div>
</body>

</html>