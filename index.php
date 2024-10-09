<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principal</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="CSS/bodyindex.css">
    <link rel="icon" type="image/png" href="favicon.png"> <!-- Asegúrate de que el nombre de tu imagen sea correcto -->


</head>

<body>
    <header>
        <div class="prese">
            <h1>Sistema de Gestión de Notas Estudiantiles</h1>
            <div class="logo">
                <img src="Imagenes/sanmiguel.png" alt="Logo San Miguel">
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
                    <li><a href="http://localhost:8080/escuela1/alumnos/listarAlumnos.php">Alumnos</a></li>
                </h2>
                <h2>
                    <li><a href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li>
                </h2>
            </ul>
            <div class="logo2">
                <img src="Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>

    <script src="../escuela1/JavaScript/menu.js"></script>

    <!-- Contenedor para las consultas -->
    <div class="query-container">
        <h2>Resumen de Alumnos</h2>
        <?php
        include("conexion.php");
        $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

        // Consulta para contar los alumnos que no están dados de baja
        $query = "SELECT COUNT(DNI_alumno) as Total_Alumnos FROM `alumnos` WHERE baja=0";
        $result = mysqli_query($con, $query) or die("ERROR DE CONSULTA");

        // Extraer el resultado
        if ($row = mysqli_fetch_assoc($result)) {
            // Imprimir el total de alumnos
            echo "<p>Total de Alumnos Activos: " . $row['Total_Alumnos'] . "</p>";
        } else {
            echo "<p>No se encontraron resultados.</p>";
        }

        // Consulta para contar el total de alumnos registrados
        $query2 = "SELECT COUNT(DNI_alumno) as Total_Alumnos FROM `alumnos`";
        $result2 = mysqli_query($con, $query2) or die("ERROR DE CONSULTA");

        // Extraer el resultado 
        if ($row2 = mysqli_fetch_assoc($result2)) {
            // Imprimir el total de alumnos
            echo "<p>Total de Alumnos Registrados: " . $row2['Total_Alumnos'] . "</p>";
        } else {
            echo "<p>No se encontraron resultados.</p>";
        }

        // Consulta para contar alumnos activos en cada curso
        $query = "SELECT curso, COUNT(DNI_alumno) as Total_Alumnos 
        FROM alumnos 
        WHERE baja = 0 
        GROUP BY curso;";
        $result = mysqli_query($con, $query) or die("ERROR DE CONSULTA");

        // Comprobar si hay resultados
        if (mysqli_num_rows($result) > 0) {
            // Imprimir los totales por curso
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Curso</th><th>Total de Alumnos</th></tr></thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row['curso'] . "</td><td>" . $row['Total_Alumnos'] . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No se encontraron resultados.</p>";
        }

        // Cerrar la conexión
        mysqli_close($con);
        ?>

    </div>
    <style>
        .query-container {
            background-color: #ffffff;
            border: 2px solid #2b8aaf;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 40px auto;
        }


        .query-container h2 {
            color: #2b8aaf;
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }


        .query-container p {
            font-size: 1.2em;
            color: #333333;
            margin-bottom: 10px;
        }
    </style>

</body>

</html>