<?php

    //database configuration
    $host       = "mysql_server";
    $user       = "root";
    $pass       = "a7m1425.";
    $database   = "ecommerce_android_app";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }



?>
