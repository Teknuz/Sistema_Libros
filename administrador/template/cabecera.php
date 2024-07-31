<?php
session_start();
if (!isset($_SESSION["nombreUsuario"])) {
    header("Location: ../index.php");
} else {
    $nombreusuario = $_SESSION["nombreUsuario"];
    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "Administrador") {
        header("Location: ../index.php");
        exit; // Asegúrate de salir del script después de redirigir.
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <title>Administrador</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
  </head>
  <body>
    <?php  $url = "http://".$_SERVER['HTTP_HOST']."/trabajo_programacion";?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo $url; ?>/administrador/inicio.php">Administrador del sitio web</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/inicio.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/seccion/productos.php">Libros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/seccion/usuarios.php">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>/administrador/seccion/cerrar.php">Cerrar Sesión</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url; ?>">Ver sitio web</a>
            </li>
        </ul>
    </div>
</nav>
        <div class="container">
        <br> 
            <div class="row">
            