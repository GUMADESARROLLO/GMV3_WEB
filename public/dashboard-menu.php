<?php
    include 'sql-query.php';
    $Stat = explode(";", getStat());
?>

<!--content area start-->
<div id="content" class="pmd-content content-area dashboard">

    <!--tab start-->
    <div class="container-fluid full-width-container">

        <!-- Title -->
        <h1 class="section-title" id="services">
        </h1><!-- End Title -->

        <!--breadcrum start-->
        <ol class="breadcrumb text-left">
            <li class="active">Dashboard</li>
        </ol><!--breadcrum end-->


        <!-- Begin Page Content -->
        <div class="container-fluid">



            <!-- Content Row -->
            <div class="row">

                <!-- FACTURADO (Monthly) -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        FACTURADO (ESTA SEMANA)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">C$ <?php echo number_format(($Stat[1]),2,'.',',');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        FACTURADO (MES)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">C$ <?php echo number_format((is_null($Stat[0])) ? 0 : $Stat[0] ,2,'.',',');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pedidos en el mes
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($Stat[2],0,'.',',');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pedidos pendientes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($Stat[3],0,'.',',');?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-6 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Comportamiento de ventas</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-6 col-lg-5">
                    <!-- Bar Chart -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Comportamiento de Ruta</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="myBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->




    </div>



    </div>
