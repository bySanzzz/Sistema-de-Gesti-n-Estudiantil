<?php
    include("../conexion.php");

    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    // Inicializar variables de filtro
    $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
    $status = isset($_GET['status']) ? $_GET['status'] : '0';
    $curso = isset($_GET['curso']) ? $_GET['curso'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : ''; // Nueva variable de búsqueda

    // Validación de columnas y estado
    $validColumns = ['nombre', 'apellido', 'fechaAlta'];
    if (!in_array($orderBy, $validColumns)) {
        $orderBy = 'nombre';
    }

    $validStatus = ['0', '1', '2'];
    if (!in_array($status, $validStatus)) {
        $status = '0';
    }

    // Obtener los cursos para el filtro
    $queryCursos = "SELECT DISTINCT curso FROM alumnos";
    $resultCursos = mysqli_query($con, $queryCursos) or die("ERROR AL OBTENER CURSOS");

    // Construir la consulta con el filtro de estado, curso, búsqueda y orden
    $query = "SELECT DNI_alumno, nombre, apellido, curso, especialidad, fechaAlta, fechaBaja
              FROM alumnos
              WHERE baja = $status";

    // Agregar el filtro de curso si se selecciona
    if (!empty($curso)) {
        $query .= " AND curso = '$curso'";
    }

    // Agregar búsqueda si se introduce una búsqueda
    if (!empty($search)) {
        $query .= " AND (nombre LIKE '%$search%' OR apellido LIKE '%$search%')";
    }

    // Agregar orden por la columna seleccionada
    $query .= " ORDER BY $orderBy";

    // Ejecutar consulta
    $result = mysqli_query($con, $query) or die("ERROR AL OBTENER ALUMNOS");

    // Obtener el número total de registros
    $queryTotal = "SELECT COUNT(*) AS total FROM alumnos WHERE baja = $status";
    if (!empty($curso)) {
        $queryTotal .= " AND curso = '$curso'";
    }
    if (!empty($search)) {
        $queryTotal .= " AND (nombre LIKE '%$search%' OR apellido LIKE '%$search%')";
    }
    $resultTotal = mysqli_query($con, $queryTotal) or die("ERROR DE CONTEO");
    $rowTotal = mysqli_fetch_assoc($resultTotal);
    $total_records = $rowTotal['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado Alumnos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
<header>
        <h1 class='text-center-titulo'>Lista de Alumnos</h1><br>
        <nav class="nav-right">
            <a href="../index.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9.5L12 2l9 7.5v11a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 3 20.5v-11z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
        </nav>
    </header>
   
    <!-- Filtros y campo de búsqueda -->
    <div class='container ' style="margin-top: 20px;">
        <div class='row mb-3'>
            <div class='col-md-3'>
                <label for='orderSelect'>Ordenar por:</label>
                <select class='form-select' id='orderSelect' onchange='changeFilter()'>
                    <option value='nombre' <?php echo $orderBy == 'nombre' ? 'selected' : ''; ?>>Nombre</option>
                    <option value='apellido' <?php echo $orderBy == 'apellido' ? 'selected' : ''; ?>>Apellido</option>
                    <option value='fechaAlta' <?php echo $orderBy == 'fechaAlta' ? 'selected' : ''; ?>>Fecha de Alta</option>
                </select>
            </div>
            <div class='col-md-3'>
                <label for='statusSelect'>Mostrar:</label>
                <select class='form-select' id='statusSelect' onchange='changeFilter()'>
                    <option value='0' <?php echo $status == '0' ? 'selected' : ''; ?>>ACTIVOS</option>
                    <option value='1' <?php echo $status == '1' ? 'selected' : ''; ?>>INACTIVOS</option>
                    <option value='2' <?php echo $status == '2' ? 'selected' : ''; ?>>ELIMINADOS</option>
                </select>
            </div>
            <div class='col-md-3'>
                <label for='cursoSelect'>Curso:</label>
                <select class='form-select' id='cursoSelect' onchange='changeFilter()'>
                    <option value='' <?php echo $curso == '' ? 'selected' : ''; ?>>Todos</option>
                    <?php while($rowCurso = mysqli_fetch_array($resultCursos)) { ?>
                        <option value='<?php echo $rowCurso['curso']; ?>' <?php echo $curso == $rowCurso['curso'] ? 'selected' : ''; ?>>
                            <?php echo $rowCurso['curso']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <!-- Campo de búsqueda con lupa -->
            <div class='col-md-3'>
                <label for="searchInput">Buscar:</label>
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar alumno..." value="<?php echo $search; ?>" onkeypress="handleSearchKeypress(event)">
                    <button class="input-group-text" onclick="changeFilter()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85zm-5.743 0a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <?php
        if ($status === '2') {
            // Mostrar la tabla de eliminados
            $query = "SELECT * FROM respaldoalumnos";
            
            // Agregar el filtro de curso si está seleccionado
            if (!empty($curso)) {
                $query .= " WHERE curso = '$curso'";
            }

            $query .= " ORDER BY $orderBy";
            $result = mysqli_query($con, $query) or die("ERROR DE CONSULTA");
        ?>
        
        <table class='table table-striped'>
            <thead class='table-sky-blue'>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Curso</th>
                    <th>Especialidad</th>
                    <th>Alta</th>
                    <th>Eliminación</th>
                    <th>Usuario Encargado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_array($result)) { ?>
                <tr>
                <td><?php echo $row['DNI_alumno']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['curso']; ?></td>
                    <td><?php echo $row['especialidad']; ?></td>
                    <td><?php echo $row['fechaAlta']; ?></td>
                    <td><?php echo $row['fechaEliminacion']; ?></td>
                    <td><?php echo $row['usuarioEncargado']; ?></td>
                    <td class="acciones">
                    <form method="POST" action="eliminar-reinsertar.php" style="display:inline;">
                        <input type="hidden" name="alumno" value="<?php echo $row['ID']; ?>">
                        <button type="submit" class="btn-accion" onclick="return confirm('¿Está seguro de que desea reinsertar este alumno?');">
                            <img src="../Imagenes/return.png" alt="reinsertar" class="icono" width="24px">
                        </button>
                    </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?php } else { ?>
        
        <!-- Tabla de alumnos activos o inactivos -->
        <table class='table table-striped'>
            <thead class='table-sky-blue'>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Curso</th>
                    <th>Especialidad</th>
                    <th>Alta</th>
                    <th>Baja</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td><?php echo $row['DNI_alumno']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['curso']; ?></td>
                    <td><?php echo $row['especialidad']; ?></td>
                    <td><?php echo $row['fechaAlta']; ?></td>
                    <td><?php echo $row['fechaBaja']; ?></td>
                    <td class="acciones">
                        <a class="btn-accion" href="listar-modi-alumno.php?alumno=<?php echo $row['DNI_alumno']; ?>">
                            <img src="../SVG/lapiz.svg" alt="Modificar" class="icono" width="24px">
                        </a>
                        <form method="POST" action="listar-delete-alumno.php" style="display:inline;">
                            <input type="hidden" name="DNI" value="<?php echo $row['DNI_alumno']; ?>">
                            <button type="submit" class="btn-accion">
                                <img src="../SVG/si.svg" alt="Eliminar" class="icono">
                            </button>
                        </form>
                        <a class="btn-accion" href="vista-boletin.php?alumno=<?php echo $row['DNI_alumno']; ?>">
                            <img src="../SVG/libro.svg" alt="Boletín" class="icono" width="24px">
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?php } ?>

        <!-- Paginación o mensajes adicionales -->
        <p>Total de registros: <?php echo $total_records; ?></p>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeFilter() {
            let orderBy = document.getElementById('orderSelect').value;
            let status = document.getElementById('statusSelect').value;
            let curso = document.getElementById('cursoSelect').value;
            let search = document.getElementById('searchInput').value;
            window.location.href = `?orderBy=${orderBy}&status=${status}&curso=${curso}&search=${search}`;
        }

        function handleSearchKeypress(event) {
            if (event.key === 'Enter') {
                changeFilter();
            }
        }
    </script>
</body>
</html>
