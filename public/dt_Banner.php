<?php
include_once ('../includes/config.php');

$sql_query = "SELECT * FROM tbl_banner";

$resouter = mysqli_query($connect, $sql_query);

$set = array();
$i=0;
$Str ="";
$total_records = mysqli_num_rows($resouter);


$set['data'][$i]['banner_status']    = "";
$set['data'][$i]['banner_name']         = "";
$set['data'][$i]['banner_image']      = "";
$set['data'][$i]['banner_permiso']    = "";

if($total_records >= 1) {


    while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){

        $Status = ($link['banner_status']=="1") ? "SI" : "NO" ;

        $StrPermisos ='

                                        <a href="edit-banner.php?banner_id='.$link['banner_id'].'">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                                            
                                        <a href="delete-banner.php?banner_id='.$link['banner_id'].'" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este banner?\')" >
                                                    <i class="material-icons">delete</i>
                                        </a>';



        $set['data'][$i]['banner_status']    = $Status;
        $set['data'][$i]['banner_name']    = $link['banner_description'];
        $set['data'][$i]['banner_image']      = '<img src="upload/banners/'. $link['banner_image'].'" width="200px" height="200px"/>';
        $set['data'][$i]['banner_permiso']    = $StrPermisos;

        $i++;
    }

}
echo json_encode($set);
?>

