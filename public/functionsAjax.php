<?php
//include_once('../includes/config.php');
include '../includes/config.php';


$i = 0;
$json = array();
$query = "SELECT * FROM tbl_product limit 10";
$result = mysqli_query($connect, $query);
$total_records = mysqli_num_rows($result);

$rProducts = mysqli_fetch_array($result, MYSQLI_ASSOC);
$res = "";
$set = array();
$html="";
//$this->fetchArray($query);
if ($total_records >= 1) {
    while ($link = mysqli_fetch_array($result, MYSQLI_ASSOC)){

        if ($link['product_status'] == 'Available') {
            $set['data'][$i]['id']          = $link['product_id'];
            $set['data'][$i]['name']        = $link['product_name'];
            $set['data'][$i]['price']    = $link['product_price'];
            $set['data'][$i]['order_total'] = $link['product_status'];
            $i++;
        }
    
    }

}else{
    $set['data'][$i]['id']  = "-";
    $set['data'][$i]['name'] = "-";
    $set['data'][$i]['price'] = "-";
    $set['data'][$i]['order_total'] = "-";
   }

echo json_encode($set);

