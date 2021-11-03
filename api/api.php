<?php

include_once ('../includes/config.php');
include_once ('../includes/config_comentario.php');
include_once ('../includes/Sqlsrv.php');
include_once ('../public/sql-query.php');



$connect->set_charset('utf8');

@$connect_comentario->set_charset('utf8');

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

    $sqlsrv = new Sqlsrv();

    $i = 0;
    $json = array();

    $PRE_VENTA = false;

    if($PRE_VENTA){
        //INGRESO DE ARTICULOS EN PRE-VENTA
        $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE ARTICULO IN ('15016023','19231011','15012011','15012021') ORDER BY CALIFICATIVO,DESCRIPCION ASC", SQLSRV_FETCH_ASSOC);
        
        foreach ($query as $fila) {
            $set_img ="SinImagen.png";
            $set_des = "";

            $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$fila["ARTICULO"]."'";
            $resouter = mysqli_query($connect, $query);
            $total_records = mysqli_num_rows($resouter);
            if($total_records >= 1) {
                $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
                $set_img = $link['product_image'];
                $set_des = $link['product_description'];
            }

            $qPromo = "SELECT * FROM tbl_news WHERE banner_sku = '".$fila["ARTICULO"]."'";
            $rsPromo = mysqli_query($connect, $qPromo);
            $total_records_promo = mysqli_num_rows($rsPromo);

            $isPromo = ($total_records_promo >= 1) ? "S" : "N" ;

            $json[$i]['product_id']               = $fila["ARTICULO"];
            $json[$i]['product_name']             = strtoupper($fila['DESCRIPCION']);
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
            $json[$i]['CALIFICATIVO']             = $fila["CALIFICATIVO"];
            $json[$i]['ISPROMO']                  = $isPromo ;
            $i++;
        }

    }

    


    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 OR ARTICULO LIKE 'VU%' ORDER BY CALIFICATIVO,DESCRIPCION ASC", SQLSRV_FETCH_ASSOC);

    
    

    foreach ($query as $fila) {
        $set_img ="SinImagen.png";
        $set_des = "";

        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$fila["ARTICULO"]."'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];
            $set_des = $link['product_description'];
        }


        $qPromo = "SELECT * FROM tbl_news WHERE banner_sku = '".$fila["ARTICULO"]."'";
        $rsPromo = mysqli_query($connect, $qPromo);
        $total_records_promo = mysqli_num_rows($rsPromo);

        $isPromo = ($total_records_promo >= 1) ? "S" : "N" ;

        $Precio_Articulo = (strpos($fila["ARTICULO"], "VU") !== false) ? 1 : $fila['PRECIO_IVA'] ;
        $Existe_Articulo = (strpos($fila["ARTICULO"], "VU") !== false) ? 1 : $fila['EXISTENCIA'] ;
        if (strpos($fila["ARTICULO"], "VU") !== false) {
            $set_des ='
            <!DOCTYPE html>
                <html>
                <head>
                    <style type="text/css">
                    .alert-box {
                        color:#555;
                        border-radius:10px;
                        font-family:Tahoma,Geneva,Arial,sans-serif;font-size:11px;
                        padding:10px 36px;
                        margin:10px;
                    }
                    .alert-box span {
                        font-weight:bold;
                        text-transform:uppercase;
                    }
                    .error {
                        border:3px solid #f5aca6;
                    }
                    </style>
                </head>
                <body>
                    <div class="alert-box error"><span>Importante: </span>Los Valores de precio y Existencia son informativos.</div>
                </body>
            </html>';
        }




        $json[$i]['product_id']               = $fila["ARTICULO"];
        $json[$i]['product_name']             = strtoupper($fila['DESCRIPCION']);
        $json[$i]['category_id']              = "20";
        $json[$i]['category_name']            = "Medicina";
        $json[$i]['product_price']            = number_format($Precio_Articulo,2,'.','');
        $json[$i]['product_status']           = "Available";
        $json[$i]['product_image']            = $set_img;
        $json[$i]['product_description']      = $set_des;
        $json[$i]['product_quantity']         = str_replace(',', '', number_format($Existe_Articulo,2));
        $json[$i]['currency_id']              = "105";
        $json[$i]['tax']                      = "0";
        $json[$i]['currency_code']            = "NIO";
        $json[$i]['currency_name']            = "Nicaraguan cordoba oro";
        $json[$i]['product_bonificado']       = $fila["REGLAS"];
        $json[$i]['product_lotes']            = trim($fila["LOTES"]);
        $json[$i]['product_und']              = $fila["UNIDAD_MEDIDA"];
        $json[$i]['CALIFICATIVO']             = $fila["CALIFICATIVO"];
        $json[$i]['ISPROMO']                  = $isPromo ;
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
        $set_img ="SinImagen.png";
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

    $query = $sqlsrv->fetchArray("SELECT T0.*,ISNULL(T1.DISPONIBLE, 0) AS SALDO_VINETA  FROM Softland.dbo.ANA_MTClientes_UMK T0 LEFT JOIN PRODUCCION.dbo.view_master_cliente_vineta T1 ON T0.CLIENTE = T1.CLIENTE WHERE VENDEDOR='".$_GET['clients_id']."' AND ACTIVO ='S' ORDER BY NOMBRE", SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {


            $query = "SELECT * FROM tlb_verificacion WHERE Cliente = '".$key['CLIENTE']."'";
            $resouter = mysqli_query($connect, $query);
            $total_records = mysqli_num_rows($resouter);
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);

            $Verificaco = ($total_records == 0) ? "N;0.00;0.00" : "S;".$link['Lati'].";".$link['Longi'] ;

            $qPin = "SELECT * FROM tlb_pins WHERE Cliente = '".$key['CLIENTE']."'";
            $rPin = mysqli_query($connect, $qPin);
            $Pin_num_rows = mysqli_num_rows($rPin);

            $isPin = ($Pin_num_rows == 0) ? "N" : "S";

            $retVal = ($key['MOROSO'] == 'S') ? $key['NOMBRE']." [MOROSO]" : $key['NOMBRE'] ;

            $dta[$i]['CLIENTE']     = $key['CLIENTE'];
            $dta[$i]['NOMBRE']      = str_replace ( "'", '', $retVal);
            $dta[$i]['DIRECCION']   = $key['DIRECCION'];
            $dta[$i]['DIPONIBLE']   = number_format($key['LIMITE_CREDITO'] - $key['SALDO'],2);
            $dta[$i]['LIMITE']      = number_format($key['LIMITE_CREDITO'],2);
            $dta[$i]['SALDO']       = number_format($key['SALDO'],2);
            $dta[$i]['MOROSO']      = $key['MOROSO'];
            $dta[$i]['TELE']        = "Tels. ".$key['TELEFONO1'].' / '.$key['TELEFONO2'];
            $dta[$i]['CONDPA']      = "Cond. Pago: ".$key['CONDICION_PAGO'].' Dias';
            $dta[$i]['VERIFICADO']  = $Verificaco;
            $dta[$i]['PIN']         = $isPin;
            $dta[$i]['vineta']       = number_format($key['SALDO_VINETA'],2);
            $i++;
        }
        //echo json_encode($dta);
        usort($dta, 'object_sorter');
        echo json_encode($dta);

    }



    $sqlsrv->close();



    //header('Content-Type: application/json; charset=utf-8');
   // echo $val = str_replace('\\/', '/', json_encode($dta));


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

    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['get_detalle_factura'])){
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_FACTURA_DETALLE_HISTORICO WHERE FACTURA='".$_GET['get_detalle_factura']."' ", SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {


        $set_img ="SinImagen.png";
        $set_des = "";

        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$key["ARTICULO"]."'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];            
        }


            $dta[$i]['ARTICULO']        = $key['ARTICULO'];
            $dta[$i]['OBSERVACIONES']        = $key['OBSERVACIONES'];
            $dta[$i]['DESCRIPCION']     = strtoupper($key['DESCRIPCION']);;
            $dta[$i]['CANTIDAD']        = number_format($key['CANTIDAD'],2);
            $dta[$i]['IMAGEN']          = $set_img;
            $dta[$i]['VENTA']           = $key['VENTA'];
            $i++;
        }

    }else{
        $dta[$i]['ARTICULO']        = "N/D";
        $dta[$i]['DESCRIPCION']     = "N/D";
        $dta[$i]['OBSERVACIONES']        = "";
         $dta[$i]['IMAGEN']          = "SinImagen.png";
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
    foreach ($query as $key) {

        $set_img ="SinImagen.png";
        $set_des = "";

        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$key["ARTICULO"]."'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];            
        }



        $dta[$i]['ARTICULO']        = $key['ARTICULO'];
        $dta[$i]['DESCRIPCION']     = strtoupper($key['DESCRIPCION']);
        $dta[$i]['CANTIDAD']        = number_format($key['CANTIDAD'],2);
        $dta[$i]['VENTA']           = $key['Venta'];
        $dta[$i]['IMAGEN']          = $set_img;
        $dta[$i]['FECHA']           = $key['Dia'];
        $i++;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['get_nc'])){
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;

    $query = $sqlsrv->fetchArray("SELECT T0.CLIENTE,T0.DOCUMENTO,T0.FECHA,T0.SALDO_LOCAL,T0.APLICACION,T0.VENDEDOR FROM Softland.dbo.APK_CxC_DocVenxCL T0  WHERE T0.CLIENTE='".$_GET['get_nc']."' and T0.TIPO='N/C'", SQLSRV_FETCH_ASSOC);

    foreach ($query as $key) {
        $dta[$i]['DOCUMENTO']        = $key['DOCUMENTO'];
        $dta[$i]['FECHA']     = $key['FECHA']->format('d-m-Y');
        $dta[$i]['SALDO_LOCAL']        = str_replace(",", "", number_format($key['SALDO_LOCAL'],2));
        $dta[$i]['APLICACION']           = $key['APLICACION'];
        $dta[$i]['VENDEDOR']           = $key['VENDEDOR'];
        $i++;
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


    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_PERFILES_RUTA WHERE VENDEDOR='".$_GET['get_stat_articulo']."' ", SQLSRV_FETCH_ASSOC);
    foreach ($query as $key) {
        $dta[$i]['NoVencidos']  = number_format($key['NoVencidos'],2);
        $dta[$i]['Vencidos']    = number_format($key['Vencidos'],2);
        $dta[$i]['Dias30']      = number_format($key['Dias30'],2);
        $dta[$i]['Dias60']      = number_format($key['Dias60'],2);
        $dta[$i]['Dias90']      = number_format($key['Dias90'],2);
        $dta[$i]['Dias120']     = number_format($key['Dias120'],2);
        $dta[$i]['Mas120']      = number_format($key['Mas120'],2);
        $dta[$i]['FACT_PEND']   = $key['FACT_PEND'];
        $i++;
    }

    /*$dta['RECUPERA'][0]['META'] ="02";
    $dta['RECUPERA'][0]['CREDITO'] ="02";
    $dta['RECUPERA'][0]['CONTADO'] ="02";
    $dta['RECUPERA'][0]['TOTAL'] ="02";
    $dta['RECUPERA'][0]['CUMPL'] ="02";*/

    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['post_rpt_ruta'])){
    $ruta        = $_GET['post_rpt_ruta'];
    $desde       = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $_GET['desde'])));
    $hasta       = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $_GET['hasta'])));


    $Q="SELECT T0.FACTURA,T0.Dia,T0.[Nombre del cliente] AS Cliente,sum(T0.Venta) as Venta FROM Softland.dbo.VtasTotal_UMK T0  WHERE T0.Ruta='".$ruta."' AND  T0.Dia BETWEEN '".$desde."' and '".$hasta."' GROUP BY  T0.FACTURA,T0.Dia,T0.[Nombre del cliente]";





    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray($Q, SQLSRV_FETCH_ASSOC);
    foreach ($query as $key) {
        $dta[$i]['FACTURA']    = $key['FACTURA'];
        $dta[$i]['FECHA']      = $key['Dia']->format('d/m/Y');
        $dta[$i]['CLIENTE']    = $key['Cliente'];
        $dta[$i]['MONTO']      = str_replace(",", "",number_format($key['Venta'],2));
        $i++;
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
}else if(isset($_GET['get_comentarios_im'])){

    $Usuario = $_GET['get_comentarios_im'];
    $OrderBy = $_GET['OrderBy'];
    $i=0;
    $array = array();

    $query = "SELECT * FROM tbl_comentarios WHERE Autor = '".$Usuario."' ORDER BY FECHA $OrderBy";
    $resouter = mysqli_query($connect_comentario, $query);

    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1)
    {
        foreach ($resouter as $key)
        {
            $array[$i]['Titulo']    = $key['Titulo'];
            $array[$i]['Contenido'] = $key['Contenido'];
            $array[$i]['Fecha']     = $key['Fecha'];
            $array[$i]['Autor']     = $key['Autor'];
            $array[$i]['Imagen']    = $key['Imagen'];
            $i++;
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($array));


} else if (isset($_GET['post_report'])) {


    $Fecha          = $_POST['sndFecha'];
    $Nombre         = $_POST['sndTitulo'];
    $CodRuta        = $_POST['sndCodigo'];
    $NamRuta        = $_POST['sndNombre'];
    $Comentario     = $_POST['snd_comentario'];
    $imagektp       = $_POST['snd_image'];


    if($imagektp !=""){
        $nama_imagen = time() . '-' . rand(0, 99999) . ".jpg";
        $pathktp = "../upload/news/" . $nama_imagen;
        file_put_contents($pathktp, base64_decode($imagektp));    
    }


    $query = "INSERT INTO tbl_comentarios (Titulo,Contenido, Autor, Nombre,Fecha,Imagen) VALUES ('$Nombre','$Comentario', '$CodRuta', '$NamRuta','$Fecha','$nama_imagen')";

    if (mysqli_query($connect_comentario, $query)) {
        //include_once ('php-mail.php');
        echo 'Data Inserted Successfully';
    } else {
        echo 'Try Again';
    }
    mysqli_close($connect);





}else if (isset($_GET['articulos_sin_facturar'])) {
    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos T1 WHERE  T1.ARTICULO NOT IN (SELECT T0.ARTICULO  FROM GMV3_hstCompra_3M T0 WHERE T0.Cliente='".$_GET['articulos_sin_facturar']."' ) AND EXISTENCIA > 1 ORDER BY CALIFICATIVO ASC", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $set_img ="SinImagen.png";
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
        $json[$i]['product_name']             = strtoupper($fila['DESCRIPCION']);
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
        $json[$i]['CALIFICATIVO']              = $fila["CALIFICATIVO"];
        $i++;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
}else if (isset($_GET['post_update_datos'])) {

	include('../public/sql-query.php');

    $KeysSecret 	= "A7M";
    $table_name 	= 'tbl_admin';
    $where_clause	= "WHERE username = '".$_POST['Ruta']."'";
	$whereSQL 		= '';

    $form_data = array(
        'email'  		  	=> $_POST['Email'],
        'Telefono'  		=> $_POST['Telefono'],
        'password'  		=> hash('sha256',$KeysSecret.$_POST['Contrasenna'])
    );


        if(!empty($where_clause)) {
            if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                $whereSQL = " WHERE ".$where_clause;
            } else {
                $whereSQL = " ".trim($where_clause);
            }
        }
        $sql = "UPDATE ".$table_name." SET ";
        $sets = array();
        foreach($form_data as $column => $value) {
             $sets[] = "`".$column."` = '".$value."'";
        }
        $sql .= implode(', ', $sets);
        $sql .= $whereSQL;

        $hasil = mysqli_query($connect, $sql);




   // $hasil = Update('tbl_admin', $data, "WHERE username = ".$_POST['Ruta']."");


    if ($hasil > 0) {
        //include_once ('php-mail.php');
        echo 'Data Inserted Successfully';
    } else {
        echo 'Try Again';
    }
}else if (isset($_GET['post_verificacion'])) {
    $Lati        = $_POST['Lati'];
    $Logi        = $_POST['Logi'];
    $cliente     = $_POST['cliente'];
    $date        = $_POST['date'];


    $query = "SELECT * FROM tlb_verificacion WHERE Cliente = '".$cliente."'";
    $resouter = mysqli_query($connect, $query);
    $total_records = mysqli_num_rows($resouter);

    if($total_records >= 1){




        $table_name 	= 'tlb_verificacion';
        $where_clause	= "WHERE Cliente = '".$cliente."'";
        $whereSQL 		= '';

        $form_data = array(
            'Lati'  		=> $Lati,
            'Longi'  		=> $Logi,
            'updated_at'  		=> $date
        );
        if(!empty($where_clause)) {
            if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                $whereSQL = " WHERE ".$where_clause;
            } else {
                $whereSQL = " ".trim($where_clause);
            }
        }
        $sql = "UPDATE ".$table_name." SET ";
        $sets = array();
        foreach($form_data as $column => $value) {
            $sets[] = "`".$column."` = '".$value."'";
        }
        $sql .= implode(', ', $sets);
        $sql .= $whereSQL;


        $hasil = mysqli_query($connect, $sql);

        if ($hasil > 0) {
            echo 'Data Inserted Successfully';
        } else {
            echo 'Try Again';
        }



    }else{
     $query = "INSERT INTO tlb_verificacion (Cliente,Lati,Longi,created_at) VALUES ('$cliente','$Lati', '$Logi', '$date')";
     if (mysqli_query($connect, $query)) {
         echo 'Data Inserted Successfully';
     } else {
         echo 'Try Again';
     }
    }

    mysqli_close($connect);
}else if (isset($_GET['get_banner'])) {

    $query = "SELECT banner_id,banner_image,banner_description FROM tbl_banner where banner_status > 0  order by banner_id DESC";
    $resouter = mysqli_query($connect, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);


    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set[] = $link;
        }
    }else{
        $set[0]['banner_id']    = "0";
        $set[0]['banner_image']         = "SinImagen.png";
        $set[0]['banner_description']      = "";

    }


    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));
}else if (isset($_GET['get_news'])) {
    $query = "SELECT banner_id,banner_image,banner_description,created_at FROM tbl_news where banner_status > 0 order by banner_id DESC";
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
}else if (isset($_GET['push_pin'])) {

    $cliente     = $_POST['cliente'];
    $date        = date('Y-m-d h:i:s');


    $query = "SELECT * FROM tlb_pins WHERE Cliente = '".$cliente."'";
    $resouter = mysqli_query($connect, $query);
    $total_records = mysqli_num_rows($resouter);

    if($total_records >= 1){
        $qDelete = "DELETE FROM tlb_pins WHERE Cliente = '".$cliente."'";
        if (mysqli_query($connect, $qDelete)) {
            echo 'Defijado';
        } else {
            echo 'Try Again';
        }

    }else{
        $query = "INSERT INTO tlb_pins (Cliente,created_at) VALUES ('$cliente','$date')";
        if (mysqli_query($connect, $query)) {
            echo 'Fijado';
        } else {
            echo 'Try Again';
        }
    }

    mysqli_close($connect);
}else if (isset($_GET['stat_recup'])) {
    $sqlsrv = new Sqlsrv();
    $dta = array(); $i = 0;

    $anio = $_GET['sAnno'];
    $mes  = $_GET['sMes'];
    $Ruta = $_GET['stat_recup'];
    $fecha       = date('Y-m-d',strtotime(str_replace('/', '-',($anio.'-'.$mes.'-01'))));

    /*$anio = 2020;
    $mes  = 11;
    $Ruta = "F13";*/

    $Meta_Recuperacion  =   0.00;
    $Recup_Credito      =   0.00;
    $Recup_Contado      =   0.00;
    $Recup_Total        =   0.00;
    $Recup_cumple       =   0.00;


    $qRecuperacion= "SELECT * FROM umk_recuperacion WHERE fecha_recup = '".$fecha."' and ruta='".$Ruta."' and idCompanny = 1";
    $rRecuperacion = mysqli_query($connect_comentario, $qRecuperacion);
    $ttRecuperado = mysqli_num_rows($rRecuperacion);
    if($ttRecuperado >= 1) {
       $link_recuperacion = mysqli_fetch_array($rRecuperacion, MYSQLI_ASSOC);
        $Recup_Credito = number_format($link_recuperacion["recuperado_credito"],2,".","");
        $Recup_Contado = number_format($link_recuperacion["recuperado_contado"],2,".","");
    }

    $qMeta= "SELECT * FROM meta_recuperacion_exl WHERE fechaMeta = '".$fecha."' and ruta='".$Ruta."' and idCompanny = 1";
    $rMeta = mysqli_query($connect_comentario, $qMeta);
    $ttMeta = mysqli_num_rows($rRecuperacion);
    if($ttMeta >= 1) {
        $link_meta = mysqli_fetch_array($rMeta, MYSQLI_ASSOC);
        $Meta_Recuperacion = number_format($link_meta['meta'],2,".","");
    }

    $Recup_Total = $Recup_Credito + $Recup_Contado;

    $dta[$i]['Meta_Recuperacion']           = $Meta_Recuperacion;
    $dta[$i]['Recup_Credito']               = $Recup_Credito;
    $dta[$i]['Recup_Contado']               = $Recup_Contado;
    $dta[$i]['Recup_Total']                 = number_format($Recup_Total,2,".","");
    $dta[$i]['Recup_cumple']                = ($Meta_Recuperacion==0) ? "100.00" : number_format(((floatval($Recup_Total)/floatval($Meta_Recuperacion))*100),2);

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));


}else if (isset($_GET['get_history_lotes'])) {
    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_Search_Lotes T0 WHERE  T0.LOTE ='".$_GET['get_history_lotes']."' AND T0.CCL='".$_GET['Cliente']."'  GROUP BY T0.CCL,T0.NCL,T0.LOTE,T0.FACTURA,T0.Dia,t0.ARTICULO,T0.DESCRIPCION", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $json[$i]['mLote']          = $fila['LOTE'];
        $json[$i]['mFactura']       = $fila['FACTURA'];
        $json[$i]['mDia']           = $fila['Dia'];
        $json[$i]['mArticulo']      = $fila['CCL'];
        $json[$i]['mDescripcion']   = $fila['NCL'];
        $i++;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
}else if (isset($_GET['get_vineta'])) {

    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM view_MasterVinnetaFacturadas_umk T0 WHERE  T0.CLIENTE='".$_GET['get_vineta']."' ORDER BY T0.FACTURA", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $json[$i]['mFactura']       = $fila['FACTURA'];
        $json[$i]['mFecha']         = $fila['FECHA']->format('d/m/Y'); 
        $json[$i]['mVineta']        = $fila['ARTICULO'];
        $json[$i]['mCantidad']      = number_format($fila['CANTIDAD'],0);
        $json[$i]['mValor']         = number_format($fila['VALOR'],0);
        $json[$i]['mTotal']         = number_format($fila['TOTAL'],0);
        $json[$i]['mLinea']         = number_format($fila['LINEA'],0);
        $i++;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
    
}else if (isset($_GET['post_order_vineta'])) {

    $ruta           = $_POST['ruta'];
    $cod_cliente    = $_POST['cod_cliente'];
    $recibo         = $_POST['recibo'];
    $name_cliente   = $_POST['name_cliente'];
    $address        = $_POST['address'];
    $order_list     = $_POST['order_list'];
    $order_total    = $_POST['order_total'];
    $comment        = $_POST['comment'];
    $comment_anul   = "";
    $player_id      = $_POST['player_id'];
    $date           = $_POST['date'];

    $query = "INSERT INTO tbl_order_vineta (ruta, cod_cliente,recibo, name_cliente,created_at, address, order_list, order_total, comment,comment_anul, player_id) 
    VALUES ('$ruta', '$cod_cliente', '$recibo', '$name_cliente','$date', '$address', '$order_list', '$order_total', '$comment', '$comment_anul', '$player_id')";


    if (mysqli_query($connect_comentario, $query)) {
        //include_once ('php-mail.php');
        echo 'Data Inserted Successfully';
    } else {
        echo 'Try Again';
    }
    mysqli_close($connect); 

}else if (isset($_GET['get_liquidacion_vineta'])) {
    
    $Usuario = $_GET['get_liquidacion_vineta'];
    $OrderBy = $_GET['OrderBy'];
    $i=0;
    $array = array();

    $query = "SELECT * FROM tbl_order_vineta WHERE ruta = '".$Usuario."' and status != 3 ORDER BY date_time,status $OrderBy";
    
    $resouter = mysqli_query($connect_comentario, $query);

    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1){
        foreach ($resouter as $key){

            $array[$i]['mId']               = $key['id'];
            $array[$i]['mRecibo']           = $key['recibo'];
            $array[$i]['mCod_Cliente']      = $key['cod_cliente'];
            $array[$i]['mName_Cliente']     = $key['name_cliente'];
            $array[$i]['mFecha']            = $key['date_time'];
            $array[$i]['mBenificiario']     = $key['address'];
            $array[$i]['mOrderTotal']       = $key['order_total'];
            $array[$i]['mComentario']       = $key['comment'];
            $array[$i]['mStatus']           = $key['status'];
            $array[$i]['mOrderList']        = $key['order_list'];
            $array[$i]['mComment_anul']     = $key['comment_anul'];            

            $i++;
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($array));

}else if (isset($_GET['del_order_vineta'])) {

    $id           = $_POST['ID'];
    $iDate        = date('Y-m-d H:i:s');

    $query ="UPDATE tbl_order_vineta SET status = '3', updated_at = '".$iDate."' WHERE id = ".$id." ";

    if (mysqli_query($connect_comentario, $query)) {
        echo 'Recibo Anulado';
    } else {
        echo 'Try Again';
    }
    mysqli_close($connect); 
}else{
    header('Content-Type: application/json; charset=utf-8');
    echo "no method found!";
}
?>