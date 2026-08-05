<?php
/* =================================================================
   SCRIPT 3 — TABLA DE MULTIPLICAR
   Genera la tabla de multiplicar de un número elegido y, además,
   la grilla completa de las tablas del 1 al 10.

   Estructuras utilizadas:
     - Variables recibidas por formulario ($_GET) con valor por defecto
     - Operaciones matemáticas: multiplicación, módulo
     - Estructuras repetitivas: for simple y for anidado (bucle dentro de otro)
     - Estructuras condicionales: if / else y operador ternario dentro del bucle
   ================================================================= */

// ---------- 1. Entrada de datos ----------
// (int) convierte el texto recibido en número entero.
$numero = isset($_GET['numero']) ? (int) $_GET['numero'] : 7;
$hasta  = isset($_GET['hasta'])  ? (int) $_GET['hasta']  : 10;

// ---------- 2. Validación de los parámetros ----------
if ($numero < 1 || $numero > 100) {
    $numero = 7;         // valor por defecto si el dato está fuera de rango
    $seCorrigio = true;
} else {
    $seCorrigio = false;
}

if ($hasta < 1 || $hasta > 20) {
    $hasta = 10;
}

$tituloPagina = "Script 3 — Tabla de multiplicar";
$subtitulo    = "Estructuras repetitivas: for simple y for anidado";
require __DIR__ . '/partials/cabecera.php';
?>

<form class="parametros" method="get" action="03-tabla-multiplicar.php">
    <div>
        <label for="numero">Tabla del número</label>
        <input type="number" id="numero" name="numero" min="1" max="100" value="<?= $numero ?>">
    </div>
    <div>
        <label for="hasta">Multiplicar hasta</label>
        <input type="number" id="hasta" name="hasta" min="1" max="20" value="<?= $hasta ?>">
    </div>
    <button type="submit">Generar tabla</button>
</form>

<?php if ($seCorrigio): ?>
    <div class="tarjeta">
        <div class="resultado alerta">
            El número ingresado estaba fuera del rango permitido (1 a 100);
            se usó el valor por defecto <strong>7</strong>.
        </div>
    </div>
<?php endif; ?>

<div class="tarjeta">
    <h2>Tabla del <?= $numero ?> (for simple)</h2>
    <table>
        <thead>
            <tr>
                <th>Operación</th>
                <th>Resultado</th>
                <th>¿Par o impar?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // ---------- 3. Primera estructura repetitiva: for ----------
            // El for reúne en una sola línea: valor inicial, condición de corte
            // y el incremento que se aplica en cada vuelta.
            for ($i = 1; $i <= $hasta; $i++) {
                $resultado = $numero * $i;              // operación matemática

                // Condicional con el operador módulo (%): si el resto de
                // dividir por 2 es 0, el número es par.
                if ($resultado % 2 == 0) {
                    $tipo  = "Par";
                    $clase = "verde";
                } else {
                    $tipo  = "Impar";
                    $clase = "naranja";
                }

                echo "<tr>";
                echo "  <td>{$numero} × {$i}</td>";
                echo "  <td><strong>{$resultado}</strong></td>";
                echo "  <td><span class='etiqueta {$clase}'>{$tipo}</span></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <p class="nota">
        El bucle se ejecutó <?= $hasta ?> veces: una por cada fila de la tabla.
    </p>
</div>

<div class="tarjeta">
    <h2>Grilla completa del 1 al 10 (for anidado)</h2>
    <p>
        Un bucle recorre las filas y, dentro de cada vuelta, un segundo bucle
        recorre las columnas. En total se realizan 10 × 10 = 100 multiplicaciones.
        Se resaltan los cuadrados perfectos, es decir, la diagonal donde
        fila y columna coinciden.
    </p>
    <table>
        <thead>
            <tr>
                <th>×</th>
                <?php for ($columna = 1; $columna <= 10; $columna++): ?>
                    <th><?= $columna ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalOperaciones = 0;

            // ---------- 4. Segunda estructura repetitiva: for anidado ----------
            for ($fila = 1; $fila <= 10; $fila++) {
                echo "<tr>";
                echo "<th class='cabecera-lateral'>{$fila}</th>";

                for ($columna = 1; $columna <= 10; $columna++) {
                    $producto = $fila * $columna;
                    $totalOperaciones++;   // ++ incrementa la variable en 1

                    // Operador ternario encadenado para elegir la clase CSS
                    $claseCelda = ($fila == $columna) ? "diagonal"
                        : (($producto % 2 == 0) ? "par" : "");

                    echo "<td class='{$claseCelda}'>{$producto}</td>";
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <p class="nota">
        Operaciones realizadas por el bucle anidado: <?= $totalOperaciones ?>.
        En azul, los cuadrados perfectos (fila = columna); en verde, los resultados pares.
    </p>
</div>

<?php require __DIR__ . '/partials/pie.php'; ?>
