<?php
session_start();
include_once ('../includes/config.php');



$sqlQuery = "SELECT Code as pedido,created_at,Cliente,NameCliente,Valor FROM view_master_pedidos T0 WHERE T0.Ruta = '".$_POST['Ruta']."' and T0.Mes = MONTH(NOW())";



$resouter = mysqli_query($connect, $sqlQuery);

$set = array();
$i=0;
$Str ="";
$total_records = mysqli_num_rows($resouter);
if($total_records >= 1) {
    while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
        $set['data'][$i]['Pedido']      = $link['pedido'];
        $set['data'][$i]['Cliente']     = $link['Cliente'].' - '.$link['NameCliente'];
        $set['data'][$i]['created_at']  = $link['created_at'];
        $set['data'][$i]['Valor']       = $link['Valor'];
        $i++;
    }
}
echo json_encode($set);
?>

