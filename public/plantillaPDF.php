<?php

$data = json_decode(file_get_contents('http://192.168.1.15:8448/gmv3/api/api.php?get_recent'), true);

$html = '<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>Lista de productos</title>
      <link rel="stylesheet" href="style.css" media="all" />
    </head>
    <body>
      <header class="clearfix">
        <div id="logo">
          <img src="../assets/images/logo-umk.png">
        </div>
        <h1>LISTA DE PRODUCTOS</h1>
        <div id="company" class="clearfix">
        </div>
        <div id="project">
            <div>UNIMARK S.A </div>
            <div><span class="font-weight-bold">DIRECCIÓN: </span>Semaforos Club Terraza, 150 mts. Oeste, Pista Jean Paul Genie, Managua</div>
            <div><span class="font-weight-bold">TELEFONO: </span> (505)  2278 8787</div>
            <div><span class="font-weight-bold">FECHA: </span>' . date('Y-m-d H:i:s') . '</div>
        </div>
      </header>
      <main>
        <table>
          <thead>
            <tr>
              <th class="service">PRODUCTO</th>
              <th class="desc">LABORATORIO</th>
              <th class="desc">IMAGEN</th>
              <th>PRECIO</th>
              <th>CANTIDAD</th>
              <th>TOTAL</th>
            </tr>
          </thead>
          <tbody>';
foreach ($data as $product) {
    $html .= '<tr>
                            <td class="service">' . $product['product_name'] . '</td>
                            <td class="desc">' . $product['LAB'] . '</td>
                            <td class="unit "> <div width="50px" height="50px"> <img src="../upload/product/'. $product['product_image'] . '" alt="" width="50px" height="50px"> </div></td>
                            <td class="unit">' . number_format($product['product_price'], 2) . '</td>
                            <td class="qty">' . number_format($product['product_quantity'], 2) . '</td>
                            <td class="total">' . number_format(($product['product_quantity'] * $product['product_price']), 2) . '</td>
                         </tr>';
}
$html .= '
          </tbody>
        </table>
      </main>
      <footer>
      Copyright © 2021. TODOS LOS DERECHOS RESERVADOS - UNIMARK S.A;
      </footer>
    </body>
</html>';
//require_once('vendor/autoload.php');
require_once('../mpdf/mpdf.php');

$mpdf = new mPDF('c', 'A4');
$css = file_get_contents('../assets/reporte/css/style.css');
$mpdf->WriteHTML($css, 1);

$mpdf->WriteHTML($html);
$mpdf->Output('reporte.pdf', 'I');
