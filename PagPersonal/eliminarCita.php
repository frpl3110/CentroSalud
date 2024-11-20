<?php
session_start();
require '../inicioSesion/conexion.php';

$conexion = new conexion();
$pdo = $conexion->conectar();

$id_cita = $_POST['id_cita'];

// Eliminar la cita
$stmt = $pdo->prepare("DELETE FROM cita WHERE id_cita = :id_cita");
$stmt->execute(['id_cita' => $id_cita]);

header("Location: citasSecretaria.php"); // Redirigir de nuevo al listado de citas
exit();
?>
