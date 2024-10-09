<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Profesores</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="prese">
            <h1>Formulario de Alta de Profesores</h1>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </div>
        <div class="menu-buttons">
            <button id="openMenu" class="botone">
                <div class="svg-container">
                    <svg width="50px" height="50px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
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
                        <svg width="50px" height="50px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#333" stroke-width="0" />
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                            <g id="SVGRepo_iconCarrier">
                                <rect x="0" fill="none" width="24" height="24" />
                                <g>
                                    <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z" />
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
                    <li><a class="nav" href="#">Profesores</a></li>
                </h2>
            </ul>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>

    <div class="content-wrapper">
        <div class="form-container">
            <div class="Tabla">
                <!-- Formulario de registro -->
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-profesor">
                    <input type="number" name="DNI" class="input-field" placeholder="DNI" required min="10000000" max="99999999"> <br>
                    <input type="text" name="Nombre" class="input-field" placeholder="Nombre" required><br>
                    <input type="text" name="Apellido" class="input-field" placeholder="Apellido" required><br>
                    <div class='col-md-4'>
                        <select class='form-select' id='materiaSelect' name="Especialidad" required style="width: 260px
    ">
                            <option value="" disabled selected>Especialidad</option>
                            <option value='Matemática'>Matemática</option>
                            <option value='Física'>Física</option>
                            <option value='Electrónica'>Electrónica</option>
                            <option value='Historia'>Historia</option>
                            <option value='Informática'>Informática</option>
                            <option value='Construcciones'>Construcciones</option>
                            <option value='Biología'>Biología</option>
                            <option value='Educación Física'>Educación Física</option>
                            <option value='Programación'>Programación</option>
                            <option value='Lengua'>Lengua</option>
                            <option value='Biología Molecular'>Biología Molecular</option>
                        </select>
                    </div>

                    <input type="submit" value="Registrar" class="submit-button">
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Incluir conexión
                    include("../conexion.php");

                    // Inicializar valores
                    $dni = $_POST['DNI'];
                    $nombre = $_POST['Nombre'];
                    $apellido = $_POST['Apellido'];
                    $especialidad = $_POST['Especialidad'];
                    $baja = 0; // El valor por defecto de baja es 0 (Alta)

                    // Conexión a la base de datos
                    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

                    // Verificar si el DNI ya existe en la base de datos
                    $check_query = "SELECT DNI_profesor FROM profesor WHERE DNI_profesor = '$dni'";
                    $check_result = mysqli_query($con, $check_query);

                    if (mysqli_num_rows($check_result) > 0) {
                        echo "<div class='alert alert-danger'>El DNI '$dni' ya existe en la base de datos.</div>";
                        echo "<a href='" . $_SERVER['PHP_SELF'] . "'>Volver al formulario</a>";
                    } else {
                        // Si el DNI no existe, insertar el nuevo profesor
                        $insert_query = "INSERT INTO profesor (DNI_profesor, nombre, apellido, especialidad, baja) VALUES
                        ('$dni', '$nombre', '$apellido', '$especialidad', '$baja')";

                        if (mysqli_query($con, $insert_query)) {
                            // Mostrar el mensaje de exito fuera del formulario
                            echo "<div class='alert alert-success mt-3'>Datos insertados correctamente.</div>";
                            echo "<a href='../alumnos/listarAlumnos.php' class='btn btn-success mt-2'>Volver</a>";
                        } else {
                            echo "<div class='alert alert-danger'>Error al insertar los datos: " . mysqli_error($con) . "</div>";
                        }
                    }

                    // Cerrar la conexion
                    mysqli_close($con);
                }
                ?>
            </div>
        </div>
    </div>

    <script src="../JavaScript/menu.js"></script>
</body>

</html>