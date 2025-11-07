<?php

    $env = parse_ini_file(__DIR__ . '/../.env', false, INI_SCANNER_RAW);
    
    $host       = $env['DB_HOST'];
    $user       = $env['DB_USERNAME'];
    $pass       = $env['DB_PASSWORD'];
    $database   = "db_gumanet";

    @$connect_comentario = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }

    



?>