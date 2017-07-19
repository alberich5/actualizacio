<?php
	//Incluimos librería y archivo de conexión
	require 'Classes/PHPExcel.php';
	require 'conexion.php';

	$fecha=$_GET['fecha'];
	
	//Consulta con la nueva conexion de postgres
	$query = "SELECT articulo.idarticulo,articulo.nombre,articulo.unidad,SUM(detalle_ingreso.cantidad) as total,detalle_ingreso.fecha
FROM articulo INNER JOIN detalle_ingreso ON (articulo.idarticulo = detalle_ingreso.idarticulo)
where detalle_ingreso.fecha='2017-07-15' group by articulo.idarticulo,detalle_ingreso.cantidad,detalle_ingreso.fecha";

$result = pg_exec($conn, $query); 
	//$sql = $conn->prepare($query);
	//$resultado=$sql->execute();


	echo $omar;

	
	
?>