<?php

include_once ('../includes/config.php');
include_once ('../includes/Sqlsrv.php');
$connect->set_charset('utf8');

$sql_query      = "SELECT * FROM tbl_admin ORDER BY id DESC LIMIT 1";
$user_result    = mysqli_query($connect, $sql_query);
$user_row       = mysqli_fetch_assoc($user_result);
$admin_email    = $user_row['email'];

if (isset($_GET['category_id'])) {
    $query = "SELECT p.product_id, p.product_name, p.category_id, n.category_name, p.product_price, p.product_status, p.product_image, p.product_description, p.product_quantity, c.currency_id, c.tax, o.currency_code, o.currency_name FROM tbl_category n, tbl_product p, tbl_config c, tbl_currency o WHERE c.currency_id = o.currency_id AND c.id = 1 AND n.category_id = p.category_id AND n.category_id ='".$_GET['category_id']."' ORDER BY p.product_id DESC";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set[] = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));

} else if (isset($_GET['get_recent'])) {

    /*$query = "SELECT p.product_id, p.product_name,p.product_sku, p.category_id, n.category_name, p.product_price, p.product_status, p.product_image, p.product_description, p.product_quantity, c.currency_id, c.tax, o.currency_code, o.currency_name FROM tbl_category n, tbl_product p, tbl_config c, tbl_currency o WHERE n.category_id = p.category_id AND c.currency_id = o.currency_id AND c.id = 1 ORDER BY p.product_id DESC";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){


        $set[] = $link;

      }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));*/

    /* $sqlsrv = new Sqlsrv();
     $conn = $sqlsrv->OPen_database_odbcSAp();

     $filtro = 0;

     $query = 'SELECT * from SBO_INNOVA_INDUSTRIAS.GMV_ARTICULOS WHERE "EXISTENCIA" > '.$filtro.'';
     $resultado =  @odbc_exec($conn,$query);
     $rtnCliente=array();
     $i=0;

     while ($key = @odbc_fetch_array($resultado)){

         $rtnCliente[$i]['product_id']               = utf8_encode($key['ARTICULO']);
         $rtnCliente[$i]['product_name']             = utf8_encode($key['NOMBRE']);
         $rtnCliente[$i]['category_id']              = "20";
         $rtnCliente[$i]['category_name']            = "Papel";
         $rtnCliente[$i]['product_price']            = number_format($key['NLP1'],2,'.','');
         $rtnCliente[$i]['product_status']           = "Available";
         $rtnCliente[$i]['product_image']            = "pBlanc.jpg";
         $rtnCliente[$i]['product_description']      = "";
         $rtnCliente[$i]['product_quantity']         = number_format($key['EXISTENCIA'],0,'.','');
         $rtnCliente[$i]['currency_id']              = "105";
         $rtnCliente[$i]['tax']                      = "15";
         $rtnCliente[$i]['currency_code']            = "NIO";
         $rtnCliente[$i]['currency_name']            = "Nicaraguan cordoba oro";
         $i++;
     }
     header('Content-Type: application/json; charset=utf-8');
     echo $val = str_replace('\\/', '/', json_encode($rtnCliente));*/

    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE EXISTENCIA > 1", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $set_img ="ND.jpg";
        $set_des = "";

        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$fila["ARTICULO"]."'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];
            $set_des = $link['product_description'];
        }


        $json[$i]['product_id']               = $fila["ARTICULO"];
        $json[$i]['product_name']             = utf8_encode(strtoupper($fila['DESCRIPCION']));
        $json[$i]['category_id']              = "20";
        $json[$i]['category_name']            = "Medicina";
        $json[$i]['product_price']            = number_format($fila['PRECIO_IVA'],2,'.','');
        $json[$i]['product_status']           = "Available";
        $json[$i]['product_image']            = $set_img;
        $json[$i]['product_description']      = $set_des;
        $json[$i]['product_quantity']         = str_replace(',', '', number_format($fila['EXISTENCIA'],2));
        $json[$i]['currency_id']              = "105";
        $json[$i]['tax']                      = "0";
        $json[$i]['currency_code']            = "NIO";
        $json[$i]['currency_name']            = "Nicaraguan cordoba oro";
        $json[$i]['product_bonificado']       = $fila["REGLAS"];
        $json[$i]['product_lotes']            = trim($fila["LOTES"]);
        $json[$i]['product_und']              = $fila["UNIDAD_MEDIDA"];
        $i++;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
    $sqlsrv->close();

} else if (isset($_GET['get_category'])) {
    $query = "SELECT DISTINCT c.category_id, c.category_name, c.category_image, COUNT(DISTINCT p.product_id) as product_count FROM tbl_category c LEFT JOIN tbl_product p ON c.category_id = p.category_id GROUP BY c.category_id ORDER BY c.category_id DESC";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set[] = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));

} else if (isset($_GET['get_tax_currency'])) {
    $query = "SELECT c.tax, o.currency_code FROM tbl_config c, tbl_currency o WHERE c.currency_id = o.currency_id AND c.id = 1";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));

} else if (isset($_GET['post_order'])) {

    $code        = $_POST['code'];
    $name        = $_POST['name'];
    $email       = $_POST['email'];
    $phone       = $_POST['phone'];
    $address     = $_POST['address'];
    $shipping    = $_POST['shipping'];
    $order_list  = $_POST['order_list'];
    $order_total = $_POST['order_total'];
    $comment     = $_POST['comment'];
    $player_id   = $_POST['player_id'];
    $date        = $_POST['date'];
    $server_url  = $_POST['server_url'];

    $query = "INSERT INTO tbl_order (code,name, email, phone,created_at, address, shipping, order_list, order_total, comment, player_id) VALUES ('$code','$name', '$email', '$phone','$date', '$address', '$shipping', '$order_list', '$order_total', '$comment', '$player_id')";

    if (mysqli_query($connect, $query)) {
        //include_once ('php-mail.php');
        echo 'Data Inserted Successfully';
    } else {
        echo 'Try Again';
    }
    mysqli_close($connect);

} else if (isset($_GET['txt_bonificado.setText(product_bonificado);'])) {

    $query = "SELECT * FROM tbl_shipping ORDER BY shipping_id ASC";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set['result'][] = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));

} else if (isset($_GET['get_help'])) {

    $query = "SELECT * FROM tbl_help ORDER BY id DESC";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set[] = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));

} else if (isset($_GET['product_id'])) {


    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 and ARTICULO='".$_GET['product_id']."'", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $set_img ="ND.jpg";
        $set_des = "";

        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$fila["ARTICULO"]."'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];
            $set_des = $link['product_description'];
        }


        $json['product_id']               = $fila["ARTICULO"];
        $json['product_name']             = utf8_encode($fila['DESCRIPCION']);
        $json['category_id']              = "20";
        $json['category_name']            = "Medicina";
        $json['product_price']            = number_format($fila['PRECIO_IVA'],2,'.','');
        $json['product_status']           = "Available";
        $json['product_image']            = $set_img;
        $json['product_description']      = $set_des;
        $json['product_quantity']         = number_format($fila['EXISTENCIA'],0,'.','');
        $json['currency_id']              = "105";
        $json['tax']                      = "0";
        $json['currency_code']            = "NIO";
        $json['currency_name']            = "Nicaraguan cordoba oro";
        $json['product_bonificado']            = $fila["REGLAS"];
        $i++;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
    $sqlsrv->close();

} else if (isset($_GET['clients_id'])) {



    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;

    $query = $sqlsrv->fetchArray("SELECT * FROM Softland.dbo.ANA_MTClientes_UMK WHERE VENDEDOR='".$_GET['clients_id']."' AND ACTIVO ='S'", SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {
            $dta[$i]['CLIENTE']     = $key['CLIENTE'];
            $dta[$i]['NOMBRE']      = $key['NOMBRE'];
            $dta[$i]['DIRECCION']   = $key['DIRECCION'];
            $dta[$i]['DIPONIBLE']   = number_format($key['LIMITE_CREDITO'] - $key['SALDO'],2);
            $dta[$i]['LIMITE']      = number_format($key['LIMITE_CREDITO'],2);
            $dta[$i]['SALDO']       = number_format($key['SALDO'],2);
            $dta[$i]['MOROSO']      = $key['MOROSO'];
            $dta[$i]['TELE']        = "Tels. ".$key['TELEFONO1'].' / '.$key['TELEFONO2'];
            $dta[$i]['CONDPA']      = "Cond. Pago: ".$key['CONDICION_PAGO'].' Dias';
            $i++;
        }

    }

    //  echo false;
    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));


} else if (isset($_GET['post_usuario'])) {

    $myString = $_GET['post_usuario'];

    $myString = substr ($myString, 0, strlen($myString) - 1);

    $myString = $str = substr($myString, 1);

    $porciones = explode("@", $myString);


    // change username to lowercase
    $username = strtolower($porciones[0]);

    $KeysSecret = "A7M";

    //encript password to sha256
    $password = hash('sha256',$KeysSecret.$porciones[1]);

    // get data from user table
    $sql_query = "SELECT username,Activo,Name,Telefono,email FROM tbl_admin WHERE username = ? AND password = ?";
    $stmt = $connect->stmt_init();
    if($stmt->prepare($sql_query)) {
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();

        $stmt->bind_result($vUserName,$vActivo,$vName,$vTelefono,$vEmail);
        $stmt->store_result();

        $num = $stmt->num_rows;

        if($num == 1) {

            while ($stmt->fetch()) {
                if($vActivo=="S"){
                    $set['result'][] = array(
                        'name' => strtoupper($vUserName),
                        'FullName' => strtoupper($vName),
                        'Tele' => strtoupper($vTelefono),
                        'Correo' => strtoupper($vEmail),
                        'success' => '1');
                }else{
                    $set['result'][] = array('msg' => 'Account disabled', 'success' => '2');
                }
            }
        }else{
            $set['result'][] = array('msg' => 'Login failed', 'success' => '0');
        }
    }
    $stmt->close();
    header( 'Content-Type: application/json; charset=utf-8' );
    $json = json_encode($set);
    echo $json;

}else if (isset($_GET['get_perfil_user'])) {
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_PERFILES_CLIENTE WHERE CLIENTE='".$_GET['get_perfil_user']."' ", SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {
            $dta[$i]['NoVencidos']  = number_format($key['NoVencidos'],2);
            $dta[$i]['Dias30']      = number_format($key['Dias30'],2);
            $dta[$i]['Dias60']      = number_format($key['Dias60'],2);
            $dta[$i]['Dias90']      = number_format($key['Dias90'],2);
            $dta[$i]['Dias120']     = number_format($key['Dias120'],2);
            $dta[$i]['Mas120']      = number_format($key['Mas120'],2);
            $dta[$i]['FACT_PEND']   = $key['FACT_PEND'];
            $i++;
        }

    }else{
        $dta[$i]['NoVencidos']  = number_format(0.00,2);
        $dta[$i]['Dias30']      = number_format(0.00,2);
        $dta[$i]['Dias60']      = number_format(0.00,2);
        $dta[$i]['Dias90']      = number_format(0.00,2);
        $dta[$i]['Dias120']     = number_format(0.00,2);
        $dta[$i]['Mas120']      = number_format(0.00,2);
        $dta[$i]['FACT_PEND']   = "-:0.00:00-00-0000:0.00";
    }

    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['get_detalle_factura'])){
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_FACTURA_DETALLE_HISTORICO WHERE FACTURA='".$_GET['get_detalle_factura']."' ", SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {
            $dta[$i]['ARTICULO']        = $key['ARTICULO'];
            $dta[$i]['DESCRIPCION']     = $key['DESCRIPCION'];
            $dta[$i]['CANTIDAD']        = number_format($key['CANTIDAD'],2);
            $dta[$i]['VENTA']           = $key['VENTA'];
            $i++;
        }

    }else{
        $dta[$i]['ARTICULO']        = "N/D";
        $dta[$i]['DESCRIPCION']     = "N/D";
        $dta[$i]['CANTIDAD']        = number_format(0.00,2);
        $dta[$i]['VENTA']           = number_format(0.00,2);
    }

    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['last_3m'])){
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;

    $query = $sqlsrv->fetchArray("SELECT * FROM GMV3_hstCompra_3M WHERE Cliente='".$_GET['last_3m']."' ORDER BY Dia", SQLSRV_FETCH_ASSOC);

    if (count($query)>0) {
        foreach ($query as $key) {
            $dta[$i]['ARTICULO']        = $key['ARTICULO'];
            $dta[$i]['DESCRIPCION']     = $key['DESCRIPCION'];
            $dta[$i]['CANTIDAD']        = number_format($key['CANTIDAD'],2);
            $dta[$i]['VENTA']           = $key['Venta'];
            $dta[$i]['FECHA']           = $key['Dia'];
            $i++;
        }

    }else{
        $dta[$i]['ARTICULO']        = "N/D";
        $dta[$i]['DESCRIPCION']     = "N/D";
        $dta[$i]['CANTIDAD']        = number_format(0.00,2);
        $dta[$i]['VENTA']           = 0.00;
        $dta[$i]['FECHA']           = "00-00-0000";
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['get_nc'])){
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;

    $query = $sqlsrv->fetchArray("SELECT T0.CLIENTE,T0.DOCUMENTO,T0.FECHA,T0.SALDO_LOCAL,T0.APLICACION,T0.VENDEDOR FROM Softland.dbo.APK_CxC_DocVenxCL T0  WHERE T0.CLIENTE='".$_GET['get_nc']."' and T0.TIPO='N/C'", SQLSRV_FETCH_ASSOC);

    if (count($query)>0) {
        foreach ($query as $key) {
            $dta[$i]['DOCUMENTO']        = $key['DOCUMENTO'];
            $dta[$i]['FECHA']     = $key['FECHA']->format('d-m-Y');
            $dta[$i]['SALDO_LOCAL']        = str_replace(",", "", number_format($key['SALDO_LOCAL'],2));
            $dta[$i]['APLICACION']           = $key['APLICACION'];
            $dta[$i]['VENDEDOR']           = $key['VENDEDOR'];
            $i++;
        }

    }else{
        $dta[$i]['DOCUMENTO']        = "N/D";
        $dta[$i]['FECHA']     = "N/D";
        $dta[$i]['SALDO_LOCAL']        = str_replace(",", "",number_format(0.00,2));
        $dta[$i]['APLICACION']           = 0.00;
        $dta[$i]['VENDEDOR']           = "00-00-0000";
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['get_stat_ruta'])){

    $sqlsrv = new Sqlsrv();
    $dta = array(); $i = 0;




    $anio = $_GET['sAnno'];
    $mes  = $_GET['sMes'];
    $Ruta = $_GET['get_stat_ruta'];
    $fecha       = date('Y-m-d',strtotime(str_replace('/', '-',($anio.'-'.$mes.'-01'))));

    $qPeriodo = $sqlsrv->fetchArray("SELECT IdPeriodo FROM DESARROLLO.dbo.metacuota_GumaNet WHERE Fecha='".$fecha."' AND IdCompany='1' ", SQLSRV_FETCH_ASSOC);

    $q_meta_unidades= $sqlsrv->fetchArray("SELECT Sum(Meta) as Meta FROM DESARROLLO.dbo.gn_cuota_x_productos WHERE IdPeriodo='".$qPeriodo[0]['IdPeriodo']."' AND CodVendedor='".$Ruta."' ", SQLSRV_FETCH_ASSOC);

    $q_meta_valor = $sqlsrv->fetchArray("SELECT Sum(val) as val FROM DESARROLLO.dbo.gn_cuota_x_productos WHERE IdPeriodo='".$qPeriodo[0]['IdPeriodo']."' AND CodVendedor='".$Ruta."' ", SQLSRV_FETCH_ASSOC);

    $sql_exec = "EXEC Ventas_Rutas ".$mes.", ".$anio;
    $qVenta = $sqlsrv->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

    $found_key = array_search($Ruta, array_column($qVenta, 'Ruta'));



    $Meta_Monto     = $qVenta[$found_key]['Monto'];
    $Meta_Cantidad  = $qVenta[$found_key]['Cantidad'];


    $dta[$i]['mVentaReal']       = str_replace(",", "",number_format($Meta_Monto,2));
    $dta[$i]['mMetaVenta']      = str_replace(",", "",number_format($q_meta_valor[0]['val'],2));
    $dta[0]['mVentaDif']        = ($Meta_Monto==0) ? "100.00" : number_format(((floatval($Meta_Monto)/floatval($q_meta_valor[0]['val']))*100),2);


    $dta[$i]['mVntCanti']        = str_replace(",", "",number_format($q_meta_unidades[0]['Meta'],2));
    $dta[$i]['mVntCantiReal']    = str_replace(",", "",number_format($Meta_Cantidad,2));
    $dta[0]['mVntCantiDif']     = ($q_meta_unidades[0]['Meta']==0) ? "100.00" : number_format(((floatval($Meta_Cantidad)/floatval($q_meta_unidades[0]['Meta']))*100),2);




    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));





}else if (isset($_GET['get_stat_articulo'])){


    $url = 'http://186.1.15.164:8448/gnet_api_movil/index.php/estadistica_articulos_ruta';
    $data = array(
        'Ruta' => $_GET['get_stat_articulo'],
        'Empresa' => 'UNIMARK',
        'Mes' => $_GET['sMes'],
        'Annio' => $_GET['sAnno'],
        'Filtro' => "",
        'Grafica' => ""
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result_V2 = file_get_contents($url, false, $context);
    if ($result_V2 === FALSE) {  }

    $arr_2 = json_decode($result_V2, true);

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($arr_2));
}else if (isset($_GET['post_rpt_ruta'])){
    $ruta        = $_GET['post_rpt_ruta'];
    $desde       = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $_GET['desde'])));
    $hasta       = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $_GET['hasta'])));


    $Q="SELECT T0.FACTURA,T0.Dia,T0.[Nombre del cliente] AS Cliente,sum(T0.Venta) as Venta FROM Softland.dbo.VtasTotal_UMK T0  WHERE T0.Ruta='".$ruta."' AND  T0.Dia BETWEEN '".$desde."' and '".$hasta."' GROUP BY  T0.FACTURA,T0.Dia,T0.[Nombre del cliente]";





    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray($Q, SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {
            $dta[$i]['FACTURA']    = $key['FACTURA'];
            $dta[$i]['FECHA']      = $key['Dia']->format('d/m/Y');
            $dta[$i]['CLIENTE']    = $key['Cliente'];
            $dta[$i]['MONTO']      = str_replace(",", "",number_format($key['Venta'],2));;


            $i++;
        }

    }else{
        $dta[$i]['FACTURA']   = "0000000";
        $dta[$i]['FACTURA']   = "N*D";
        $dta[$i]['FECHA']     = "00/00/0000";
        $dta[$i]['MONTO']     = number_format(0.00,2);


    }

    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));


}else if (isset($_GET['get_comentarios'])){
    $query = "SELECT * FROM tbl_comment WHERE orden_code= '".$_GET['get_comentarios']."' ";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set[] = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));
}else{
    header('Content-Type: application/json; charset=utf-8');
    echo "no method found!";
}

?>