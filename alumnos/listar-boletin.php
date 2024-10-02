<link rel="stylesheet" href="./CSS/indexmodi.css">
<link rel="stylesheet" href="./CSS/header.css">
    <?php
       
        include("../html/header.php");
        include("../conexion.php");

        $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

        // Obteniendo parametro alumno
        $alumno = isset($_GET['alumno']) ? mysqli_real_escape_string($con, $_GET['alumno']) : null;

        // Verificar si se ha enviado el formulario de registro
        if (isset($_POST['register'])) {
            $DNI_alumno = mysqli_real_escape_string($con, trim($_POST['DNI']));
            $nombre = mysqli_real_escape_string($con, trim($_POST['nombre']));
            $apellido = mysqli_real_escape_string($con, trim($_POST['apellido']));
            $curso = mysqli_real_escape_string($con, trim($_POST['curso']));
            $especialidad = mysqli_real_escape_string($con, trim($_POST['especialidad']));
            $baja = isset($_POST['baja']) ? (int)$_POST['baja'] : 0;
            $fechaAlta = date('Y-m-d');
            $fechaBaja = null;

            // Validar campos vacios
            if (empty($DNI_alumno) || empty($nombre) || empty($apellido) || empty($curso) || empty($especialidad)) {
                echo "<h3>Rellena todos los cuadros</h3>";
            } else {
                // Preparar sentencia para insertar
                $stmt = $con->prepare("INSERT INTO alumnos (DNI_alumno, nombre, apellido, curso, especialidad, baja, fechaAlta, fechaBaja) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('issssiss', $DNI_alumno, $nombre, $apellido, $curso, $especialidad, $baja, $fechaAlta, $fechaBaja);

                if ($stmt->execute()) {
                    echo "<h3>Inscripto</h3>";
                } else {
                    echo "<h3>Error: No se pudo registrar</h3>";
                }
                $stmt->close();
            }
        }

        // Verificar si se ha enviado el formulario para la actualizacion
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modiDNI'])) {
            // Actualizar los datos
            $baja = isset($_POST['modiBaja']) ? 1 : 0; // Baja sera 1 si esta seleccionada, de lo contrario 0
            $fechaBaja = $baja == 1 ? date('Y-m-d') : NULL; // Si el alumno esta de baja, asigna la fecha actual

            $query_update = "UPDATE alumnos SET
                nombre = '$_POST[modiNombre]',
                apellido = '$_POST[modiApellido]',
                curso = '$_POST[modiCurso]',
                especialidad = '$_POST[modiEspecialidad]',
                baja = '$baja',
                fechaBaja = '$fechaBaja'
            WHERE DNI_alumno = '$_POST[modiDNI]'";

            $resultado_update = mysqli_query($con, $query_update) or die("FALLO DE CONSULTA DE ACTUALIZACIoN");
            echo ("DATOS ACTUALIZADOS<br>");
        }


        // Si no se ha enviado el formulario de actualizacion, mostrar el formulario de modificacion
        if ($alumno) {
            // Aqui podrias buscar el alumno por DNI en la base de datos
            $query_select = "SELECT * FROM alumnos WHERE DNI_alumno = '$alumno'";
            $result_select = mysqli_query($con, $query_select) or die("ERROR DE CONSULTA");

            // Si se encuentran datos, mostrar el formulario con los datos actuales
            if (mysqli_num_rows($result_select) > 0) {
                while ($row = mysqli_fetch_array($result_select)) {
        ?>

                <!-- Formulario de modificacion -->
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

                    ¿Baja?: <input type="checkbox" name="modiBaja" <?php if ($row['baja'] == 1) echo 'checked'; ?>> <br>

                    <input type="submit" value="Actualizar">
                </form>

        <?php
                }
            } else {
                echo "No se encontro ningun alumno con ese DNI.";
            }
        }
        mysqli_close($con);
        ?>

        <!-- Boton para volver -->
        <button>
            <a href="../index.php">VOLVER</a>
        </button>
</body>