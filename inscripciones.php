<?php
// inscripciones.php
require_once 'config/db_config.php';
require_once 'includes/Inscripciones.php';
require_once 'includes/Cursos.php';
require_once 'includes/Estudiantes.php';

$inscripciones = new Inscripciones($pdo);
$cursos = new Cursos($pdo);
$estudiantes = new Estudiantes($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['inscribir'])) {
        $inscripciones->inscribir($_POST['estudiante_id'], $_POST['curso_id']);
    } elseif (isset($_POST['cancelar'])) {
        $inscripciones->cancelarInscripcion($_POST['estudiante_id'], $_POST['curso_id']);
    }
}

$lista_cursos = $cursos->leer();
$lista_estudiantes = $estudiantes->leer();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inscripciones</title>
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
    
    <h2>Inscribir Estudiante a Curso</h2>
    <form method="POST">
        <div class="form-group">
            <label for="estudiante_id">Estudiante:</label>
            <select id="estudiante_id" name="estudiante_id" required>
                <option value="">Seleccione un estudiante</option>
                <?php foreach ($lista_estudiantes as $estudiante): ?>
                    <option value="<?php echo $estudiante['estudiante_id']; ?>">
                        <?php echo $estudiante['nombre'] . ' ' . $estudiante['apellido']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="curso_id">Curso:</label>
            <select id="curso_id" name="curso_id" required>
                <option value="">Seleccione un curso</option>
                <?php foreach ($lista_cursos as $curso): ?>
                    <option value="<?php echo $curso['curso_id']; ?>">
                        <?php echo $curso['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="inscribir">Inscribir</button>
    </form>

    <h2>Inscripciones Actuales</h2>
    <?php foreach ($lista_cursos as $curso): ?>
        <h3><?php echo $curso['nombre']; ?></h3>
        <?php $estudiantes_inscritos = $inscripciones->obtenerInscripcionesPorCurso($curso['curso_id']); ?>
        <?php if (empty($estudiantes_inscritos)): ?>
            <p>No hay estudiantes inscritos en este curso.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre del Estudiante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes_inscritos as $estudiante): ?>
                        <tr>
                            <td><?php echo $estudiante['nombre'] . ' ' . $estudiante['apellido']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="estudiante_id" value="<?php echo $estudiante['estudiante_id']; ?>">
                                    <input type="hidden" name="curso_id" value="<?php echo $curso['curso_id']; ?>">
                                    <button type="submit" name="cancelar">Cancelar Inscripción</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
</body>
</html>