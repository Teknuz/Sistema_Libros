<?php 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require_once("./administrador/config/bd.php");
$correo = isset($_POST["txtcorreo"]) ? $_POST["txtcorreo"] : "";
$sentenciaSQL = $conexion->prepare("SELECT * FROM usuario WHERE correo = :correo");
$sentenciaSQL->bindParam(':correo', $correo);
$sentenciaSQL->execute();
$row = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['btnRecuperar'])){
if ($sentenciaSQL->rowCount() > 0) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp-mail.outlook.com';                    
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'elrinconliterariodejuan@hotmail.com';                     
        $mail->Password   = 'Elrinconliterario1!';                               
        $mail->Port       =  587;                                    
        $mail->setFrom('elrinconliterariodejuan@hotmail.com', 'El rincon Literario de Juan');
        $mail->addAddress($correo, 'Hola');     
        $mail->isHTML(true);                               
        $mail->Subject = 'Recuperacion de Contraseña';
        $mail->CharSet = 'UTF-8';
        $mail->Body    = 'Hola, este es un correo generado para solicitar tu recuperación de contraseña, por favor, visita la página
        <a href="http://localhost/trabajo_programacion/cambiarpass.php?id='. $row['idusuario'] .'">Recuperación de contraseña</a>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; 
    
        $mail->send();
        $_SESSION['correo']  = $correo;
        header('Location: ./administrador/index.php?message=ok');
    } catch (Exception $e) {
        header('Location: ./administrador/index.php?message=error');
    }
    
} else {
    header('Location: ./administrador/index.php?message=not_found');
}
}
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Recuperación</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                            <form method="POST" action="recuperarpass.php">
                                <div class="form-group">
                            <label>Correo</label>
                                <input type="text" class="form-control" name="txtcorreo"  placeholder="Ingrese el correo">
                            <small class="form-text text-muted">Verifica un correo válido</small>
                            </div>
                            <button type="submit" class="btn btn-primary" name="btnRecuperar">Recuperar Contraseña</button>
                            <button type="button" class="btn btn-light"><a href="administrador/index.php">Volver </a></button>
                            <br>
                        </form>
                            
                            
                        </div>
                        
                    </div>

                </div>
                
            </div>
        </div>
   
</body>
</html>

