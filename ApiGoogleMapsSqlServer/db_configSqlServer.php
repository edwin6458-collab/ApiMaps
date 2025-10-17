<?php
// db_config.php

//CREATE TABLE Coordenadas (
//    Id INT IDENTITY(1,1) PRIMARY KEY,
//    Lat DECIMAL(10, 8) NOT NULL,
//    Lng DECIMAL(11, 8) NOT NULL,
//    Fecha_Creacion DATETIME DEFAULT GETDATE()
//);

// 1. CONFIGURACIÓN DE CONEXIÓN A SQL SERVER
// Ajusta estos valores a tu entorno de SQL Server
$serverName = "IT-SRV-DB1\SQLINFASA"; // Ej. "localhost\SQLEXPRESS" o la IP
$databaseName = "CONSIS";
$uid = "db.user";
$pwd = "db.user@inFasa90";

// Array de opciones de conexión
$connectionOptions = array(
    "Database" => $databaseName,
    "Uid" => $uid,
    "PWD" => $pwd,
    "CharacterSet" => "UTF-8"
);

/**
 * Función para establecer la conexión a SQL Server
 * @return mixed Objeto de conexión si es exitoso, o false si falla.
 */
function connectDB() {
    global $serverName, $connectionOptions;
    
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
    if ($conn === false) {
        // En un entorno de producción, esto debería ser un log, no un echo
        error_log(print_r(sqlsrv_errors(), true));
        return false;
    }
    return $conn;
}
?>


