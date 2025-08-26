<?php

namespace Model;


class Admin extends ActiveRecord
{
    // Base DE DATOS
    // La tabla de la base de datos que se va a utilizar para este modelo es 'usuarios'
    protected static $tabla = 'usuarios';
    // Las columnas que se van a utilizar de esta tabla son: id, email, password, nombreUsuario
    protected static $columnasDB = ['id', 'email', 'password', 'nombreUsuario'];

    // Propiedades del modelo
    public $id;
    public $email;
    public $password;
    public $nombreUsuario;
    public $autenticado;
    
    // Constructor del modelo
    // Se encarga de asignar los valores pasados como argumentos a las propiedades del modelo
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->nombreUsuario = $args['nombreUsuario'] ?? '';
    }

    // Método para validar el modelo
    // Verifica que los campos obligatorios estén llenos y que el email sea válido
    public function validar()
    {
        if (!$this->email) {
            self::$errores[] = "El Email del usuario es obligatorio";
        }
        if (!$this->password) {
            self::$errores[] = "El Password del usuario es obligatorio";
        }
        return self::$errores;
    }

    // Método para verificar si el usuario existe en la base de datos
    public function existeUsuario()
    {
        // Consulta a la base de datos para buscar el usuario por su email
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        // Verificar si el usuario existe
        if (!$resultado->num_rows) {
            self::$errores[] = 'El Usuario No Existe';
            return;
        }

        return $resultado;
    }

    // Método para verificar si el password es correcto
    public function verificarPassword($resultado)
    {
        $usuario = $resultado->fetch_object();

        $this->autenticado = password_verify( $this->password, $usuario->password );

        if(!$this->autenticado) {
            self::$errores[] = 'El Password es Incorrecto';
            return;
        }
    }

    // Método para autenticar al usuario
    // Llena el arreglo de session con los datos del usuario y redirige a la página de inicio de la sección de administración
    public function autenticar()
    {
        session_start();
        // Llenar el arreglo de session
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] = true;

        header('Location: /admin');
    }

    public static function getNombreUsuario($id)
    {
        $query = "SELECT u.nombreUsuario
                FROM blogs b
                INNER JOIN usuarios u ON b.usuarioId = u.id
                WHERE b.id = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc()['nombreUsuario'];
    }
}
