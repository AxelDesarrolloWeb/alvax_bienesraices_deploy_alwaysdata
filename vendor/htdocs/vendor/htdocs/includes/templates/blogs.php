<?php

use Model\Blog;

if ($_SERVER['SCRIPT_NAME'] === ['/blogs.php']) {
    $blogs = Blog::all();
} else {

    $blogs = Blog::get(3);
}
?>

<article class="entrada-blog">
    <div class="imagen">
        <img loading="lazy" src="/imagenes/<?php echo $blog->imagen; ?>" alt="anuncio">
    </div>

    <div class="texto-entrada">
        <a href="/entrada">
            <h4><?php echo $blog->titulo; ?></h4>
            <p class="informacion-meta">Escrito el: <span><?php echo $blog->creado; ?></span> por: <span><?php echo $blog->usuarioId; ?></span></p>
            <p><?php echo $blog->descripcion; ?></p>
        </a>
    </div>

</article>