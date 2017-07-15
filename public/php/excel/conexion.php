<?php	
//conexion de postgres
$usuario = 'postgres';
$password = 'jarvis56';
$conn = new PDO('pgsql:host=localhost;port=5433;dbname=prueba2', $usuario, $password);
?>