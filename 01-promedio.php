<?php
/* =================================================================
   SCRIPT 1 — CÁLCULO DE PROMEDIO
   Calcula el promedio de las notas de un alumno, determina la nota
   más alta y la más baja, cuenta cuántas notas están aprobadas y
   define la condición final del alumno.

   Estructuras utilizadas:
     - Variables recibidas por formulario ($_GET) con valor por defecto
     - Conversión de un texto a un arreglo (explode) con validación
     - Operaciones matemáticas: suma, división, porcentaje, redondeo
     - Estructuras repetitivas: foreach
     - Estructuras condicionales: if / elseif / else, ternario
   ================================================================= */

// ---------- 1. Variables de entrada ----------
// Si el formulario no envía nada, se usan los valores de ejemplo.
$alumno      = $_GET['alumno'] ?? 'Leandro Licata';
$materia     = $_GET['materia'] ?? 'Programación en PHP';
$notasTexto  = $_GET['notas'] ?? '8, 6, 9, 4, 7, 10';

$notaMinimaAprobacion = isset($_GET['minima']) ? (int) $_GET['minima'] : 6;

if ($notaMinimaAprobacion < 1 || $notaMinimaAprobacion > 10) {
    $notaMinimaAprobacion = 6;
}

// ---------- 2. Del texto al arreglo ----------
// Las notas llegan como un único texto ("8, 6, 9"). explode() lo corta
// por las comas y devuelve un arreglo con cada trozo por separado.
$partes       = explode(',', $notasTexto);
$notas        = [];
$descartadas  = 0;

foreach ($partes as $parte) {
    $parte = trim($parte);          // quita los espacios de los costados

    if ($parte === '') {
        continue;                   // salta los trozos vacíos (comas de más)
    }

    // is_numeric() indica si el texto representa un número.
    if (!is_numeric($parte)) {
        $descartadas++;
        continue;                   // "continue" pasa a la vuelta siguiente
    }

    $valor = (float) $parte;        // convierte el texto en número

    if ($valor < 1 || $valor > 10) {
        $descartadas++;
        continue;
    }

    $notas[] = $valor;              // agrega la nota al final del arreglo
}

// Si no quedó ninguna nota válida, se vuelve al ejemplo por defecto.
$sinNotasValidas = false;

if (count($notas) === 0) {
    $notas = [8, 6, 9, 4, 7, 10];
    $sinNotasValidas = true;
}

// ---------- 3. Acumuladores ----------
// Se inicializan antes del bucle porque se van modificando dentro de él.
$suma           = 0;      // acumula la sumatoria de las notas
$cantidad       = count($notas);
$notaMasAlta    = $notas[0];
$notaMasBaja    = $notas[0];
$cantAprobadas  = 0;

// ---------- 4. Estructura repetitiva: recorrido del arreglo ----------
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

// ---------- 5. Operaciones matemáticas finales ----------
$promedio          = $suma / $cantidad;                          // división
$promedioRedondeado = round($promedio, 2);                       // redondeo a 2 decimales
$porcentajeAprobadas = round(($cantAprobadas * 100) / $cantidad, 1); // regla de tres
$cantDesaprobadas  = $cantidad - $cantAprobadas;                 // resta

// ---------- 6. Estructura condicional: condición final ----------
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

<form class="parametros" method="get" action="01-promedio.php">
    <div>
        <label for="alumno">Alumno</label>
        <input type="text" id="alumno" name="alumno" value="<?= htmlspecialchars($alumno) ?>">
    </div>
    <div>
        <label for="materia">Materia</label>
        <input type="text" id="materia" name="materia" value="<?= htmlspecialchars($materia) ?>">
    </div>
    <div>
        <label for="notas">Notas (separadas por coma)</label>
        <input type="text" id="notas" name="notas" size="24" value="<?= htmlspecialchars($notasTexto) ?>">
    </div>
    <div>
        <label for="minima">Nota mínima</label>
        <input type="number" id="minima" name="minima" min="1" max="10" value="<?= $notaMinimaAprobacion ?>">
    </div>
    <button type="submit">Calcular promedio</button>
</form>

<?php if ($sinNotasValidas): ?>
    <div class="tarjeta">
        <div class="resultado alerta">
            No se reconoció ninguna nota válida en el texto ingresado.
            Se usaron las notas de ejemplo. Las notas deben ser números
            del 1 al 10 separados por comas.
        </div>
    </div>
<?php elseif ($descartadas > 0): ?>
    <div class="tarjeta">
        <div class="resultado alerta">
            Se descartaron <strong><?= $descartadas ?></strong> valor(es) por no ser
            números del 1 al 10. El cálculo se hizo con las <?= $cantidad ?> notas válidas.
        </div>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <h2>Datos de entrada</h2>
    <ul class="datos">
        <li><strong>Alumno:</strong> <?= htmlspecialchars($alumno) ?></li>
        <li><strong>Materia:</strong> <?= htmlspecialchars($materia) ?></li>
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
