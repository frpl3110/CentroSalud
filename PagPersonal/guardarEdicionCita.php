<?php
session_start();
require '../inicioSesion/conexion.php';

if ($_SESSION['perfil'] !== 'secretaria') {
    echo "<script>
            alert('Acceso denegado. No tienes permiso para realizar esta acción.');
            window.location.href = '/CentroSalud/inicioSesion/login.php';
          </script>";
    exit();
}

// Verifica que los datos necesarios hayan sido enviados
if (!isset($_POST['id_cita'], $_POST['id_paciente'], $_POST['id_medico'], $_POST['fecha_cita'])) {
    echo "<script>
            alert('Datos incompletos para la actualización de la cita.');
            window.location.href = 'citasSecretaria.php';
          </script>";
    exit();
}

$conexion = new conexion();
$pdo = $conexion->conectar();

$id_cita = $_POST['id_cita'];
$id_paciente = $_POST['id_paciente'];
$id_medico = $_POST['id_medico'];
$fecha_cita = $_POST['fecha_cita'];

// Actualizar la cita en la base de datos
$stmt = $pdo->prepare("
    UPDATE cita
    SET id_paciente = :id_paciente,
        id_medico = :id_medico,
        fechaCita = :fecha_cita
    WHERE id_cita = :id_cita
");

if ($stmt->execute([
    ':id_paciente' => $id_paciente,
    ':id_medico' => $id_medico,
    ':fecha_cita' => $fecha_cita,
    ':id_cita' => $id_cita
])) {
    echo "<script>
            alert('Cita actualizada exitosamente.');
            window.location.href = 'citasSecretaria.php';
          </script>";
} else {
    echo "<script>
            alert('Error al actualizar la cita.');
            window.location.href = 'citasSecretaria.php';
          </script>";
}
?>
