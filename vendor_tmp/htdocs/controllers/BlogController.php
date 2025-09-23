<?php

namespace Controllers;

use MVC\Router;
use Model\Blog;
use Model\Admin;

use Intervention\Image\ImageManager as Image;
use Intervention\Image\Drivers\Gd\Driver;

class BlogController
{

    public static function index(Router $router)
    {
        $pagina = $_GET['pagina'] ?? 1;
        $porPagina = 5;
        $total = Blog::count();
        $blogs = Blog::paginar($porPagina, $pagina);
        $usuario = new Admin;
        $usuarios = Admin::all();

        $router->render('paginas/blog', [
            'blogs' => $blogs,
            'paginas' => ceil($total / $porPagina),
            'pagina_actual' => $pagina,
            'usuarios' => $usuarios,
            'usuario' => $usuario
        ]);
    }

    public static function crear(Router $router)
    {

        $errores = Blog::getErrores();
        $blog = new Blog;
        $blogs = Blog::all();

        // Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /** Crea una nueva instancia */
            $blog = new Blog($_POST['blog']);

            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";


            // Setear la imagen
            // Realiza un resize a la imagen con intervention
            if ($_FILES['blog']['tmp_name']['imagen']) {
                $manager = new Image(Driver::class);
                $imagen = $manager->read($_FILES['blog']['tmp_name']['imagen'])->cover(800, 600);
                $blog->setImagen($nombreImagen);
            }

            // Validar
            $errores = $blog->validar();
            if (empty($errores)) {

                // Crear la carpeta para subir imagenes
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guarda la imagen en el servidor
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);

                // Guarda en la base de datos
                $resultado = $blog->guardar();

                if ($resultado) {
                    header('location: /blogs');
                }
            }
        }

        $router->render('blogs/crear', [
            'errores' => $errores,
            'blog' => $blog,
            'Blogs' => $blogs,
            'blogs' => $blogs
        ]);
    }

    public static function actualizar(Router $router)
    {

        $id = validarORedireccionar("/admin");

        $blog = Blog::find($id);

        $blogs = Blog::all();

        $blogs = Blog::all();
        $errores = Blog::getErrores();


        // Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los atributos
            $args = $_POST['blog'];

            $blog->sincronizar($args);


            // Validación
            $errores = $blog->validar();

            // Subida de archivos
            // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            if ($_FILES['blog']['tmp_name']['imagen']) {

                $manager = new Image(Driver::class);
                $imagen = $manager->read($_FILES['blog']['tmp_name']['imagen'])->cover(800, 600);
                $blog->setImagen($nombreImagen);
            }

            // Guardar los cambios si no hay errores
            if (empty($errores)) {
                // Crear la carpeta para imágenes si no existe
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guardar la imagen en el servidor si se subió una nueva
                if ($_FILES['blog']['tmp_name']['imagen']) {
                    $imagen->toJpeg()->save(CARPETA_IMAGENES . $nombreImagen);
                }

                // Guardar en la base de datos
                $blog->guardar();
                header('Location: /admin?resultado=2');
                exit;
            }
        }


        $router->render('/blogs/actualizar', [
            'blog' => $blog,
            'errores' => $errores,
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
                if ($tipo === 'blog') {
                    $blog = Blog::find($id);
                    $blog->eliminar();
                }
            }
        }
    }

    // En BlogController
    public static function entrada(Router $router)
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /blog');
            exit;
        }

        $blog = Blog::find($id);

        if (!$blog) {
            header('Location: /blog');
            exit;
        }

        $usuario = new Admin;
        $usuarios = Admin::all();
        $usuario = Admin::getNombreUsuario($id); 
        $router->render('paginas/entrada', [
            'blog' => $blog,
            'nombreUsuario' => $blog,
            'usuarios' => $usuarios,
            // Comentado porque falta solucionar tema muestra de 1 solo usuario(el logeado y no todos)
            // 'usuario' => $usuario->getNombreUsuario() 
        ]);
    }
}
