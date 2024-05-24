<?php
// Datos de conexión a la base de datos
$servername = "bclca1jzsz7d9ilwuuut-mysql.services.clever-cloud.com"; // Nombre del servidor
$username = "uqusftn8l2isj2bo"; // Nombre de usuario de la base de datos
$password = "CS961Uzy0sL8spE7scm7"; // Contraseña de la base de datos
$dbname = "bclca1jzsz7d9ilwuuut"; // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
  } 


// Cerrar conexión
/* $conn->close();
?>
 */