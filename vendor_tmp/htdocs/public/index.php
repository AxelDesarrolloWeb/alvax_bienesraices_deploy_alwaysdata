<?php
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\PaginasController;
use Controllers\PropiedadController;
use Controllers\VendedorController;
use Controllers\BlogController;
use Controllers\LoginController;

$router = new Router();

// Rutas públicas
$router->get('/', [PaginasController::class, 'index']);
$router->get('/nosotros', [PaginasController::class, 'nosotros']);
$router->get('/propiedades', [PaginasController::class, 'propiedades']);
$router->get('/propiedad', [PaginasController::class, 'propiedad']);
$router->get('/blog', [PaginasController::class, 'blog']);
$router->get('/entrada', [PaginasController::class, 'entrada']);
$router->get('/contacto', [PaginasController::class, 'contacto']);
$router->post('/contacto', [PaginasController::class, 'contacto']);

//  Login y autenticación
 $router->get('/login', [LoginController::class, 'login']);
 $router->post('/login', [LoginController::class, 'login']);
 $router->get('/logout', [LoginController::class, 'logout']);

// Rutas de administración
$router->get('/admin', [PropiedadController::class, 'index']);
$router->get('/propiedades/crear', [PropiedadController::class, 'crear']);
$router->post('/propiedades/crear', [PropiedadController::class, 'crear']);
$router->get('/propiedades/actualizar', [PropiedadController::class, 'actualizar']);
$router->post('/propiedades/actualizar', [PropiedadController::class, 'actualizar']);
$router->post('/propiedades/eliminar', [PropiedadController::class, 'eliminar']);

// Rutas de administración
$router->get('/vendedores/crear', [VendedorController::class, 'crear']);
$router->post('/vendedores/crear', [VendedorController::class, 'crear']);
$router->get('/vendedores/actualizar', [VendedorController::class, 'actualizar']);
$router->post('/vendedores/actualizar', [VendedorController::class, 'actualizar']);
$router->post('/vendedores/eliminar', [VendedorController::class, 'eliminar']);

// Rutas públicas
$router->get('/', [PaginasController::class, 'index']);
$router->get('/blogs', [BlogController::class, 'index']);
$router->get('/blogs/crear', [BlogController::class, 'crear']);
$router->post('/blogs/crear', [BlogController::class, 'crear']);
$router->get('/blogs/actualizar', [BlogController::class, 'actualizar']);
$router->post('/blogs/actualizar', [BlogController::class, 'actualizar']);
$router->post('/blogs/eliminar', [BlogController::class, 'eliminar']);
$router->get('/blog/entrada', [BlogController::class, 'entrada']);

// Modificar estas rutas:
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/entrada', [BlogController::class, 'entrada']);

$router->comprobarRutas();