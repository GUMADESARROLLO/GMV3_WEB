<?php
header('Access-Control-Allow-Origin: *');
include 'functions.php';
include 'sql-query.php';
include 'includes/Sqlsrv.php';

if (isset($_POST['txt_buscar'])) {
    $text = $_POST['txt_buscar'];
    $sqlsrv = new Sqlsrv();
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE DESCRIPCION LIKE '% '" . $text . "'%' AND EXISTENCIA > 1");
    foreach ($query as $fila) {
        $set_img = "SinImagen.png";
        $set_des = "";

        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '" . $fila["ARTICULO"] . "'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if ($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];
            $set_des = $link['product_description'];
        }
        $qPromo = "SELECT * FROM tbl_news WHERE banner_sku = '" . $fila["ARTICULO"] . "'";
        $rsPromo = mysqli_query($connect, $qPromo);
        $total_records_promo = mysqli_num_rows($rsPromo);
        $isPromo = ($total_records_promo >= 1) ? "S" : "N";
        if (strpos($fila["ARTICULO"], "VU") !== false) {
            $set_des = '
            <!DOCTYPE html>
                <html>
                <head>
                    <style type="text/css">
                    .alert-box {
                        color:#555;
                        border-radius:10px;
                        font-family:Tahoma,Geneva,Arial,sans-serif;font-size:11px;
                        padding:10px 36px;
                        margin:10px;
                    }
                    .alert-box span {
                        font-weight:bold;
                        text-transform:uppercase;
                    }
                    .error {
                        border:3px solid #f5aca6;
                    }
                    </style>
                </head>
                <body>
                    <div class="alert-box error"><span>Importante: </span>Los Valores de precio y Existencia son informativos.</div>
                </body>
            </html>';
        }
        $json[$i]['product_id']               = $fila["ARTICULO"];
        $json[$i]['product_name']             = strtoupper($fila['DESCRIPCION']);
        $json[$i]['product_image']            = $set_img;
        $json[$i]['product_description']      = $set_des;
        $i++;
    }
}

try {
    $sqlsrv = new Sqlsrv();
    $numfilas = $sqlsrv->fetchArray("SELECT COUNT(*) AS num FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 OR ARTICULO LIKE 'VU%'");
    foreach ($numfilas as $fila) {
        $rowCount = $fila['num'];
    }
    echo $rowCount;
    $total_pages = ceil($rowCount / 15);
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $record_size = $rowCount;
    $total_pages = ceil($record_size / 15);
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * 15;

    echo  " " . $offset;
} catch (Throwable $th) {
    echo ($th->getMessage());
}


$sql_category = "SELECT * FROM tbl_category ORDER BY category_name ASC";
$category_result = mysqli_query($connect, $sql_category);

$sql_currency = "SELECT currency_code FROM tbl_config, tbl_currency WHERE tbl_config.currency_id = tbl_currency.currency_id";
$result = mysqli_query($connect, $sql_currency);
$row = mysqli_fetch_assoc($result);

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
                                            <option value="TODOS">TODOS</option>
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

                <!-- <div class="col-md-4" id="col-clo">
                    <div class="card shadow mb-4 ">
                        <div class="card-body size-body">
                            <div class="row">
                                <div class="col-md-6 p-0 m-0">
                                    <div class="container-fluid p-0 m-0 text-center" id="content-img">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 id="tilte-medicament"></h4>
                                    <div class="container-fluid p-0 m-0" id="container-description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow mb-4 ">
                            <div class="card-body size-body">
                                <div class="row">
                                    <div class="col-md-6 p-0 m-0">
                                        <div class="container-fluid p-0 m-0 text-center" id="content-img">
                                            <img src="upload/product/1570551400_MONTAJE-BACTELID-300x154.png" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 id="tilte-medicament">ABIRATERONA ACETATO 250 MG TABLETAS 60/FRASCO 1/CAJA (NAPROD)</h4>
                                        <div class="container-fluid p-0 m-0" id="container-description">
                                            <p class="size-body"><strong>Vía de Administración:&nbsp;</strong>Oral.<br>
                                                <strong>Indicaciones:</strong>&nbsp;está indicado con Prednisona para: el tratamiento del cáncer de próstata metastásico resistente a la castración (CPRC), tratamiento del cáncer de próstata metastásico de alto riesgo sensible a la castración (CPSC).<br>
                                                <strong>Presentación:&nbsp;</strong>Caja con 60 tabletas y prospecto.<br>
                                                <strong>Modalidad de Venta:&nbsp;</strong>Bajo Prescripcion Medica.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow mb-4 ">
                            <div class="card-body size-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="upload/product/1599859505__6095673.png" class="img-fluid size-image" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <h1>Aceite Kativa</h1>
                                        <div class="container-fluid p-0 m-0">
                                            <p><strong>Vía de Administración:&nbsp;</strong>Oral.<br>
                                                <strong>Indicaciones:</strong>&nbsp;está indicado con Prednisona para: el tratamiento del cáncer de próstata metastásico resistente a la castración (CPRC), tratamiento del cáncer de próstata metastásico de alto riesgo sensible a la castración (CPSC).<br>
                                                <strong>Presentación:&nbsp;</strong>Caja con 60 tabletas y prospecto.<br>
                                                <strong>Modalidad de Venta:&nbsp;</strong>Bajo Prescripcion Medica.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" id="col-ejem-last">
                        <div class="card shadow mb-4">
                            <div class="card-body size-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="upload/product/1603925148_6061094.png" class="img-fluid size-image" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <h1>Aceite Kativa</h1>
                                        <div class="container-fluid p-0 m-0">
                                            <p><strong>Vía de Administración:&nbsp;</strong>Oral.<br>
                                                <strong>Indicaciones:</strong>&nbsp;está indicado con Prednisona para: el tratamiento del cáncer de próstata metastásico resistente a la castración (CPRC), tratamiento del cáncer de próstata metastásico de alto riesgo sensible a la castración (CPSC).<br>
                                                <strong>Presentación:&nbsp;</strong>Caja con 60 tabletas y prospecto.<br>
                                                <strong>Modalidad de Venta:&nbsp;</strong>Bajo Prescripcion Medica.
                                            </p>
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

                <div class="container-fluid p-0 m-0 " id="load-products-default">
                    <div class="row" id="">
                        <div class="content" id="content">

                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <li class="page-item">
                        <a class="page-link <?php echo ($page == 1) ? "disabled-link" : ""; ?>" href="manage-product2.php?page=1" data-toggle="tooltip" data-placement="bottom" title="Primera página">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Primero</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link <?php echo ($page == 1) ? "disabled-link" : ""; ?>" href="<?php echo ($page == 1) ? "javascript:void(0)" : "manage-product2.php?page=" . ($page - 1); ?>" aria-label="Previous" data-toggle="tooltip" data-placement="bottom" title="Página anterior">
                            <span aria-hidden="true">&lsaquo;</span>
                            <span class="sr-only">Anterior</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link  <?php echo ($page == $total_pages) ? "disabled-link" : "" ?>" href="<?php echo ($page == $total_pages) ? "javascript:void(0)" : "manage-product2.php?page=" . ($page + 1); ?>" aria-label="Next" data-toggle="tooltip" data-placement="bottom" title="Página siguiente">
                            <span aria-hidden="true">&rsaquo;</span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link <?php echo ($page == $total_pages) ? "disabled-link" : "" ?>" href="manage-product2.php?page=<?php echo $total_pages; ?>" data-toggle="tooltip" data-placement="bottom" title="Última página">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Último</span>
                        </a>
                    </li>
                </ul>
                <div class="container-fluid text-right">
                    <span id="info-table" class="mr-2">
                        <span class="text-primary"><?php echo $page; ?></span>&nbsp;de&nbsp;<span class="text-primary"><?php echo $total_pages; ?></span>&nbsp;páginas,&nbsp;<span class="text-primary"><?php echo $record_size; ?></span>&nbsp;registros
                    </span>
                </div>
            </nav>
        </div>
    </div>
    </div>
</body>

</html>