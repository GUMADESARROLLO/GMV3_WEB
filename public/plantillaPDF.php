<?php

$array = json_decode(file_get_contents('http://186.1.15.166:8448/gmv3/api/api.php?get_recent'), true);

$json = array();
$i = 0;
$img = '';
//$laboratorio = $_REQUEST['filtro'];
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : "TODOS";

foreach ($array as $data) {
  if (trim($filtro) === trim($data['LAB'])) {
    $json[$i]['product_name']             = $data['product_name'];
    $json[$i]['product_status']           = $data['product_status'];
    $json[$i]['product_image']            = $data['product_image'];
    $json[$i]['product_description']      = $data['product_description'];
    $json[$i]['product_quantity']         = $data['product_quantity'];
    $json[$i]['product_und']              = $data['product_und'];
    $i++;
  } else if (trim($filtro) === "TODOS") {
    if ($i <= 100) {
      $json[$i]['product_name']             = $data['product_name'];
      $json[$i]['product_status']           = $data['product_status'];
      $json[$i]['product_image']            = $data['product_image'];
      $json[$i]['product_description']      = $data['product_description'];
      $json[$i]['product_quantity']         = $data['product_quantity'];
      $json[$i]['product_und']              = $data['product_und'];
      $i++;
    }
  }
}

$html = '<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>Lista de productos</title>
      <link rel="stylesheet" href="style.css" media="all" />
    </head>
    <body>';
$header = '<header>
             <div class="title-content">
               <h2 class="title text-right" id="title-prod">CATÁLOGO DE PRODUCTOS</h2>
             </div>
          </header>';

$html .= '<main>
          <table>';
foreach ($json as $product) {
  $html .= '
                    <tr class="Style" >
                        <td><img class="" src="http://186.1.15.166:8448/gmv3/upload/product/' . $product['product_image'] . '" height="160px"   width="160px" alt="Card image cap" ></td>
                        <td class ="table-style"  width="600px">
                        <h6 class="">' . $product['product_name'] . '</h6>
                                          ' . $product['product_description'] . '
                        </td>
                    </tr>
        ';  
}
$html .= '</table>
   </main>
  </body>
</html>';


$footer = '<footer>
            <div class="text-right">
            <img class="text-left" src="../assets/reporte/img/polygonal19.jpg"  width="277px";>
            <img class="" src="../assets/images/logo-umk-small.png" style="margin-right:15px;">
            </div>
</footer>';
//require_once('vendor/autoload.php');
require_once('../mpdf/mpdf.php');

$mpdf = new mPDF('c', 'A4-L');
$css = file_get_contents('../assets/reporte/css/style.css');
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($html);
$mpdf->Output('reporte.pdf', 'I');
