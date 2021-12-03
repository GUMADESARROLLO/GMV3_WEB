<?php

$array = json_decode(file_get_contents('http://186.1.15.166:8448/gmv3/api/api.php?get_recent'), true);

$json = array();
$i = 0;
if (isset($_POST['callback'])) {
    $function = $_POST['callback'];
    if ($function == 'Buscar') {
        if (isset($_POST['text'])) {
            $text = $_POST['text'];
            foreach ($array as $data) {
                $textFormat = trim($text);
                $productName = trim($data['product_name']);
                $str = $productName;
                $pattern = "/$textFormat/i";
                $match = preg_match_all($pattern, $str);
                //echo $match;
                if($match > 0){
                    $json[$i]['product_name']            = $data['product_name'];
                    $json[$i]['product_status']           = $data['product_status'];
                    $json[$i]['product_image']            = $data['product_image'];
                    $json[$i]['product_description']      = $data['product_description'];
                    $json[$i]['product_quantity']         = $data['product_quantity'];
                    $json[$i]['product_und']              = $data['product_und'];
                    $i++;
                }
            }

            header('Content-Type: application/json; charset=utf-8');
            echo $val = str_replace('\\/', '/', json_encode($json));
        }
    }
}

