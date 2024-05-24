<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MI CUENTA</title>
    <link rel="stylesheet" href="assets/estyles.css">
     <!--  bootstrap css -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <section>
        <a href="index.html" id="logo" target="_blank">Coodinanet AMMOSA</a>
        
        <label for="toggle-1" class="toggle-menu"><ul><li></li> <li></li> <li></li></ul></label>
        <input type="checkbox" id="toggle-1">
        
        <nav>
        <ul>
        <li><a href="index.html"><i ></i>Inicio</a></li>
        <li><a href="rutas.html"><i ></i>Rutas</a></li>
        <li><a href="login.html"></i></i>Mi cuenta</a></li>

        </ul>
        </nav>
        </header>
        

 <h1><span>Nuestras Mejores Rutas</span> Melchor Ocampo</h1>

 <?php
if (isset($_POST["nickname"])) {

  $nickname = $_POST["nickname"];
  $password = $_POST["password"];

  include_once "database.php"; // Asumiendo que este archivo establece la conexión con la base de datos

  // Consulta SQL para validar el inicio de sesión
  $sql = "SELECT * FROM Usuario WHERE nombre_us = ? ";

  // Preparar la sentencia con consulta preparada
  $stmt = $conexion->prepare($sql); // Asegúrate de que la variable de conexión es $conn

  // Verificar si la preparación de la consulta fue exitosa
  if ($stmt === false) {
      echo "Error en la preparación de la consulta: " . $conexion->error;
      exit();
  }

  // Vincular parámetros
  $stmt->bind_param("s", $nickname);

  // Ejecutar la consulta
  $stmt->execute();

  // Obtener el resultado
  $result = $stmt->get_result();

  // Verificar si se encontró un usuario
  if ($result->num_rows > 0) {
      // Obtener los datos del usuario de la consulta
      $row = $result->fetch_assoc();

      // Guardar los datos en variables  
  $nombre_us = $row['nombre_us'];  
  $rol = $row['rol'];
  $telefono = $row['telefono'];
  $correo = $row['correo'];
  $nombre_completo = $row['nombre_completo'];
  $celular = $row['celular'];
  $direccion = $row['direccion'];
  $clave = $row['clave_usuario'];

      // Verificar el rol y redirigir a la página correspondiente
      if ($rol == 'trabajador') {
       // Consultar las rutas asociadas al usuario con el nickname proporcionado
       $sql = "SELECT r.clave_ruta, r.nombre_ruta, r.descripcion_ruta, c.especificaciones, c.fecha_ad, c.num_camion 
       FROM detalle_rutas dr 
       INNER JOIN rutas r ON dr.clave_ruta = r.clave_ruta
       INNER JOIN Camiones c ON dr.clave_camion = c.num_camion
       INNER JOIN Usuario u ON dr.clave_us = u.clave_usuario
       WHERE u.nombre_us = ?";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nickname);
$stmt->execute();
$result = $stmt->get_result();
          
          include_once 'trabajador.html';

      } elseif ($rol == 'directivo') {

          include_once 'directivo.html';

      } else {
          echo "Rol no reconocido.";
      }
  } else {
      echo "Nickname o contraseña incorrectos.";
  }



  /* ACTUALIZAR USUARIO */
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han recibido los datos necesarios del formulario
    if (isset($_POST["nombre_completo"]) && isset($_POST["correo"])) {
        // Obtener los datos enviados desde el formulario
        $nombre_completo = $_POST["nombre_completo"];
        $correo = $_POST["correo"];
        $celular = $_POST["celular"];
        $direccion = $_POST["direccion"];
        $telefono= $_POST["telefono"];

        // Aquí puedes validar los datos si es necesario

        // Incluir el archivo de conexión a la base de datos
        include_once "database.php";

               // Sentencia SQL para actualizar los datos del usuario
               $sql = "UPDATE Usuario 
               SET nombre_completo = ?, 
                   correo = ?, 
                   telefono = ?, 
                   celular = ?, 
                   direccion = ? 
               WHERE nombre_us = ?";
       $stmt = $conexion->prepare($sql);
       $stmt->bind_param("ssssss", $nombre_completo, $correo, $telefono, $celular, $direccion, $nickname);

       // Ejecutar la consulta
       if ($stmt->execute()) {
           echo "<script> alert('Datos actualizados exitosamente.');</script> ";
       } else {
           echo "<script> alert('Error al Actualizar los datos.');</script>";
       }

    } 
} else {
    echo "Acceso denegado.";
}


// SUBIR ARCHIVOS
// Verificar si se recibió un archivo y los datos del formulario
if (isset($_FILES['archivo']) && isset($_POST['nombre']) && isset($_POST['tipo'])) {
    $archivo = $_FILES['archivo'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    // Verificar si hay algún error en la subida del archivo
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        // Obtener información del archivo
        $nombreArchivo = basename($archivo['name']);
        $directorioDestino = 'uploads/';
        $rutaDestino = $directorioDestino . $nombreArchivo;

        // Mover el archivo al directorio de destino
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            // Guardar la información en la base de datos
            $sql = "INSERT INTO recurso (nombre, tipo, ruta_material) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $nombre, $tipo, $rutaDestino);

            if ($stmt->execute()) {
                echo "<script>alert('Archivo subido y guardado exitosamente.');</script>";
            } else {
                echo "<script>alert('Error al guardar la información del archivo en la base de datos.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error al mover el archivo al directorio de destino.');</script>";
        }
    } else {
        echo "<script>alert('Error al subir el archivo: " . $archivo['error'] . "');</script>";
    }
} 


  // Cerrar la sentencia
  $stmt->close();

  // Cerrar la conexión
  $conexion->close();

} else {
  echo "Por favor, ingrese su nickname y contraseña.";
}
?>



 
<footer>
<ul class="social">
<li><a href="https://twitter.com/mayursuthar2693" target="_blank"><i class="icon-twitter"></i></a></li>
<li><a href="https://www.facebook.com/mayursuthar2693" target="_blank"><i class="icon-facebook"></i></a></li>
<li><a href="https://www.linkedin.com/in/sutharmayur" target="_blank"><i class="icon-linkedin"></i></a></li>
<li><a href="https://www.pinterest.com/MayurSuthar2693/" target="_blank"><i class="icon-pinterest"></i></a></li>
<li><a href="https://plus.google.com/109916819421919014146/posts" target="_blank"><i class="icon-google-plus"></i></a></li>
<li><a href="https://www.instagram.com/mayursuthar2693/" target="_blank"><i class="icon-instagram"></i></a></li>
</ul>
</footer>
    

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>


</html>



