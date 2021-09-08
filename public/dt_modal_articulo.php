<?php
include_once ('../includes/config.php');
include_once ('../includes/Sqlsrv.php');

$sqlsrv = new Sqlsrv();

$query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 ORDER BY DESCRIPCION ASC ", SQLSRV_FETCH_ASSOC);
$i = 0;
$json = array();

foreach ($query as $fila) {
    $set_img ="SinImagen.png";

    $query = "SELECT p.product_image FROM tbl_product p WHERE p.product_sku= '".$fila["ARTICULO"]."'";
    $resouter = mysqli_query($connect, $query);
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
        $set_img = $link['product_image'];
    }
    $json['data'][$i]['product_sku']              = $fila["ARTICULO"];
    $json['data'][$i]['product_name']             = strtoupper($fila['DESCRIPCION']);
    $json['data'][$i]['product_image']            = '<img width="100px" height="100px" src="upload/product/'. $set_img.'" width="200px" height="200px"/>';
    $i++;
}

header('Content-Type: application/json; charset=utf-8');
echo $val = str_replace('\\/', '/', json_encode($json));
$sqlsrv->close();


?>

