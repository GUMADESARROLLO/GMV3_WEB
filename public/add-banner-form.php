<?php include_once('sql-query.php'); ?>

<?php

 	if (isset($_POST['submit'])) {

		$product_image = time().'_'.$_FILES['product_image']['name'];
		$pic2			 = $_FILES['product_image']['tmp_name'];
		$tpath2			 = 'upload/banners/'.$product_image;
		copy($pic2, $tpath2);

        $data = array(
			'banner_image' 		=> $product_image,
            'banner_status'  		=> $_POST['promo_status'],
            'banner_description'	=> $_POST['product_description']
		);		

 		$qry = Insert('tbl_banner', $data);
                      
  		//$_SESSION['msg'] = "";
		        $succes =<<<EOF
					<script>					
					window.location = 'manage-banner.php';
					</script>
EOF;
				echo $succes;
		exit;

 	}

	$sql_category = "SELECT * FROM tbl_category ORDER BY category_name ASC";
	$category_result = mysqli_query($connect, $sql_category);

	$sql_currency = "SELECT currency_code FROM tbl_config, tbl_currency WHERE tbl_config.currency_id = tbl_currency.currency_id";
	$result = mysqli_query($connect, $sql_currency);
	$row = mysqli_fetch_assoc($result);

?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

	<!--tab start-->
	<div class="container-fluid full-width-container">
		<h1></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Agreagr Banner</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="lead">AGREAGAR BANNER</div>
						</div>
					</div>

					<div class="group-fields clearfix row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label>PUBLICADO *</label>
                                <select class="select-simple form-control pmd-select2" name="promo_status">
                                    <option value="1">SI</option>
                                    <option value="0">NO</option>
                                </select>
                            </div>
                        </div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="regular1" class="control-label">ImageN ( jpg / png ) *</label>
								<input type="file" name="product_image" id="product_image" id="product_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" required />
							</div>
						</div>						

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label class="control-label">Descripción *</label>
  								<textarea required class="form-control" name="product_description"></textarea>
  								<script>                             
									CKEDITOR.replace( 'product_description' );
								</script>	
							</div>
						</div>

						<div class="pmd-card-actions col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p align="right">
							<button type="submit" class="btn pmd-ripple-effect btn-danger" name="submit">GUARDAR</button>
							</p>
						</div>						

						</div>

				</div>

			</div> <!-- section content end -->  
			</form>
		</div>
			
	</div><!-- tab end -->

</div><!--end content area