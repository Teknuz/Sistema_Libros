<?php
session_start();

require_once("./administrador/config/bd.php");

if (isset($_POST["btnRecuperar"])) { 
    $password = isset($_POST["new_password"]) ? md5($_POST["new_password"]) : "";
    $id = isset($_POST["id"]) ? $_POST["id"] : "";

    $sentenciaSQL = $conexion->prepare("UPDATE usuario SET contrasenia = :contrasenia WHERE idusuario = :idusuario");
    $sentenciaSQL->bindParam(':contrasenia', $password);
    $sentenciaSQL->bindParam(':idusuario', $id);
    $sentenciaSQL->execute();

    header('Location:./administrador/index.php?message=success_password');
}

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Recuperación</title>
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
                            Recuperar Contraseña
                        </div>
                        <div class="card-body">
                        <?php if(isset($mensaje)){ ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $mensaje;?>
                            </div>
                        <?php } ?>
                            <form method="POST" action="cambiarpass.php">
                                <div class="form-group">
                            <label>Nueva Contraseña</label>
                                <input type="text" class="form-control" name="new_password"  placeholder="">
                                <input type="hidden" class="form-control" name="id"  value="<?php echo $_GET['id'] ?>">
                            
                            </div>
                            
                            <button type="submit" class="btn btn-primary" name="btnRecuperar">Recuperar Contraseña</button>
                            <br>
                        </form>
                            
                            
                        </div>
                        
                    </div>

                </div>
                
            </div>
        </div>
   
</body>
</html>