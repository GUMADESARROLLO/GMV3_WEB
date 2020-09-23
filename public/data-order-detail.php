<?php 
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
			$data['order_list'],
			$data['order_total'],
			$data['comment'],
			$data['status'],
			$data['player_id']
		);
		$stmt->fetch();
		$stmt->close();
	}

	$order_list = explode(',', $data['order_list']);
			
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
                        'headings'=> array("en" => "Hola $buyerName,"),
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
 
		$data = array(
			'status'  => 1
		);	

		$hasil = Update('tbl_order', $data, "WHERE id = '".$_GET['id']."'");


		if ($hasil > 0) {
			//$_SESSION['msg'] = "";
		    $succes =<<<EOF
				<script>
				alert('Felicidades, esta orden ha sido procesada.');
				window.history.go(-1);
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
                        'headings'=> array("en" => "Hi $buyerName,"),
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
 
		$data = array(
			'status'  => 2
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

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li><a href="manage-order.php">Bandeja de Ordenes</a></li>
		  <li class="active">Ordenes detalles</li>
		</ol><!--breadcrum end-->
	
		<div class="section" >
			
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="lead">ORDEN DETALLES</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 right">
							<div align="right">
								
								<?php if ($data['status'] == '0') { ?> 
								<form id="validationForm" method="post">
									<input type="submit" name="cancel_order" class="btn pmd-ripple-effect btn-default" value="CANCELAR" onclick="cancelClicked(event)"/>

									<input type="submit" name="submit_order" class="btn pmd-ripple-effect btn-danger" value="PROCESAR" onclick="processClicked(event)" />
								</form>
								<?php } else if ($data['status'] == '1') { ?> 
								<form id="validationForm" method="post">
									<input type="submit" name="cancel_order" class="btn pmd-ripple-effect btn-default" value="CANCELAR" onclick="cancelClicked(event)"/>
								</form>
								<?php } else if ($data['status'] == '2') { ?>
									<input type="submit" name="cancel_order" class="btn pmd-ripple-effect btn-default" value="CANCELAR" disabled/>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="table-responsive"> 
						<table cellspacing="0" cellpadding="0" class="table pmd-table table-hover" id="table-propeller">
							<tbody>

								<tr>
									<td>RUTA</td>
									<td>:</td>
									<td><?php echo $data['name']; ?></td>
								</tr>

								<tr>
									<td width="15%">Code</td>
									<td width="1%">:</td>
									<td><?php echo $data['code']; ?></td>
								</tr>

								<tr>
									<td>Status</td>
									<td>:</td>
									<td>
										<?php if ($data['status'] == '1') { ?>
										<span class="badge badge-success">PROCESADO</span>
										<?php } else if ($data['status'] == '2') { ?>
										<span class="badge">CANCELADO</span>
										<?php } else { ?>
										<span class="badge badge-error">PENDIENTE</span>
										<?php } ?>										
									</td>
								</tr>

								<tr>
									<td>Codigo Cliente</td>
									<td>:</td>
									<td><?php echo $data['email']; ?></td>
								</tr>

								<tr>
									<td>Farmacia</td>
									<td>:</td>
									<td><?php echo $data['phone']; ?></td>
								</tr>

								<tr>
									<td>Direccion</td>
									<td>:</td>
									<td><?php echo $data['address']; ?></td>
								</tr>
								<tr>
									<td>Fecha</td>
									<td>:</td>
									<td><?php echo $data['date_time']; ?></td>
								</tr>
								
								<tr>
									<td>Orden</td>
									<td>:</td>
									<td><pre class="my-pre"><?php echo $data['order_list']; ?></pre></td>
								</tr>

								<?php if ($data['comment'] != '') { ?>
								<tr>
									<td>Comentario</td>
									<td>:</td>
									<td><?php echo $data['comment']; ?></td>
								</tr>
								<?php } ?>

							</tbody>
						</table>

					</div>
				</div>
			</div> <!-- section content end -->
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

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