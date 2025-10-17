<?php
//CREATE TABLE coordenadas (
//    id INT AUTO_INCREMENT PRIMARY KEY,
//    lat DECIMAL(10, 8) NOT NULL,
//    lng DECIMAL(11, 8) NOT NULL,
//    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//);


// 1. CONFIGURACIÓN DE CONEXIÓN A MYSQL/MARIADB
// Generalmente, 'localhost' y 'root' son los valores predeterminados de XAMPP.
$db_host = "localhost"; 
$db_name = "mapas"; // Reemplaza con el nombre de tu BD
$db_user = "root";                   // Reemplaza si has cambiado el usuario
$db_pass = "";                       // Reemplaza si has puesto una contraseña (por defecto está vacía)

 
// db_config.php - Configuración para MySQL/MariaDB usando PDO
 
/**
 * Función para establecer la conexión a la base de datos MySQL/MariaDB.
 * @return PDO|false Objeto PDO si la conexión es exitosa, o false si falla.
 */
function connectDB() {
    global $db_host, $db_name, $db_user, $db_pass;
    $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
    
    // Opciones de PDO:
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve resultados como array asociativo
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Mejor rendimiento y seguridad
    ];

    try {
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);
        return $pdo;
    } catch (\PDOException $e) {
        // En un entorno de desarrollo, puedes mostrar el error.
        // En producción, solo deberías registrarlo (log) por seguridad.
        error_log("Error de conexión a la BD: " . $e->getMessage());
        return false;
    }
}
?>