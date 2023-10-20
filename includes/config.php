<?php

    //database configuration
    $host       = "192.168.1.15";
    $user       = "root";
    $pass       = "a7m1425.";
    $database   = "db_preventa_umk";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }



?>
