<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumno/Alta</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="prese">
        <h1>Alta Alumno</h1>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </div>
    <div class="menu-buttons">
        <button id="openMenu" class="botone">
            <div class="svg-container">
                <svg width="50px" height="50px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                    <g id="SVGRepo_bgCarrier" stroke-width="0">
                        <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#2b8aaf" stroke-width="0"/>
                    </g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                    <g id="SVGRepo_iconCarrier">
                        <rect x="0" fill="none" width="24" height="24"/>
                        <g>
                            <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z"/>
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
                    <svg width="50px" height="50px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#333" stroke-width="0"/>
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier">
                            <rect x="0" fill="none" width="24" height="24"/>
                            <g>
                                <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z"/>
                            </g>
                        </g>
                    </svg>
                </div>
            </button>
        </div>
        <ul>
            <h2><li><a href="http://localhost:8080/escuela1/">Principal</a></li></h2>
            <h2><li><a href="http://localhost:8080/escuela1/alumnos/listarAlumnos.php">Alumno</a></li></h2>
            <h2><li><a class="nav" href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li></h2>
        </ul>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </nav>
</header>
<script src="../JavaScript/menu.js"></script>
<div class="content-wrapper">
    <div class="Tabla">
        <form method="POST" action="">
            <input type="number" name="DNI" placeholder="DNI" required min="10000000" max="99999999"> <br>
            <input type="text" name="Nombre" placeholder="Nombre" required><br>
            <input type="text" name="Apellido" placeholder="Apellido" required><br>
            <select name="Curso" required>
                <option value="1ro">1ro</option>
                <option value="2do">2do</option>
                <option value="3ro">3ro</option>
                <option value="4to">4to</option>
                <option value="5to">5to</option>
                <option value="6to">6to</option>
                <option value="7mo">7mo</option>
            </select>
            <select name="Especialidad" required>
                <option value="Informatica">Informatica</option>
                <option value="Electronica">Electronica</option>
                <option value="Construcciones">Construcciones</option>
            </select>
            <input type="submit" value="Registrar">
        </form>

        <?php
        // Incluir el archivo de conexion
        include("../conexion.php");

        // Verificar si el formulario fue enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Variables del formulario
            $dni = mysqli_real_escape_string($conex, $_POST['DNI']);
            $nombre = mysqli_real_escape_string($conex, $_POST['Nombre']);
            $apellido = mysqli_real_escape_string($conex, $_POST['Apellido']);
            $curso = mysqli_real_escape_string($conex, $_POST['Curso']);
            $especialidad = mysqli_real_escape_string($conex, $_POST['Especialidad']);
            $baja = 0; // Estado del alumno (0 = activo)

            // Verificar si el DNI_alumno ya existe
            $check_query = "SELECT DNI_alumno FROM alumnos WHERE DNI_alumno = '$dni'";
            $check_result = mysqli_query($conex, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ya hay un usuario registrado con ese DNI',
                        });
                      </script>";
            } else {
                // Insertar los datos del alumno en la base de datos
                $query = "INSERT INTO alumnos (DNI_alumno, nombre, apellido, curso, especialidad, baja) 
                          VALUES ('$dni', '$nombre', '$apellido', '$curso', '$especialidad', '$baja')";

                if (mysqli_query($conex, $query)) {
                    echo "<script>
                            Swal.fire({
                                position: 'mid',
                                icon: 'success',
                                title: 'Datos insertados correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                          </script>";
                    echo "<a href='../index.php' class='btn btn-primary mt-2'>Volver al inicio</a>";
                } else {
                    echo "<div class='alert alert-danger'>Error al insertar los datos: " . mysqli_error($conex) . "</div>";
                }
            }

            // Cerrar la conexion
            mysqli_close($conex);
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
