<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTAS</title>
</head>
<body>
    <form method="POST" action="registrar-notas-alumno.php">
		DNI: <input type="number" name="DNI" required min="10000000" max="99000000">
		<input type="submit">
	</form>
	¿No sabes el DNI?
	<br>Presiona en listar alumno
	<a href="listarAlumno.php"><button>listar alumnos</button></a>

</body>
</html>
