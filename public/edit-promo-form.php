<?php

    include('public/sql-query.php');

	if (isset($_GET['banner_id'])) {
 		$qry 	= "SELECT * FROM tbl_news WHERE banner_id ='".$_GET['banner_id']."'";
		$result = mysqli_query($connect, $qry);
		$row 	= mysqli_fetch_assoc($result);
 	}

	if(isset($_POST['submit'])) {

		if ($_FILES['product_image']['name'] != '') {
			unlink('upload/news/'.$_POST['old_image']);
			$product_image = time().'_'.$_FILES['product_image']['name'];
			$pic2 = $_FILES['product_image']['tmp_name'];
   			$tpath2 = 'upload/news/'.$product_image;
			copy($pic2, $tpath2);
		} else {
			$product_image = $_POST['old_image'];
		}

        $data = array(

            'banner_image' 		=> $product_image,
            'banner_sku' 		=> $_POST['product_sku'],
            'sku_name' 		    => $_POST['sku_name'],
            'banner_status'  		=> $_POST['promo_status'],
            'banner_description'	=> $_POST['product_description']


        );



        $hasil = Update('tbl_news', $data, "WHERE banner_id = '".$_POST['product_id']."'");

			if ($hasil > 0) {
			//$_SESSION['msg'] = "";
		        $succes =<<<EOF
					<script>					
					window.location = 'manage-promos.php';
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
		<h1></h1>
			
		<!--breadcrum start-->
		<ol class="breadcrumb text-left">
		  <li><a href="dashboard.php">Dashboard</a></li>
		  <li class="active">Editar Promoción</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">

                <input type="hidden" name="product_sku" id="id_product_sku" value="<?php echo $row['banner_sku'];?>">
                <input type="hidden" name="sku_name" id="id_sku_name" value="<?php echo $row['sku_name'];?>">

                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-12 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="input-group">
                                        <input type="text" value="<?php echo $row['sku_name'];?>" class="form-control bg-light border-0 small" placeholder="Seleccione algun articulo al cual vincular (Opcional)" disabled id="Id_Buscar_promo">
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
					<div class="group-fields clearfix row">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group pmd-textfield">
                                    <label>PUBLICADO *</label>
                                    <select class="select-simple form-control pmd-select2" name="promo_status">
                                        <option <?php if($row['banner_status'] == '1'){ echo 'selected';} ?> value="1">SI</option>
                                        <option <?php if($row['banner_status'] == '0'){echo 'selected';} ?> value="0">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="form-group pmd-textfield">
                                    <label for="regular1" class="control-label">Imagen ( jpg / png ) *</label>
                                    <input type="file" name="product_image" id="product_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/news/<?php echo $row['banner_image'];?>" data-show-remove="false" />
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="form-group pmd-textfield">
                                    <label class="control-label">Descripción *</label>
                                    <textarea required class="form-control" name="product_description"><?php echo $row['banner_description'];?></textarea>
                                    <script>
                                        CKEDITOR.replace( 'product_description' );
                                    </script>
                                </div>
                            </div>
                        </div>

						<input type="hidden" name="old_image" value="<?php echo $row['banner_image'];?>">
						<input type="hidden" name="product_id" value="<?php echo $row['banner_id'];?>">

						<div class="pmd-card-actions col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p align="right">
							<button type="submit" class="btn pmd-ripple-effect btn-danger" name="submit">ACTUALIZAR</button>
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
