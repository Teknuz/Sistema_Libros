<?php  include("template/cabecera.php")?>

<?php 
include("administrador/config/bd.php");
$sentenciaSQL = $conexion ->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listalibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach($listalibros as $libro){ ?>
 
    <div class="col-md-3">
    <div class="card h-100"> <!-- Agregamos la clase h-100 para que la tarjeta ocupe toda la altura -->
        <img class="card-img-top custom-img" src="./img/<?php echo $libro['imagen']?>" alt="">
        <div class="card-body d-flex flex-column"> <!-- Agregamos las clases d-flex y flex-column para alinear y expandir el contenido -->
            <h4 class="card-title"><?php echo $libro['nombre']?></h4>
            <a href="https://z-library.es/" target="_blank" class="mt-auto btn btn-primary" role="button">Dónde Encontrar</a> <!-- mt-auto empuja el botón hacia la parte inferior -->
        </div>
    </div>
</div>





<?php }?>



<?php  include("template/pie.php")?>