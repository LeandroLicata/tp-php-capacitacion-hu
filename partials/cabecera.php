<?php
/**
 * Cabecera común a todos los scripts del trabajo práctico.
 * Cada script define $tituloPagina y $subtitulo antes de incluir este archivo.
 * El operador ?? asigna un valor por defecto si la variable no está definida.
 */
$tituloPagina = $tituloPagina ?? 'Trabajo Práctico PHP';
$subtitulo    = $subtitulo ?? 'Capacitación HU';
$esInicio     = $esInicio ?? false;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $tituloPagina ?> — Capacitación HU</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="contenedor">
        <header class="encabezado">
            <h1><?= $tituloPagina ?></h1>
            <p><?= $subtitulo ?></p>
        </header>
        <div class="migas">
            <?php if ($esInicio): ?>
                Leandro Licata — Capacitación HU — Módulo: Programación en PHP
            <?php else: ?>
                <a href="index.php">← Volver al índice de scripts</a>
            <?php endif; ?>
        </div>
