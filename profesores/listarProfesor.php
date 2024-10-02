<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado Profesor</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <header>
        <h1 class='text-center-titulo'>Lista de Profesores</h1><br>
        <nav class="nav-right">
            <a href="../index.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9.5L12 2l9 7.5v11a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 3 20.5v-11z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
        </nav>
    </header>

    <div class="image-container">
        <a href="../profesores/form-alta-profesor.php">
            <img src="../SVG/add.svg" alt="Añadir" class="add">
        </a>
    </div>

    <?php
    include("../conexion.php");

    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
    $status = isset($_GET['status']) ? $_GET['status'] : '0'; // Default es alta (0)
    $especialidadFilter = isset($_GET['especialidad']) ? $_GET['especialidad'] : ''; 

    // Validación de columnas y estado
    $validColumns = ['nombre', 'apellido', 'especialidad', 'fechaAlta'];
    if (!in_array($orderBy, $validColumns)) {
        $orderBy = 'nombre';
    }

    $validStatus = ['0', '1']; // 0 = Alta, 1 = Baja
    if (!in_array($status, $validStatus)) {
        $status = '0';
    }

    // Filtro de especialidad
    $especialidadesQuery = "SELECT DISTINCT especialidad FROM profesor";
    $especialidadesResult = mysqli_query($con, $especialidadesQuery) or die("ERROR DE CONSULTA DE ESPECIALIDADES");

    $especialidadCondition = $especialidadFilter ? "AND especialidad = '$especialidadFilter'" : '';

    // Consulta principal
    $query = "SELECT DNI_profesor, nombre, apellido, especialidad, fechaAlta, fechaBaja 
              FROM profesor 
              WHERE baja = $status $especialidadCondition
              ORDER BY $orderBy";
    $result = mysqli_query($con, $query) or die("ERROR DE CONSULTA");
    ?>

    <div class='container' style="margin-top: 20px;">
        <div class='row mb-3'>
            <div class='col-md-4'>
                <label for='orderSelect'>Ordenar por:</label>
                <select class='form-select' id='orderSelect' onchange='changeFilter()'>
                    <option value='nombre' <?php echo $orderBy == 'nombre' ? 'selected' : ''; ?>>Nombre</option>
                    <option value='apellido' <?php echo $orderBy == 'apellido' ? 'selected' : ''; ?>>Apellido</option>
                    <option value='especialidad' <?php echo $orderBy == 'especialidad' ? 'selected' : ''; ?>>Especialidad</option>
                    <option value='fechaAlta' <?php echo $orderBy == 'fechaAlta' ? 'selected' : ''; ?>>Fecha de Alta</option>
                </select>
            </div>
            <div class='col-md-4'>
                <label for='statusSelect'>Mostrar:</label>
                <select class='form-select' id='statusSelect' onchange='changeFilter()'>
                    <option value='0' <?php echo $status == '0' ? 'selected' : ''; ?>>ACTIVOS</option>
                    <option value='1' <?php echo $status == '1' ? 'selected' : ''; ?>>INACTIVOS</option>
                </select>
            </div>
            <div class='col-md-4'>
                <label for='especialidadSelect'>Especialidad:</label>
                <select class='form-select' id='especialidadSelect' onchange='changeFilter()'>
                    <option value=''>Todas</option>
                    <?php while($rowEspecialidad = mysqli_fetch_array($especialidadesResult)) { ?>
                        <option value='<?php echo $rowEspecialidad['especialidad']; ?>' <?php echo $especialidadFilter == $rowEspecialidad['especialidad'] ? 'selected' : ''; ?>>
                            <?php echo $rowEspecialidad['especialidad']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <table class='table table-striped'>
            <thead class='table-sky-blue'>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Especialidad</th>
                    <th>Alta</th>
                    <th>Baja</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td><?php echo $row['DNI_profesor']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['especialidad']; ?></td>
                    <td><?php echo $row['fechaAlta']; ?></td>
                    <td><?php echo $row['fechaBaja']; ?></td>
                    <td class="acciones">
                        <a class="btn-accion" href="listar-modi-profesor.php?profesor=<?php echo $row['DNI_profesor']; ?>">
                            <img src="../SVG/lapiz.svg" alt="Modificar" class="icono" width="24px">
                        </a>
                        <form method="POST" action="listar-delete-profesor.php" style="display:inline;">
                            <input type="hidden" name="DNI" value="<?php echo $row['DNI_profesor']; ?>">
                            <button type="submit" class="btn-accion" onclick="return confirm('¿Está seguro de que desea eliminar este profesor?');">
                                <img src="../SVG/si.svg" alt="Eliminar" class="icono">
                            </button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script>
        function changeFilter() {
            let orderBy = document.getElementById('orderSelect').value;
            let status = document.getElementById('statusSelect').value;
            let especialidad = document.getElementById('especialidadSelect').value;
            window.location.href = `?orderBy=${orderBy}&status=${status}&especialidad=${especialidad}`;
        }
    </script>
</body>
</html>
