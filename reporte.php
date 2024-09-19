<?php
// reporte.php
require_once 'config/db_config.php';
require_once 'includes/Cursos.php';
require_once 'includes/Inscripciones.php';


$cursos = new Cursos($pdo);
$inscripciones = new Inscripciones($pdo);

function obtenerCursosPopulares($pdo) {
    $sql = "SELECT c.curso_id, c.nombre, COUNT(i.estudiante_id) as num_estudiantes
            FROM Cursos c
            LEFT JOIN Inscripciones i ON c.curso_id = i.curso_id
            GROUP BY c.curso_id
            ORDER BY num_estudiantes DESC
            LIMIT 5";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

$cursos_populares = obtenerCursosPopulares($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Cursos Populares</title>
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
    
    <button onclick="window.open('generar_pdf.php', '_blank')" class="print-button">Imprimir Reporte</button>

    <h2>Los 5 cursos más populares</h2>
    <table>
        <thead>
            <tr>
                <th>Posición</th>
                <th>Nombre del Curso</th>
                <th>Número de Estudiantes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos_populares as $index => $curso): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $curso['nombre']; ?></td>
                <td><?php echo $curso['num_estudiantes']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Detalles de todos los cursos</h2>
    <table>
        <thead>
            <tr>
                <th>ID del Curso</th>
                <th>Nombre del Curso</th>
                <th>Número de Estudiantes</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $todos_los_cursos = $cursos->leer();
            foreach ($todos_los_cursos as $curso): 
                $estudiantes_inscritos = $inscripciones->obtenerInscripcionesPorCurso($curso['curso_id']);
                $num_estudiantes = count($estudiantes_inscritos);
            ?>
            <tr>
                <td><?php echo $curso['curso_id']; ?></td>
                <td><?php echo $curso['nombre']; ?></td>
                <td><?php echo $num_estudiantes; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>