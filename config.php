<?php
// datos para la conexion a mysql
$servername = "localhost";
$username = "sulbaranjc";
$password = "4688";
$dbname = "instituto";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname); //variable global para acceder a la conexion

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>