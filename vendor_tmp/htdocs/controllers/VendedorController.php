<?php

namespace Controllers;

use MVC\Router;
use Model\Vendedor;
use Model\Admin;
class VendedorController
{

    public static function crear(Router $router)
    {
        // Arreglo con mensajes de errores
        $errores = Vendedor::getErrores();
        $vendedor = new Vendedor;
        $vendedores = Vendedor::all();


        // Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los atributos
            $args = $_POST['vendedor'];

            $vendedor->sincronizar($args);

            // Validación
            $errores = $vendedor->validar();


            if (empty($errores)) {
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor,
            'vendedores' => $vendedores
        ]);
    }

    public static function actualizar(Router $router)
    {

        $errores = Vendedor::getErrores();
        $vendedor = new Vendedor;
        $vendedores = Vendedor::all();
        $id = validarORedireccionar('/admin');

        // Obtener los datos del vendedor a editar...
        $vendedor = Vendedor::find($id);


        // Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los atributos
            $args = $_POST['vendedor'];

            $vendedor->sincronizar($args);

            // Validación
            $errores = $vendedor->validar();


            if (empty($errores)) {
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD']) {
            // Valida el tipo a eliminar
            $tipo = $_POST['tipo'];

            // Validar el id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                // Valida el tipo a eliminar
                $tipo = $_POST['tipo'];

                if (validarTipoContenido($tipo)) {
                    $vendedor =  Vendedor::find($id);
                    $vendedor->eliminar();

                }
            }
        }
    }
}
