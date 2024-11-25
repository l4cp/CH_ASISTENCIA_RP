<?php
session_start();

// Verifica si se ha enviado el formulario
if (!empty($_POST["btningresar"])) {
    // Muestra los datos recibidos para depuración (puedes eliminarlo en producción)


    if (!empty($_POST["usuarme"]) && !empty($_POST["password"])) {
        // Capturar los valores ingresados por el usuario
        $usuario = $_POST["usuarme"];
        $password = md5($_POST["password"]); // Codifica la contraseña con MD5

        // Incluye el archivo de conexión


        require   '../../modelo/conexion.php';


        // Prepara la consulta
        $sql = $conexion->prepare("SELECT * FROM Usuarios WHERE usuarme = ? AND password = ?");
        $sql->bind_param("ss", $usuario, $password);
        $sql->execute();
        $result = $sql->get_result();

        // Verifica si se encontró algún registro
        if ($datos = $result->fetch_object()) {
            // Almacena la información en la sesión
            $_SESSION["nombre"] = $datos->nombre_usuario;
            $_SESSION["apellido"] = $datos->apellido_usuario;
            $_SESSION["id"] = $datos->usuario_id;

            // Redirige al inicio
            header("Location: ../inicio.php");
            exit(); // Asegura que no se ejecute más código después de la redirección
        } else {
            echo "<div class='alert alert-danger'>El usuario no existe</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Los campos están vacíos</div>";
    }
}
