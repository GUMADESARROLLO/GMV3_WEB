<?php
header('Access-Control-Allow-Origin: *');

include 'functions.php';
include 'sql-query.php';
include 'includes/Sqlsrv.php';

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : "TODOS";

$offset = ($page - 1) * 15;
try {
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    if (trim($filtro) == "TODOS") {
        $sqlsrv = new Sqlsrv();
        $numfilas = $sqlsrv->fetchArray("SELECT COUNT(*) AS num FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 OR ARTICULO LIKE 'VU%'");
        foreach ($numfilas as $fila) {
            $rowCount = $fila['num'];
        }
        $total_pages = ceil($rowCount / 15);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $lab =  $filtro;
        $record_size = $rowCount;
        $offset = ($page - 1) * 15;
    } else {
        $sqlsrv = new Sqlsrv();
        $numfilas = $sqlsrv->fetchArray("SELECT COUNT(*) AS num FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 AND LABORATORIO = '" . $filtro . "'");

        foreach ($numfilas as $fila) {
            $rowCount = $fila['num'];
        }
        $total_pages = ceil($rowCount / 15);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $lab =  $filtro;
        $record_size = $rowCount;
        $offset = ($page - 1) * 15;
    }
    //echo  " " . $offset;
} catch (Throwable $th) {
    echo ($th->getMessage());
}
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
    <input id="offset" type="hidden" name="offset" value="<?php echo $offset; ?>">
    <input id="lab" type="hidden" name="lab" value="<?php echo $filtro; ?>">

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
                                        <select name="laboratorios" id="laboratorios" class="form-control">
                                            <option id="item-seleccionado" name="item-seleccionado" value="TODOS">TODOS</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-sm-2">
                                        <div class="container text-right">
                                            <a id="" href="public/plantillaPDF.php?filtro=<?php echo $filtro ?>" target="_blank" class="btn btn-light text-danger  float-right">
                                                <i class="fas fa-file-pdf fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--content-products-->
                <div class="container-fluid p-0 m-0 " id="load-products-default">
                    <div class="row" id="content-products">
                    </div>
                </div>
                <div class="container-fluid p-0 m-0" id="">
                    <div class="row" id="">
                        <div class="content" id="messages">

                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <a href=""></a>
                </div>

                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">ver mas...</button>-->
                <!-- start popup product-->
                <div class="modal fade bd-example-modal-lg" id="#Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!--modal body start -->
                            <div class="modal-header text-center bg-info">
                                <button type="button bg-danger" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title text-light p-3"></h5>
                            </div>
                            <div class="container-fluid" id="">
                                <div class="row" id="">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="card shadow mb-4 ">
                                            <div class="modal-body">
                                                <div class="card-body ">
                                                    <div class="row d-flex">
                                                        <div class="col-md-4 d-flex ">
                                                            <div class=" justify-content-center align-self-center" id="content-img">
                                                                <img id="img-modal" src="" class="img-content" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8  p-0 m-0">
                                                            <h6 class="" id="tilte-medicament-modal"></h6>
                                                            <div class="container-fluid p-0 m-0" id="container-description-modal">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-info ">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <nav aria-label="Page navigation example" id="pagination-products">
                <ul class="pagination justify-content-end">
                    <li class="page-item">
                        <!--Página primera -->
                        <a id="first-page" class="page-link <?php echo ($page == 1) ? "disabled-link" : ""; ?>" href="manage-product2.php?page=1<?php "&filtro=" . $lab ?>" data-toggle="tooltip" data-placement="bottom" title="Primera página">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Primero</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <!-- página anterior -->
                        <a id="pag-previous" class="page-link <?php echo ($page == 1) ? "disabled-link" : ""; ?>" href="<?php echo ($page == 1) ? "javascript:void(0)" : "manage-product2.php?page=" . ($page - 1) . "&filtro=" . $lab; ?>" aria-label="Previous" data-toggle="tooltip" data-placement="bottom" title="Página anterior">
                            <span aria-hidden="true">&lsaquo;</span>
                            <span class="sr-only">Anterior</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <!-- Página siguiente -->
                        <a id="pag-next" class="page-link  <?php echo ($page == $total_pages) ? "disabled-link" : "" ?>" href="<?php echo ($page == $total_pages) ? "javascript:void(0)" : "manage-product2.php?page=" . ($page + 1) . "&filtro=" . $lab ?>" aria-label="Next" data-toggle="tooltip" data-placement="bottom" title="Página siguiente">
                            <span aria-hidden="true">&rsaquo;</span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <!-- Página última -->
                        <a id="pag-last" class="page-link <?php echo ($page == $total_pages) ? "disabled-link" : "" ?>" href="manage-product2.php?page=<?php echo $total_pages . "&filtro=" . $lab ?>" data-toggle="tooltip" data-placement="bottom" title="Última página">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Último</span>
                        </a>
                    </li>
                </ul>
                <div class="container-fluid text-right">
                    <span id="info-table" class="mr-2">
                        <span class="text-primary"><?php echo $page; ?></span>&nbsp;de&nbsp;<span class="text-primary"><?php echo $total_pages; ?></span>&nbsp;páginas,&nbsp;<span class="text-primary"><?php echo $rowCount; ?></span>&nbsp;registros
                    </span>
                </div>
            </nav>
        </div>
    </div>
    </div>
</body>

</html>