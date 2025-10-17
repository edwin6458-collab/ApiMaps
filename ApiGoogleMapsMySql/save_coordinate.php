<?php
// save_coordinate.php (MODIFICADO para MySQL/PDO)

header('Content-Type: application/json');
require_once 'db_config.php'; 

$lat = filter_input(INPUT_POST, 'lat', FILTER_VALIDATE_FLOAT);
$lng = filter_input(INPUT_POST, 'lng', FILTER_VALIDATE_FLOAT);

if ($lat === false || $lng === false) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

$pdo = connectDB();
if ($pdo === false) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

try {
    // 1. Inserción
    $stmt = $pdo->prepare("INSERT INTO coordenadas (lat, lng) VALUES (:lat, :lng)");
    $stmt->execute(['lat' => $lat, 'lng' => $lng]);
    
    // 2. Obtener el último ID insertado (MySQL usa lastInsertId())
    $newId = $pdo->lastInsertId();

    echo json_encode(['success' => true, 'id' => $newId]);

} catch (\PDOException $e) {
    error_log("Error al insertar: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al insertar.']);
}
// PDO no requiere una función de cierre explícita, pero la conexión se cierra al finalizar el script.
?>