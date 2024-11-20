<?php
require_once 'conexion.php';
$nombre = "Secretaria";
$contrasena = "1106";


$contrasenaHasheada = password_hash($contrasena, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuario (nombre, contrasena, perfil) VALUES (:nombre, :contrasena, :perfil)");
$stmt->execute(['nombre' => $nombre, 'contrasena' => $contrasenaHasheada, 'perfil' => 'secretaria']);

?>
