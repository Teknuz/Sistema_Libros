<?php include("../template/cabecera.php") ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php'; ?>
<?php 
include("../config/bd.php");

function obtenerListaUsuarios($conexion) {
    $sentenciaSQL = $conexion->prepare("SELECT correo FROM usuarios");
    $sentenciaSQL->execute();
    $usuarios = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

    $listaCorreos = array();
    foreach ($usuarios as $usuario) {
        $listaCorreos[] = $usuario['correo'];
    }

    return $listaCorreos;
}
function agregarLibro($conexion, $txtNombre, $txtImagen) {
    if (empty($txtNombre)) {
        return "Rellene ambos campos";
    } else {
        $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre, :imagen);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);

        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : 'imagen.jpg';
        $tmpimagen = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpimagen != "") {
            move_uploaded_file($tmpimagen, "../../img/" . $nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();

        header("Location:productos.php");
    }
}

function modificarLibro($conexion, $txtID, $txtNombre, $txtImagen) {
    if (empty($txtNombre)) {
        return "Rellene ambos campos";
    } else {
        $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        if ($txtImagen != "") {
            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : 'imagen.jpg';
            $tmpimagen = $_FILES["txtImagen"]["tmp_name"];
            move_uploaded_file($tmpimagen, "../../img/" . $nombreArchivo);

            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
                if (file_exists("../../img/" . $libro["imagen"])) {
                    unlink("../../img/" . $libro["imagen"]);
                }
            }

            $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        }

        header("Location:productos.php");
    }
}

function eliminarLibro($conexion, $txtID) {
    if (empty($txtID)) {
        return "Rellene los campos";
    } else {
        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
            if (file_exists("../../img/" . $libro["imagen"])) {
                unlink("../../img/" . $libro["imagen"]);
            }
        }

        $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id;");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        header("Location:productos.php");
    }
}

function obtenerLibros($conexion) {
    $sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
    $sentenciaSQL->execute();
    return $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
}

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

$mensaje = "";
switch ($accion) {
    case "Agregar":
            $mensaje = agregarLibro($conexion, $txtNombre, $txtImagen);
            
            if ($mensaje === "") {
                // Nuevo producto agregado con éxito, ahora notificaremos a todos los usuarios.
                $listaUsuarios = obtenerListaUsuarios($conexion);
                $nuevoProducto = $txtNombre; // Obtén el nombre del nuevo producto desde algún lugar.
            
                foreach ($listaUsuarios as $usuario) {
                    $correoDestino = $usuario;
                    $asunto = "Nuevo producto agregado";
                    $mensaje = 'Hola, hemos agregado un nuevo producto a nuestro catálogo: ' . $nuevoProducto . '. ¡Compruébalo!';
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp-mail.outlook.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'elrinconliterariodejuan@hotmail.com';
                        $mail->Password = 'Elrinconliterario1!';
                        $mail->Port = 587;
        
                        $mail->setFrom('elrinconliterariodejuan@hotmail.com', 'El rincon Literario de Juan');
                        $mail->addAddress($correoDestino, 'Hola, nuevo producto!');
                        $mail->isHTML(true);
                        $mail->Subject = $asunto;
                        $mail->CharSet = 'UTF-8';
                        $mail->Body = $mensaje;
                        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
                        $mail->send();
                    } catch (Exception $e) {
                        // Manejo de errores
                    }
                }
            }
            
            break;
    
        

    case "Modificar":
        $mensaje = modificarLibro($conexion, $txtID, $txtNombre, $txtImagen);
        break;
    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];
        break;
    case "Borrar":
        $mensaje = eliminarLibro($conexion, $txtID);
        break;
}

$listalibros = obtenerLibros($conexion);

?>

<div class="col-md-5" >
    <div class="card">
        <div class="card-header">
            Datos de Libro
        </div>

        <div class="card-body">

    <form method="POST" enctype="multipart/form-data">

        <div class = "form-group">
        <label for="">ID: </label>
        <input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID" readonly required>
        </div>

    <div class = "form-group">
    <label for="">Nombre: </label>
    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del ID">
    </div>

    <div class = "form-group">
    <label for="">Imagen: </label>
    <br/>

    <?php if($txtImagen != ""){?>
        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen?>" width="50"  alt="">


        <?php }?>

    <input type="file"  class="form-control"  name="txtImagen" id="txtImagen" placeholder="ID">
    </div>
    
        <div class="btn-group" role="group" aria-label="">
        <button type="submit" name="accion" <?php echo ($accion =="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
        <button type="submit" name="accion" <?php echo ($accion !="Seleccionar")?"disabled":""; ?> value="Modificar"class="btn btn-warning">Modificar</button>
        <button type="submit" name="accion" <?php echo ($accion !="Seleccionar")?"disabled":""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
        </div>


    </form>
    
        </div>

      
    </div>
    
    


</div>

<div class="col-md-7">

    <table class="table table-bordered" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach($listalibros as $libro){ ?>
            <tr>
                <td><?php echo $libro['id']?></td>
                <td><?php echo $libro['nombre']?></td>

                <td>
                    <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']?>" width="50"  alt="">
                </td>


                <td>
                <form  method="post">
                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']?>">

                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>


                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>



                </form>



                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>

</div>
<?php include("../template/pie.php") ?>