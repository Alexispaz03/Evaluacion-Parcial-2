<?php
// cursos.php
require_once 'config/db_config.php';
require_once 'includes/Cursos.php';

$cursos = new Cursos($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear'])) {
        $cursos->crear($_POST['nombre'], $_POST['descripcion'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
    } elseif (isset($_POST['actualizar'])) {
        $cursos->actualizar($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
    } elseif (isset($_POST['eliminar'])) {
        $cursos->eliminar($_POST['id']);
    }
    // Redirigir para evitar reenvío del formulario
    header('Location: cursos.php');
    exit();
}

$lista_cursos = $cursos->leer();
$curso_editar = null;

if (isset($_GET['editar'])) {
    $curso_editar = $cursos->leerPorId($_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos</title>
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
    
    <h2><?php echo $curso_editar ? 'Editar Curso' : 'Crear Curso'; ?></h2>
    <form method="POST">
        <?php if ($curso_editar): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($curso_editar['curso_id']); ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="nombre">Nombre del curso:</label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($curso_editar ? $curso_editar['nombre'] : ''); ?>">
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($curso_editar ? $curso_editar['descripcion'] : ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="fecha_inicio">Fecha de inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required value="<?php echo htmlspecialchars($curso_editar ? $curso_editar['fecha_inicio'] : ''); ?>">
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha de fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required value="<?php echo htmlspecialchars($curso_editar ? $curso_editar['fecha_fin'] : ''); ?>">
        </div>
        <button type="submit" name="<?php echo $curso_editar ? 'actualizar' : 'crear'; ?>">
            <?php echo $curso_editar ? 'Actualizar Curso' : 'Crear Curso'; ?>
        </button>
    </form>

    <h2>Lista de Cursos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_cursos as $curso): ?>
            <tr>
                <td><?php echo htmlspecialchars($curso['curso_id']); ?></td>
                <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                <td><?php echo htmlspecialchars($curso['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($curso['fecha_inicio']); ?></td>
                <td><?php echo htmlspecialchars($curso['fecha_fin']); ?></td>
                <td>
                    <a href="?editar=<?php echo htmlspecialchars($curso['curso_id']); ?>" class="btn-editar">Editar</a>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($curso['curso_id']); ?>">
                        <button type="submit" name="eliminar" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este curso?');">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>