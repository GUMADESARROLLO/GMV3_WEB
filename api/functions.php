<?php

function getRuta($connect, $CODIGO_RUTA) {
    $queryGrupo = "SELECT * FROM tbl_grupos_proyectos g WHERE g.VENDEDOR = '".$CODIGO_RUTA."' ";
    $resulGrupo = mysqli_query($connect, $queryGrupo);
    $inforGrupo = mysqli_fetch_array($resulGrupo, MYSQLI_ASSOC);   
    return $inforGrupo['RUTA'];
}

function dd(...$args) {
    foreach ($args as $var) {
        var_dump($var);
    }
    die;
}
