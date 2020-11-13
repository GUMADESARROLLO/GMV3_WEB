<?php include_once('sql-query.php'); ?>

<?php
if (isset($_GET['id_delete'])) {

    // $sql = 'SELECT * FROM tbl_fcm_template WHERE id=\''.$_GET['id'].'\'';
    // $img_rss = mysqli_query($connect, $sql);
    // $img_rss_row = mysqli_fetch_assoc($img_rss);

    // if ($img_rss_row['image'] != "") {
    //     unlink('upload/notification/'.$img_rss_row['image']);
    // }

    Delete('tbl_comment','id_coment='.$_GET['id_delete'].'');
    if (isset($_GET['id'])) {
        $ID = $_GET['id'];
    } else {
        $ID = "";
    }

   header("location: order-detail.php?id=".$ID);
    exit;

}



	if (isset($_GET['id'])) {
		$ID = $_GET['id'];
	} else {
		$ID = "";
	}
			
	$error = array();
	$data = array();

	$sql_query = "SELECT * FROM tbl_order WHERE id = ?";



	$stmt = $connect->stmt_init();
	if ($stmt->prepare($sql_query)) {
		$stmt->bind_param('s', $ID);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result(
			$data['id'],
			$data['code'],
            $data['code_vendedor'],
            $data['name_vendedor'],
            $data['code_cliente'],
			$data['name'],
			$data['email'],
			$data['phone'],
			$data['address'],
			$data['shipping'],
			$data['date_time'],
            $data['created_at'],
            $data['updated_at'],
			$data['order_list'],
			$data['order_total'],
			$data['comment'],
			$data['status'],
			$data['player_id']
		);
		$stmt->fetch();
		$stmt->close();
	}

    $eComment = array();
    $dComment = array();
    $var_name_vendedor ="N/D";

    $sql_comment = "SELECT * FROM tbl_comment WHERE orden_code = ?";
    $stmt_comment = $connect->stmt_init();
    if ($stmt_comment->prepare($sql_comment)) {
        $stmt_comment->bind_param('s', $data['code']);
        $stmt_comment->execute();
        $stmt_comment->store_result();
        $stmt_comment->bind_result(
            $dComment['id_coment'],
            $dComment['orden_code'],
            $dComment['orden_comment'],
            $dComment['date_coment'],
            $dComment['player_id']
        );
    }

    $qName_Vendedor = "SELECT * FROM tbl_admin WHERE username= '".$data["name"]."'";
    $rName_vendedor= mysqli_query($connect, $qName_Vendedor);
    $ttRowVendedor = mysqli_num_rows($rName_vendedor);

    if($ttRowVendedor >= 1) {
        $link = mysqli_fetch_array($rName_vendedor, MYSQLI_ASSOC);
        $var_name_vendedor = $link['Name'];
    }


?>

<?php

  $setting_qry    = "SELECT * FROM tbl_config where id = '1'";
  $setting_result = mysqli_query($connect, $setting_qry);
  $settings_row   = mysqli_fetch_assoc($setting_result);

  $onesignal_app_id = $settings_row['onesignal_app_id']; 
  $onesignal_rest_api_key = $settings_row['onesignal_rest_api_key'];
  $protocol_type = $settings_row['protocol_type'];

  define("ONESIGNAL_APP_ID", $onesignal_app_id);
  define("ONESIGNAL_REST_KEY", $onesignal_rest_api_key);

?>
<?php



if (isset($_POST['submit_coment'])) {


    $userId 	    = $data['player_id'];
    $buyerName      = $data['name'];
    $buyerCode      = $data['code'];
    $NameCliente    = $data['phone'];

    $content = array(
        "en" => "En el pedido de comomentario en : $NameCliente"
    );


    $data_noti_comentario = array(
        'app_id' => ONESIGNAL_APP_ID,
        'include_player_ids' => array($userId),
        'data' => array("foo" => "bar", "cat_id"=> "1010101010"),
        'headings'=> array("en" => "Nuevo Comentario"),
        'contents' => $content
    );


    $data_noti_comentario = json_encode($data_noti_comentario);

    print_r($data_noti_comentario);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
        'Authorization: Basic '.ONESIGNAL_REST_KEY));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_noti_comentario);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);




    $data = array(
        'orden_comment'	=> $_POST['orden_commint'],
        'date_coment'		=> date('Y-m-d h:i:s'),
        'orden_code'  			=> $_POST['ordencode'],
        'player_id'  			=> $_SESSION['user']
    );

    $qry = Insert('tbl_comment', $data);

    //$_SESSION['msg'] = "";
    $succes =<<<EOF
					<script>
					        alert('Comentario Agreagado...');
					        window.location = 'manage-order.php' ;
					</script>

EOF;
    echo $succes;
    exit;

}
?>
<?php 
	
	include_once('sql-query.php');

	$userId 	    = $data['player_id'];
	$buyerName      = $data['name'];
	$buyerCode      = $data['code'];
    $NameCliente    = $data['phone'];

	if (isset($_POST['submit_order'])) {

        $content = array(                                              
                         "en" => "La orden de: $NameCliente ha sido procesado."
                         );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'include_player_ids' => array($userId),
                        'data' => array("foo" => "bar", "cat_id"=> "1010101010"),
                        'headings'=> array("en" => "Procesado"),
                        'contents' => $content    
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $fecha = new DateTime();

		$data = array(
			'status'  => 1,
            'updated_at'  =>date('Y-m-d H:i:s')
		);	

		$hasil = Update('tbl_order', $data, "WHERE id = '".$_GET['id']."'");


		if ($hasil > 0) {
			//$_SESSION['msg'] = "";
		    $succes =<<<EOF
				<script>
				alert('Felicidades, esta orden ha sido procesada.');
				 window.location = 'manage-order.php' ;
				</script>
EOF;
			echo $succes;
			exit;
		}

	}


	if (isset($_POST['cancel_order'])) {

        $content = array(                                              
                         "en" => "Lo sentimos, su orden id : $buyerCode ha sido Cancelada."
                         );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'include_player_ids' => array($userId),
                        'data' => array("foo" => "bar", "cat_id"=> "1010101010"),
                        'headings'=> array("en" => "Cancelada!"),
                        'contents' => $content    
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);		

        $fecha = new DateTime();

        $data = array(
            'status'  => 2,
            'updated_at'  => date('Y-m-d H:i:s')
        );


        $hasil = Update('tbl_order', $data, "WHERE id = '".$_GET['id']."'");

		if ($hasil > 0) {
		    $succes =<<<EOF
				<script>
				alert('Esta orden ha sido cancelada.');
				window.history.go(-1);
				</script>
EOF;
			echo $succes;
			exit;
		}

	}

?>
<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">


        <!--breadcrum start-->
        <ol class="breadcrumb text-left">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="manage-order.php">Bandeja de Ordenes</a></li>
          <li class="active">Ordenes detalles</li>
        </ol><!--breadcrum end-->

        <div id="invoice">

    <div class="toolbar hidden-print">
        <div class="text-right">



            <?php 
            if($_SESSION['permisos'] == 3 || $_SESSION['permisos'] == 1){

                if ($data['status'] == '0') {
                    echo '
                    <form id="validationForm" method="post">
                        <input type="submit" name="cancel_order" class="btn btn-info" value="CANCELAR" onclick="cancelClicked(event)"/>
                        <input type="submit" name="submit_order" class="btn btn-info" value="PROCESAR" onclick="processClicked(event)" />
                    </form>';
                } else if ($data['status'] == '1') {
                    echo'
                    <form id="validationForm" method="post">
                        <input type="submit" name="cancel_order" class="btn btn-info" value="CANCELAR" onclick="cancelClicked(event)"/>
                    </form>';
                } else if ($data['status'] == '2') {
                    echo '<button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button><input type="submit" name="cancel_order" class="btn btn-default" value="CANCELAR" disabled/>';
                } 

            }
            
            ?>

        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        
                            <img src="assets/images/logo-umk.png" width="500px" height="100px"/>
                            
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            <?php echo $data['email'].$data['phone']; ?>
                        </h2>
                        <div><?php echo $data['address']; ?></div>
                        
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">                   
                    <div class="col invoice-details">
                        <h1 class="invoice-id"><?php echo $data['code']; ?></h1>
                        <div class="date">Realizado por <?php echo $data['name'] . " " . $var_name_vendedor; ?></div>
                        <div class="date">Fecha: <?php echo $data['date_time']; ?></div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="unit">ARTICULO</th>
                            <th class="text-left">DESCRIPCION</th>
                            <th class="unit">CANTIDAD</th>
                            <th class="text-center">BONIFICADO</th>
                            <th class="total text-right">VALOR</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                                $OrdenList  = $data['order_list'];
                                $Lineas     = explode("],", $OrdenList);
                                $cLineas    = count($Lineas) -1;
                                $set_img ="SinImagen.png";

                                for ($l=0; $l < $cLineas ; $l++){
                                    $Lineas_detalles     = explode(";", $Lineas[$l]);
                                   





                                    echo '<tr>
                                            <td class="unit">'.$Lineas_detalles[1].'</td>
                                            <td class="text-left">'.$Lineas_detalles[2].'</td>
                                            <td class="unit">'.str_replace('[', '', $Lineas_detalles[0]).'</td>
                                            <td class="qty">'.$Lineas_detalles[3].'</td>
                                            <td class="total text-right">'.$Lineas_detalles[4].'</td>
                                        </tr>';
                                    }
                            ?>
                       
                    </tbody>
                    <?php $Orden_Resumen     = explode(";", str_replace(array("[Orden :","Impuesto :","Total :","]"), "", $Lineas[$cLineas])); ?>

                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">SUBTOTAL</td>
                            <td><?php echo $Orden_Resumen[0]; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">IMPUESTO</td>
                            <td><?php echo $Orden_Resumen[1]; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2">TOTAL</td>
                            <td><?php echo $Orden_Resumen[2]; ?></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="thanks">Gracias!</div>
                <div class="notices">
                    <div>Comentario:</div>
                    <div class="notice">
                        <?php if ($data['comment'] != '')  echo $data['comment']; ?>
                    </div>
                </div>
            </main>
            <footer>
                Puede agregar algun comentario al pedido.
            </footer>

        <div class="section" >
            <div class="pmd-card pmd-z-depth">
                <div class="pmd-card-body">
                    <form id="validationForm" method="post">
                        <div style="display: none">
                        <input type="text" value="<?php echo $data['code']; ?>" name="ordencode">
                        <input type="text" value="<?php echo $_GET['id']; ?>" name="ordenid">
                        </div>
                    <div class="form-group pmd-textfield">                        
                        <textarea required class="form-control" name="orden_commint"></textarea>
                        <script>
                            CKEDITOR.replace( 'orden_commint' );
                        </script>
                        <br>
                        <p align="right">
                            <button type="submit" class="btn pmd-ripple-effect btn-danger" name="submit_coment">Guardar</button>
                        </p>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div>
            
        </div>
    </div>
    <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1><small class="pull-right"><?php echo $stmt_comment->num_rows;?> Comentarios</small> Comentarios </h1>
                </div>
                <div class="comments-list">
                    <?php
                    while ($stmt_comment->fetch()) { ?>
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="align-items-left" >
                                        <h4 class="small font-weight-bold"><?php echo $dComment['player_id'];?><span
                                                class="float-right"><?php echo $dComment['date_coment'];?></span></h4>
                                    <p><?php echo $dComment['orden_comment'];?></p>
                                </div>
                                <a class="float-right" href="order-detail.php?id=<?php echo $_GET['id'];?>&id_delete=<?php echo $dComment['id_coment'];?>">Borrar</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            </div>
        </div>

    </div><!-- tab end -->
    </div>

</script>



<?php
$stmt_comment->close();?>



<script>
	function processClicked(e) {
	    if(!confirm("El estado de la Orden no se puede cambiar después de procesado a menos que lo cancele."))e.preventDefault();
	}
</script>

<script>
	function cancelClicked(e) {
	    if(!confirm("¿Está seguro de que desea cancelar la Orden de <?php echo $data['name']; ?>"))e.preventDefault();
	}
</script>