<?php
include_once('../includes/config.php');
include_once('functions.php');
include_once('sql-query.php');
include_once('../includes/Sqlsrv.php');

function pagination($filtro)
{
    $json = array();
    $page = 0;
    $total_pages = 0;
    $record_size = 0;
    $val = '';
    $i = 0;
    $laboratio = $_POST['laboratorio'];
    try {
        $sqlsrv = new Sqlsrv();
        if ($laboratio == "TODOS") {
            $numfilas = $sqlsrv->fetchArray("SELECT COUNT(*) AS num FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 OR ARTICULO LIKE 'VU%'");
            foreach ($numfilas as $fila) {
                $rowCount = $fila['num'];
            }
            //echo $rowCount;
            if ($rowCount > 0 && $rowCount != null) {
                $total_pages = ceil($rowCount / 15);
                $record_size = $rowCount;
                $total_pages = ceil($record_size / 15);
                $page = 1;
                $offset = ($page - 1) * 15;

                $json[$i]['page']                 = $page;
                $json[$i]['offset']               = $offset;
                $json[$i]['rowCount']             = $rowCount;
                $json[$i]['totalPage']            = $total_pages;

                header('Content-Type: application/json; charset=utf-8');
                echo $val =  json_encode($json);
            }
        } else {
            $numfilas = $sqlsrv->fetchArray("SELECT COUNT(*) AS num FROM GMV_mstr_articulos WHERE LABORATORIO='$laboratio' AND EXISTENCIA > 1 ");
            foreach ($numfilas as $fila) {
                $rowCount = $fila['num'];
            }
            //echo $rowCount;
            if ($rowCount > 0 && $rowCount != null) {
                $total_pages = ceil($rowCount / 15);
                $record_size = $rowCount;
                $total_pages = ceil($record_size / 15);
                $page = 1;
                $offset = ($page - 1) * 15;

                $json[$i]['page']                 = $page;
                $json[$i]['offset']               = $offset;
                $json[$i]['rowCount']             = $rowCount;
                $json[$i]['totalPage']            = $total_pages;

                header('Content-Type: application/json; charset=utf-8');
                echo $val =  json_encode($json);
            }
        }
    } catch (Throwable $th) {
        echo ($th->getMessage());
    }
}

function search($texto)
{
    if (isset($_POST['txt_buscar'])) {
        $i=0;

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
}
if (isset($_POST['callback'])) {
    $function = $_POST['callback'];
    if ($function == 'Pagination') {
        pagination($_POST['laboratorio']);
    }
    if ($function == 'Buscar') {
        search($_POST['texto']);
    }
}
