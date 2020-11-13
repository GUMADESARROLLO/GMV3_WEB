<?php
session_start();
include_once ('../includes/config.php');

$retVal = ($_SESSION['permisos'] == '3' || $_SESSION['permisos'] == '2') ? "WHERE name in (".$_SESSION['grupos'].") and status =0" : "where status = 0" ;

$sql_query = "SELECT * FROM tbl_order ".$retVal." ORDER BY id DESC ";

$resouter = mysqli_query($connect, $sql_query);

$set = array();
$i=0;
$Str ="";
$total_records = mysqli_num_rows($resouter);
if($total_records >= 1) {
    while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){

        if ($link['status'] == '1') {
            $Str = '<span class="badge badge-success">PROCESADO</span>';
        } else if ($link['status'] == '2') {
            $Str = '<span class="badge">&nbsp;CANCELADO&nbsp;</span>';
        } else {
            $Str = '<span class="badge badge-error">&nbsp;&nbsp;&nbsp;PENDIENTE&nbsp;&nbsp;&nbsp;</span>';
        }

        $StrPermisos = ($_SESSION['permisos'] != 2 && $_SESSION['permisos'] != 3) ? '<a href="manage-order.php?id='.$link['id'].'"  onclick="return confirm(\'¿Está seguro de que desea eliminar este pedido de forma permanente?\')" <i class="material-icons">delete</i> </a>':'';

        $accciones ='<a href="order-detail.php?id='.$link['id'].'">
                                                    <i class="material-icons">open_in_new</i>
                                                </a>';


        $set['data'][$i]['N']           = $link['code'];
        $set['data'][$i]['name']        = $link['name'];
        $set['data'][$i]['FullName']    = $link['email'].$link['phone'];
        $set['data'][$i]['order_total'] = $link['order_total'];
        $set['data'][$i]['date_time']   = $link['date_time'];
        $set['data'][$i]['status']      = $Str;
        $set['data'][$i]['Estado']      = $link['status'];
        $set['data'][$i]['permisos']    = $accciones.$StrPermisos;
        $i++;
    }

}else{
    $set['data'][$i]['N']           = "-";
    $set['data'][$i]['name']        = "-";
    $set['data'][$i]['FullName']    = "-";
    $set['data'][$i]['order_total'] = "-";
    $set['data'][$i]['date_time']   = "-";
    $set['data'][$i]['status']      = "-";
    $set['data'][$i]['Estado']      = "-";
    $set['data'][$i]['permisos']    = "-";
}
echo json_encode($set);
?>

