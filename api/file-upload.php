<?php
    
    $nomImagen = $_POST['nom'];
    $imagen = $_POST['imagenes'];
    
    // RUTA DONDE SE GUARDARAN LAS IMAGENES
    //$path = "recibos/$nomImagen.png";
    
    $actualpath = "../upload/recibos/".$nomImagen . ".png";
    
    file_put_contents($actualpath, base64_decode($imagen));
    
    echo "SE SUBIO EXITOSAMENTE";



?>