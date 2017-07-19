<?php	


$strCnx = "host=localhost port=5433 dbname=prueba3 user=postgres password=jarvis56";
$cnx = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
echo "Conexion exitosa <hr>";



?>