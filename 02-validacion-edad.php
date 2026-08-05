<?php
/* =================================================================
   SCRIPT 2 — VALIDACIÓN DE EDAD
   A partir de una fecha de nacimiento calcula la edad exacta de un
   paciente, valida que la fecha sea posible y, según la edad,
   determina el servicio de la clínica que debe atenderlo.

   Estructuras utilizadas:
     - Variables recibidas por formulario ($_GET) con valor por defecto
     - Operaciones matemáticas: restas, división entera, módulo
     - Estructuras condicionales: if anidado, if / elseif / else, switch
     - Operador ternario
   ================================================================= */

// ---------- 1. Entrada de datos ----------
// Si el formulario no envía nada, se usa una fecha de ejemplo.
$fechaNacimiento = $_GET['nacimiento'] ?? '1990-05-14';
$nombrePaciente  = $_GET['paciente'] ?? 'María González';

// La fecha llega como texto "AAAA-MM-DD": se separa en tres partes.
$partes = explode('-', $fechaNacimiento);

$anioNac = isset($partes[0]) ? (int) $partes[0] : 0;
$mesNac  = isset($partes[1]) ? (int) $partes[1] : 0;
$diaNac  = isset($partes[2]) ? (int) $partes[2] : 0;

// Fecha actual del servidor
$anioHoy = (int) date('Y');
$mesHoy  = (int) date('n');
$diaHoy  = (int) date('j');

// ---------- 2. Cálculo de la edad ----------
// Primero la diferencia de años...
$edad = $anioHoy - $anioNac;

// ...y después se resta un año si todavía no cumplió en el año en curso.
// Este es un if anidado: la segunda condición solo se evalúa si el mes coincide.
if ($mesHoy < $mesNac) {
    $edad = $edad - 1;
} elseif ($mesHoy == $mesNac) {
    if ($diaHoy < $diaNac) {
        $edad = $edad - 1;
    }
}

// Días vividos aproximados: diferencia de timestamps dividida por los
// segundos que tiene un día (60 * 60 * 24 = 86400).
$segundosNacimiento = mktime(0, 0, 0, $mesNac, $diaNac, $anioNac);
$diasVividos = (int) floor((time() - $segundosNacimiento) / 86400);
$semanasVividas = intdiv($diasVividos, 7);   // división entera
$diasSueltos    = $diasVividos % 7;          // módulo: resto de la división

// ---------- 3. Validación de los datos ----------
$esValida = true;
$errorMensaje = '';

if (!checkdate($mesNac, $diaNac, $anioNac)) {
    $esValida = false;
    $errorMensaje = "La fecha ingresada no existe en el calendario.";
} elseif ($edad < 0) {
    $esValida = false;
    $errorMensaje = "La fecha de nacimiento es posterior al día de hoy.";
} elseif ($edad > 120) {
    $esValida = false;
    $errorMensaje = "La edad calculada ({$edad} años) supera el máximo admitido.";
}

// ---------- 4. Clasificación por edad (if / elseif / else) ----------
if ($esValida) {
    if ($edad < 1) {
        $categoria = "Lactante";
    } elseif ($edad < 13) {
        $categoria = "Pediatría";
    } elseif ($edad < 18) {
        $categoria = "Adolescente";
    } elseif ($edad < 65) {
        $categoria = "Adulto";
    } else {
        $categoria = "Adulto mayor";
    }

    // ---------- 5. Segunda condicional: switch sobre la categoría ----------
    // El switch es más claro que una cadena de if cuando se compara
    // una misma variable contra varios valores exactos.
    switch ($categoria) {
        case "Lactante":
        case "Pediatría":
        case "Adolescente":
            $servicio    = "Pediatría";
            $consultorio = "201 — Piso 2";
            $colorEtiqueta = "azul";
            break;
        case "Adulto":
            $servicio    = "Clínica Médica";
            $consultorio = "101 — Piso 1";
            $colorEtiqueta = "verde";
            break;
        case "Adulto mayor":
            $servicio    = "Gerontología";
            $consultorio = "305 — Piso 3";
            $colorEtiqueta = "naranja";
            break;
        default:
            $servicio    = "Guardia general";
            $consultorio = "Planta baja";
            $colorEtiqueta = "azul";
    }

    // Operador ternario: versión corta de un if / else
    $mayorDeEdad  = ($edad >= 18) ? "Sí" : "No";
    $acompaniante = ($edad < 18 || $edad >= 80) ? "Requiere acompañante" : "Puede asistir solo";
    $anioProximo  = $anioHoy + 1;
}

$tituloPagina = "Script 2 — Validación de edad";
$subtitulo    = "Condicionales if anidado, if / elseif / else y switch";
require __DIR__ . '/partials/cabecera.php';
?>

<form class="parametros" method="get" action="02-validacion-edad.php">
    <div>
        <label for="paciente">Nombre del paciente</label>
        <input type="text" id="paciente" name="paciente" value="<?= htmlspecialchars($nombrePaciente) ?>">
    </div>
    <div>
        <label for="nacimiento">Fecha de nacimiento</label>
        <input type="date" id="nacimiento" name="nacimiento" value="<?= htmlspecialchars($fechaNacimiento) ?>">
    </div>
    <button type="submit">Validar edad</button>
</form>

<div class="tarjeta">
    <h2>Datos recibidos</h2>
    <ul class="datos">
        <li><strong>Paciente:</strong> <?= htmlspecialchars($nombrePaciente) ?></li>
        <li><strong>Fecha de nacimiento:</strong> <?= $diaNac ?>/<?= $mesNac ?>/<?= $anioNac ?></li>
        <li><strong>Fecha de hoy (servidor):</strong> <?= $diaHoy ?>/<?= $mesHoy ?>/<?= $anioHoy ?></li>
    </ul>
</div>

<?php if (!$esValida): ?>

    <div class="tarjeta">
        <h2>Resultado de la validación</h2>
        <div class="resultado error">
            <strong>Fecha rechazada.</strong><br>
            <?= $errorMensaje ?>
        </div>
        <p class="nota">
            La validación se ejecuta antes de clasificar al paciente: si los datos
            no son correctos, el script no continúa con el resto del cálculo.
        </p>
    </div>

<?php else: ?>

    <div class="tarjeta">
        <h2>Edad calculada</h2>
        <div class="resultado exito">
            <span class="numero-grande"><?= $edad ?> años</span>
            Fecha válida: el paciente puede ser registrado en el sistema.
        </div>
        <ul class="datos" style="margin-top:1rem">
            <li><strong>Días vividos (aprox.):</strong> <?= number_format($diasVividos, 0, ',', '.') ?> días</li>
            <li><strong>Equivalen a:</strong> <?= number_format($semanasVividas, 0, ',', '.') ?> semanas y <?= $diasSueltos ?> días</li>
            <li><strong>¿Es mayor de edad?</strong> <?= $mayorDeEdad ?></li>
            <li><strong>Edad que cumple en <?= $anioProximo ?>:</strong> <?= $edad + 1 ?> años</li>
        </ul>
    </div>

    <div class="tarjeta">
        <h2>Derivación según la edad</h2>
        <ul class="datos">
            <li><strong>Categoría etaria:</strong> <span class="etiqueta <?= $colorEtiqueta ?>"><?= $categoria ?></span></li>
            <li><strong>Servicio asignado:</strong> <?= $servicio ?></li>
            <li><strong>Consultorio:</strong> <?= $consultorio ?></li>
            <li><strong>Observación:</strong> <?= $acompaniante ?></li>
        </ul>
        <p class="nota">
            La categoría se obtiene con una cadena <code>if / elseif / else</code> sobre
            rangos de edad, y el servicio con un <code>switch</code> sobre la categoría ya calculada.
        </p>
    </div>

<?php endif; ?>

<?php require __DIR__ . '/partials/pie.php'; ?>
