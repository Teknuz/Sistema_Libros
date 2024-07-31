<?php
require_once("./administrador/config/bd.php");
session_start();
if (isset($_POST["btnRecuperar"])) {
    $old_password = isset($_POST["old_password"]) ? md5($_POST["old_password"]) : "";
    $new_password = isset($_POST["new_password"]) ? md5($_POST["new_password"]) : "";
    $id = $_SESSION['idusuario'];
    $sentenciaSQL = $conexion->prepare("SELECT contrasenia FROM usuario WHERE idusuario = :idusuario");
    $sentenciaSQL->bindParam(':idusuario', $id);
    $sentenciaSQL->execute();
    $row = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
    
    if ($row && $old_password === $row['contrasenia']) {
        $sentenciaSQL = $conexion->prepare("UPDATE usuario SET contrasenia = :contrasenia WHERE idusuario = :idusuario");
        $sentenciaSQL->bindParam(':contrasenia', $new_password);
        $sentenciaSQL->bindParam(':idusuario', $id);
        $sentenciaSQL->execute();

        header('Location:gestionusuario.php?message=success_password');
    } else {
        $mensaje = "Contraseña antigua incorrecta ";
    }
}

?>



<!doctype html>
<html lang="en">
  <head>
    <title>Cambio de Contraseña</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                            Recuperar Contraseña
                        </div>
                        <div class="card-body">
                        <?php if(isset($mensaje)){ ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $mensaje;?>
                            </div>
                        <?php } ?>
                            <form method="POST" action="autocambiopass.php">
                            <div class="form-group">
                            <label>Antigua Contraseña</label>
                                <input type="password" class="form-control" name="old_password"  placeholder="">        
                            </div>
                                <div class="form-group">
                            <label>Nueva Contraseña</label>
                                <input type="password" class="form-control" name="new_password"  placeholder="">
                            </div>
                            <input type="hidden" class="form-control" name="id"  value="<?php echo $id; ?>">
                           
                            
                            <button type="submit" class="btn btn-primary" name="btnRecuperar">Cambiar</button>
                            &nbsp;&nbsp;<button type="submit" class="btn btn-primary" ><a href="gestionusuario.php">Volver </a></button>
                            <br>
                        </form>
                            
                            
                        </div>
                        
                    </div>

                </div>
                
            </div>
        </div>
   
</body>
</html>