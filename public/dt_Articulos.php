<?php
include_once ('../includes/config.php');

$sql_query = "SELECT p.product_id, p.product_name,p.product_sku, p.product_price, p.product_status, p.product_image, p.product_description, p.product_quantity, p.category_id, c.category_name FROM tbl_product p ,tbl_category c WHERE p.category_id = c.category_id ORDER BY p.product_id ";

$resouter = mysqli_query($connect, $sql_query);

$set = array();
$i=0;
$Str ="";
$total_records = mysqli_num_rows($resouter);
if($total_records >= 1) {
    while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){

        $StrPermisos =' <a href="send-onesignal-product-notification.php?id='.$link['product_id'].'">
                                            <i class="material-icons">notifications_active</i>
                                        </a>

                                        <a href="edit-product.php?product_id='.$link['product_id'].'">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                                            
                                        <a href="delete-product.php?product_id='.$link['product_id'].'" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este producto?\')" >
                                                    <i class="material-icons">delete</i>
                                        </a>';
        if ($link['product_status'] == 'Available') {
            $StrStatus ='<span class="badge badge-success">PUBLICADO</span>';
        }else{
            $StrStatus ='<span class="badge badge-success">OCULTO</span>';
        }



        $set['data'][$i]['product_sku']    = $link['product_sku'];
        $set['data'][$i]['product_name']    = $link['product_name'];
        $set['data'][$i]['product_image']      = '<img src="upload/product/'. $link['product_image'].'" width="64px" height="64px"/>';
        $set['data'][$i]['product_status']     = $StrStatus;
        $set['data'][$i]['product_permiso']    = $StrPermisos;

        $i++;
    }
    echo json_encode($set);
}

?>

