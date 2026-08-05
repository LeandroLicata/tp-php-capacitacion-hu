# Trabajo Práctico — Programación en PHP

**Capacitación HU — Módulo: Sintaxis, variables y estructuras de control**
Alumno: Leandro Licata

Conjunto de scripts en PHP que resuelven problemas básicos de lógica. Todos los
ejemplos usan la temática de una clínica, para dar continuidad al modelo de datos
del trabajo práctico de Bases de Datos.

---

## Cómo ejecutarlo

1. Copiar la carpeta `tp-php-capacitacion-hu` dentro de `C:\laragon\www\`.
2. Iniciar Laragon (botón **Iniciar todo**) para levantar Apache + PHP.
3. Abrir en el navegador: <http://localhost/tp-php-capacitacion-hu/>

Entorno usado para las pruebas: **Laragon 6 — Apache 2.4 + PHP 8.3.30** en Windows 11.

---

## Estructura del proyecto

```
tp-php-capacitacion-hu/
├── index.php                     Índice: menú de scripts y tabla de requisitos
├── 01-promedio.php               Cálculo de promedio de notas
├── 02-validacion-edad.php        Validación de edad y derivación al servicio
├── 03-tabla-multiplicar.php      Tabla de multiplicar simple y grilla completa
├── 04-contador-numeros.php       Contador de pares, impares y múltiplos
├── 05-liquidacion-consultas.php  Liquidación con cobertura y plan de cuotas
├── partials/
│   ├── cabecera.php              Cabecera HTML común a todas las páginas
│   └── pie.php                   Pie de página común
└── assets/
    └── style.css                 Hoja de estilos compartida
```

Los archivos de `partials/` se incluyen con `require` para no repetir el mismo
HTML en los seis scripts.

---

## Los scripts

| # | Script | Qué resuelve | Estructuras aplicadas |
|---|--------|--------------|-----------------------|
| 1 | `01-promedio.php` | Promedia las notas de un alumno, busca la mayor y la menor, cuenta aprobadas y define la condición final. | `foreach`, `if / elseif / else`, ternario |
| 2 | `02-validacion-edad.php` | Calcula la edad exacta desde la fecha de nacimiento, valida que sea posible y deriva al paciente al servicio correspondiente. | `if` anidado, `if / elseif / else`, `switch` |
| 3 | `03-tabla-multiplicar.php` | Genera la tabla de un número elegido y la grilla completa del 1 al 10. | `for`, `for` anidado, `if / else` |
| 4 | `04-contador-numeros.php` | Recorre un rango contando pares, impares y múltiplos de 5, y acumula la suma. | `while`, `do...while`, `if / else` |
| 5 | `05-liquidacion-consultas.php` | Calcula el importe de un tratamiento con la cobertura de la obra social y el ajuste por forma de pago. | `switch`, `if / elseif / else`, `foreach`, `for` |

---

## Checklist de requisitos

- [x] Al menos 5 scripts en PHP → 5 scripts + índice
- [x] Uso de variables → escalares, arreglos indexados y asociativos, constantes (`define`)
- [x] Operaciones matemáticas básicas → suma, resta, multiplicación, división, módulo, porcentajes, redondeo
- [x] Al menos 2 estructuras condicionales → `if / else`, `if / elseif / else`, `if` anidado, `switch`, operador ternario
- [x] Al menos 2 estructuras repetitivas → `for`, `for` anidado, `foreach`, `while`, `do...while`
- [x] Ejemplo de cálculo de promedio → script 1
- [x] Ejemplo de validación de edad → script 2
- [x] Ejemplo de tabla de multiplicar → script 3
- [x] Ejemplo de contador de números → script 4
- [x] Capturas de ejecución en el servidor local → carpeta `capturas-php`

---

## Notas técnicas

- Los cinco scripts reciben parámetros por `$_GET` mediante un formulario, pero
  todos definen valores por defecto con el operador `??`, así que funcionan
  igual si se los abre sin parámetros.
- En el script 1 las notas llegan como un único texto ("8, 6, 9"), así que se
  convierten en arreglo con `explode()` descartando los valores que no son
  números del 1 al 10.
- Los datos que provienen del usuario se imprimen con `htmlspecialchars()` para
  evitar que se inyecte HTML en la página.
- La validación de rangos se hace siempre antes del cálculo: si un dato es
  inválido, el script lo corrige al valor por defecto o corta la ejecución con
  un mensaje de error.
