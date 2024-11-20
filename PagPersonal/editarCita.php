<?php
session_start();
require '../inicioSesion/conexion.php';

if ($_SESSION['perfil'] !== 'secretaria') {
    echo "<script>
            alert('Acceso denegado. No tienes permiso para acceder a esta página. Inicia sesión de nuevo.');
            window.location.href = '/CentroSalud/inicioSesion/login.php';
          </script>";
    exit();
}

$conexion = new conexion();
$pdo = $conexion->conectar();

// Obtener el ID de la cita a editar
$id_cita = $_GET['id_cita'] ?? null;

if (!$id_cita) {
    echo "<script>
            alert('ID de cita no proporcionado.');
            window.location.href = 'verCitas.php';
          </script>";
    exit();
}

// Consultar la información de la cita
$stmt = $pdo->prepare("
    SELECT c.fechaCita, c.tipo_cita, c.id_paciente, c.id_medico
    FROM cita c
    WHERE c.id_cita = :id_cita
");
$stmt->execute(['id_cita' => $id_cita]);
$cita = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cita) {
    echo "<script>
            alert('La cita no existe.');
            window.location.href = 'verCitas.php';
          </script>";
    exit();
}

// Obtener los pacientes y médicos para cargar en el formulario
$pacientes = $pdo->query("SELECT id_paciente, nombre FROM pacientes")->fetchAll(PDO::FETCH_ASSOC);
$medicos = $pdo->query("SELECT id_medico, nombre FROM medico")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <link rel="stylesheet" href="/CentroSalud/styles/stylesC.css">
</head>
<body>
    <div class="contenedor-citas">
        <h1 class="citas">Editar Cita</h1>
        
        <form method="POST" action="guardarEdicionCita.php">
            <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($id_cita); ?>">
            <label for="paciente">Paciente:</label>
            <select id="paciente" name="id_paciente" required>
                <?php foreach ($pacientes as $paciente) { ?>
                    <option value="<?= $paciente['id_paciente'] ?>" <?= $paciente['id_paciente'] == $cita['id_paciente'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($paciente['nombre']) ?>
                    </option>
                <?php } ?>
            </select><br>

            <label for="medico">Médico:</label>
            <select id="medico" name="id_medico" required>
                <?php foreach ($medicos as $medico) { ?>
                    <option value="<?= $medico['id_medico'] ?>" <?= $medico['id_medico'] == $cita['id_medico'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($medico['nombre']) ?>
                    </option>
                <?php } ?>
            </select><br>

            <label for="fecha_cita">Fecha de Cita:</label>
            <input type="date" id="fecha_cita" name="fecha_cita" value="<?= htmlspecialchars($cita['fechaCita']) ?>" required><br>

            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
</body>
</html>
