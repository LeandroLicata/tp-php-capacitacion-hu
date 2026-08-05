<?php
/* =================================================================
   SCRIPT 4 — CONTADOR DE NÚMEROS
   Recorre un rango de números y va contando cuántos son pares,
   impares y múltiplos de 5, además de acumular la suma total.
   Al final hace una cuenta regresiva.

   Estructuras utilizadas:
     - Variables acumuladoras y contadoras
     - Operaciones matemáticas: suma acumulada, módulo, promedio
     - Estructuras repetitivas: while y do...while
     - Estructuras condicionales: if / elseif / else dentro del bucle
   ================================================================= */

// ---------- 1. Entrada de datos ----------
$desde = isset($_GET['desde']) ? (int) $_GET['desde'] : 1;
$hasta = isset($_GET['hasta']) ? (int) $_GET['hasta'] : 20;

// Validación: el rango se limita para que la salida sea legible.
if ($desde > $hasta) {
    // Si el usuario invierte los valores, se intercambian usando una
    // variable auxiliar (procedimiento clásico de intercambio).
    $auxiliar = $desde;
    $desde    = $hasta;
    $hasta    = $auxiliar;
}

if (($hasta - $desde) > 200) {
    $hasta = $desde + 200;
}

// ---------- 2. Contadores y acumuladores ----------
$contadorPares      = 0;
$contadorImpares    = 0;
$contadorMultiplos5 = 0;
$sumaTotal          = 0;
$sumaPares          = 0;
$sumaImpares        = 0;
$vueltas            = 0;
$detalle            = [];   // arreglo donde se guarda cada número con su tipo

// ---------- 3. Primera estructura repetitiva: while ----------
// A diferencia del for, en el while el contador se inicializa antes
// del bucle y se incrementa manualmente dentro de él. Si se olvidara
// el incremento, el bucle nunca terminaría (bucle infinito).
$numero = $desde;

while ($numero <= $hasta) {
    $sumaTotal = $sumaTotal + $numero;   // acumulador
    $vueltas++;                          // cuenta las iteraciones

    // Condicional: clasificación del número
    if ($numero % 2 == 0) {
        $contadorPares++;
        $sumaPares = $sumaPares + $numero;
        $tipo = "par";
    } else {
        $contadorImpares++;
        $sumaImpares = $sumaImpares + $numero;
        $tipo = "impar";
    }

    // Un número puede ser par o impar Y ADEMÁS múltiplo de 5,
    // por eso este if va separado del anterior.
    $esMultiplo5 = ($numero % 5 == 0);
    if ($esMultiplo5) {
        $contadorMultiplos5++;
    }

    $detalle[] = [
        'numero'     => $numero,
        'tipo'       => $tipo,
        'multiplo5'  => $esMultiplo5,
    ];

    $numero++;   // ¡incremento obligatorio para que el bucle termine!
}

// ---------- 4. Operaciones matemáticas finales ----------
$cantidadNumeros = $vueltas;
$promedio        = ($cantidadNumeros > 0) ? round($sumaTotal / $cantidadNumeros, 2) : 0;
$porcentajePares = ($cantidadNumeros > 0) ? round(($contadorPares * 100) / $cantidadNumeros, 1) : 0;
$porcentajeImpares = ($cantidadNumeros > 0) ? round(($contadorImpares * 100) / $cantidadNumeros, 1) : 0;

// ---------- 5. Segunda estructura repetitiva: do...while ----------
// El do...while ejecuta el bloque ANTES de evaluar la condición,
// por lo que siempre corre al menos una vez. Se usa acá para una
// cuenta regresiva de los últimos 5 turnos del día.
$cuentaRegresiva = [];
$turnosRestantes = 5;

do {
    $cuentaRegresiva[] = $turnosRestantes;
    $turnosRestantes--;
} while ($turnosRestantes > 0);

$tituloPagina = "Script 4 — Contador de números";
$subtitulo    = "Estructuras repetitivas: while y do...while";
require __DIR__ . '/partials/cabecera.php';
?>

<form class="parametros" method="get" action="04-contador-numeros.php">
    <div>
        <label for="desde">Desde</label>
        <input type="number" id="desde" name="desde" value="<?= $desde ?>">
    </div>
    <div>
        <label for="hasta">Hasta</label>
        <input type="number" id="hasta" name="hasta" value="<?= $hasta ?>">
    </div>
    <button type="submit">Contar</button>
</form>

<div class="tarjeta">
    <h2>Recorrido realizado</h2>
    <p>
        El bucle <code>while</code> recorrió el rango <strong><?= $desde ?> – <?= $hasta ?></strong>
        y ejecutó <strong><?= $vueltas ?></strong> iteraciones. En cada vuelta clasificó el número
        y lo sumó al acumulador.
    </p>
    <p>
        <?php
        // Recorrido del arreglo generado, para mostrar el resultado de la clasificación
        foreach ($detalle as $item) {
            $clase = $item['multiplo5'] ? "azul" : ($item['tipo'] === "par" ? "verde" : "naranja");
            echo "<span class='etiqueta {$clase}' style='margin:0 3px 5px 0'>{$item['numero']}</span> ";
        }
        ?>
    </p>
    <p class="nota">
        Verde: pares — Naranja: impares — Azul: múltiplos de 5 (tengan la paridad que tengan).
    </p>
</div>

<div class="tarjeta">
    <h2>Resultados de los contadores</h2>
    <table>
        <thead>
            <tr>
                <th class="izquierda">Concepto</th>
                <th>Cantidad</th>
                <th>Porcentaje</th>
                <th>Suma</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="izquierda">Números pares</td>
                <td><?= $contadorPares ?></td>
                <td><?= $porcentajePares ?> %</td>
                <td><?= $sumaPares ?></td>
            </tr>
            <tr>
                <td class="izquierda">Números impares</td>
                <td><?= $contadorImpares ?></td>
                <td><?= $porcentajeImpares ?> %</td>
                <td><?= $sumaImpares ?></td>
            </tr>
            <tr>
                <td class="izquierda">Múltiplos de 5</td>
                <td><?= $contadorMultiplos5 ?></td>
                <td>—</td>
                <td>—</td>
            </tr>
            <tr>
                <td class="izquierda"><strong>Total del rango</strong></td>
                <td><strong><?= $cantidadNumeros ?></strong></td>
                <td><strong>100 %</strong></td>
                <td><strong><?= $sumaTotal ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="resultado">
        <span class="numero-grande"><?= $sumaTotal ?></span>
        Suma de todos los números del rango. Promedio: <strong><?= $promedio ?></strong>
    </div>
</div>

<div class="tarjeta">
    <h2>Cuenta regresiva (do...while)</h2>
    <p>
        El <code>do...while</code> ejecuta primero y pregunta después, por eso
        siempre da al menos una vuelta. Se usa para mostrar los últimos turnos
        disponibles del día:
    </p>
    <p>
        <?php foreach ($cuentaRegresiva as $t): ?>
            <span class="etiqueta azul" style="margin-right:5px">Quedan <?= $t ?></span>
        <?php endforeach; ?>
        <span class="etiqueta roja">Sin turnos</span>
    </p>
</div>

<?php require __DIR__ . '/partials/pie.php'; ?>
