<?php

require_once("./administrador/config/bd.php");

function validarRegistro() {
    if (isset($_POST["btnIngresar"])) {
        $txtUsuario = $_POST["txtusuario"];
        $txtContrasenia = $_POST["txtpass"];
        $txtCorreo = $_POST["txtcorreo"];
        
        $mensaje = "";

        if (empty($txtUsuario) || empty($txtContrasenia) || empty($txtCorreo)) {
            $mensaje = "Favor, rellene todos los campos.";
        } else {
            $mensaje = validarNombreUsuario($txtUsuario);
            if (empty($mensaje)) {
                $mensaje = validarCorreo($txtCorreo);
            }
            if (empty($mensaje)) {
                registrarUsuario($txtUsuario, $txtContrasenia, $txtCorreo);
            }
        }

        return $mensaje;
    }

    return "";
}

function validarNombreUsuario($nombreUsuario) {
    $sentenciaSQL = $GLOBALS['conexion']->prepare("SELECT COUNT(*) AS total FROM usuario WHERE nombre = :nombre");
    $sentenciaSQL->bindParam(':nombre', $nombreUsuario);
    $sentenciaSQL->execute();
    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] > 0) {
        return "El usuario ya está registrado. Por favor, utiliza otro usuario.";
    }

    return "";
}

function validarCorreo($correo) {
    $sentenciaSQL = $GLOBALS['conexion']->prepare("SELECT COUNT(*) AS total FROM usuario WHERE correo = :correo");
    $sentenciaSQL->bindParam(':correo', $correo);
    $sentenciaSQL->execute();
    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] > 0) {
        return "El correo ya está registrado. Por favor, utiliza otro correo.";
    }

    return "";
}

function registrarUsuario($nombreUsuario, $contrasenia, $correo) {
    $rol = "Usuario";
    $hashedPassword = md5($contrasenia);
    $sentenciaSQL = $GLOBALS['conexion']->prepare("INSERT INTO usuario (nombre, correo, contrasenia, rol) VALUES (:nombre, :correo, :contrasenia, :rol);");
    $sentenciaSQL->bindParam(':nombre', $nombreUsuario);
    $sentenciaSQL->bindParam(':contrasenia', $hashedPassword);
    $sentenciaSQL->bindParam(':correo', $correo);
    $sentenciaSQL->bindParam(':rol', $rol);
    $sentenciaSQL->execute();
    header('Location: ./administrador/index.php?message=usuario_creado');
}

$mensaje = validarRegistro();
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Crear Usuario</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                
                </div>
                
                <div class="col-md-4">
                <br><br><br><br>
                    <div class="card">
                        <div class="card-header">
                            Creación de nuevo Usuario
                        </div>
                        <div class="card-body">
                        
                            <form method="POST">
                            <?php if (!empty($mensaje)) { ?>
                            <div class="alert alert-danger" role="alert">
                             <?php echo $mensaje; ?>
                            </div>
                             <?php } ?>
                                <div class="form-group">
                            <label>Usuario</label>
                                <input type="text" class="form-control" name="txtusuario"  placeholder="Ingrese el usuario">
                            <small class="form-text text-muted">No entregues tu usuario a cualquier persona.</small>
                            </div>

                            <div class="form-group">
                            <label>Contraseña:</label>
                                <input type="password"  autocomplete="new-password" class="form-control" name="txtpass" placeholder="Escribe tu contraseña">
                                <small class="form-text text-muted">Ingresa una contraseña segura.</small>
                            </div>

                            <div class="form-group">
                            <label>Correo:</label>
                                <input type="email" class="form-control" name="txtcorreo" placeholder="ejemplo@gmail.com">
                            </div>
                            

                            <button type="submit" class="btn btn-primary" name="btnIngresar">Crear usuario</button>
                            <button type="button" class="btn btn-Secondary" ><a href="administrador/index.php">Volver </a></button>
                            <br>
                            
                        </form>
                            
                            
                        </div>
                        
                    </div>

                </div>
                
            </div>
        </div>
   
  </body>
</html>
