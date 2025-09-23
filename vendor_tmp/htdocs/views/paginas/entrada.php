<!-- vista entrada.php -->
<main class="contenedor seccion contenido-centrado">
    <?php if($blog): ?>
    <h1><?php echo $blog->titulo; ?></h1>
    
    <div class="imagen-entrada">
        <img loading="lazy" src="/imagenes/<?php echo $blog->imagen; ?>" alt="imagen blog">
    </div>
    
   
    <p class="informacion-meta">
        Escrito el: <span><?php echo $blog->creado; ?></span> 
        por: <span><?php 
        // echo $usuario->nombreUsuario; 
        echo 'admin'; 
        ?></span>
    </p>
    
    
    <div class="contenido-entrada">
        <p><?php echo nl2br($blog->descripcion); ?></p>
    </div>
    <?php else: ?>
    <p>Entrada no encontrada</p>
    <?php endif; ?>
</main>