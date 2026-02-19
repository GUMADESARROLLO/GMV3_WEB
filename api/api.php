<?php
header('Access-Control-Allow-Origin: *');

include_once ('../includes/config.php');
include_once ('../includes/config_comentario.php');
include_once ('../includes/Sqlsrv.php');
include_once ('../public/sql-query.php');
include_once ('../api/functions.php');
//$env = parse_ini_file(__DIR__ . '/../.env', false, INI_SCANNER_RAW);


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

}else if (isset($_GET['get_recent'])) {

    if ( $_GET['APP_KEY'] !== $env['APP_KEY'] ) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    $sqlsrv = new Sqlsrv();

    $CODIGO_RUTA = $_GET['get_recent'];
    $cliente     = ($_GET['Cliente'] != 'ND') ? $_GET['Cliente'] : 'ND';
    $NUM_RUTA    = $CODIGO_RUTA;

    $json           = array();
    $Lotes          = "  :0:N/D";
    $i              = 0;
    $Img_array      = array();
    $count_imgs     = 0;
    $val_viñeta     = "C$ 00.00";
    $isPromo        = "N";
    $set_img        = "SinImagen.png";
    $set_des        = "";
    $articulos_sql  = []; 
    $Arti_Clientes  = [];
    $count_clientes = 0;
    $UnLock         = true;
    $isWhere        = "";

    $ListaPrecio     = "Nv. Prec. Farmacia";

    //mysqli_query($connect_comentario, "SET SESSION group_concat_max_len = 10000");   
    $queryGrupo = "SELECT * FROM tbl_grupos_proyectos g WHERE g.VENDEDOR = '".$CODIGO_RUTA."' ";
    $resulGrupo = mysqli_query($connect, $queryGrupo);
    $inforGrupo = mysqli_fetch_array($resulGrupo, MYSQLI_ASSOC);    
    $VendeGrupo = $inforGrupo['RUTA'];
    $ListaGrupo = $inforGrupo['GRUPO'];
    $CODIGO_RUTA = $VendeGrupo;


    
    //TIENE QUE EXCLUIR QUE FUERON DE ESENCIAL PERO EXPANSION LO FACTURO

    if ($ListaGrupo === "A") { 

        $isWhere = " AND GRUPOS = 'A' ";

        if ($cliente != 'ND') {
            $isWhere .= " AND ARTICULO NOT IN (SELECT ARTICULO FROM PRODUCCION.dbo.tbl_gmv_umk_excepciones WHERE ESENCIAL = '$VendeGrupo' AND CLIENTE = '$cliente') ";
        }

    }
    //$isWhere = ($ListaGrupo === "A") ? " AND GRUPOS = 'A' " : "" ;


    // LA TABLA ES ALIMENTADA CON EL PROCEDURE sp_gmv_masterArticulos
    $qListArticulos = "SELECT ARTICULO,CLIENTES_FACT,GRUPOS FROM PRODUCCION.dbo.tbl_gmv_master_articulos WHERE VENDEDOR = '".$VendeGrupo."'" . $isWhere ;
    $MASTER_ARTICULOS = $sqlsrv->fetchArray($qListArticulos, SQLSRV_FETCH_ASSOC);    

    //EXTRAER EL VENCIMIENTO DE LOS PRODUCTOS CON EXISTENCIA
    $LotesVencimiento = $sqlsrv->fetchArray("SELECT ARTICULO,FECHA_VENCIMIENTO FROM PRODUCCION.dbo.gmv_lotes_vecimientos", SQLSRV_FETCH_ASSOC);

    
    foreach ($MASTER_ARTICULOS as $articulo) {
        $articulo_escapado = str_replace("'", "''", $articulo['ARTICULO']);
        $articulos_sql[] = "'$articulo_escapado'";
    }
    
    $articulos_str = implode(",", $articulos_sql);
    
    if ($ListaGrupo === "B" && $cliente != 'ND') {      
        foreach ($MASTER_ARTICULOS as $art) {
            $clientes = array_map('trim', explode(',', $art['CLIENTES_FACT']));
            if (in_array($cliente, $clientes)) {
                $Arti_Clientes[$count_clientes] =[
                    'ARTICULO'  => $art['ARTICULO']
                ];
                $count_clientes++;
            }
        }
    }

    

    if ($CODIGO_RUTA == 'F18') {
        $sql = "SELECT * 
                FROM GMV_mstr_articulos 
                WHERE ARTICULO IN (SELECT * FROM DESARROLLO.dbo.tbl_gmv_articulos_f18) 
                ORDER BY DESCRIPCION ASC";
    } else {

        $View = in_array($CODIGO_RUTA, ['F02', 'F2802']) ? "view_gmv_articulos_insti" : "GMV_mstr_articulos";
        $sql = "SELECT * FROM $View WHERE ARTICULO IN ($articulos_str) ORDER BY DESCRIPCION ASC";    

        if (in_array($CODIGO_RUTA, ['F22', 'F02', 'F04'])) {
            $sql = "SELECT * FROM $View WHERE EXISTENCIA > 1 ORDER BY DESCRIPCION ASC";  
        }
    }

    //RUTAS QUE ESTAN SIN VENDEDOR EN GRUPO B PERO REQUIEREN MOSTRAR TODOS LOS PRODUCTOS CON EXISTENCIA PARA NO BLOQUEAR LA APP
    if (in_array($CODIGO_RUTA, ['F09', 'F10','F11', 'F19', 'F20'])) {
        $sql = "SELECT * FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 AND NOT ARTICULO LIKE 'VU%' AND ARTICULO LIKE '1%' ORDER BY CALIFICATIVO,DESCRIPCION ASC";

    }


    

    $query = $sqlsrv->fetchArray($sql, SQLSRV_FETCH_ASSOC);

    $RutaAsignada = $CODIGO_RUTA;
    $rImagenes = mysqli_fetch_all(mysqli_query($connect, "SELECT product_sku,product_image FROM tbl_product"), MYSQLI_ASSOC);


    foreach ($query as $fila) 
    {            

        $CODIGO_ARTICULO = $fila["ARTICULO"];
        $key = array_search($CODIGO_ARTICULO, array_column($rImagenes, 'product_sku'));
        $set_img = ($key === false) ? "SinImagen.png" : $rImagenes[$key]['product_image'];    
        
        $keyLote = array_search($CODIGO_ARTICULO, array_column($LotesVencimiento, 'ARTICULO'));
        $set_des = ($keyLote === false) ? "N/D" : $LotesVencimiento[$keyLote]['FECHA_VENCIMIENTO']->format('d/m/Y') ;

        $Precio_Articulo = (strpos($CODIGO_ARTICULO, "VU") !== false) ? 1 : $fila['PRECIO_IVA'] ;
        $Existe_Articulo = (strpos($CODIGO_ARTICULO, "VU") !== false) ? 999 : $fila['EXISTENCIA'] ;

        
        
        if (in_array($CODIGO_RUTA, array('F18', 'F04', 'F2804'))) {
            $Precio_Articulo = $fila['PRECIO_MAYORISTA'];
            $ListaPrecio = "Nv. Prec. Mayorista";
        }

        // NIVEL DE PRECIO INSTITUCIONAL
        if (in_array($CODIGO_RUTA, array('F02', 'F2802'))){
            $Precio_Articulo = $fila['PRECIO_INSTI'];
            $ListaPrecio = "Nv. Prec. Institucional";
        }
        // NIVEL DE PRECIO CADENA DE FARMACIA
        if (in_array($CODIGO_RUTA, array('F22', 'F2822'))){
            $Precio_Articulo = $fila['CADENAS_FARMACIAS'];
            $RutaAsignada = "F22 - CADENAS";
            $ListaPrecio = "Nv. Prec. Cadenas Farmacias";

        } else {
            $RutaAsignada = $NUM_RUTA ;
        }

        $UnLock = ($ListaGrupo === "B" && $cliente != 'ND'  ) ? (array_search($CODIGO_ARTICULO, array_column($Arti_Clientes, 'ARTICULO')) === false) : true ;
        
        $KeyGrupArticulo = array_search($CODIGO_ARTICULO, array_column($MASTER_ARTICULOS, 'ARTICULO'));
        $GrupArticulo = $MASTER_ARTICULOS[$KeyGrupArticulo]['GRUPOS'] ?? "N/D";

        if ( $GrupArticulo === "B" && $UnLock === false ) {
            $UnLock = true;
        }
        
        $set_reglas = $fila["REGLAS"];

        if ($set_des != "") {
            
            $set_des ='
                <!DOCTYPE html>
                    <html>
                    <head>
                        <style type="text/css">
                        .alert-box {
                            color:#555;
                            border-radius:10px;
                            font-family:Tahoma,Geneva,Arial,sans-serif;font-size:18px;
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
                        <div class="alert-box error">Fecha de Venc.: '.$set_des.'</div>
                    </body>
                </html>';
        }

        $json[$i] = array(
            'product_id'            => $fila["ARTICULO"],
            'product_name'          => strtoupper($fila['DESCRIPCION']) . " - ( " . $GrupArticulo . " )" ,
            //'product_name'          => strtoupper($fila['DESCRIPCION']),
            'category_id'           => "20",
            'category_name'         => "Medicina",
            'product_price'         => number_format($Precio_Articulo,2,'.',''),
            'product_status'        => "Available",
            'product_image'         => $set_img,
            'product_description'   => $set_des,
            'product_quantity'      => str_replace(',', '', number_format($Existe_Articulo,2)),
            'currency_id'           => "105",
            'tax'                   => "0",
            'currency_code'         => "NIO",
            'currency_name'         => "Nicaraguan cordoba oro",
            'product_bonificado'    => $set_reglas,
            'product_lotes'         => $Lotes,
            'product_und'           => $fila["UNIDAD_MEDIDA"],
            'CALIFICATIVO'          => $fila["CALIFICATIVO"],
            'ISPROMO'               => $isPromo. ":" . $val_viñeta . ":" . $RutaAsignada,
            'LAB'                   => $fila["LABORATORIO"],
            'isUnLock'              => $UnLock,
            'ListaPrecio'           => $ListaPrecio,
            'ListaGrupo'            => $GrupArticulo,
        );

        $i++;
    }


    usort($json, function($a, $b) {
        return $a['isUnLock'] < $b['isUnLock'];
    });


    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
    $sqlsrv->close();

}else if (isset($_GET['get_category'])) {
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

}else if (isset($_GET['get_tax_currency'])) {
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

}else if (isset($_GET['post_order'])) {

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

}else if (isset($_GET['txt_bonificado.setText(product_bonificado);'])) {

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

}else if (isset($_GET['get_help'])) {

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

}else if (isset($_GET['product_id'])) {


    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_mstr_articulos WHERE EXISTENCIA > 1 and ARTICULO='".$_GET['product_id']."'", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $set_img ="SinImagen.png";
        $set_des = "ND";

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
        $json['product_bonificado']       = $fila["REGLAS"];
        $i++;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
    $sqlsrv->close();

}else if (isset($_GET['clients_id'])) {


    
    if ( $_GET['APP_KEY'] !== $env['APP_KEY'] ) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    $RutaPrincipal = substr($_GET['clients_id'], 0, 3);
    
    //RECUPERA LA RUTA ASIGNADA AL VENDEDOR
    $Ruta  = getRuta($connect, $_GET['clients_id']);

    $sqlsrv = new Sqlsrv();
    $dta = array(); 
    $i=0;


    //$Clientes = $sqlsrv->fetchArray("SELECT *  FROM PRODUCCION.dbo.tbl_gmv_master_articulos T0 WHERE T0.VENDEDOR='".$Ruta."'", SQLSRV_FETCH_ASSOC)[0];

    
    //$ArrayClientes = explode(",",$Clientes['CLIENTES_FACT']);

    //$Condicional = ($Clientes['GRUPOS'] === "A") ? " T0.CLIENTE IN ('".implode("','", $ArrayClientes)."') " : " T0.CLIENTE NOT IN ('".implode("','", $ArrayClientes)."') " ;

    //$sql_query ="SELECT T0.*, ISNULL( 0, 0 ) AS SALDO_VINETA  FROM PRODUCCION.dbo.GMV3_MASTER_CLIENTES T0 WHERE $Condicional AND VENDEDOR='".$Ruta."' AND ACTIVO ='S' ORDER BY NOMBRE";
    $sql_query ="SELECT T0.*, ISNULL( 0, 0 ) AS SALDO_VINETA  FROM PRODUCCION.dbo.GMV3_MASTER_CLIENTES T0 WHERE VENDEDOR IN ('".$RutaPrincipal."' , '".$Ruta."')  AND ACTIVO ='S' ORDER BY NOMBRE";
    
    //dd($sql_query);

    //$sql_query = "SELECT T0.*,ISNULL(T1.DISPONIBLE, 0) AS SALDO_VINETA  FROM dbo.GMV3_MASTER_CLIENTES T0 LEFT JOIN PRODUCCION.dbo.view_master_cliente_vineta T1 ON T0.CLIENTE = T1.CLIENTE WHERE VENDEDOR='".$_GET['clients_id']."' AND ACTIVO ='S' ORDER BY NOMBRE";

    $query = $sqlsrv->fetchArray($sql_query, SQLSRV_FETCH_ASSOC);
    if (count($query)>0) {
        foreach ($query as $key) {


            // $query = "SELECT * FROM tlb_verificacion WHERE Cliente = '".$key['CLIENTE']."'";
            // $resouter = mysqli_query($connect, $query);
            // $total_records = mysqli_num_rows($resouter);
            // $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);

            // $Verificaco = ($total_records == 0) ? "N;0.00;0.00" : "S;".$link['Lati'].";".$link['Longi'] ;

            // $qPin = "SELECT * FROM tlb_pins WHERE Cliente = '".$key['CLIENTE']."'";
            // $rPin = mysqli_query($connect, $qPin);
            // $Pin_num_rows = mysqli_num_rows($rPin);

            // $isPin = ($Pin_num_rows == 0) ? "N" : "S";
            // $isPlan =($key['PLAN_CRECI'] == 0) ? "N" : "S";




            $Verificaco = "N;0.00;0.00";
            $isPin = "N";
            $isPlan = "N";

            $retVal = ($key['MOROSO'] == 'S') ? $key['NOMBRE']." [MOROSO]" : $key['NOMBRE'] ;

            $dta[$i]['CLIENTE']     = $key['CLIENTE'];
            $dta[$i]['NOMBRE']      = str_replace ( "'", '', $retVal);
            $dta[$i]['DIRECCION']   = $key['DIRECCION'];
            $dta[$i]['DIPONIBLE']   = number_format($key['LIMITE_CREDITO'] - $key['SALDO'],2);
            $dta[$i]['LIMITE']      = number_format($key['LIMITE_CREDITO'],2);
            $dta[$i]['SALDO']       = number_format($key['SALDO'],2);
            $dta[$i]['MOROSO']      = $key['MOROSO'];
            $dta[$i]['TELE']        = "Tels. ".$key['TELEFONO1'].' / '.$key['TELEFONO2'];
            $dta[$i]['CONDPA']      = $key['CONDICION_PAGO'];
            $dta[$i]['VERIFICADO']  = $Verificaco;
            $dta[$i]['PIN']         = $isPin;
            $dta[$i]['PLAN']         = $isPlan;
            $dta[$i]['vineta']       = number_format($key['SALDO_VINETA'],2);
            $dta[$i]['NIVEL_PRECIO'] =$key['NIVEL_PRECIO'];
            $i++;
        }
        //echo json_encode($dta);
        //usort($dta, 'object_sorter');
        //echo json_encode($dta);

    }else{
        $dta[$i]['CLIENTE']      = '0000';
        $dta[$i]['NOMBRE']       = 'CLIENTE EN BLANCO';
        $dta[$i]['DIRECCION']    = 'EN ESPERA DE ASIGNACION DE CLIENTES';
        $dta[$i]['DIPONIBLE']    = '0.00';
        $dta[$i]['LIMITE']       = '0.00';
        $dta[$i]['SALDO']        = '0.00';
        $dta[$i]['MOROSO']       = 'N';
        $dta[$i]['TELE']         = 'Tels. X /';
        $dta[$i]['CONDPA']       = 'Crédito 0 Días';
        $dta[$i]['VERIFICADO']   = "N;0.00;0.00";
        $dta[$i]['PIN']          = 'N';
        $dta[$i]['PLAN']         = 'N';
        $dta[$i]['vineta']       = '0.00';
        $dta[$i]['NIVEL_PRECIO'] = 'FARMACIA';

    }



    $sqlsrv->close();



    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));


}else if (isset($_GET['post_usuario'])) {

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
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV_FACTURA_DETALLE_HISTORICO WHERE FACTURA='".$_GET['get_detalle_factura']."' ORDER BY ARTICULO", SQLSRV_FETCH_ASSOC);
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
    
    $anio = $_GET['sAnno'];
    $mes  = $_GET['sMes'];
    $Ruta = $_GET['get_stat_ruta'];

    $fecha = date('Y-m-d',strtotime(str_replace('/', '-',($anio.'-'.$mes.'-01'))));
    
    $q_meta_unidades = 0;
    $q_meta_valor    = 0;
    $dta[0] = [ 
        'mVentaReal' => 0,
        'mMetaVenta' => 0,
        'mVentaDif'  => 0,
        'mVntCanti'  => 0,
        'mVntCantiReal' => 0,
        'mVntCantiDif'  => 0
    ];; 

    $sqlsrv = new Sqlsrv();
    $qPeriodo = $sqlsrv->fetchArray("SELECT IdPeriodo FROM DESARROLLO.dbo.metacuota_GumaNet WHERE Fecha ='".$fecha."' AND IdCompany='1' ", SQLSRV_FETCH_ASSOC);
    if ($qPeriodo == null || $qPeriodo == '') {
        header('Content-Type: application/json; charset=utf-8');
        echo $val = str_replace('\\/', '/', json_encode($dta));
        $sqlsrv->close();
        exit();
    }

    $PeriodoActivo = $qPeriodo[0]['IdPeriodo'];
    $q_meta = $sqlsrv->fetchArray("SELECT Sum(Meta) as Meta, Sum(val) as Valor FROM DESARROLLO.dbo.gn_cuota_x_productos WHERE IdPeriodo = '".$PeriodoActivo."' AND CodVendedor ='".$Ruta."' GROUP BY IdPeriodo, CodVendedor", SQLSRV_FETCH_ASSOC);
    $q_meta_unidades = $q_meta[0]['Meta'];
    $q_meta_valor    = $q_meta[0]['Valor'];

    $sql_exec = "SELECT Ruta, SUM(VENTA) AS Monto, SUM(Cantidad) AS Cantidad FROM Softland.DBO.VtasTotal_UMK (nolock) WHERE month(DIA)= '".$mes."' AND  year(DIA) = '".$anio."' AND NOT [P. Unitario] = 0 AND  Ruta = '".$Ruta."' GROUP BY Ruta";
    $qVenta = $sqlsrv->fetchArray($sql_exec,SQLSRV_FETCH_ASSOC);

    $Meta_Monto     = $qVenta[0]['Monto'];
    $Meta_Cantidad  = $qVenta[0]['Cantidad'];

    $dta[0] = [ 
        'mVentaReal' => str_replace(",", "",number_format($Meta_Monto,2)),
        'mMetaVenta' => str_replace(",", "",number_format($q_meta_valor,2)),
        'mVentaDif'  => ($Meta_Monto==0) ? "100.00" : number_format(((floatval($Meta_Monto)/floatval($q_meta_valor))*100),2),
        'mVntCanti'  => str_replace(",", "",number_format($q_meta_unidades,2)),
        'mVntCantiReal' => str_replace(",", "",number_format($Meta_Cantidad,2)),
        'mVntCantiDif'  => ($q_meta_unidades==0) ? "100.00" : number_format(((floatval($Meta_Cantidad) / floatval($q_meta_unidades))*100),2)
    ];


    $sqlsrv->close();
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
}else if (isset($_GET['get_comentarios_im'])){

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
}else if (isset($_GET['get_comments_post_im'])) {

    $IdPost = $_GET['get_comments_post_im'];

    $query = "SELECT * FROM tbl_comments_post_im WHERE id_post= '".$IdPost."' ORDER BY created_at DESC";
    $resouter = mysqli_query($connect_comentario, $query);

    $set = array();
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1) {
        while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
            $set[] = $link;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($set));


}else if (isset($_GET['post_report'])) {


    $Fecha          = $_POST['sndFecha'];
    $Nombre         = $_POST['sndTitulo'];
    $CodRuta        = $_POST['sndCodigo'];
    $NamRuta        = $_POST['sndNombre'];
    $Comentario     = $_POST['snd_comentario'];
    $imagektp       = $_POST['snd_image'];
    $Empresa        = '1';
    $Read           = '0';
    $Updated_at     = date('Y-m-d H:i:s');


    if($imagektp !=""){
        $nama_imagen = time() . '-' . rand(0, 99999) . ".jpg";
        $pathktp = "../upload/news/" . $nama_imagen;
        file_put_contents($pathktp, base64_decode($imagektp));    
    }


    $query = "INSERT INTO tbl_comentarios (Titulo,Contenido, Autor, Nombre,Fecha,Imagen,empresa,`Read`,updated_at) VALUES ('$Nombre','$Comentario', '$CodRuta', '$NamRuta','$Fecha','$nama_imagen','$Empresa','$Read','$Updated_at')";

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


    $KeysSecret     = "A7M";
    $table_name     = 'tbl_admin';
    $where_clause   = "WHERE username = '".$_POST['Ruta']."'";
    $whereSQL       = '';

    $form_data = array(
        'email'             => $_POST['Email'],
        'Telefono'          => $_POST['Telefono'],
        'password'          => hash('sha256',$KeysSecret.$_POST['Contrasenna'])
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




        $table_name     = 'tlb_verificacion';
        $where_clause   = "WHERE Cliente = '".$cliente."'";
        $whereSQL       = '';

        $form_data = array(
            'Lati'          => $Lati,
            'Longi'         => $Logi,
            'updated_at'        => $date
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

    $query = $sqlsrv->fetchArray("SELECT T0.FACTURA,T0.FECHA,T0.ARTICULO,(T0.CANTIDAD - T0.CANT_LIQUIDADA) AS CANTIDAD,T0.VALOR,T0.TOTAL,T0.LINEA  FROM view_MasterVinnetaFacturadas_umk T0 WHERE  T0.CLIENTE='".$_GET['get_vineta']."' ORDER BY T0.FACTURA", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        if ($fila['CANTIDAD'] > 0) {

            $Total = $fila['CANTIDAD'] * $fila['VALOR'];

            $json[$i]['mFactura']       = $fila['FACTURA'];
            $json[$i]['mFecha']         = $fila['FECHA']->format('d/m/Y'); 
            $json[$i]['mVineta']        = $fila['ARTICULO'];
            $json[$i]['mCantidad']      = number_format($fila['CANTIDAD'],0);
            $json[$i]['mValor']         = number_format($fila['VALOR'],0);
            $json[$i]['mTotal']         = number_format($Total,0);
            $json[$i]['mLinea']         = number_format($fila['LINEA'],0);
            $i++;
        }
        
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
            $array[$i]['mRuta']             = $key['ruta'];
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
}else if (isset($_GET['post_order_recibo'])) {

    $ruta           = $_POST['ruta'];
    $cod_cliente    = $_POST['cod_cliente'];

    $recibo         = $_POST['recibo'];
    $fecha_recibo   = $_POST['fecha_recibo'];
    
    $name_cliente   = $_POST['name_cliente'];    
    $order_list     = $_POST['order_list'];
    $order_total    = $_POST['order_total'];
    $comment        = $_POST['comment'];
    $comment_anul   = "";
    $player_id      = $_POST['player_id'];
    $date           = $_POST['date'];

    $qIsExist = "SELECT * FROM tbl_order_recibo T0 WHERE T0.recibo = '".$recibo."' AND  T0.ruta  = '".$ruta."' AND T0.status in (0,1,4) ";    
    $rsCount = mysqli_query($connect_comentario, $qIsExist);
    $total_records = mysqli_num_rows($rsCount);

    if($total_records != 1){
        $query = "INSERT INTO tbl_order_recibo (ruta, cod_cliente,recibo,fecha_recibo, name_cliente,created_at, order_list, order_total, comment,comment_anul, player_id) 
        VALUES ('$ruta', '$cod_cliente', '$recibo', '$fecha_recibo', '$name_cliente','$date', '$order_list', '$order_total', '$comment', '$comment_anul', '$player_id')";
        if (mysqli_query($connect_comentario, $query)) {
            //include_once ('php-mail.php');
            echo 'Nuevo';
        } else {
            echo 'Error';
        }
        mysqli_close($connect); 
    }else{
        echo 'Existe';
    }
    
}else if (isset($_GET['get_recibos_colector'])) {

    $Usuario    = $_GET['get_recibos_colector'];
    $OrderBy    = $_GET['OrderBy'];
    $Desde      = $_GET['Desde'];;
    $Hasta      = $_GET['Hasta'];;
    
    $i=0;
    
    $array = array();

    $query = "SELECT * FROM tbl_order_recibo T0 WHERE T0.fecha_recibo BETWEEN '".$Desde."' AND '".$Hasta."' AND  ruta = '".$Usuario."' and status != 3 ORDER BY id $OrderBy";
    
    $resouter = mysqli_query($connect_comentario, $query);

    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1){
        foreach ($resouter as $key){

            $array[$i]['mId']               = $key['id'];
            $array[$i]['mRuta']             = $key['ruta'];
            $array[$i]['mRecibo']           = $key['recibo'];
            $array[$i]['mCod_Cliente']      = $key['cod_cliente'];
            $array[$i]['mName_Cliente']     = $key['name_cliente'];
            $array[$i]['mFecha']            = $key['date_time'];
            $array[$i]['mBenificiario']     = "----";
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
    
}else if (isset($_GET['del_recibo_colector'])) {

    $id           = $_POST['ID'];
    $iDate        = date('Y-m-d H:i:s');

    $query ="UPDATE tbl_order_recibo SET status = '3', updated_at = '".$iDate."' WHERE id = ".$id." ";

    if (mysqli_query($connect_comentario, $query)) {
        echo 'Recibo Anulado';
    } else {
        echo 'Try Again';
    }
    mysqli_close($connect); 
}else if (isset($_GET['post_adjunto'])) {
    

    $nomImagen  = $_POST['nom'];
    $imagen     = $_POST['imagenes'];    
    $Id_Recibo  = $_POST['Id_Recibo'];    

    $id_img = time() . '-' . rand(0, 99999);

    $nameImagen = $Id_Recibo. " - ". $id_img .  ".png";
    
    $actualpath = "../upload/recibos/". $nameImagen;    
    file_put_contents($actualpath, base64_decode($imagen));


    $query = "INSERT INTO tbl_order_recibo_adjuntos (id_recibo,Nombre_imagen) VALUES ('$Id_Recibo','$nameImagen')";

    if (mysqli_query($connect_comentario, $query)) {
        //include_once ('php-mail.php');
        echo 'Data Inserted Successfully';
    } else {
        echo 'Try Again';
    }
    mysqli_close($connect);

}else if (isset($_GET['get_recibos_adjuntos'])) {
    
    $IdRecibo = $_GET['get_recibos_adjuntos'];
    $i=0;
    $array = array();

    $query = "SELECT * FROM tbl_order_recibo_adjuntos WHERE id_recibo = '".$IdRecibo."' ";

    
    $resouter = mysqli_query($connect_comentario, $query);

    $total_records = mysqli_num_rows($resouter);

    if($total_records >= 1){
        foreach ($resouter as $key){

            $array[$i]['mId']               = $key['id'];
            $array[$i]['mRecibo']           = $key['id_recibo'];
            $array[$i]['mNombreImagen']     = $key['Nombre_imagen'];
            $i++;
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($array));
}else if (isset($_GET['post_historico_factura'])){
    $ruta        = $_GET['post_historico_factura'];


    $Q="SELECT
        T0.FACTURA,
        T0.Dia,
        T0.[Nombre del cliente] AS Cliente,
        SUM ( T0.Venta ) AS Venta,
        ( SELECT COUNT ( * ) FROM Softland.dbo.APK_CxC_DocVenxCL AS T1 WHERE T1.DOCUMENTO= T0.FACTURA ) AS ACTIVA,
        ( SELECT ISNULL(SUM(T4.SALDO_LOCAL) , 0) FROM Softland.dbo.APK_CxC_DocVenxCL AS T4 WHERE T4.DOCUMENTO = T0.FACTURA ) AS SALDO,
        ISNULL(convert(nvarchar(11),( SELECT T2.FECHA_VENCE FROM Softland.dbo.APK_CxC_DocVenxCL AS T2 WHERE T2.DOCUMENTO= T0.FACTURA ),103), '-/-/-') AS FECHA_VENCE,
        (SELECT T3.DVencidos FROM Softland.dbo.APK_CxC_DocVenxCL AS T3 WHERE T3.DOCUMENTO= T0.FACTURA ) AS DVencidos,
        T0.Plazo
    FROM
        Softland.dbo.VtasTotal_UMK T0 
    WHERE
        T0.[Cod. Cliente] ='".$ruta."' 
    GROUP BY
        T0.FACTURA,
        T0.Dia,
        T0.[Nombre del cliente],
        T0.Plazo
    ORDER BY
    T0.Dia DESC";



    $sqlsrv = new Sqlsrv();
    $dta = array(); $i=0;
    $query = $sqlsrv->fetchArray($Q, SQLSRV_FETCH_ASSOC);
    foreach ($query as $key) {
        $dta[$i]['FACTURA']    = $key['FACTURA'];
        $dta[$i]['FECHA']      = $key['Dia']->format('d/m/Y');
        $dta[$i]['CLIENTE']    = $key['Cliente'];
        $dta[$i]['MONTO']      = str_replace(",", "",number_format($key['Venta'],2));
        $dta[$i]['ACTIVA']     = $key['ACTIVA'];
        $dta[$i]['PLAZO']      = $key['Plazo'];
        $dta[$i]['VENCE']      = $key['FECHA_VENCE'];
        $dta[$i]['DVENCIDOS']  = $key['DVencidos'];
        $dta[$i]['SALDO']      = str_replace(",", "",number_format($key['SALDO'],2));
        $i++;
    }

    $sqlsrv->close();

    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dta));
}else if (isset($_GET['recibo_anular'])){

    $recibo         = $_GET['recibo_anular'];
    $fecha_recibo   = $_GET['Fecha_Recibo'];
    $Ruta           = $_GET['Ruta'];
    
    $qIsExist = "SELECT * FROM tbl_order_recibo T0 WHERE T0.recibo = '".$recibo."' AND  T0.ruta  = '".$Ruta."' AND T0.status in (0,1,4) ";    
    $rsCount = mysqli_query($connect_comentario, $qIsExist);
    $total_records = mysqli_num_rows($rsCount);
    if($total_records != 1){

        $ruta           = $Ruta;
        $cod_cliente    = "00000";

        $recibo         = $recibo;
        $fecha_recibo   = $fecha_recibo;
        
        $name_cliente   = "N/D";    
        $order_list     = "[00000000;0.00;0.00;0;0.00;0.00;0.00;00000;ANULADO],";
        $order_total    = "C$ 0.00";
        $comment        = "ESTE RECIBO FUE ANULADO POR EL VENDEDOR";
        $comment_anul   = "";
        $player_id      = $_GET['Player_Id'];
        $date           = date('Y-m-d H:i:s');

        $query = "INSERT INTO tbl_order_recibo (ruta, cod_cliente,recibo,fecha_recibo, name_cliente,created_at, order_list, order_total, comment,comment_anul, player_id,status) 
            VALUES ('$ruta', '$cod_cliente', '$recibo', '$fecha_recibo', '$name_cliente','$date', '$order_list', '$order_total', '$comment', '$comment_anul', '$player_id',4)";
        if (mysqli_query($connect_comentario, $query)) {
            echo 'Nuevo';
        } else {
            echo 'Error';
        }
        mysqli_close($connect); 
        
    }else{
        echo 'Existe';
    }

    
    
}else if (isset($_GET['PLAN'])){

   

    $ruta        = $_GET['RUTA'];
    $Cliente      = $_GET['PLAN'];

    $Q01="SELECT * FROM view_cliente_stats WHERE CLIENTE_CODIGO='".$Cliente ."'";
    
    $Q02="SELECT month(T0.Fecha_de_Factura) number_month,SUBSTRING(t0.MES,0,4) name_month,t0.[AÑO] annio,sum(T0.VentaNetaLocal) ttMonth 
        FROM Softland.dbo.ANA_VentasTotales_MOD_Contabilidad_UMK T0 WHERE T0.Fecha_de_Factura >= DATEADD(MONTH, -6, GETDATE())
        AND T0.CLIENTE_CODIGO= '".$Cliente ."' and T0.VentaNetaLocal  > 0
        GROUP BY MONTH ( T0.Fecha_de_Factura ),YEAR  ( T0.Fecha_de_factura),t0.MES,t0.[AÑO] ORDER BY YEAR( T0.Fecha_de_factura) ASC,MONTH ( T0.Fecha_de_Factura )";

    $sqlsrv = new Sqlsrv();

    $dta        = array(); 
    $dta_month  = array(); 

    $i=0;

    $query_result01 = $sqlsrv->fetchArray($Q01, SQLSRV_FETCH_ASSOC);
    

    if (empty($query_result01)) {
        $dta['EVALUADO'] = number_format(0,2);
        $dta['CRECIMIENTO'] = 0;
        $dta['COMPRA_MIN'] = 0;
        $dta['PROM_CUMP'] = 0;
    } else {
        foreach ($query_result01 as $key) {
            $dta['EVALUADO']      = ceil($key['EVALUADO']);
            $dta['CRECIMIENTO']      = ceil($key['CRECIMIENTO']);
            $dta['COMPRA_MIN']      = ceil($key['COMPRA_MIN']);
            $dta['PROM_CUMP']      = ceil(number_format($key['PROM_CUMP'],0));
        }
        
    }

    $query_result02 = $sqlsrv->fetchArray($Q02, SQLSRV_FETCH_ASSOC);
    foreach ($query_result02 as $key) {        
        $dta_month[$i]['number_month']    = $key['number_month'];      
        $dta_month[$i]['name_month']    = $key['name_month'];      
        $dta_month[$i]['annio']    = $key['annio'];      
        $dta_month[$i]['ttMonth']      = ceil($key['ttMonth']);
        $i++;
    }

    $dtaBodega[] = array(
        'InfoCliente' => $dta,
        'SalesMonths' => $dta_month
    );

    $sqlsrv->close();

    $InserteDate = date('Y-m-d');
    $rowInsert   = "INSERT INTO tbl_logs (RUTA,FECHA, MODULO) VALUES ('$ruta','$InserteDate', 'PlanCrecimiento')";
    mysqli_query($connect, $rowInsert);



    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($dtaBodega));

}else if (isset($_GET['get_devolucion'])) {
    $sqlsrv = new Sqlsrv();

    $query = $sqlsrv->fetchArray("SELECT * FROM view_sac_devoluciones T0 WHERE  T0.FACTURA ='".$_GET['get_devolucion']."'", SQLSRV_FETCH_ASSOC);
    $i = 0;
    $json = array();

    foreach ($query as $fila) {
        $set_img ="SinImagen.png";
        $query = "SELECT p.product_image,p.product_description FROM tbl_product p WHERE p.product_sku= '".$fila["ARTICULO"]."'";
        $resouter = mysqli_query($connect, $query);
        $total_records = mysqli_num_rows($resouter);
        if($total_records >= 1) {
            $link = mysqli_fetch_array($resouter, MYSQLI_ASSOC);
            $set_img = $link['product_image'];
        }
        $json[$i]['mLote']          = $fila['LOTE'];
        $json[$i]['mFactura']       = $fila['FACTURA'];
        $json[$i]['mDia']           = $fila['FECHA_FACTURA'];
        $json[$i]['mArticulo']      = $fila['ARTICULO'];
        $json[$i]['mDescripcion']   = $fila['DESCRIPCION'];
        $json[$i]['mCantidad']      = number_format($fila['CANTIDAD'],2);
        $json[$i]['mImages']        = $set_img;
        $json[$i]['mRuta']   = $fila['VENDEDOR'];
        $i++;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $val = str_replace('\\/', '/', json_encode($json));
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = file_get_contents('php://input');

    // Decodificar datos JSON
    $json = json_decode($data);

    // Verificar si se pudieron decodificar los datos
    if ($json !== null) {
        // Recuperar las coordenadas
        $Ruta = $json->ruta;
        $Fecha = $json->Fecha;
        $latitude = $json->latitude;
        $longitude = $json->longitude;
        

        $rowInsert   = "INSERT INTO tbl_gps_logs (Ruta,longitude, latitude,Fecha) VALUES ('$Ruta', '$longitude', '$latitude', '$Fecha')";

        
         if (mysqli_query($connect, $rowInsert)) {
            echo json_encode(array('status' => 'success'));
        } else {
           echo json_encode(array('status' => 'error', 'message' => 'Error al insertar los datos recibidos'));
        }

        // Realizar acciones con las coordenadas
        // Por ejemplo, almacenarlas en una base de datos

        // Enviar una respuesta al cliente (puede ser un simple mensaje de éxito)
        
    } else {
        // Error al decodificar datos JSON
        echo json_encode(array('status' => 'error', 'message' => 'Error en los datos recibidos'));
    }

}else {
    header('Content-Type: application/json; charset=utf-8');
    echo "no method found!";
}
?>