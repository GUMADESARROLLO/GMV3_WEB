<?php

    include('public/sql-query.php');

	if (isset($_GET['id'])) {
 		$qry 	= "SELECT * FROM tbl_Admin WHERE id ='".$_GET['id']."'";
		$result = mysqli_query($connect, $qry);
		$row 	= mysqli_fetch_assoc($result);
 	}

	if(isset($_POST['submitEditUser'])) {


		// if ($_FILES['product_image']['name'] != '') {
		// 	unlink('upload/product/'.$_POST['old_image']);
		// 	$product_image = time().'_'.$_FILES['product_image']['name'];
		// 	$pic2 = $_FILES['product_image']['tmp_name'];
  //  			$tpath2 = 'upload/product/'.$product_image;
		// 	copy($pic2, $tpath2);
		// } else {
		// 	$product_image = $_POST['old_image'];
		// }
		$KeysSecret = "A7M";
 
		$data = array(
			'id'  		  		=> intval($_POST['user_id']),
            'Name'  		    => $_POST['user_fullname'],
			'Telefono'  		=> $_POST['user_telefono'],
			'username'  		=> strtolower($_POST['user_nickname']),
			'password'  		=> hash('sha256',$KeysSecret.$_POST['user_password']),
			'email'  			=> $_POST['email'],
            'Activo'			=> $_POST['user_activo'],
            'permisos'			=> intval($_POST['user_permisos'])
		);	

		$hasil = Update('tbl_admin', $data, "WHERE id = ".$_POST['user_id']."");


			if ($hasil > 0) {
			//$_SESSION['msg'] = "";
		        $succes =<<<EOF
					<script>
					alert('Usuario actualizado exitosamente...');
					window.location = 'manage-user.php';
					</script>
EOF;
				echo $succes;
			exit;
			}

	}

 // 	$sql_query = "SELECT * FROM tbl_category ORDER BY category_name ASC";
	// $ringtone_qry_cat = mysqli_query($connect, $sql_query);

	// $sql_currency = "SELECT currency_code FROM tbl_config, tbl_currency WHERE tbl_config.currency_id = tbl_currency.currency_id";
	// $sql_result = mysqli_query($connect, $sql_currency);
	// $row_currency = mysqli_fetch_assoc($sql_result);

?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
		<h1></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php"> Dashboard</a></li>
		  <li class="active">Editar Usuario</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="lead">EDITAR USUARIO</div>
						</div>
					</div>

					<div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label for="user_fullname" class="control-label">Nombre *</label>
                                <input type="text" name="user_fullname" id="user_fullname" class="form-control" placeholder="SKU" value="<?php echo $row['Name'];?>" required>
                            </div>
                        </div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="user_telefono" class="control-label">Teléfono *</label>
								<input type="text" name="user_telefono" id="user_telefono" class="form-control" placeholder="Product Name" value="<?php echo $row['Telefono'];?>" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="user_nickname" class="control-label">Nombre de Usuario *</label>
								<input type="text" name="user_nickname" id="user_nickname" class="form-control" placeholder="Product Name" value="<?php echo $row['username'];?>" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="user_password" class="control-label">Contraseña *</label>
								<input type="text" name="user_password" id="user_password" class="form-control" placeholder="Ingrese nueva contraseña" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="email" class="control-label">Email *</label>
								<input type="text" name="email" id="email" class="form-control" placeholder="Product Name" value="<?php echo $row['email'];?>" required>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Permiso *</label>
								<select class="select-simple form-control pmd-select2" name="user_activo">
									<option <?php if($row['Activo'] == 'S'){ echo 'selected';} ?> value="S">Activo</option>
									<option  <?php if($row['Activo'] == 'N'){ echo 'selected';} ?> value="N">Inactivo</option>
								</select>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">       
								<label>Estado *</label>
								<select class="select-simple form-control pmd-select2" name="user_permisos">
									<?php if ($result_permission['permisos'] == 2) {?>
										<option <?php if($row['permisos'] == 2){ echo 'selected';} ?> value="2">Supp SAC</option>
										<option <?php if($row['permisos'] == 3){ echo 'selected';} ?> value="3">SAC</option>
									<?php  }else{?>
										<option <?php if($row['permisos'] == 1){ echo 'selected';} ?> value="1">Administrador</option>
										<option <?php if($row['permisos'] == 2){ echo 'selected';} ?> value="2">Supp SAC</option>
										<option <?php if($row['permisos'] == 3){ echo 'selected';} ?> value="3">SAC</option>
										<option <?php if($row['permisos'] == 4){ echo 'selected';} ?> value="4">Digitador</option>
									<?php }?>
								</select>
							</div>
						</div>

						<input type="hidden" name="user_id" value="<?php echo $row['id'];?>">

						<div class="pmd-card-actions col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p align="right">
							<button type="submit" class="btn pmd-ripple-effect btn-danger" name="submitEditUser">ACTUALIZAR</button>
							</p>
						</div>						

						</div>

				</div>

			</div> <!-- section content end -->  
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area