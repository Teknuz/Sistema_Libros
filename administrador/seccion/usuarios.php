<?php
require_once("../template/cabecera.php");
include("../config/bd.php");

function agregarUsuario($conexion, $nombre, $correo, $contrasenia, $roles) {
    $hashedPassword = md5($contrasenia);

    $rolesCadena = implode(", ", $roles); 
    $sentenciaSQL = $conexion->prepare("INSERT INTO usuario (nombre, correo, contrasenia, rol) VALUES (:nombre, :correo, :contrasenia, :rol);");
    $sentenciaSQL->bindParam(':nombre', $nombre);
    $sentenciaSQL->bindParam(':correo', $correo);
    $sentenciaSQL->bindParam(':contrasenia', $hashedPassword);
    $sentenciaSQL->bindParam(':rol', $rolesCadena); // Almacena la cadena de roles
    $sentenciaSQL->execute();
}

function modificarUsuario($conexion, $id, $nombre, $correo, $rol) {
    $sentenciaSQL = $conexion->prepare("UPDATE usuario SET nombre = :nombre, correo = :correo, rol = :rol WHERE id = :id");
    $sentenciaSQL->bindParam(':nombre', $nombre);
    $sentenciaSQL->bindParam(':correo', $correo);
    $sentenciaSQL->bindParam(':id', $id);
    $sentenciaSQL->bindParam(':rol', $rol);
    $sentenciaSQL->execute();
}

function seleccionarUsuario($conexion, $id) {
    $sentenciaSQL = $conexion->prepare("SELECT * FROM usuario WHERE idusuario = :id");
    $sentenciaSQL->bindParam(':id', $id);
    $sentenciaSQL->execute();
    return $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
}

function borrarUsuario($conexion, $id) {
    $sentenciaSQL = $conexion->prepare("DELETE FROM usuario WHERE idusuario = :id");
    $sentenciaSQL->bindParam(':id', $id);
    $sentenciaSQL->execute();
}


$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtContrasenia = (isset($_POST['txtcontrasenia'])) ? $_POST['txtcontrasenia'] : "";
$txtCorreo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
$roles = (isset($_POST["roles"])) ? $_POST["roles"] : array();

switch ($accion) {
    case "Agregar":
        $hashedPassword = md5($txtContrasenia);
        $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total FROM usuario WHERE correo = :correo");
        $sentenciaSQL->bindParam(':correo', $txtCorreo);
        $sentenciaSQL->execute();
        $resultado = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $sentenciaSQL = $conexion->prepare("SELECT COUNT(*) AS total1 FROM usuario WHERE nombre = :nombre");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->execute();
        $resultadoNombre = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        if ($resultado['total'] > 0) {
            $mensaje = "El correo ya está registrado. Por favor, utiliza otro correo.";
        } elseif ($resultadoNombre['total1'] > 0) {
            $mensaje1 = 'El usuario ya está registrado. Por favor, utiliza otro usuario';
        } else {
            agregarUsuario($conexion, $txtNombre, $txtCorreo, $txtContrasenia, $roles);
            header("Location: usuarios.php");
        }
        break;

    case "Modificar":
        modificarUsuario($conexion, $txtID, $txtNombre, $txtCorreo, $roles);
        header("Location: usuarios.php");
        break;

    case "Cancelar":
        header("Location: usuarios.php");
        break;

    case "Seleccionar":
        $usuario = seleccionarUsuario($conexion, $txtID);
        $txtNombre = $usuario['nombre'];
        $txtCorreo = $usuario['correo'];
        $roles = explode(", ", $usuario['rol']);
        break;

    case "Borrar":
        borrarUsuario($conexion, $txtID);
        header("Location: usuarios.php");
        break;
}

$sentenciaSQL = $conexion->prepare("SELECT * FROM usuario");
$sentenciaSQL->execute();
$listausuarios = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de Usuario
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="txtID">ID:</label>
                    <input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID"
                        placeholder="ID" readonly required>
                </div>
                <?php if (isset($mensaje1)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $mensaje1; ?>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="txtNombre">Nombre de Usuario:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre"
                        id="txtNombre" placeholder="Nombre del Usuario">
                </div>
                <?php if (isset($mensaje)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $mensaje; ?>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="txtCorreo">Correo:</label>
                    <input type="email" required class="form-control" value="<?php echo $txtCorreo; ?>" name="txtCorreo"
                        id="txtCorreo" placeholder="Ingrese su Correo">
                </div>
                <div class="form-group">
                    <label for="txtContrasenia">Contraseña:</label>
                    <input type="password" autocomplete="new-password" required class="form-control" value="<?php echo $txtContrasenia; ?>"
                        <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> name="txtcontrasenia"
                        id="txtcontrasenia" placeholder="">
                    <br>
                    <div class="form-check">
                        <input type="checkbox"  kclass="form-check-input" name="roles[]" value="Usuario"
                            <?php if (in_array('Usuario', $roles)) echo 'checked'; ?>>
                        <label class="form-check-label">Usuario &nbsp; &nbsp; &nbsp;</label>
                        <input type="checkbox" class="form-check-input" name="roles[]" value="Administrador"
                            <?php if (in_array('Administrador', $roles)) echo 'checked'; ?>>
                        <label class="form-check-label">Administrador</label>
                    </div>
                </div>
        </div>
        <div class="btn-group" role="group" aria-label="">
            <button type="submit" name="accion"
                <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-success">Agregar
            </button>
            <button type="submit" name="accion"
                <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Modificar"
                class="btn btn-warning">Modificar</button>
            <button type="submit" name="accion"
                <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Cancelar"
                class="btn btn-info">Cancelar</button>
        </div>
        </form>
    </div>
</div>
</div>
<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listausuarios as $usuario) { ?>
            <tr>
                <td><?php echo $usuario['idusuario'] ?></td>
                <td><?php echo $usuario['nombre'] ?></td>
                <td><?php echo $usuario['correo'] ?></td>
                <td><?php echo $usuario['rol'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $usuario['idusuario'] ?>">
                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />
                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../template/pie.php") ?>
