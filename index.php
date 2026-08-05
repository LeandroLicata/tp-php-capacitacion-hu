<?php
/* =================================================================
   ÍNDICE DEL TRABAJO PRÁCTICO
   Página de inicio: arma el menú de scripts recorriendo un arreglo
   de arreglos asociativos con un foreach.
   ================================================================= */

$alumno    = "Leandro Licata";
$programa  = "Capacitación HU";
$modulo    = "Programación en PHP — Sintaxis, variables y estructuras de control";

// Arreglo con la información de cada script del trabajo práctico
$scripts = [
    [
        'archivo'      => '01-promedio.php',
        'titulo'       => '1. Cálculo de promedio',
        'descripcion'  => 'Promedia las notas de un alumno, busca la mayor y la menor, cuenta aprobadas y define su condición final.',
        'estructuras'  => ['foreach', 'if / elseif / else', 'ternario'],
    ],
    [
        'archivo'      => '02-validacion-edad.php',
        'titulo'       => '2. Validación de edad',
        'descripcion'  => 'Calcula la edad exacta a partir de la fecha de nacimiento, valida que sea posible y deriva al paciente al servicio que corresponde.',
        'estructuras'  => ['if anidado', 'if / elseif / else', 'switch'],
    ],
    [
        'archivo'      => '03-tabla-multiplicar.php',
        'titulo'       => '3. Tabla de multiplicar',
        'descripcion'  => 'Genera la tabla de un número elegido y la grilla completa del 1 al 10 con un bucle dentro de otro.',
        'estructuras'  => ['for', 'for anidado', 'if / else'],
    ],
    [
        'archivo'      => '04-contador-numeros.php',
        'titulo'       => '4. Contador de números',
        'descripcion'  => 'Recorre un rango contando pares, impares y múltiplos de 5, acumula la suma y hace una cuenta regresiva.',
        'estructuras'  => ['while', 'do...while', 'if / else'],
    ],
    [
        'archivo'      => '05-liquidacion-consultas.php',
        'titulo'       => '5. Liquidación de consultas',
        'descripcion'  => 'Calcula el importe de un tratamiento aplicando la cobertura de la obra social y el descuento o recargo según la forma de pago.',
        'estructuras'  => ['switch', 'if / elseif / else', 'foreach', 'for'],
    ],
];

// Contadores para el resumen: se calculan recorriendo el arreglo
$totalScripts     = count($scripts);
$totalEstructuras = 0;
$estructurasUsadas = [];

foreach ($scripts as $script) {
    foreach ($script['estructuras'] as $estructura) {
        $totalEstructuras++;
        // Se guarda una sola vez cada tipo de estructura
        if (!in_array($estructura, $estructurasUsadas)) {
            $estructurasUsadas[] = $estructura;
        }
    }
}

$tituloPagina = "Trabajo Práctico — Programación en PHP";
$subtitulo    = $modulo;
$esInicio     = true;
require __DIR__ . '/partials/cabecera.php';
?>

<div class="tarjeta">
    <h2>Presentación</h2>
    <ul class="datos">
        <li><strong>Alumno:</strong> <?= $alumno ?></li>
        <li><strong>Programa:</strong> <?= $programa ?></li>
        <li><strong>Módulo:</strong> <?= $modulo ?></li>
        <li><strong>Entorno:</strong> Laragon — Apache + PHP <?= phpversion() ?></li>
        <li><strong>Scripts desarrollados:</strong> <?= $totalScripts ?></li>
        <li><strong>Estructuras de control aplicadas:</strong> <?= count($estructurasUsadas) ?> tipos distintos (<?= $totalEstructuras ?> usos en total)</li>
    </ul>
    <p>
        Cada script resuelve un problema de lógica distinto y está comentado
        línea por línea. Todos parten de variables, aplican operaciones
        matemáticas y combinan estructuras condicionales y repetitivas.
    </p>
</div>

<h2 style="color:#1f3a68">Scripts del trabajo práctico</h2>

<div class="menu">
    <?php
    // Recorrido del arreglo para generar una tarjeta por script
    foreach ($scripts as $script) {
        echo "<div class='item'>";
        echo "  <h2>{$script['titulo']}</h2>";
        echo "  <p>{$script['descripcion']}</p>";
        echo "  <div class='estructuras'>";

        // Bucle interno: las etiquetas de estructuras de cada script
        foreach ($script['estructuras'] as $estructura) {
            echo "<code>{$estructura}</code> ";
        }

        echo "  </div>";
        echo "  <a href='{$script['archivo']}'>Ejecutar el script →</a>";
        echo "</div>";
    }
    ?>
</div>

<div class="tarjeta" style="margin-top:1.5rem">
    <h2>Cobertura de los requisitos del trabajo práctico</h2>
    <table>
        <thead>
            <tr>
                <th class="izquierda">Requisito</th>
                <th class="izquierda">Dónde se cumple</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $requisitos = [
                ['Al menos 5 scripts en PHP', 'Scripts 1 a 5 + este índice', true],
                ['Uso de variables', 'Todos los scripts (escalares, arreglos y constantes)', true],
                ['Operaciones matemáticas básicas', 'Scripts 1, 2, 3, 4 y 5', true],
                ['Al menos 2 estructuras condicionales', 'if / elseif / else, if anidado, switch y ternario', true],
                ['Al menos 2 estructuras repetitivas', 'for, for anidado, foreach, while y do...while', true],
                ['Cálculo de promedio', 'Script 1', true],
                ['Validación de edad', 'Script 2', true],
                ['Tabla de multiplicar', 'Script 3', true],
                ['Contador de números', 'Script 4', true],
            ];

            foreach ($requisitos as $requisito) {
                $etiqueta = $requisito[2]
                    ? "<span class='etiqueta verde'>Cumplido</span>"
                    : "<span class='etiqueta roja'>Pendiente</span>";
                echo "<tr>";
                echo "  <td class='izquierda'>{$requisito[0]}</td>";
                echo "  <td class='izquierda'>{$requisito[1]}</td>";
                echo "  <td>{$etiqueta}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/partials/pie.php'; ?>
