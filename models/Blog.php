<?php
// ...existing code...
namespace Model;

class Blog extends ActiveRecord {
    protected static $tabla = 'blogs'; // o el nombre real de la tabla
    protected static $columnasDB = ['id','titulo','descripcion','imagen','creado','usuarioId'];

    public $id;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $creado;
    public $usuarioId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->creado = $args['creado'] ?? date('Y-m-d');
        $this->usuarioId = $args['usuarioId'] ?? 2;
    }

    public function validar()
    {

        if (!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }

        if (strlen($this->descripcion) < 50) {
            self::$errores[] = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
        }

        if (!$this->id) {
            $this->validarimagen();
        }
        return self::$errores;
    }

    public function validarimagen()
    {
        if (!$this->imagen) {
            self::$errores[] = 'La Imagen es Obligatoria';
        }
    }


     public function getNombreUsuario()
     {
         $query = "SELECT u.nombreUsuario
                 FROM blogs b
                 INNER JOIN usuarios u ON b.usuarioId = u.id
                 WHERE b.id = ?";

         $stmt = self::$db->prepare($query);
         $stmt->bind_param("i", $this->id);
         $stmt->execute();
         $resultado = $stmt->get_result();

         return $resultado->fetch_assoc()['nombreUsuario'];
     }

     
     public static function getAllWithUsers($limite = null)
     {
         $query = "SELECT b.*, u.nombreUsuario 
               FROM blogs b
               INNER JOIN usuarios u ON b.usuarioId = u.id";

         if ($limite) {
             $query .= " LIMIT " . $limite;
         }

         $resultado = self::consultarSQL($query);
         return $resultado;
     }

    // En Blog.php
    public static function paginar($porPagina, $pagina)
    {
        $offset = ($pagina - 1) * $porPagina;
        $query = "SELECT b.*, u.nombreUsuario 
              FROM blogs b
              INNER JOIN usuarios u ON b.usuarioId = u.id
              LIMIT {$porPagina} OFFSET {$offset}";

        return self::consultarSQL($query);
    }

    public static function count()
    {
        $query = "SELECT COUNT(*) as total FROM blogs";
        $resultado = self::$db->query($query);
        return $resultado->fetch_assoc()['total'];
    }
}
