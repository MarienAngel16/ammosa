<!-- GUARDAR COMENTARIOS -->
<?php

// Verificar si se han enviado datos por el formulario
if (isset($_POST["comentario"])) {
    // Obtener los datos del formulario
    $email = $_POST["email"];
    $comentario = $_POST["comentario"];
    $ruta = $_POST["ruta"];
    $calificacion = $_POST["rating"];

    // Validar y sanitizar los datos si es necesario

    // Conectar a la base de datos
    include_once "database.php";

    // Preparar la consulta SQL para insertar el comentario
    $sql = "INSERT INTO comentarios (correo_cliente, comentario, id_ruta, evaluacion, fecha_com) VALUES (?, ?, ?, ?, NOW())";
    
    // Preparar la sentencia con consulta preparada
    $stmt = $conexion->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("ssii", $email, $comentario, $ruta, $calificacion);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Comentario guardado exitosamente
        echo '<script>alert("Comentario guardado exitosamente."); window.location.href = "rutas.html";</script>';
    } else {
        // Error al guardar el comentario
        echo '<script>alert("Error al enviar el comentario. "); window.location.href = "rutas.html";</script>';
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conexion->close();
}
?>