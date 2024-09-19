<?php
// generar_pdf.php
require_once 'config/db_config.php';
require_once 'includes/Cursos.php';
require_once 'includes/Inscripciones.php';
require_once 'fpdf/fpdf.php';

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
$todos_los_cursos = $cursos->leer();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'Reporte de Cursos Populares',0,1,'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Los 5 cursos más populares
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Los 5 cursos más populares',0,1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,7,'Posición',1);
$pdf->Cell(100,7,'Nombre del Curso',1);
$pdf->Cell(70,7,'Número de Estudiantes',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
foreach ($cursos_populares as $index => $curso) {
    $pdf->Cell(20,6,$index + 1,1);
    $pdf->Cell(100,6,$curso['nombre'],1);
    $pdf->Cell(70,6,$curso['num_estudiantes'],1);
    $pdf->Ln();
}

$pdf->Ln(10);

// Detalles de todos los cursos
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Detalles de todos los cursos',0,1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,7,'ID',1);
$pdf->Cell(100,7,'Nombre del Curso',1);
$pdf->Cell(70,7,'Número de Estudiantes',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
foreach ($todos_los_cursos as $curso) {
    $estudiantes_inscritos = $inscripciones->obtenerInscripcionesPorCurso($curso['curso_id']);
    $num_estudiantes = count($estudiantes_inscritos);
    
    $pdf->Cell(20,6,$curso['curso_id'],1);
    $pdf->Cell(100,6,$curso['nombre'],1);
    $pdf->Cell(70,6,$num_estudiantes,1);
    $pdf->Ln();
}

$pdf->Output('I', 'reporte_cursos.pdf');
?>