<?php
// save_coordinate.php

header('Content-Type: application/json');
require_once 'db_configSqlServer.php'; // Incluye la configuración de conexión

// Asegúrate de que tu tabla en SQL Server tiene: Id INT IDENTITY(1,1), Lat DECIMAL(10,8), Lng DECIMAL(11,8)

// Recibir datos de POST
$lat = filter_input(INPUT_POST, 'lat', FILTER_VALIDATE_FLOAT);
$lng = filter_input(INPUT_POST, 'lng', FILTER_VALIDATE_FLOAT);

if ($lat === false || $lng === false) {
    echo json_encode(['success' => false, 'message' => 'Datos de latitud/longitud inválidos.']);
    exit;
}

$conn = connectDB();
if ($conn === false) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

try {
    // Usamos SCOPE_IDENTITY() para obtener el ID de la fila recién insertada en SQL Server
    $tsql = "INSERT INTO Coordenadas (Lat, Lng) VALUES (?, ?); SELECT SCOPE_IDENTITY() AS Id;";
    $params = array(&$lat, &$lng);
    
    $stmt = sqlsrv_query($conn, $tsql, $params);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        throw new Exception($errors[0]['message']);
    }

    // Obtener el ID de la fila insertada
    sqlsrv_next_result($stmt); 
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $newId = round($row['Id']); // El driver puede devolverlo como float

    echo json_encode(['success' => true, 'id' => $newId]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al insertar: ' . $e->getMessage()]);
} finally {
    sqlsrv_close($conn);
}
?>