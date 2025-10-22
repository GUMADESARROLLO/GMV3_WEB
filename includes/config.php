<?php
    $env = parse_ini_file(__DIR__ . '/../.env', false, INI_SCANNER_RAW);
    //database configuration
    $host       = $env['DB_HOST'];
    $user       = $env['DB_USERNAME'];
    $pass       = $env['DB_PASSWORD'];
    $database   = $env['DB_DATABASE'];

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }



?>