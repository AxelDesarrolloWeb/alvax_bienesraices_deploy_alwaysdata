<?php
function conectarDB(): mysqli
{
    $db = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    $db->set_charset("utf8");

    if ($db->connect_error) {
        echo "Error de conexión: " . $db->connect_error;
        exit;
    }
    return $db;
}
