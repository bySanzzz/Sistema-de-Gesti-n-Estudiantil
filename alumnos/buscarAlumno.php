<?php
include("../conexion.php");

$searchText = isset($_GET['query']) ? $_GET['query'] : '';

// Consulta para buscar alumnos que coincidan con el texto ingresado (en nombre, apellido o DNI)
$query = "SELECT DNI_alumno, nombre, apellido, curso, especialidad, fechaAlta, fechaBaja 
          FROM alumnos 
          WHERE baja = 0 
          AND (nombre LIKE '%$searchText%' OR apellido LIKE '%$searchText%' OR DNI_alumno LIKE '%$searchText%') 
          ORDER BY nombre"; 

$result = mysqli_query($con, $query) or die("ERROR DE CONSULTA");

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['DNI_alumno'] . "</td>";
    echo "<td>" . $row['nombre'] . "</td>";
    echo "<td>" . $row['apellido'] . "</td>";
    echo "<td>" . $row['curso'] . "</td>";
    echo "<td>" . $row['especialidad'] . "</td>";
    echo "<td>" . $row['fechaAlta'] . "</td>";
    echo "<td>" . $row['fechaBaja'] . "</td>";
    echo "<td class='acciones'>
            <a class='btn-accion' href='listar-modi-alumno.php?alumno={$row['DNI_alumno']}'>
                <img src='../SVG/lapiz.svg' alt='Modificar' class='icono' width='24px'>
            </a>
            <form method='POST' action='listar-delete-alumno.php' style='display:inline;'>
                <input type='hidden' name='DNI' value='{$row['DNI_alumno']}'>
                <button type='submit' class='btn-accion'>
                    <img src='../SVG/si.svg' alt='Eliminar' class='icono'>
                </button>
            </form>
            <a class='btn-accion' href='vista-boletin.php?alumno={$row['DNI_alumno']}'>
                <img src='../SVG/libro.svg' alt='Boletín' class='icono' width='24px'>
            </a>
        </td>";
    echo "</tr>";
}

mysqli_close($con);
?>
