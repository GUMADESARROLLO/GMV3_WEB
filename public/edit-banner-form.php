<?php

    include('public/sql-query.php');

	if (isset($_GET['banner_id'])) {
 		$qry 	= "SELECT * FROM tbl_banner WHERE banner_id ='".$_GET['banner_id']."'";
		$result = mysqli_query($connect, $qry);
		$row 	= mysqli_fetch_assoc($result);
 	}

	if(isset($_POST['submit'])) {

		if ($_FILES['product_image']['name'] != '') {
			unlink('upload/banners/'.$_POST['old_image']);
			$product_image = time().'_'.$_FILES['product_image']['name'];
			$pic2 = $_FILES['product_image']['tmp_name'];
   			$tpath2 = 'upload/banners/'.$product_image;
			copy($pic2, $tpath2);
		} else {
			$product_image = $_POST['old_image'];
		}

        $data = array(

            'banner_image' 		=> $product_image,
            'banner_status'  		=> $_POST['promo_status'],
            'banner_description'	=> $_POST['product_description']


        );

        $hasil = Update('tbl_banner', $data, "WHERE banner_id = '".$_POST['product_id']."'");

			if ($hasil > 0) {
			//$_SESSION['msg'] = "";
		        $succes =<<<EOF
					<script>					
					window.location = 'manage-banner.php';
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
		  <li class="active">Editar banner</li>
		</ol>
		<!--breadcrum end-->
	
		<div class="section"> 

			<form id="validationForm" method="post" enctype="multipart/form-data">
			<div class="pmd-card pmd-z-depth">
				<div class="pmd-card-body">

					<div class="group-fields clearfix row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="lead">EDITAR BANNER</div>
						</div>
					</div>

					<div class="group-fields clearfix row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group pmd-textfield">
                                <label>PUBLICADO *</label>
                                <select class="select-simple form-control pmd-select2" name="promo_status">
                                    <option <?php if($row['banner_status'] == '1'){ echo 'selected';} ?> value="1">SI</option>
                                    <option <?php if($row['banner_status'] == '0'){echo 'selected';} ?> value="0">NO</option>
                                </select>
                            </div>
                        </div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="form-group pmd-textfield">
								<label for="regular1" class="control-label">Imagen ( jpg / png ) *</label>
								<input type="file" name="product_image" id="product_image" id="product_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/banners/<?php echo $row['banner_image'];?>" data-show-remove="false" />
							</div>
						</div>						

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group pmd-textfield">
								<label class="control-label">Descripcion *</label>
  								<textarea required class="form-control" name="product_description"><?php echo $row['banner_description'];?></textarea>
  								<script>                             
									CKEDITOR.replace( 'product_description' );
								</script>	
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

</div><!--end content area