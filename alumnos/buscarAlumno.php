<?php
include("../conexion.php");

$con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

$limite = 7;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
$status = isset($_GET['status']) ? $_GET['status'] : '0';
$curso = isset($_GET['curso']) ? $_GET['curso'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : ''; // Nueva variable de búsqueda

// Validación de columnas y estado
$validColumns = ['nombre', 'apellido', 'fechaAlta'];
if (!in_array($orderBy, $validColumns)) {
    $orderBy = 'nombre';
}

$validStatus = ['0', '1', '2'];
if (!in_array($status, $validStatus)) {
    $status = '0';
}

// Construir la consulta con el filtro de estado, curso, búsqueda y orden
$query = "SELECT DNI_alumno, nombre, apellido, curso, especialidad, fechaAlta, fechaBaja
          FROM alumnos
          WHERE baja = $status";

// Agregar el filtro de curso si se selecciona
if (!empty($curso)) {
    $query .= " AND curso = '$curso'";
}

// Agregar búsqueda si se introduce una búsqueda
if (!empty($search)) {
    $query .= " AND (nombre LIKE '%$search%' OR apellido LIKE '%$search%')";
}

// Agregar orden por la columna seleccionada
$query .= " ORDER BY $orderBy";

// Agregar la limitación de paginación
$query .= " LIMIT $limite OFFSET $offset";

// Ejecutar consulta
$result = mysqli_query($con, $query) or die("ERROR AL OBTENER ALUMNOS");

// Generar la tabla de resultados
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['DNI_alumno'] . "</td>";
    echo "<td>" . $row['nombre'] . "</td>";
    echo "<td>" . $row['apellido'] . "</td>";
    echo "<td>" . $row['curso'] . "</td>";
    echo "<td>" . $row['especialidad'] . "</td>";
    echo "<td>" . $row['fechaAlta'] . "</td>";
    echo "<td class='acciones'>
            <a class='btn-accion' href='listar-modi-alumno.php?alumno=" . $row['DNI_alumno'] . "'>
                <img src='../SVG/lapiz.svg' alt='Modificar' class='icono' width='24px'>
            </a>
            <form method='POST' action='listar-delete-alumno.php' style='display:inline;'>
                <input type='hidden' name='DNI' value='" . $row['DNI_alumno'] . "'>
                <button type='submit' class='btn-accion'>
                    <img src='../SVG/si.svg' alt='Eliminar' class='icono'>
                </button>
            </form>
        </td>";
    echo "</tr>";
}
