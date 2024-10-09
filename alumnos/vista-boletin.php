<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletin del Alumno</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <header>
        <div class="prese">
            <h1>Boletin del Alumno</h1>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </div>
        <div class="menu-buttons">
            <button id="openMenu" class="botone">
                <div class="svg-container">
                    <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#2b8aaf" stroke-width="0" />
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                        <g id="SVGRepo_iconCarrier">
                            <rect x="0" fill="none" width="24" height="24" />
                            <g>
                                <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z" />
                            </g>
                        </g>
                    </svg>
                </div>
            </button>
        </div>
        <nav class="nav-list">
            <div class="menu-buttons">
                <button id="closeMenu" class="botone2">
                    <div class="svg-container">
                        <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#333" stroke-width="0" />
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                            <g id="SVGRepo_iconCarrier">
                                <rect x="0" fill="none" width="24" height="24" />
                                <g>
                                    <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z" />
                                </g>
                            </g>
                        </svg>
                    </div>
                </button>
            </div>
            <ul>
                <h2>
                    <li><a href="http://localhost:8080/escuela1/">Principal</a></li>
                </h2>
                <h2>
                    <li><a class="nav" href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li>
                </h2>
                <h2>
                    <li><a class="nav" href="#">Alumnos</a></li>
                </h2>
            </ul>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>
    <script src="../JavaScript/menu.js"></script>

    <div class="container mt-4">
        <?php
        include("../conexion.php");

        // Crear conexion
        $con = mysqli_connect($host, $user, $pwd, $BD);

        // Verificar conexion
        if (!$con) {
            die("Conexion fallida: " . mysqli_connect_error());
        }

        // Capturar DNI desde la URL
        $dni = isset($_GET['alumno']) ? $_GET['alumno'] : null;

        if (!$dni) {
            die("No se ha proporcionado un DNI válido.");
        }
        $alumno_dni = isset($_GET['alumno']) ? mysqli_real_escape_string($con, $_GET['alumno']) : null;

        // Consulta para obtener la informacion del boletin junto con la última fecha
        $query = "SELECT
            alumnos.DNI_alumno,
            alumnos.nombre,
            alumnos.apellido,
            alumnos.curso,
            alumnos.especialidad,
            materias.nombreMateria,
            boletin.notaTP,
            boletin.notaExamen,
            boletin.notaConcepto,
            boletin.promedio,
            boletin.DNI_alumno as tiene,
            MAX(boletin.fecha) AS ultima_fecha
        FROM
            alumnos
        INNER JOIN
            boletin ON alumnos.DNI_alumno = boletin.DNI_alumno
        INNER JOIN
            materias ON boletin.ID_materia = materias.ID_materia
        WHERE
            alumnos.DNI_alumno = '$dni'
        GROUP BY
            materias.ID_materia, boletin.notaTP, boletin.notaExamen, boletin.notaConcepto, boletin.promedio";

        // Ejecutar la consulta
        $result = mysqli_query($con, $query);

        // Verificar si hay resultados
        if (mysqli_num_rows($result) > 0) {
            // Obtener los datos del alumno (solo de la primera fila)
            $row = mysqli_fetch_array($result);
        ?>
            <!-- Mostrar los datos del alumno -->
            <div class="row">
                <div class="col-md-6">
                    <p><strong>DNI:</strong> <?php echo $row['DNI_alumno']; ?></p>
                    <p><strong>Nombre y Apellido:</strong> <?php echo $row['nombre'] . " " . $row['apellido']; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Curso:</strong> <?php echo $row['curso']; ?></p>
                    <p><strong>Especialidad:</strong> <?php echo $row['especialidad']; ?></p>
                </div>
            </div>

            <!-- Mostrar la ultima fecha en la que se cargaron notas -->
            <div class="d-flex justify-content-between mt-4">
                <h3>Boletin de Notas</h3>
                <h5>Última fecha de carga de notas: <?php echo  date('d-m-Y', strtotime($row['ultima_fecha'])); ?></h5>
                <!-- Boton para agregar notas boletin -->
                <a class="btn-accion" href="registrar-notas-alumno.php?alumno=<?php echo $dni; ?>">
                    <img src="../SVG/add.svg" alt="Boletin" class="icono">
                </a>
            </div>
            <style>
                .acciones {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                }

                .btn-accion {
                    justify-content: center;
                    cursor: pointer;
                    transition: background-color 0.3s;
                    border: none;
                    background: none;
                }

                .icono {
                    width: 24px;
                    height: 24px;
                    margin-top: 5px;
                }
            </style>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>TP</th>
                        <th>Examen</th>
                        <th>Concepto</th>
                        <th>Promedio</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reiniciar el cursor de los resultados y recorrer todas las filas para generar la tabla del boletin
                    mysqli_data_seek($result, 0); // Volver al primer registro

                    // Salida de datos de cada fila
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["nombreMateria"]) . "</td>"; // Asegúrate de escapar datos para evitar XSS
                        echo "<td>" . htmlspecialchars($row["notaTP"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["notaExamen"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["notaConcepto"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["promedio"]) . "</td>";
                        echo "<td>" . date('d-m-Y', strtotime($row['ultima_fecha'])) . "</td>";  // Mostrar la fecha en el formato deseado
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php
        } else {
            echo "<div class='alert alert-danger'>No hay notas registradas para este Alumno " . "</div>";
        }

        // Cerrar la conexion
        mysqli_close($con);
        ?>
    </div>

    <!-- Boton para volver -->
    <div class="text-center mt-4">
        <a href="../alumnos/listarAlumnos.php" class="btn btn-primary">VOLVER</a>
        <a class="btn-accion" href="registrar-notas-alumno.php?alumno=<?php echo $dni; ?>" style="display: inline-block;">
            <img src="../SVG/add.svg" alt="Boletin" class="icono">
        </a>
    </div>

</body>

</html>