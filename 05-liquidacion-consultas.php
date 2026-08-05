<?php
/* =================================================================
   SCRIPT 5 — LIQUIDACIÓN DE CONSULTAS MÉDICAS
   Calcula cuánto debe abonar un paciente por un tratamiento de varias
   sesiones, aplicando la cobertura de su obra social y el descuento o
   recargo según la forma de pago.

   Estructuras utilizadas:
     - Variables, constantes (define) y arreglos asociativos
     - Operaciones matemáticas: multiplicación, porcentajes, división, redondeo
     - Estructuras condicionales: switch y if / elseif / else
     - Estructuras repetitivas: foreach (detalle de sesiones) y for (plan de cuotas)
   ================================================================= */

// ---------- 1. Constantes del sistema ----------
// Una constante es un valor que no cambia durante la ejecución.
define('VALOR_CONSULTA', 25000);       // valor de una sesión, en pesos
define('DESCUENTO_CONTADO', 10);       // % de descuento por pago contado
define('RECARGO_CUOTAS', 15);          // % de recargo por pago en cuotas

// ---------- 2. Entrada de datos ----------
$paciente   = $_GET['paciente'] ?? 'Jorge Pereyra';
$obraSocial = $_GET['obra_social'] ?? 'OSDE';
$sesiones   = isset($_GET['sesiones']) ? (int) $_GET['sesiones'] : 4;
$formaPago  = $_GET['forma_pago'] ?? 'cuotas';
$cantCuotas = isset($_GET['cuotas']) ? (int) $_GET['cuotas'] : 3;

// Validaciones básicas de rango
if ($sesiones < 1) {
    $sesiones = 1;
} elseif ($sesiones > 12) {
    $sesiones = 12;
}

if ($cantCuotas < 2 || $cantCuotas > 12) {
    $cantCuotas = 3;
}

// ---------- 3. Estructura condicional: switch por obra social ----------
// Cada obra social cubre un porcentaje distinto de la consulta.
switch ($obraSocial) {
    case 'OSDE':
        $porcentajeCobertura = 80;
        break;
    case 'PAMI':
        $porcentajeCobertura = 100;
        break;
    case 'Swiss Medical':
        $porcentajeCobertura = 70;
        break;
    case 'OSEP':
        $porcentajeCobertura = 50;
        break;
    default:                          // "Particular" o cualquier otro valor
        $obraSocial = 'Particular';
        $porcentajeCobertura = 0;
}

// ---------- 4. Operaciones matemáticas ----------
$subtotal        = VALOR_CONSULTA * $sesiones;                       // multiplicación
$montoCobertura  = ($subtotal * $porcentajeCobertura) / 100;         // porcentaje
$aCargoPaciente  = $subtotal - $montoCobertura;                      // resta

// ---------- 5. Segunda condicional: forma de pago ----------
if ($formaPago === 'contado') {
    $ajustePorcentaje = -DESCUENTO_CONTADO;                          // descuento
    $ajusteMonto      = ($aCargoPaciente * DESCUENTO_CONTADO) / 100;
    $total            = $aCargoPaciente - $ajusteMonto;
    $textoAjuste      = "Descuento del " . DESCUENTO_CONTADO . " % por pago contado";
    $claseAjuste      = "exito";
} elseif ($formaPago === 'cuotas') {
    $ajustePorcentaje = RECARGO_CUOTAS;                              // recargo
    $ajusteMonto      = ($aCargoPaciente * RECARGO_CUOTAS) / 100;
    $total            = $aCargoPaciente + $ajusteMonto;
    $textoAjuste      = "Recargo del " . RECARGO_CUOTAS . " % por pago en {$cantCuotas} cuotas";
    $claseAjuste      = "alerta";
} else {
    $formaPago        = 'transferencia';
    $ajustePorcentaje = 0;
    $ajusteMonto      = 0;
    $total            = $aCargoPaciente;
    $textoAjuste      = "Sin descuentos ni recargos";
    $claseAjuste      = "";
}

$total       = round($total, 2);
$valorCuota  = round($total / $cantCuotas, 2);    // división

$tituloPagina = "Script 5 — Liquidación de consultas";
$subtitulo    = "Operaciones matemáticas, switch, if / elseif / else y bucles";
require __DIR__ . '/partials/cabecera.php';
?>

<form class="parametros" method="get" action="05-liquidacion-consultas.php">
    <div>
        <label for="paciente">Paciente</label>
        <input type="text" id="paciente" name="paciente" value="<?= htmlspecialchars($paciente) ?>">
    </div>
    <div>
        <label for="obra_social">Obra social</label>
        <select id="obra_social" name="obra_social">
            <?php
            // El arreglo asociativo guarda pares clave => valor
            $opciones = [
                'OSDE'          => 'OSDE (80 %)',
                'PAMI'          => 'PAMI (100 %)',
                'Swiss Medical' => 'Swiss Medical (70 %)',
                'OSEP'          => 'OSEP (50 %)',
                'Particular'    => 'Particular (0 %)',
            ];
            foreach ($opciones as $clave => $texto) {
                $seleccionada = ($clave === $obraSocial) ? "selected" : "";
                echo "<option value='{$clave}' {$seleccionada}>{$texto}</option>";
            }
            ?>
        </select>
    </div>
    <div>
        <label for="sesiones">Sesiones</label>
        <input type="number" id="sesiones" name="sesiones" min="1" max="12" value="<?= $sesiones ?>">
    </div>
    <div>
        <label for="forma_pago">Forma de pago</label>
        <select id="forma_pago" name="forma_pago">
            <option value="contado" <?= $formaPago === 'contado' ? 'selected' : '' ?>>Contado</option>
            <option value="cuotas" <?= $formaPago === 'cuotas' ? 'selected' : '' ?>>En cuotas</option>
            <option value="transferencia" <?= $formaPago === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
        </select>
    </div>
    <div>
        <label for="cuotas">Cantidad de cuotas</label>
        <input type="number" id="cuotas" name="cuotas" min="2" max="12" value="<?= $cantCuotas ?>">
    </div>
    <button type="submit">Calcular</button>
</form>

<div class="tarjeta">
    <h2>Detalle de las sesiones</h2>
    <table>
        <thead>
            <tr>
                <th>Sesión</th>
                <th class="izquierda">Concepto</th>
                <th class="derecha">Valor</th>
                <th class="derecha">Acumulado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // ---------- 6. Estructura repetitiva: foreach sobre el rango ----------
            $acumulado = 0;
            foreach (range(1, $sesiones) as $nroSesion) {
                $acumulado = $acumulado + VALOR_CONSULTA;
                echo "<tr>";
                echo "  <td>{$nroSesion}</td>";
                echo "  <td class='izquierda'>Consulta médica</td>";
                echo "  <td class='derecha'>$ " . number_format(VALOR_CONSULTA, 2, ',', '.') . "</td>";
                echo "  <td class='derecha'>$ " . number_format($acumulado, 2, ',', '.') . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="tarjeta">
    <h2>Liquidación</h2>
    <ul class="datos">
        <li><strong>Paciente:</strong> <?= htmlspecialchars($paciente) ?></li>
        <li><strong>Obra social:</strong> <?= $obraSocial ?> — cubre el <?= $porcentajeCobertura ?> %</li>
        <li><strong>Subtotal (<?= $sesiones ?> × $ <?= number_format(VALOR_CONSULTA, 2, ',', '.') ?>):</strong>
            $ <?= number_format($subtotal, 2, ',', '.') ?></li>
        <li><strong>Cubre la obra social:</strong> – $ <?= number_format($montoCobertura, 2, ',', '.') ?></li>
        <li><strong>A cargo del paciente:</strong> $ <?= number_format($aCargoPaciente, 2, ',', '.') ?></li>
        <li><strong><?= $textoAjuste ?>:</strong>
            <?= $ajustePorcentaje < 0 ? '–' : ($ajustePorcentaje > 0 ? '+' : '') ?>
            $ <?= number_format($ajusteMonto, 2, ',', '.') ?></li>
    </ul>

    <div class="resultado <?= $claseAjuste ?>">
        <span class="numero-grande">$ <?= number_format($total, 2, ',', '.') ?></span>
        Total a abonar por el paciente.
    </div>
</div>

<?php if ($formaPago === 'cuotas'): ?>
    <div class="tarjeta">
        <h2>Plan de pago en <?= $cantCuotas ?> cuotas</h2>
        <table>
            <thead>
                <tr>
                    <th>Cuota</th>
                    <th>Vencimiento</th>
                    <th class="derecha">Importe</th>
                    <th class="derecha">Saldo restante</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ---------- 7. Segunda estructura repetitiva: for ----------
                $saldo = $total;
                for ($cuota = 1; $cuota <= $cantCuotas; $cuota++) {
                    // La última cuota absorbe la diferencia por redondeo,
                    // para que la suma de las cuotas dé exactamente el total.
                    if ($cuota == $cantCuotas) {
                        $importe = round($saldo, 2);
                    } else {
                        $importe = $valorCuota;
                    }

                    $saldo = round($saldo - $importe, 2);
                    // strtotime suma meses a la fecha actual
                    $vencimiento = date('d/m/Y', strtotime("+{$cuota} month"));

                    echo "<tr>";
                    echo "  <td>{$cuota} de {$cantCuotas}</td>";
                    echo "  <td>{$vencimiento}</td>";
                    echo "  <td class='derecha'>$ " . number_format($importe, 2, ',', '.') . "</td>";
                    echo "  <td class='derecha'>$ " . number_format($saldo, 2, ',', '.') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="nota">
            El bucle recalcula el saldo en cada vuelta y ajusta la última cuota,
            de modo que la suma de las cuotas coincida con el total exacto.
        </p>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/pie.php'; ?>
