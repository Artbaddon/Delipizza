<?php

/***
 *       PROYECTO: DELIPIZZA
 *       MODULO SERVIDO - CONEXION BD
 *       PROGRAMA: conectionpdo.php
 *       Se realiza la conexion a la BD via PDO
 * 
 */

//  Coneccion Mysql
$host = "localhost";
$user = "root";
$password = "";
$db = "delipizza-db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Configurar DSN
$dsn = "mysql:host=$host;dbname=$db";

//Crear instancia PDO
try {
    // Agregar el setattribute de manera global
    $pdo = new PDO($dsn, $user,$password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {

    die('Error: ' . $e->getMessage());
}



