<?php
	ob_start();
	include('session.php');
	include('public/sql-query.php');
	$data = array(
            'id_user' 	=> $_POST['mUser'],
            'Ruta'		=> $_POST['mRuta']
        );      

        return Insert('tbl_grupos', $data);     


?>

