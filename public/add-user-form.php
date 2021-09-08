<?php include_once('sql-query.php'); ?>

<?php

 	if (isset($_POST['submit'])) {

		// $product_image = time().'_'.$_FILES['product_image']['name'];
		// $pic2			 = $_FILES['product_image']['tmp_name'];
		// $tpath2			 = 'upload/product/'.$product_image;
		// copy($pic2, $tpath2);
		$KeysSecret = "A7M";

        $data = array(
            'username'  		    => strtolower($_POST['Usuario']),
			'name'  				=> $_POST['Nombre'],
			'Telefono'  			=> $_POST['Telefono'],
			'password'  			=> hash('sha256',$KeysSecret.$_POST['Contrasenna']),
			'email' 				=> $_POST['Email'],
            //'full_name'				=> $_POST['Nombre_Completo'],
            //'user_role'				=> $_POST['Role'],
            'Activo'  				=>'S',
            'permisos'	  			=> intval($_POST['Permisos'])
		);		

 		$qry = Insert('tbl_admin', $data);									
                      
  		//$_SESSION['msg'] = "";
		        $succes =<<<EOF
					<script>
					alert('Nuevo usuario agregado sastifactoriamente...');
					window.location = 'manage-user.php';
					</script>
EOF;
				echo $succes;
		exit;

 	}

	// $sql_category = "SELECT * FROM tbl_category ORDER BY category_name ASC";
	// $category_result = mysqli_query($connect, $sql_category);

	// $sql_currency = "SELECT currency_code FROM tbl_config, tbl_currency WHERE tbl_config.currency_id = tbl_currency.currency_id";
	// $result = mysqli_query($connect, $sql_currency);
	// $row = mysqli_fetch_assoc($result);

?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
		<h1></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Usuarios</a></li>
		  <li class="active">Agregar Nuevo</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="lead">AGEGAR USUARIO</div>
						</div>
					</div>

					<div class="group-fields clearfix row">

                        
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="Usuario" class="control-label">Nombre de usuario *</label>
								<input type="text" name="Usuario" id="Usuario" class="form-control" placeholder="Nombre de usuario" required>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="Nombre" class="control-label">Nombre Completo *</label>
								<input type="text" name="Nombre" id="Nombre" class="form-control" placeholder="Nombre completo" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="Telefono" class="control-label">Teléfono *</label>
								<input type="text" name="Telefono" id="Telefono" class="form-control" placeholder="Teléfono" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="Contrasenna" class="control-label">Contraseña *</label>
								<input type="text" name="Contrasenna" id="Contrasenna" class="form-control" placeholder="Contraseña" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="Email" class="control-label">Email</label>
								<input type="text" name="Email" id="Email" class="form-control" placeholder="Email">
							</div>
						</div>
<!-- 
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Role *</label>
								<select class="select-simple form-control pmd-select2" name="Role">
									<option value="100">Administrador</option>
									<option value="101">SAC</option>
									<option value="101">Digitador</option>
								</select>
							</div>
						</div> -->

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Permiso *</label>
								<select class="select-simple form-control pmd-select2" name="Permisos">
								<?php if ($result_permission['permisos'] == 2) {?>
						 			<option value="2">SuppSAC</option>
									<option value="3">SAC</option>
								<?php } else{?>
									<option value="1">Administrador</option>
									<option value="2">SuppSAC</option>
									<option value="3">SAC</option>
									<option value="4">Digitador</option>
                                    <option value="5">Mercadeo</option>
								<?php }?>
									
									
								</select>
							</div>
						</div>

						<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Activo *</label>
								<select class="select-simple form-control pmd-select2" name="Activo">
									<option value="S">Si</option>
									<option value="N">No</option>
								</select>
							</div>
						</div> -->

						<div class="pmd-card-actions col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p align="right">
							<button type="submit" class="btn pmd-ripple-effect btn-danger" name="submit">Submit</button>
							</p>
						</div>						

						</div>

				</div>

			</div> <!-- section content end -->  
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area