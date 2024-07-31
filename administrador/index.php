<?php
session_start();
require_once("config/bd.php");

function validarCredenciales($conexion, $usuario, $contrasenia) {
    $contrasenia = md5($contrasenia);
    $sentenciaSQL = $conexion->prepare("SELECT * FROM usuario WHERE nombre=:nombre");
    $sentenciaSQL->bindParam(':nombre', $usuario);
    $sentenciaSQL->execute();
    return $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
}

function establecerSesion($usuarioData) {
    $_SESSION['usuario'] = $usuarioData['nombre'];
    $_SESSION['nombreUsuario'] = $usuarioData['nombre'];
    $_SESSION['idusuario'] = $usuarioData['idusuario'];
    $_SESSION['correo'] = $usuarioData['correo'];
    $_SESSION['rol'] = $usuarioData['rol'];
    if ($usuarioData['rol'] == 'Administrador') {
        header("Location: inicio.php");
    } else {
        header("Location: ../index.php");
    }
}



if (isset($_POST["btnIngresar"])) {
  $mensaje = '';
    $txtUsuario = $_POST["txtusuario"];
    $txtContrasenia = $_POST["txtpass"];

    if (empty($txtUsuario) || empty($txtContrasenia)) {
      $mensaje = 'Rellene ambos campos';
  } else {
      $usuarioData = validarCredenciales($conexion, $txtUsuario, $txtContrasenia);

      if ($usuarioData !== false) {
          establecerSesion($usuarioData);
          if(isset($_POST['chkRecordar']) ){
            $exp = time() + 60 *60 * 24 * 30;
            setcookie('nombre_cookie', $usuarioData['usu_nombre'], $exp);
            setcookie('clave_cookie', $txtContrasenia, $exp); 
            setcookie('check', "checked", $exp);
          } else{
            setcookie("nombre_cookie", "", time()- 1);
            setcookie("clave_cookie", "", time()- 1);
            setcookie("check", "", time()- 1);
          }
      } else {
          $mensaje = "Error: El usuario o la contraseña son incorrectos";
      }
    }
}
?>






<!doctype html>
<html lang="en">
  <head>
    <title>Administrador</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
  <div class="container">
      <div class="row">
        <div class="col-md-4">
          <!-- ... -->
        </div>
        <div class="col-md-4">
          <br><br><br><br>
          <div class="card">
            <div class="card-header">
              Login
            </div>
            <div class="card-body">
              <?php if (isset($mensaje)) { ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $mensaje; ?>
                </div>
              <?php } ?>
              <form method="POST">
                <div class="form-group">
                  <label>Usuario</label>
                  <input type="text" class="form-control" name="txtusuario" placeholder="Ingrese el usuario">
                  <small class="form-text text-muted">No entregues tu usuario a cualquier persona.</small>
                </div>
                <div class="form-group">
                  <label>Contraseña:</label>
                  <input type="password" class="form-control" name="txtpass" placeholder="Escribe tu contraseña">
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="recordarContrasenia">
                  <label class="form-check-label" for="recordarContrasenia">Recordar Contraseña</label>
                </div>
                <button type="submit" class="btn btn-primary" name="btnIngresar">Ingresar</button>
                <button type="submit" class="btn btn-light"><a href="../index.php">Ingresar como invitado</a></button>
                <br>
                <a href="../recuperarpass.php">¿Olvidaste tu contraseña?</a>
                <label class="label bold">Si no tienes una cuenta, creála <a href="../crearusuario.php">aquí</a>  </label>
                <?php
                if (isset($_GET['message'])) {
                ?>
                  <div class="alert alert-danger" role="alert">
                    <?php
                    switch ($_GET['message']) {
                      case 'ok':
                        echo 'Por favor, revisa tu correo electrónico';
                        break;
                      case 'success_password':
                        echo 'Inicia sesión con tu nueva contraseña';
                        break;
                      case 'usuario_creado':
                        echo 'Usuario creado, ingrese con sus datos';
                        break;
                      case 'account_deleted':
                        echo 'Cuenta borrada con éxito. Para volver a ingresar, por favor loguearse.';
                        break;
                      default:
                        echo 'Algo salió mal, intenta de nuevo';
                        break;
                    }
                    ?>
                  </div>
                <?php
                }
                ?>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>