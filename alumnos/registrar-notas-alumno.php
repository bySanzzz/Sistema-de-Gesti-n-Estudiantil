<?php
    include("../conexion.php");

    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    // Verificar si se recibió el DNI del alumno
    $alumno_dni = isset($_GET['alumno']) ? mysqli_real_escape_string($con, $_GET['alumno']) : null;

    // Verificar si se seleccionó un DNI de profesor
    $dni_profesor = isset($_GET['DNI_profesor']) ? mysqli_real_escape_string($con, $_GET['DNI_profesor']) : null;

    // Consulta para verificar si el DNI del alumno ya existe en la base de datos
    $check_query = "SELECT DNI_alumno, nombre, apellido FROM alumnos WHERE DNI_alumno = '$alumno_dni'";
    $check_result = mysqli_query($con, $check_query);

    // Obtener lista de profesores
    $profesor_query = "SELECT DNI_profesor, nombre FROM profesor";
    $profesores = mysqli_query($con, $profesor_query);

    // Si hay un profesor seleccionado, obtener las materias asociadas a ese profesor
    $materias = [];
    if ($dni_profesor) { 
        $materia_query = "SELECT materias.ID_materia, materias.nombreMateria 
                          FROM profesor_materias 
                          JOIN materias ON profesor_materias.ID_materia = materias.ID_materia 
                          WHERE profesor_materias.DNI_profesor = '$dni_profesor'";
        $materias = mysqli_query($con, $materia_query);
    }

    if (mysqli_num_rows($check_result) > 0) {
        $alumno = mysqli_fetch_assoc($check_result);
        $nombre_completo = $alumno['nombre'] . " " . $alumno['apellido'];

        // Si se enviaron las notas
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notaTP'], $_POST['notaConcepto'], $_POST['notaExamen'], $_POST['ID_materia'])) {
            $notaTP = mysqli_real_escape_string($con, $_POST['notaTP']);
            $notaConcepto = mysqli_real_escape_string($con, $_POST['notaConcepto']);
            $notaExamen = mysqli_real_escape_string($con, $_POST['notaExamen']);
            $ID_materia = mysqli_real_escape_string($con, $_POST['ID_materia']);

            $promedio = ($notaTP + $notaConcepto + $notaExamen) / 3;

            $insert_query = "INSERT INTO boletin (notaTP, notaExamen, notaConcepto, promedio, DNI_alumno, ID_materia) VALUES (
                '$notaTP', '$notaExamen', '$notaConcepto', '$promedio', '$alumno_dni', '$ID_materia')";
            $resultado = mysqli_query($con, $insert_query);

            echo "<script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Nota registrada con éxito.',
                    html: '<a href=\"vista-boletin.php?alumno=$alumno_dni\" class=\"btn btn-success\">VOLVER</a>',
                    showConfirmButton: false,
                });
            </script>";
        } else {
            ?>
            <div class="container">
                <form method="POST" action="" class="form-group">
                    <label>Alumno: <?php echo $nombre_completo; ?> </label>

                    <label>DNI Alumno:</label>
                    <input class="form-control" type="text" name="DNI" value="<?php echo $alumno_dni; ?>" readonly>

                    <label>Seleccionar Profesor:</label>
                    <select class="form-control" name="DNI_profesor" onchange="location = this.value;">
                        <option value="">Seleccione un profesor</option>
                        <?php while ($row = mysqli_fetch_assoc($profesores)) { ?>
                            <option value="?alumno=<?php echo $alumno_dni; ?>&DNI_profesor=<?php echo $row['DNI_profesor']; ?>"
                                <?php if ($dni_profesor == $row['DNI_profesor']) echo 'selected'; ?>>
                                <?php echo $row['DNI_profesor'] . " - " . $row['nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if ($dni_profesor) { ?>
                        <label>Seleccionar Materia:</label>
                        <select class="form-control" name="ID_materia" required>
                            <option value="">Seleccione una materia</option>
                            <?php while ($row = mysqli_fetch_assoc($materias)) { ?>
                                <option value="<?php echo $row['ID_materia']; ?>">
                                    <?php echo $row['ID_materia'] . " - " . $row['nombreMateria']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } ?>

                    <label>Nota TP:</label>
                    <input class="form-control" type="number" name="notaTP" required min="1" max="10">
                    <label>Nota Concepto:</label>
                    <input class="form-control" type="number" name="notaConcepto" required min="1" max="10">

                    <label>Nota Examen:</label>
                    <input class="form-control" type="number" name="notaExamen" required min="1" max="10">

                    <input class="btn btn-primary" type="submit" value="Registrar" style="background-color: green;">
                </form>
            </div>
            <a class="btn btn-secondary" href="listarAlumnos.php">listado Alumnos</a>
            <?php
        }
    } else {
        echo "<div class='alert alert-danger'>El DNI '$alumno_dni' no está registrado.</div><br>";
        ?>
        <div class="volvido">
            <a class="btn btn-secondary" href="../index.php">VOLVER</a>
        </div>
        <?php
    }

    mysqli_close($con);
?>
