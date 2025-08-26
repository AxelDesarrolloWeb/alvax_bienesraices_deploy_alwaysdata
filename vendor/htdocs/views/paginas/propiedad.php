<main class="contenedor seccion contenido-centrado">
    <h1><?php echo $propiedad->titulo;?></h1>

        <img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="Imagen destacada de la propiedad" loading="lazy">    

    <div class="resumen-propiedad">
        <p class="precio">$<?php echo $propiedad->precio; ?></p>
        <ul class="iconos-caracteristicas">
            <li>
                <img class="icono" src="build/img/icono_wc.svg" alt="Icono wc" loading="lazy">
                <p><?php echo $propiedad->wc; ?></p>
            </li>
            <li>
                <img class="icono" src="build/img/icono_estacionamiento.svg" alt="Icono Estacionamiento"
                    loading="lazy">
                <p><?php echo $propiedad->estacionamiento; ?></p>
            </li>
            <li>
                <img class="icono" src="build/img/icono_dormitorio.svg" alt="Icono Habitaciones" loading="lazy">
                <p><?php echo $propiedad->habitaciones; ?></p>
            </li>
        </ul>

        <p><?php echo $propiedad->descripcion; ?></p>

    </div> <!-- .contenido-anuncio -->
</main>