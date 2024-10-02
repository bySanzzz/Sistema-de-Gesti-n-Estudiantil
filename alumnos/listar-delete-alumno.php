<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Alumno</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="prese">
        <h1>Eliminar Alumno</h1>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </div>
    <div class="menu-buttons">
        <button id="openMenu" class="botone">
            <div class="svg-container">
                <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
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
                    <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
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
            <h2><li><a href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li></h2>
        </ul>
        <div class="logo">
            <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
        </div>
    </nav>
</header>
<script src="../JavaScript/menu.js"></script>

<?php
    include("../conexion.php");

    // Variable para almacenar mensajes
    $mensaje = '';

    // Conectar a la base de datos
    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    // Verificar si se ha recibido el DNI por POST
    if (isset($_POST['DNI'])) {
        // Obtener el DNI del alumno desde el POST
        $dni = $_POST['DNI'];

        // Consulta para verificar si el DNI ya existe en la base de datos
        $check_query = "SELECT * FROM alumnos WHERE DNI_alumno = '$dni'";
        $check_result = mysqli_query($con, $check_query) or die("Fallo en la consulta");

        // Si hay resultados, significa que el DNI existe
        if (mysqli_num_rows($check_result) > 0) {
            // Mostrar los datos del alumno
            while ($row = mysqli_fetch_array($check_result)) {
?>
                <form method="POST" action="">
                    DNI: <input type="text" name="modiDNI" value="<?php echo($row['DNI_alumno']); ?>" readonly> <br>
                    Nombre: <?php echo($row['nombre']); ?> <br>
                    Apellido: <?php echo($row['apellido']); ?> <br>
                    Curso: <?php echo($row['curso']); ?> <br>
                    Especialidad: <?php echo($row['especialidad']); ?> <br>
                    Fecha Alta: <?php echo($row['fechaAlta']); ?> <br>
                    Fecha Baja: <?php echo($row['fechaBaja']); ?> <br>
                    <br>
                    <input type="button" name="eliminar" value="Eliminar Alumno" class="btn btn-danger" style="background-color: red;" onclick="confirmDelete('<?php echo $row['DNI_alumno']; ?>');">
                </form>
                <div class="volvido">
                    <a href="../alumnos/listarAlumnos.php">VOLVER</a>
                </div>
<?php
            }
        } else {
            // Si el DNI no existe en la base de datos
            $mensaje = "<div class='alert alert-danger'>El alumno con DNI '$dni' no existe.</div>";
        }
    }

    // Código para eliminar al alumno
    if (isset($_POST['eliminar']) && isset($_POST['modiDNI'])) {
        $dni_eliminar = $_POST['modiDNI'];
        $query_delete = "DELETE FROM alumnos WHERE DNI_alumno ='$dni_eliminar'";
        $resultado = mysqli_query($con, $query_delete) or die("FALLO DE CONSULTA");

        if ($resultado) {
            echo "<script>
                Swal.fire({
                    position: 'mid',
                    icon: 'success',
                    title: 'El alumno ha sido eliminado correctamente.',
                    html: '<a href=\"../alumnos/listarAlumnos.php\" class=\"btn btn-success\">VOLVER A LISTA DE ALUMNOS</a>',
                    showConfirmButton: false,
                    
                });
              </script>";
        }    
    }
?>

<!-- Mostrar mensaje si existe -->
<?php if ($mensaje != ''): ?>
    <?php echo $mensaje; ?>
<?php endif; ?>

<!-- Confirmación antes de eliminar -->
<script>
function confirmDelete(dni) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear un formulario y enviarlo para eliminar el alumno
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // La misma página
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'modiDNI';
            input.value = dni; // Asignar el DNI del alumno a eliminar
            form.appendChild(input);
            const eliminarInput = document.createElement('input');
            eliminarInput.type = 'hidden';
            eliminarInput.name = 'eliminar';
            eliminarInput.value = 'true'; // Indicador de eliminación
            form.appendChild(eliminarInput);
            document.body.appendChild(form);
            form.submit(); // Enviar el formulario
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                icon: "error"
            });
        }
    });
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
