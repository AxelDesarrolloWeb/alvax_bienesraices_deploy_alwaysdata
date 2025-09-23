<main class="contenedor seccion contenido-centrado">
    <h1>Nuestro Blog</h1>

    <?php foreach($blogs as $blog): ?>
    <article class="entrada-blog">
        <div class="imagen">
            <img loading="lazy" src="/imagenes/<?php echo $blog->imagen; ?>" alt="anuncio">
        </div>

        <div class="texto-entrada">
            <a href="/entrada?id=<?php echo $blog->id; ?>">
                <h4><?php echo $blog->titulo;?></h4>
                <!-- foreach($usuarios as $usuario): ?> -->
                <p class="informacion-meta">
                    Escrito el: <span><?php echo $blog->creado;?></span> 
                    por: <span><?php 
                    // echo $usuario->nombreUsuario;
                    echo 'admin';
                    ?></span>
                </p>
                 <!-- endforeach; ?> -->
                <p><?php echo substr($blog->descripcion, 0, 100) . '...'; ?></p>
            </a>
        </div>
    </article>
    <?php endforeach; ?>
</main>