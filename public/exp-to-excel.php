<?php
session_start();
define( 'ABSPATH', dirname( __FILE__ ) . '/' );
include_once ('../includes/config.php');
include(ABSPATH.'libraries\PHPExcel\Classes\PHPExcel.php');


$F1        = $_GET['D1'].' 00:00:00';
$F2        = $_GET['D2'].' 23:59:00';;


$retVal = ($_SESSION['permisos'] == '3') ? "WHERE name in (".$_SESSION['grupos'].") AND date_time between '".$F1."' and  '".$F2."'" : "WHERE date_time between '".$F1."' and  '".$F2."'" ;
$sql_query = "SELECT * FROM tbl_order ".$retVal." ORDER BY id DESC ";

$f1 = date('Y-m-d',strtotime($F1));
$f2 = date('Y-m-d',strtotime($F2));

$resouter = mysqli_query($connect, $sql_query);

$set = array();
$i=0;
$Str ="";
$total_records = mysqli_num_rows($resouter);
if($total_records >= 1) {

    $objPHPExcel = new PHPExcel();

    $tituloReporte = "Reporte de GMV3 | PENDIDOS";
    $titulosColumnas = array('Nº','FECHA','RUTA','CLIENTE','MONTO','ESTADO');

    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:F1');


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',$tituloReporte)
        ->setCellValue('A3',  $titulosColumnas[0])
        ->setCellValue('B3',  $titulosColumnas[1])
        ->setCellValue('C3',  $titulosColumnas[2])
        ->setCellValue('D3',  $titulosColumnas[3])
        ->setCellValue('E3',  $titulosColumnas[4])
        ->setCellValue('F3',  $titulosColumnas[5]);
    $i=4;
    while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){

        if ($link['status'] == '1') {
            $Str = 'PROCESADO';
        } else if ($link    ['status'] == '2') {
            $Str = 'CANCELADO';
        } else {
            $Str = 'PENDIENTE';
        }





        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $link['code'])
            ->setCellValue('B'.$i, date('d-m-Y h:i:s', strtotime($link['date_time'])))
            ->setCellValue('C'.$i,  $link['name'])
            ->setCellValue('D'.$i,  $link['email'].$link['phone'])
            ->setCellValue('E'.$i,  $link['order_total'])
            ->setCellValue('F'.$i,  $Str);
        $i++;
    }

    $estiloTituloReporte = array(
        'font' => array(
            'name'      => 'Verdana',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size' =>18,
            'color'     => array(
                'rgb' => '212121'
            )
        ),
        'alignment' =>  array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'rotation'   => 0,
            'wrap'       => TRUE,
        )
    );

    $estiloTituloColumnas = array(
        'font' => array(
            'name'      => 'Arial',
            'bold'      => true
        ),
        'alignment' =>  array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'wrap'          => TRUE
        ));

    $estiloInformacion = new PHPExcel_Style();
    $estiloInformacion->applyFromArray(
        array(
            'font' => array(
                'name'      => 'Arial',
                'size' => 11
            )
        ));
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);

    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:E".($i-1));

    $objPHPExcel->getActiveSheet()->setTitle('Reporte CUALI | PUBLISA');

    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->getActiveSheet(0)->freezePane('A4');
    $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte de '.$f1.' Hasta '.$f2.'.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');



}
else{
    print_r('No hay resultados para mostrar');
}


?>