<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Notas</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        .container {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .datos-alumno {
            padding: 20px;
            border-radius: 8px;
            flex: 1;
        }

        .registrar-notas {
            background-color: darkgray;
            color: black;
            padding: 20px;
            border-radius: 8px;
            flex: 1;
        }

        .form-label {
            color: black;
        }

        .form-control {
            color: black;
        }

        h4 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .btn-accion {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-accion:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <header>
        <div class="prese">
            <h1>Registrar Notas del Alumno</h1>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </div>
    </header>

    <?php
    include("../conexion.php");

    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    $alumno_dni = isset($_GET['alumno']) ? mysqli_real_escape_string($con, $_GET['alumno']) : null;
    $dni_profesor = isset($_GET['DNI_profesor']) ? mysqli_real_escape_string($con, $_GET['DNI_profesor']) : null;

    $check_query = "SELECT DNI_alumno, nombre, apellido FROM alumnos WHERE DNI_alumno = '$alumno_dni'";
    $check_result = mysqli_query($con, $check_query);

    $profesor_query = "SELECT DNI_profesor, nombre FROM profesor";
    $profesores = mysqli_query($con, $profesor_query);

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
                    title: 'Nota registrada con éxito.',
                    text: '¡La nota se ha guardado correctamente!',
                    icon: 'success',
                    showClass: {
                        popup: 'animate__animated animate__fadeInUp animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutDown animate__faster'
                    },
                    confirmButtonText: '<a href=\"vista-boletin.php?alumno=$alumno_dni\" style=\"color:white; text-decoration:none;\">VOLVER</a>',
                    confirmButtonColor: '#007bff'
                });
            </script>";
        } else {
    ?>
            <div class="container mt-4">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 datos-alumno">
                            <h4>Datos del Alumno</h4>
                            <div class="mb-3">
                                <label>Nombre Alumno:</label>
                                <input class="form-control" type="text" value="<?php echo $nombre_completo; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label>DNI Alumno:</label>
                                <input class="form-control" type="text" name="DNI" value="<?php echo $alumno_dni; ?>" readonly>
                            </div>
                            <div class="mb-3">
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
                            </div>
                            <?php if ($dni_profesor) { ?>
                                <div class="mb-3">
                                    <label>Seleccionar Materia:</label>
                                    <select class="form-control" name="ID_materia" required>
                                        <option value="">Seleccione una materia</option>
                                        <?php while ($row = mysqli_fetch_assoc($materias)) { ?>
                                            <option value="<?php echo $row['ID_materia']; ?>"><?php echo $row['nombreMateria']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-md-6 registrar-notas">
                            <h4>Registrar Notas</h4>
                            <div class="mb-3">
                                <label class="form-label">Nota TP:</label>
                                <input class="form-control" type="number" name="notaTP" min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nota Concepto:</label>
                                <input class="form-control" type="number" name="notaConcepto" min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nota Examen:</label>
                                <input class="form-control" type="number" name="notaExamen" min="1" max="10" required>
                            </div>
                            <br>
                            <input class="btn btn-primary" type="submit" value="Registrar Nota">
                        </div>
                    </div>
                </form>
            </div>
            <div class="container" style="margin-bottom: 20px;">
                <a class="btn btn-primary" href="vista-boletin.php?alumno=<?php echo $row['DNI_alumno']; ?>">Volver</a>
            </div>

    <?php
        }
    }
    mysqli_close($con);
    ?>

</body>

</html>