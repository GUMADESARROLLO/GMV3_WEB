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
                                        <select name="categorias" id="categorias" class="form-control">
                                            <option value="">TODOS</option>
                                            <option value="">Lab 1</option>
                                            <option value="">Lab 2</option>
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
                <div class="container-fluid p-0 m-0 load-products-default" id="container-products">
                    <div class="row " id="info-products">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 load-products">
                            <div class="card shadow mb-4">
                                <div class="card-body ">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-6">
                                            <img src="upload/product/1603925409_02.png" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-5 col-sm-6 p-0 m-0">
                                            <h2>Aceite Kativa</h2>
                                            <p class="justify-content-center text-secondary ">
                                                Aceite kativa para el pelo 100% organico 100 ml.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-6">
                                            <img src="upload/product/1601479935_CARBOPLATINO 10 MG.png" class="img-fluid " alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-6 p-0 m-0">
                                            <h2>Polvo Naturals</h2>
                                            <p class="justify-content-center text-secondary">
                                                Polvo Naturals para las manchas en la piel 100% natural
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-6">
                                            <img src="assets/images/Recursos/prod3.jpg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-6 p-0">
                                            <h2>Polvo Naturals</h2>
                                            <p class="justify-content-center text-secondary">
                                                Polvo Naturals para las manchas en la piel 100% natural
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-6">
                                            <img src="assets/images/Recursos/prod1.jpg" class="img-fluid" min-height="10rem" max-height="14rem" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-6  m-0 p-0">
                                            <h2>Aceite Kativa</h2>
                                            <p class="justify-content-center text-secondary">
                                                Aceite kativa para el pelo 100% organico 100 ml.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body ">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-6">
                                            <img src="assets/images/Recursos/prod2.jpg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-6 m-0 p-0">
                                            <h2>Pastillas Naturals</h2>
                                            <p class="justify-content-center text-secondary">
                                                Polvo Naturals para las manchas en la piel 100% natural
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-8">
                                            <img src="assets/images/Recursos/prod3.jpg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-0 p-0">
                                            <h2>Medicamento Naturals</h2>
                                            <p class="justify-content-center text-secondary">
                                                Polvo Naturals para las manchas en la piel 100% natural
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-8">
                                            <img src="assets/images/Recursos/prod1.jpg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-0 p-0">
                                            <h2>Aceite Kativa</h2>
                                            <p class="justify-content-center text-secondary">
                                                Aceite kativa para el pelo 100% organico 100 ml.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body ">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-8">
                                            <img src="assets/images/Recursos/prod2.jpg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-0 p-0">
                                            <div class="container"></div>
                                            <h2>Polvo Naturals</h2>
                                            <p class="justify-content-center text-secondary">
                                                Polvo Naturals para las manchas en la piel 100% natural
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row size-body">
                                        <div class="col-md-7 col-sm-8">
                                            <img src="assets/images/Recursos/prod3.jpg" class="img-fluid" alt="">
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-0 p-0">
                                            <h2>Polvo Naturals</h2>
                                            <p class="justify-content-center text-secondary">
                                                Polvo Naturals para las manchas en la piel 100% natural
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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