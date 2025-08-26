<?php

namespace MVC;

class Router
{

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn)
    {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->rutasPOST[$url] = $fn;
    }

    // Router.php
    public function comprobarRutas()
    {

        session_start();
        $auth = $_SESSION['login'] ?? null;
        // Arrreglo de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];


        // Obtener la URL actual desde REQUEST_URI
        $urlActual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';

        // Remover el query string si existe
        $urlActual = strtok($urlActual, '?');

        // Normalizar la URL
        if ($urlActual !== '/' && substr($urlActual, -1) === '/') {
            $urlActual = rtrim($urlActual, '/');
        }

        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // Proteger las rutas
        if (in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('Location: /');
        }

        if ($fn) {
            call_user_func($fn, $this);
        } else {
            // Manejar error 404
            $this->render('paginas/error', [
                'mensaje' => 'Página no encontrada'
            ]);
        }
    }


    // Router.php
    public function render($view, $datos = [])
    {
        // Leer lo que le pasamos  a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        include_once __DIR__ . '/views/layout.php';
    }
}
