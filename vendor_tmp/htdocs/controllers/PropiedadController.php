<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Model\Blog;

use Intervention\Image\ImageManager as Image;
use Intervention\Image\Drivers\Gd\Driver;
use Model\Admin;

class PropiedadController
{

    public static function index(Router $router)
    {
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $blogs = Blog::all();
        // Obtener la entrada de blog correspondiente
        $usuario = new Admin;
        $usuarios = Admin::all();

        // Muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/index', [
            'propiedades' => $propiedades,
            'vendedores' => $vendedores,
            'blogs' => $blogs,
            'usuarios' => $usuarios,
            'usuario' => $usuario,
            'resultado' => $resultado
        ]);
    }

    public static function crear(Router $router)
    {

        $errores = Propiedad::getErrores();
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        $blogs = Blog::all();

        // Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /** Crea una nueva instancia */
            $propiedad = new Propiedad($_POST['propiedad']);

            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";


            // Setear la imagen
            // Realiza un resize a la imagen con intervention
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $manager = new Image(Driver::class);
                $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            // Validar
            $errores = $propiedad->validar();
            if (empty($errores)) {

                // Crear la carpeta para subir imagenes
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guarda la imagen en el servidor
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);

                // Guarda en la base de datos
                $resultado = $propiedad->guardar();

                if ($resultado) {
                    header('location: /propiedades');
                }
            }
        }

        $router->render('propiedades/crear', [
            'errores' => $errores,
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'blogs' => $blogs
        ]);
    }

    public static function actualizar(Router $router)
    {

        $id = validarORedireccionar("/admin");

        $propiedad = Propiedad::find($id);

        $vendedores = Vendedor::all();

        $blogs = Blog::all();
        $errores = Propiedad::getErrores();


        // Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los atributos
            $args = $_POST['propiedad'];

            $propiedad->sincronizar($args);


            // Validación
            $errores = $propiedad->validar();

            // Subida de archivos
            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            if ($_FILES['propiedad']['tmp_name']['imagen']) {

                $manager = new Image(Driver::class);
                $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            // Guardar los cambios si no hay errores
            if (empty($errores)) {
                // Crear la carpeta para imágenes si no existe
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guardar la imagen en el servidor si se subió una nueva
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    $imagen->toJpeg()->save(CARPETA_IMAGENES . $nombreImagen);
                }

                // Guardar en la base de datos
                $propiedad->guardar();
                header('Location: /admin?resultado=2');
                exit;
            }
        }


        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores,
            'blogs' => $blogs

        ]);
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $tipo = $_POST['tipo'];

            // peticiones validas
            if (validarTipoContenido($tipo)) {
                $id = $_POST['id'];
                $id = filter_var($id, FILTER_VALIDATE_INT);

                // Comparar para saber que eliminar
                if ($tipo === 'propiedad') {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                } 
            }
        }
    }
}

