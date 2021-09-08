<?php include 'functions.php'; ?>

<?php

	$query = "SELECT currency_code FROM tbl_config, tbl_currency WHERE tbl_config.currency_id = tbl_currency.currency_id";
	$result = mysqli_query($connect, $query);
	$row = mysqli_fetch_assoc($result);

?>

	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		
			if ($result_permission['permisos']==2) {
			
				if (empty($keyword)) {
					$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin WHERE permisos IN (2,3)";
				} else {
					$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin WHERE permisos IN (2,3) AND Name LIKE ? ORDER BY id DESC";
				}
			}else{
				if (empty($keyword)) {
					$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos  FROM tbl_admin";
				} else {
					$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin Where Name LIKE ? ORDER BY id DESC";
				}
			}

		
		
		$stmt = $connect->stmt_init();
		if ($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if (!empty($keyword)) {
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();

			
			$stmt->bind_result( 
					$data['id'],
					$data['Name'],
					$data['Telefono'],
	                $data['username'],
					$data['email'],
					$data['Activo'],
					$data['permisos']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page) {
			$from 	= ($page * $offset) - $offset;
		} else {
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	


		if ($result_permission['permisos']==2) {
		
			if (empty($keyword)) {
				$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin WHERE permisos IN (2,3) ORDER BY id DESC LIMIT ?, ?";
			} else {
				$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin WHERE permisos IN(2,3) AND Name LIKE ? ORDER BY id DESC LIMIT ?, ?";
			}

		}else{

			if (empty($keyword)) {
				$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin ORDER BY id DESC LIMIT ?, ?";
			} else {
				$sql_query = "SELECT id, Name, Telefono, username, email, Activo, permisos FROM tbl_admin WHERE Name LIKE ? ORDER BY id DESC LIMIT ?, ?";
			}

		}

		
		$stmt_paging = $connect->stmt_init();
		if ($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if (empty($keyword)) {
				$stmt_paging ->bind_param('ss', $from, $offset);
			} else {
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result(
				$data['id'],
				$data['Name'],
				$data['Telefono'],
                $data['username'],
				$data['email'],
				$data['Activo'],
				$data['permisos']
			);

			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if ($total_records_paging == 0) {
	
	?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Visualizar Usuarios</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">Administrar Usuarios</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<input type="text" name="keyword" class="form-control" placeholder="Buscar...">
							</div>
						</div>
					</div>

					<div class="table-responsive"> 
						<table cellspacing="0" cellpadding="0" class="table pmd-table table-hover" id="table-propeller">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Teléfono</th>
									<th>Usuario</th>
									<th>Email</th>
									<th>Permiso</th>
									<th>Estado</th>
									<th width="15%">Opciones</th>
								</tr>
							</thead>

						</table>
						<p align="center">ops!, No se encontraron registros!</p>

					</div>
				</div>
			</div> <!-- section content end -->  
			<?php $function->doPages($offset, 'manage-user.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } else { $row_number = $from + 1; ?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
	
		<h1 class="section-title" id="services"></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Visualizar Usuarios</li>
		</ol><!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="get">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<div class="lead">Administrar Usuarios</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
							<div class="form-group pmd-textfield">
								<input type="text" name="keyword" class="form-control" placeholder="Buscar...">
							</div>
						</div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php if(isset($_SESSION['msg'])) { ?>
                                <div role="alert" class="alert alert-warning alert-dismissible">
                                    <?php echo $_SESSION['msg']; ?>
                                </div>
                            <?php unset($_SESSION['msg']); }?>
                        </div>

					</div>

					<div class="table-responsive"> 
						<table cellspacing="0" cellpadding="0" class="table pmd-table table-hover" id="table-propeller">
							<thead>
								<tr>
									<th>Nombre </th>
									<th>Teléfono</th>
									<th>Usuario</th>
									<th>Email</th>
									<th>Permiso</th>
									<th>Estado</th>
									<th width="15%">Opciones</th>
								</tr>
							</thead>

							<?php 
								while ($stmt_paging->fetch()) { ?>

							<tbody>
								<tr>
                                    <td><?php echo $data['Name'];?></td>
									<td><?php echo $data['Telefono'];?></td>
									<td><?php echo $data['username'];?></td>
									<td><?php echo $data['email'];?></td>
									<td><?php 
										if ($data['permisos'] == 1) {
											echo 'Administrador';
										}else if ($data['permisos'] == 2){
											echo 'Supp SAC';
										}else if ($data['permisos'] == 3){
											echo 'SAC';
                                        }else if ($data['permisos'] == 4){
											echo 'Digitador';
										}else{
                                            echo 'Mercadeo';
                                        }
									?>
									</td>
									<td>
										<?php if ($data['Activo'] == 'S') { ?>
										<span class="badge badge-success">ACTIVO</span>
										<?php } else { ?>
										<span class="badge badge-error">INACTIVO</span>
										<?php } ?>
									</td>
									<td>
									    <a href="edit-user.php?id=<?php echo $data['id'];?>">
									        <i class="material-icons">mode_edit</i>
									    </a>


									   <?php
									   //usuario no puede eliminar su propio rgistro
									    if ($result_permission['id'] != $data['id']) { ?>
									                        
										    <a href="delete-user.php?id=<?php echo $data['id'];?>" onclick="return confirm('Esta seguro que desea eliminar este Usuario?')" >
										            <i class="material-icons">delete</i>
										    </a>

									     <?php }?>
									</td>									
								</tr>
							</tbody>

							<?php } ?>

						</table>

					</div>
				</div>
			</div> <!-- section content end -->  
			<?php $function->doPages($offset, 'manage-user.php', '', $total_records, $keyword); ?>
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area-->

<?php } ?>