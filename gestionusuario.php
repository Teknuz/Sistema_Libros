<?php include("template/cabecera.php") ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<?php
$habilitarCampos = false;
if (isset($_SESSION['nombreUsuario'])) {
    $txtNombre = $_SESSION['nombreUsuario'];
    $txtID = $_SESSION['idusuario'];
    $txtCorreo = $_SESSION['correo'];
} else {
}
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
include("administrador/config/bd.php");


if ($accion === "Modificar") {
    $txtNuevoNombre = $_POST["txtNombre"];
    $txtNuevoCorreo = $_POST["txtCorreo"];

    $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total FROM usuario WHERE correo = :nuevoCorreo");
    $sentenciaSQL->bindParam(':nuevoCorreo', $txtNuevoCorreo);
    $sentenciaSQL->execute();
    $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total1 FROM usuario WHERE nombre = :nuevoNombre");
    $sentenciaSQL->bindParam(':nuevoNombre', $txtNuevoNombre);
    $sentenciaSQL->execute();
    $resultadoNombre = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] > 0 && $txtNuevoCorreo != $txtCorreo) {
        $mensaje = "El correo ya está registrado por otro usuario. Por favor, utiliza otro correo.";
    } elseif ($resultadoNombre['total1'] > 0 && $txtNuevoNombre != $txtNombre) {
        $mensaje = "El nombre de usuario ya está registrado por otro usuario. Por favor, utiliza otro nombre de usuario.";
    } else {
        $sentenciaSQL = $conexion->prepare("UPDATE usuario SET nombre = :nuevoNombre, correo = :nuevoCorreo WHERE idusuario = :idUsuario");
        $sentenciaSQL->bindParam(':nuevoNombre', $txtNuevoNombre);
        $sentenciaSQL->bindParam(':nuevoCorreo', $txtNuevoCorreo);
        $sentenciaSQL->bindParam(':idUsuario', $txtID);
        $sentenciaSQL->execute();

        session_start();
        $_SESSION['nombreUsuario'] = $txtNuevoNombre;
        $_SESSION['correo'] = $txtNuevoCorreo;

        header("Location: gestionusuario.php?message=success_update");
    }

}
if ($accion === "Cancelar") {
    header("Location:gestionusuario.php");
}
if ($accion === "Borrar") {

        $sentenciaSQL = $conexion->prepare("DELETE FROM usuario WHERE idusuario = :idUsuario");
        $sentenciaSQL->bindParam(':idUsuario', $txtID);
        $sentenciaSQL->execute();

        $errorInfo = $sentenciaSQL->errorInfo();
    if ($errorInfo[0] !== '00000') {
        die("Error en la consulta SQL: " . $errorInfo[2]);
    }
        session_destroy();

        header("Location: ./administrador/index.php?message=account_deleted");
    }




$sentenciaSQL = $conexion->prepare("SELECT * FROM usuario");
$sentenciaSQL->execute();
$listausuarios = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de su Usuario
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group hidden" style="display: hidden;">
                    <input type="hidden" class="form-control" value="" name="txtID" id="txtID" placeholder="ID" readonly
                        required>
                </div>

                <?php if (isset($mensaje1)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $mensaje; ?>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="">Nombre de Usuario: </label>
                    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre"
                        id="txtNombre" placeholder="Nombre del Usuario">
                </div>

                <?php if (isset($mensaje)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $mensaje; ?>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="">Correo: </label>
                    <input type="email" required class="form-control" value="<?php echo $txtCorreo; ?>" name="txtCorreo"
                        id="txtCorreo" placeholder="Ingrese su Correo">
                </div>

                <div class="form-group">
                    <label for="">Contraseña: </label>
                    <input type="password" required <?php echo ($habilitarCampos) ? '' : 'readonly'; ?>
                        class="form-control" value="<?php echo $txtContrasenia; ?>" name="txtcontrasenia"
                        id="txtcontrasenia" placeholder="Ingrese su contraseña">
                    <a href="autocambiopass.php" style="padding-left:40px">Cambiar Contraseña </a>
                </div>


                <div class="btn-group " role="group" aria-label="">
                    <button type="submit" name="accion" value="Modificar" class="btn btn-primary">Modificar</button>
                    <button type="submit" name="accion" value="Cancelar" class="btn btn-secondary">Cancelar</button>
                    
                </div>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmDeleteModal">
    Borrar tu cuenta
</button>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmación de borrado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas borrar tu cuenta? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form method="POST" action="gestionusuario.php">
                    <button type="submit" class="btn btn-danger" name="accion" value="Borrar">Borrar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include("template/pie.php") ?>


            
                <?php if (isset($_GET['message'])) {

                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                        switch ($_GET['message']) {
                            case 'success_password':
                                echo 'Contraseña Cambiada';
                                break;
                            case 'success_update':
                                echo 'Se modificaron exitosamente los datos';
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

                <div class="modal-body"></div>
            </form>
            
        
        </div>
    </div>
</div>

</div>

<script>
    $(document).ready(function() {
        $("#confirmDeleteButton").click(function() {
            $('#confirmDeleteModal').modal('hide');
        });
    });
</script>


<?php include("template/pie.php") ?>