<?php

namespace Controllers;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use MVC\Router;
use Model\Propiedad;
use Model\Blog;
use Model\Admin;

class PaginasController
{
    // En PaginasController
    public static function index(Router $router)
    {
        $propiedades = Propiedad::get(3);
        $blogs = Blog::getAllWithUsers(3); // Obtener 3 blogs con usuarios
        $inicio = true;
        $usuario = new Admin;
        $usuarios = Admin::all();

        $router->render('paginas/index', [
            'inicio' => $inicio,
            'propiedades' => $propiedades,
            'usuarios' => $usuarios,
            'usuario' => $usuario,
            'blogs' => $blogs // Pasar blogs a la vista
        ]);
    }

    public static function nosotros(Router $router)
    {
        $router->render('paginas/nosotros', []);
    }
    public static function propiedades(Router $router)
    {
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => Propiedad::all()
        ]);
    }
    public static function propiedad(Router $router)
    {
        $id = validarORedireccionar('/propiedades');


        // Buscar la propiedad por su id
        $propiedad = Propiedad::find($id);

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /');
        }

        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => Propiedad::find($_GET['id'])
        ]);
    }
    // En PaginasController
    public static function blog(Router $router)
    {
        $blogs = Blog::getAllWithUsers(); // Obtener todos los blogs
        $router->render('paginas/blog', [
            'blogs' => $blogs // Pasar blogs a la vista
        ]);
    }
    public static function entrada(Router $router)
    {
        $router->render('paginas/entrada');
    }


    public static function contacto(Router $router)
    {

        $mensaje = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear una instancia de phpmailer

            $respuestas = $_POST['contacto'];

            $mail = new PHPMailer(true);

            // Configuración del servidor de correo
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
            $mail->SMTPSecure = 'tls';

            // Configuración del correo
            $mail->setFrom('alvaxG@example.com');
            $mail->addAddress('alvaxG@example.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';


            // Contenido del correo
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo mensaje</p>';
            $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . '</p>';

            // Enviar de forma condicional algunos campos de email o teléfono
            if ($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligió ser contactado por teléfono</p>';
                $contenido .= '<p>Telefono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= '<p>Fecha contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= '<p>Hora contacto: ' . $respuestas['hora'] . '</p>';
            } else {
                $contenido .= '<p>Eligió ser contactado por email</p>';
                $contenido .= '<p>Email: ' . $respuestas['email'] . '</p>';
            }


            $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
            $contenido .= '<p>Precio o presupuesto: $' . $respuestas['presupuesto'] . '</p>';
            $contenido .= '<p>Prefiere ser contactado por: ' . $respuestas['contacto'] . '</p>';

            $contenido .= '</html>';
            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es un texto alternativo sin HTML';


            if ($mail->send()) {

                // Enviar el email
                $mensaje = 'Formulario enviado correctamente';
            } else {
                $mensaje = "El formulario no se puedo enviar...";
            }
        }
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}
