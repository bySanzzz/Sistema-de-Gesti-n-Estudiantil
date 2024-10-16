<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reintegrar Alumno</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header>
        <div class="prese">
            <h1>Reintegrar Alumno</h1>
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
                    <li><a href="http://localhost:8080/escuela1/alumnos/listarAlumnos.php">Alumno</a></li>
                </h2>
                <h2>
                    <li><a href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li>
                </h2>
            </ul>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>
    <script src="../JavaScript/menu.js"></script>

    <?php
    include("../conexion.php");


    function obtenerAlumno($con, $id)
    {

        // Obtener los datos del alumno desde la tabla respaldoAlumnos
        $query_select = "SELECT * FROM respaldoalumnos WHERE ID = '$id'";
        $result = mysqli_query($con, $query_select);
        $alumno = mysqli_fetch_assoc($result);
        return $alumno;
    }

    // Variable para almacenar mensajes
    $mensaje = '';

    // Conectar a la base de datos
    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");
    if (!$con) {
        die("Fallo de conexión: " . mysqli_connect_error());
    }
    $alumno = null;
    if (isset($_POST['alumno'])) {
        // Obtener el ID del alumno desde POST
        $alumno_id = mysqli_real_escape_string($con, $_POST['alumno']);

        $alumno = obtenerAlumno($con, $alumno_id);


        // Si existe el alumno en respaldoAlumnos
        if ($alumno) {
            //print_r($alumno);
    ?>
            <form method="POST" action="" id="reintegrarForm">
                DNI: <input type="text" name="modiDNI" value="<?php echo $alumno['DNI_alumno']; ?>" readonly><br>
                Nombre: <?php echo $alumno['nombre']; ?><br>
                Apellido: <?php echo $alumno['apellido']; ?><br>
                Curso: <?php echo $alumno['curso']; ?><br>
                Especialidad: <?php echo $alumno['especialidad']; ?><br>
                <br>
                <input type="hidden" name="reintegrar" value="<?php echo $alumno['ID']; ?>">
                <button type="button" class="btn btn-success" onclick="confirmReintegrate()">Restaurar Alumno</button>
            </form>
            <div class="volvido">
                <a href="../alumnos/listarAlumnos.php">VOLVER</a>
            </div>
    <?php
        } else {
            $mensaje = "<div class='alert alert-danger'>Alumno no encontrado en la tabla respaldoAlumnos.</div>";
        }
    }

    if (isset($_POST['reintegrar']) && isset($_POST['modiDNI'])) {

        $alumno_id = $_POST['reintegrar'];
        $dni = $_POST['modiDNI'];
        $alumno = obtenerAlumno($con, $alumno_id);

        $check_query = "SELECT * FROM alumnos WHERE DNI_alumno = '$dni'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $mensaje = "<div class='alert alert-warning'>El alumno con DNI $dni ya está registrado.</div>";
        } else {

            $insert_query = "INSERT INTO alumnos (DNI_alumno, nombre, apellido, curso, especialidad, baja) VALUES ('{$alumno['DNI_alumno']}', '{$alumno['nombre']}', '{$alumno['apellido']}', '{$alumno['curso']}', '{$alumno['especialidad']}', 0)";
            $resultado = mysqli_query($con, $insert_query);

            if ($resultado) {
                $delete_query = "DELETE FROM respaldoAlumnos WHERE ID = '$alumno_id'";
                mysqli_query($con, $delete_query);

                echo "<script>
                    Swal.fire({
                        position: 'mid',
                        icon: 'success',
                        title: 'Alumno reintegrado correctamente.',
                        html: '<a href=\"../alumnos/listarAlumnos.php\" class=\"btn btn-success\">VOLVER A LISTA DE ALUMNOS</a>',
                        showConfirmButton: false,
                    });
                  </script>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Hubo un error al reintegrar al alumno.</div>";
            }
        }
    }
    ?>

    <!-- Mostrar mensaje si existe -->
    <?php if ($mensaje != ''): ?>
        <?php echo $mensaje; ?>
    <?php endif; ?>

    <script>
        function confirmReintegrate() {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡El alumno será reintegrado!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí",
                cancelButtonText: "No",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reintegrarForm').submit();
                }
            });
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>