<?php include_once('sql-query.php'); ?>

<?php

 	if (isset($_POST['submit'])) {

		$product_image = time().'_'.$_FILES['product_image']['name'];
		$pic2			 = $_FILES['product_image']['tmp_name'];
		$tpath2			 = 'upload/news/'.$product_image;
		copy($pic2, $tpath2);

        $data = array(
			'banner_image' 		    => $product_image,
            'banner_sku' 		    => $_POST['product_sku'],
            'sku_name' 		        => $_POST['sku_name'],
            'banner_status'  		=> $_POST['promo_status'],
            'banner_description'	=> $_POST['product_description'],
            'created_at'            => date('Y-m-d h:i:s')
		);		

 		$qry = Insert('tbl_news', $data);
                      
  		//$_SESSION['msg'] = "";
		        $succes =<<<EOF
					<script>					
					window.location = 'manage-promos.php';
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
		  <li class="active">agregar Promo</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_sku" id="id_product_sku">
                <input type="hidden" name="sku_name" id="id_sku_name">

                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-12 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Seleccione algun articulo al cual vincular (Opcional)" disabled id="Id_Buscar_promo">
                                        <button class="btn btn-primary" type="button" id="id_accion_add_articulo_promo">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
			<div class="pmd-card pmd-z-depth">


				<div class="pmd-card-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group pmd-textfield">
                            <label>PUBLICADO *</label>
                            <select class="select-simple form-control pmd-select2" name="promo_status">
                                <option value="1">SI</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="form-group pmd-textfield">
                                <label for="regular1" class="control-label">Imagen ( jpg / png ) *</label>
                                <input type="file" name="product_image" id="product_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" required />
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="form-group pmd-textfield">
                                <label class="control-label">Descripción *</label>
                                <textarea required class="form-control" name="product_description"></textarea>
                                <script>
                                    CKEDITOR.replace( 'product_description' );
                                </script>
                            </div>
                        </div>
                    </div>



					<div class="group-fields clearfix row">
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
    <div class="modal fade" id="ModalPromos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-height-modal">
            <div class="modal-content">
                <div class="container-fluid">


                    <br>

                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-12 col-md-12 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-sm-11">
                                            <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2" id="srch_modal_articulo_promo">
                                        </div>


                                        <div class="form-group col-sm-1">
                                            <select id="selc_modal_articulo_promo" class="form-control">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="-1">*</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tbl_modal_articulo_promo" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>ARTICULO</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>ARTICULO</th>

                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


            </div>
        </div>


    </div>



</div>

</div><!--end content area

