<?php

    //database configuration
    $host       = "mysql";
    $user       = "usr_container";
    $pass       = "pss_container";
    $database   = "data_gmv3";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }



?>