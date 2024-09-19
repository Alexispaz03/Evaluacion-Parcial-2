<?php
// estudiantes.php
require_once 'config/db_config.php';
require_once 'includes/Estudiantes.php';

$estudiantes = new Estudiantes($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        $estudiantes->crear($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['fecha_nacimiento']);
    } elseif (isset($_POST['actualizar'])) {
        $estudiantes->actualizar($_POST['id'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['fecha_nacimiento']);
    } elseif (isset($_POST['eliminar'])) {
        $estudiantes->eliminar($_POST['id']);
    }
    // Redirigir para evitar reenvío del formulario
    header('Location: estudiantes.php');
    exit();
}

$lista_estudiantes = $estudiantes->leer();
$estudiante_editar = null;

if (isset($_GET['editar'])) {
    $estudiante_editar = $estudiantes->leerPorId($_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sistema de Gestión de Cursos</h1>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="cursos.php">Gestionar Cursos</a></li>
            <li><a href="estudiantes.php">Gestionar Estudiantes</a></li>
            <li><a href="inscripciones.php">Gestionar Inscripciones</a></li>
            <li><a href="reporte.php">Reporte de Cursos Populares</a></li>
        </ul>
    </nav>
    
    <h2><?php echo $estudiante_editar ? 'Editar Estudiante' : 'Crear Estudiante'; ?></h2>
    <form method="POST">
        <?php if ($estudiante_editar): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($estudiante_editar['estudiante_id']); ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($estudiante_editar ? $estudiante_editar['nombre'] : ''); ?>">
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required value="<?php echo htmlspecialchars($estudiante_editar ? $estudiante_editar['apellido'] : ''); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($estudiante_editar ? $estudiante_editar['email'] : ''); ?>">
        </div>
        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required value="<?php echo htmlspecialchars($estudiante_editar ? $estudiante_editar['fecha_nacimiento'] : ''); ?>">
        </div>
        <button type="submit" name="<?php echo $estudiante_editar ? 'actualizar' : 'crear'; ?>">
            <?php echo $estudiante_editar ? 'Actualizar Estudiante' : 'Crear Estudiante'; ?>
        </button>
    </form>

    <h2>Lista de Estudiantes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Fecha de Nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_estudiantes as $estudiante): ?>
            <tr>
                <td><?php echo htmlspecialchars($estudiante['estudiante_id']); ?></td>
                <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                <td><?php echo htmlspecialchars($estudiante['apellido']); ?></td>
                <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                <td><?php echo htmlspecialchars($estudiante['fecha_nacimiento']); ?></td>
                <td>
                    <a href="?editar=<?php echo htmlspecialchars($estudiante['estudiante_id']); ?>" class="btn-editar">Editar</a>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($estudiante['estudiante_id']); ?>">
                        <button type="submit" name="eliminar" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este estudiante?');">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
