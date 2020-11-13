<?php
    include 'functions.php';
    include 'sql-query.php';
    if (isset($_GET['id'])) {

        Delete('tbl_order','id='.$_GET['id'].'');

        header("location: manage-order.php");
        exit;

    }

?>

    <!--content area start-->

    <div id="content" class="pmd-content content-area dashboard">

        <!--tab start-->
        <div class="container-fluid full-width-container">

            <h1 class="section-title" id="services"></h1>


            <!-- Content Row -->
            <span class="float-right" id="xls_f1" style="display: none"><?php echo date('Y-m-d')?></span>
            <span class="float-right" id="xls_f2" style="display: none"><?php echo date('Y-m-d')?></span>
            <div class="section">
                <!-- Begin Page Content -->
                <div class="container-fluid">



                    <div class="row" >
                        <div class="col-md-12" >
                            <a id="exp-to-excel" href="#!" class="btn btn-light text-success float-right"><i class="fas fa-file-excel"></i> Excel</a>

                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-12 col-md-12 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-sm-6">
                                            <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2" id="Id_Buscar_Orden">
                                        </div>
                                        
                                        <div class="form-group col-sm-2">
                                            <select id="selct_estados" class="form-control">
                                                <option selected value="">Todo</option>
                                                <<option value="0">PENDIENTE</option>
                                                <option value="1">PROCESADO</option>
                                                <option value="2">CANCELADO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <select id="frm_lab_row" class="form-control">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="-1">*</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-2 ">
                                            <a href="#" id="dom-id" class="btn btn-info btn-icon-split float-right">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-calendar-day"></i>
                                            </span>
                                                <span class="text">Fecha</span>
                                            </a>

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
                               <h4 class="small font-weight-bold">Listado de Pedidos <span class="float-right"> <i class="fas fa-redo-alt fa-2x" style="margin-bottom: 10px" id="id_redo-alt"></i></span></h4>

                                <table class="table table-bordered" id="dtPedidos" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Ruta</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Status</th>
                                        <th></th>
                                        <th>Accion</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Ruta</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Status</th>
                                        <th></th>
                                        <th>Accion</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                PROCESADO</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="txt_procesado">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                PENDIENTE</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="txt_pendiente">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                CANCELADO</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="txt_cancelado">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            </div>

        </div><!-- tab end -->

    </div><!--end content area-->

