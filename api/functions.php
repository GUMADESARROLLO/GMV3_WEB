<?php

function getRuta($connect, $CODIGO_RUTA) {
    $queryGrupo = "
        SELECT g.RUTA 
        FROM tbl_grupos_proyectos g 
        WHERE g.VENDEDOR = '".$CODIGO_RUTA."'
        LIMIT 1
    ";

    $resulGrupo = mysqli_query($connect, $queryGrupo);
    $inforGrupo = mysqli_fetch_assoc($resulGrupo);

    if (!$inforGrupo || empty($inforGrupo['RUTA'])) {
        return '';
    }

    // Convertir "F04,F02,F22" → ['F04','F02','F22']
    $rutas = explode(',', $inforGrupo['RUTA']);

    // Agregar comillas simples a cada valor
    $rutas = array_map(function ($ruta) {
        return "'" . trim($ruta) . "'";
    }, $rutas);

    // Unir con coma
    return implode(',', $rutas);
}


function getClienteVendedor($connect, $Cliente) {

    $sqlsrv = new Sqlsrv();
    $query = $sqlsrv->fetchArray("SELECT * FROM GMV3_MASTER_CLIENTES WHERE CLIENTE='".$Cliente."' ", SQLSRV_FETCH_ASSOC);

    return $query[0]['VENDEDOR'];
    
}

function dd(...$args) {
    foreach ($args as $var) {
        var_dump($var);
    }
    die;
}
