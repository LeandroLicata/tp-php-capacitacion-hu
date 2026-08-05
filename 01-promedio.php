<?php
/* =================================================================
   SCRIPT 1 — CÁLCULO DE PROMEDIO
   Calcula el promedio de las notas de un alumno, determina la nota
   más alta y la más baja, cuenta cuántas notas están aprobadas y
   define la condición final del alumno.

   Estructuras utilizadas:
     - Variables escalares y arreglo (array)
     - Operaciones matemáticas: suma, división, porcentaje, redondeo
     - Estructura repetitiva: foreach
     - Estructuras condicionales: if / elseif / else
   ================================================================= */

// ---------- 1. Variables de entrada ----------
$alumno   = "Leandro Licata";
$materia  = "Programación en PHP";
$notaMinimaAprobacion = 6;

// Arreglo con las notas de las evaluaciones del cuatrimestre
$notas = [8, 6, 9, 4, 7, 10];

// ---------- 2. Acumuladores ----------
// Se inicializan antes del bucle porque se van modificando dentro de él.
$suma           = 0;      // acumula la sumatoria de las notas
$cantidad       = count($notas);
$notaMasAlta    = $notas[0];
$notaMasBaja    = $notas[0];
$cantAprobadas  = 0;

// ---------- 3. Estructura repetitiva: recorrido del arreglo ----------
foreach ($notas as $nota) {
    $suma = $suma + $nota;          // operación matemática: suma acumulada

    if ($nota > $notaMasAlta) {     // condicional simple dentro del bucle
        $notaMasAlta = $nota;
    }

    if ($nota < $notaMasBaja) {
        $notaMasBaja = $nota;
    }

    if ($nota >= $notaMinimaAprobacion) {
        $cantAprobadas = $cantAprobadas + 1;
    }
}

// ---------- 4. Operaciones matemáticas finales ----------
$promedio          = $suma / $cantidad;                          // división
$promedioRedondeado = round($promedio, 2);                       // redondeo a 2 decimales
$porcentajeAprobadas = round(($cantAprobadas * 100) / $cantidad, 1); // regla de tres
$cantDesaprobadas  = $cantidad - $cantAprobadas;                 // resta

// ---------- 5. Estructura condicional: condición final ----------
if ($promedioRedondeado >= 8) {
    $condicion = "Promocionado";
    $color     = "verde";
    $mensaje   = "Excelente rendimiento: no debe rendir examen final.";
} elseif ($promedioRedondeado >= $notaMinimaAprobacion) {
    $condicion = "Aprobado";
    $color     = "azul";
    $mensaje   = "Aprobó la cursada. Debe rendir el examen final.";
} elseif ($promedioRedondeado >= 4) {
    $condicion = "Regular";
    $color     = "naranja";
    $mensaje   = "Debe recuperar las evaluaciones desaprobadas.";
} else {
    $condicion = "Desaprobado";
    $color     = "roja";
    $mensaje   = "No alcanzó el promedio mínimo para aprobar la cursada.";
}

// Datos para la cabecera común
$tituloPagina = "Script 1 — Cálculo de promedio";
$subtitulo    = "Variables, arreglos, foreach y condicionales if / elseif / else";
require __DIR__ . '/partials/cabecera.php';
?>

<div class="tarjeta">
    <h2>Datos de entrada</h2>
    <ul class="datos">
        <li><strong>Alumno:</strong> <?= $alumno ?></li>
        <li><strong>Materia:</strong> <?= $materia ?></li>
        <li><strong>Notas cargadas:</strong> <?= implode(" &nbsp;–&nbsp; ", $notas) ?></li>
        <li><strong>Cantidad de evaluaciones:</strong> <?= $cantidad ?></li>
        <li><strong>Nota mínima de aprobación:</strong> <?= $notaMinimaAprobacion ?></li>
    </ul>
</div>

<div class="tarjeta">
    <h2>Detalle de cada evaluación</h2>
    <table>
        <thead>
            <tr>
                <th>N° de evaluación</th>
                <th>Nota</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Segundo recorrido: foreach con clave => valor para numerar las filas
            foreach ($notas as $indice => $nota) {
                // Operador ternario: forma abreviada de un if / else
                $estado = ($nota >= $notaMinimaAprobacion) ? "Aprobada" : "Desaprobada";
                $claseEtiqueta = ($nota >= $notaMinimaAprobacion) ? "verde" : "roja";
                echo "<tr>";
                echo "  <td>" . ($indice + 1) . "</td>";
                echo "  <td>{$nota}</td>";
                echo "  <td><span class='etiqueta {$claseEtiqueta}'>{$estado}</span></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="tarjeta">
    <h2>Resultados del cálculo</h2>
    <ul class="datos">
        <li><strong>Suma de las notas:</strong> <?= $suma ?></li>
        <li><strong>Promedio exacto:</strong> <?= $suma ?> ÷ <?= $cantidad ?> = <?= $promedio ?></li>
        <li><strong>Promedio redondeado:</strong> <?= $promedioRedondeado ?></li>
        <li><strong>Nota más alta:</strong> <?= $notaMasAlta ?></li>
        <li><strong>Nota más baja:</strong> <?= $notaMasBaja ?></li>
        <li><strong>Evaluaciones aprobadas:</strong> <?= $cantAprobadas ?> de <?= $cantidad ?> (<?= $porcentajeAprobadas ?> %)</li>
        <li><strong>Evaluaciones desaprobadas:</strong> <?= $cantDesaprobadas ?></li>
    </ul>

    <div class="resultado <?= $color === 'verde' ? 'exito' : ($color === 'roja' ? 'error' : ($color === 'naranja' ? 'alerta' : '')) ?>">
        <span class="numero-grande"><?= $promedioRedondeado ?></span>
        Condición final: <span class="etiqueta <?= $color ?>"><?= $condicion ?></span><br>
        <?= $mensaje ?>
    </div>
</div>

<?php require __DIR__ . '/partials/pie.php'; ?>
