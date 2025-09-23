<fieldset>
    <legend>Información General</legend>

    <label for="titulo">Titulo:</label>
    <input type="text" id="titulo" name="blog[titulo]" placeholder="Titulo blog" value="<?php echo s( $blog->titulo ); ?>">

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="blog[imagen]">

    <?php if($blog->imagen) { ?>
        <img src="/imagenes/<?php echo $blog->imagen ?>" class="imagen-small">
    <?php } ?>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="blog[descripcion]"><?php echo s($blog->descripcion); ?></textarea>

</fieldset>
