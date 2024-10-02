<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Alumnos</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<header>
<div class="prese">
            <h1>Modificar Alumno</h1>
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
        <nav class="nav-list" >
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
                <h2><li><a class="nav" href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li></h2>
            </ul>
            <div class="logo">
                 <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>
<script src="../JavaScript/menu.js"></script>

<?php
    include("../conexion.php");
    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    $alumno = isset($_GET['alumno']) ? mysqli_real_escape_string($con, $_GET['alumno']) : null;
    $mensaje_actualizacion = ""; // Variable para el mensaje de actualizacion
    $mostrar_alerta = false; // Variable para controlar la visualización de SweetAlert

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modiDNI'])) {
        // Verificar el valor de modiBaja
        $baja = $_POST['modiBaja'] == "on" ? 0 : 1;
        $fechaBaja = $baja == 1 ? date('Y-m-d') : NULL;
    
        $query_update = "UPDATE alumnos SET
            nombre = '$_POST[modiNombre]',
            apellido = '$_POST[modiApellido]',
            curso = '$_POST[modiCurso]',
            especialidad = '$_POST[modiEspecialidad]',
            baja = '$baja',
            fechaBaja = '$fechaBaja'
        WHERE DNI_alumno = '$_POST[modiDNI]'";
    
        $resultado_update = mysqli_query($con, $query_update) or die("FALLO DE CONSULTA DE ACTUALIZACIoN");

        // Si la actualización fue exitosa, cambiar la variable de alerta a true
        if ($resultado_update) {
            $mostrar_alerta = true; // Activar la alerta
        }
    }
    
    if ($alumno) {
        $query_select = "SELECT * FROM alumnos WHERE DNI_alumno = '$alumno'";
        $result_select = mysqli_query($con, $query_select) or die("ERROR DE CONSULTA");

        if (mysqli_num_rows($result_select) > 0) {
            while ($row = mysqli_fetch_array($result_select)) {
?>

<form method="POST" action="">
    DNI: <input type="text" name="modiDNI" value="<?php echo($row['DNI_alumno']); ?>" readonly> <br>
    Nombre: <input type="text" name="modiNombre" value="<?php echo($row['nombre']); ?>"> <br>
    Apellido: <input type="text" name="modiApellido" value="<?php echo($row['apellido']); ?>"> <br>
    Curso:
    <select name="modiCurso">
        <option value="1ro" <?php if($row['curso'] == '1ro') echo 'selected'; ?>>1ro</option>
        <option value="2do" <?php if($row['curso'] == '2do') echo 'selected'; ?>>2do</option>
        <option value="3ro" <?php if($row['curso'] == '3ro') echo 'selected'; ?>>3ro</option>
        <option value="4to" <?php if($row['curso'] == '4to') echo 'selected'; ?>>4to</option>
        <option value="5to" <?php if($row['curso'] == '5to') echo 'selected'; ?>>5to</option>
        <option value="6to" <?php if($row['curso'] == '6to') echo 'selected'; ?>>6to</option>
        <option value="7mo" <?php if($row['curso'] == '7mo') echo 'selected'; ?>>7mo</option>
    </select> <br>
    Especialidad:
    <select name="modiEspecialidad">
        <option value="Informatica" <?php if($row['especialidad'] == 'Informatica') echo 'selected'; ?>>Informatica</option>
        <option value="Electro" <?php if($row['especialidad'] == 'Electro') echo 'selected'; ?>>Electronica</option>
        <option value="Construcciones" <?php if($row['especialidad'] == 'Construcciones') echo 'selected'; ?>>Construcciones</option>
    </select> <br>

    <div class="form-check form-switch">
        <input type="hidden" name="modiBaja" value="off"> <!-- Campo oculto para enviar el valor "off" si no se marca el checkbox -->
        <input type="checkbox" class="form-check-input" id="modiBaja" name="modiBaja" 
            <?php echo ($row['baja'] == 0) ? 'checked' : ''; ?> onchange="updateLabel(this)">
        <label class="form-check-label" for="modiBaja" id="bajaLabel">
            <?php echo ($row['baja'] == 0) ? 'Activo' : 'Inactivo'; ?>
        </label>
    </div>

    <script>
    function updateLabel(checkbox) {
        var label = document.getElementById('bajaLabel');
        label.textContent = checkbox.checked ? 'Activo' : 'Inactivo';
    }
    </script>

    <input type="submit" value="Actualizar">
</form>

<?php
            }
        } else {
            echo "<div class='alert alert-danger'>No se encontraron resultados para el DNI: " . $dni . "</div>";
        }
    }

    mysqli_close($con);
?>

<!-- Mostrar el mensaje de actualizacion aqui -->
<?php echo $mensaje_actualizacion; ?>

<div class="volvido">
    <a href="../alumnos/listarAlumnos.php">VOLVER</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    // Mostrar SweetAlert si $mostrar_alerta es verdadero
    <?php if ($mostrar_alerta): ?>
        Swal.fire({
            position: 'mid',
            icon: 'success',
            title: 'Datos actualizados correctamente',
            showConfirmButton: false,
            timer: 1500
        });
    <?php endif; ?>
</script>

</body>
</html>
