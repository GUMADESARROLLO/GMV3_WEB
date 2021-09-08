<?php
session_start();
header('Content-Type: application/json');


$conn = mysqli_connect("localhost","root","a7m1425.","ecommerce_android_app");
$retVal = ($_SESSION['permisos'] == '3' || $_SESSION['permisos'] == '2') ? " AND Ruta in (".$_SESSION['grupos'].") " : "" ;


$sqlQuery = 'SELECT DATE_FORMAT(T0.created_at, "%b. %d") as Fecha,Sum(T0.Valor) AS VALOR FROM view_master_pedidos T0 WHERE  T0.status = 1 and T0.Mes = MONTH(NOW())'.$retVal.' GROUP BY T0.Dia';

$result = mysqli_query($conn,$sqlQuery);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>