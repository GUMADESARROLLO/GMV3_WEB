<?php
include 'functions.php';
include 'sql-query.php';
?>

<!--content area start-->

<div id="content" class="pmd-content content-area dashboard">

    <!--tab start-->
    <div class="container-fluid full-width-container">

        <h1 class="section-title" id="services"></h1>


        <!-- Content Row -->


        <div class="section">
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <div class="row" >
                    <div class="col-md-12" >
                        <a  href="add-promo.php" class="btn btn-light text-success float-right"><i class="fas fa-save"></i> AGREGAR</a>

                    </div>
                </div>
                <br>


                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-12 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2" id="Id_Buscar_promo">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>

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
                            <table class="table table-bordered" id="dtPromos" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Activo</th>
                                    <th>Articulo</th>
                                    <th>Image</th>
                                    <th>Descripcion</th>
                                    <th width="10%">Action</th>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Activo</th>
                                    <th>Articulo</th>
                                    <th>Image</th>
                                    <th>Descripcion</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </tfoot>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
    </div>

</div><!-- tab end -->

</div><!--end content area-->

