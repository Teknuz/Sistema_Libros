<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio web</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body> 
    
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">
        <img src="./img/logo.jpg" style="width:75px" alt="Logo">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Inicio</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="productos.php">Libros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="nosotros.php">Nosotros</a>
        </li>
    </ul>
    <?php if (isset($_SESSION['nombreUsuario'])) { ?>
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
        <div class="d-flex align-items-center"> 
            <img src="./img/user.ico" alt="Logo" class="mr-2"> 
            <a class="nav-link" href="gestionusuario.php">
                <?php echo $_SESSION['nombreUsuario']; ?>
            </a>
        </div>
    </li>
    
            <li class="nav-item">
                <a class="nav-link ml-auto" href="cerrar.php">Cerrar sesión</a>
            </li>
        </ul>
    <?php } else { ?>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="administrador/index.php">Iniciar Sesión</a>
            </li>
        </ul>
    <?php } ?>
</nav>


<style>
    .navbar a {
        font-size: 18px; /* Tamaño de fuente deseado */
        margin-right: 20px; /* Espacio entre enlaces */
    }
</style>

   <div class="container">
    <br>
    <div class="row">