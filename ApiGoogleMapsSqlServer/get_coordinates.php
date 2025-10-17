<?php
// get_coordinates.php

header('Content-Type: application/json');
require_once 'db_configSqlServer.php'; // Incluye la configuración de conexión

$conn = connectDB();
if ($conn === false) {
    // Devuelve un array vacío si falla la conexión
    echo json_encode([]); 
    exit;
}

try {
    $tsql = "SELECT Id, Lat, Lng FROM Coordenadas ORDER BY Id DESC";
    $stmt = sqlsrv_query($conn, $tsql);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        throw new Exception($errors[0]['message']);
    }

    $coordinates = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Mapea y asegura que Lat y Lng sean cadenas o números para el JSON
        $coordinates[] = [
            'id' => $row['Id'],
            'lat' => (string)$row['Lat'], 
            'lng' => (string)$row['Lng']
        ];
    }

    echo json_encode($coordinates);

} catch (Exception $e) {
    error_log('Error al consultar: ' . $e->getMessage());
    echo json_encode([]);
} finally {
    sqlsrv_close($conn);
}
?>