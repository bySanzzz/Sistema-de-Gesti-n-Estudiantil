<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


<?php
include("../conexion.php");

function listaProfesores(){

	$con = conex();

	// Obtener el criterio de ordenamiento y el estado de alta/baja desde la URL (usando GET)
	$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre'; // Default es 'nombre'
	$status = isset($_GET['status']) ? $_GET['status'] : '0'; // Default es alta (0)

	// Validacion para evitar inyecciones SQL en la variable de ordenamiento
	$validColumns = ['nombre', 'apellido', 'especialidad', 'fechaAlta'];
	if (!in_array($orderBy, $validColumns)) {
		$orderBy = 'nombre'; // Default si no es valido
	}

	// Validacion del filtro de estado
	$validStatus = ['0', '1']; // 0 = Alta, 1 = Baja
	if (!in_array($status, $validStatus)) {
		$status = '0'; // Default si no es valido
	}

	// Consulta con filtro de estado y ordenamiento
	$query = "SELECT DNI_profesor, nombre, apellido, especialidad, fechaAlta, fechaBaja FROM profesor WHERE baja = $status ORDER BY $orderBy";
	$data = mysqli_query($con, $query) or die("ERROR DE CONSULTA");
	return $data;
}

//UPDATE PROFESOR
function actualizarProfesor(){
	$con = conex();

	$profesor = isset($_GET['profesor']) ? mysqli_real_escape_string($con, $_GET['profesor']) : null;

	// Verificar si se ha enviado el formulario para registrar un nuevo profesor
	if (isset($_POST['register'])) {
		$DNI = trim($_POST['DNI']);
		$nombre = trim($_POST['nombre']);
		$apellido = trim($_POST['apellido']);
		$especialidad = trim($_POST['especialidad']);
		$baja = isset($_POST['baja']) ? trim($_POST['baja']) : 0; // Baja sera 0 si no se marca
		$fechaAlta = date('Y-m-d'); // Usamos la fecha actual
		$fechaBaja = NULL; // La fecha de baja es NULL inicialmente

		// Validar si los campos estan vacios
		if (empty($DNI) || empty($nombre) || empty($apellido) || empty($especialidad)) {
			echo "<h3>Rellena todos los cuadros</h3>";
		} else {
			// Prepara la consulta de insercion
			$stmt = $con->prepare("INSERT INTO profesor (DNI_profesor, nombre, apellido, especialidad, baja, fechaAlta, fechaBaja) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('issssis', $DNI, $nombre, $apellido, $especialidad, $baja, $fechaAlta, $fechaBaja);

			// Ejecutar la consulta y verificar si fue exitosa
			if ($stmt->execute()) {
				echo "<h3>Inscripto</h3>";
			} else {
				echo "<h3>Upsi dupsi, ha ocurrido un error</h3>";
			}

			// Cerrar el statement
			$stmt->close();
		}
	}

	// Verificar si se ha enviado el formulario para la actualizacion
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modiDNI'])) {
		// Actualizar los datos
		// Verificar el valor de modiBaja
		$baja = $_POST['modiBaja'] == "on" ? 0 : 1;
		$fechaBaja = $baja == 1 ? date('Y-m-d') : NULL; // Si el alumno esta de baja, asigna la fecha actual

		$query_update = "UPDATE profesor SET
			nombre = '$_POST[modiNombre]',
			apellido = '$_POST[modiApellido]',
			especialidad = '$_POST[modiEspecialidad]',
			baja = '$baja',
			fechaBaja = '$fechaBaja'
		WHERE DNI_profesor = '$_POST[modiDNI]'";

		$resultado_update = mysqli_query($con, $query_update) or die("FALLO DE CONSULTA DE ACTUALIZACION");
		echo "<div class='alert alert-success'>DATOS ACTUALIZADOS</div><br>";
        return $resultado_update;
	}
}
