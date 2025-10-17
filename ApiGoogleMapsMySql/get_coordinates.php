<?php
// get_coordinates.php (MODIFICADO para MySQL/PDO)

header('Content-Type: application/json');
require_once 'db_config.php'; 

$pdo = connectDB();
if ($pdo === false) {
    echo json_encode([]); 
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, lat, lng FROM coordenadas ORDER BY id DESC");
    
    // FETCH_ASSOC es el valor predeterminado, pero lo hacemos explícito
    $coordinates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($coordinates);

} catch (\PDOException $e) {
    error_log("Error al consultar: " . $e->getMessage());
    echo json_encode([]);
}
?>