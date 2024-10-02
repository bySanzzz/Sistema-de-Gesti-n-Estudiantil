<?php
    include("../conexion.php");

    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");    
    $dni = $_POST['DNI'];//obtiene dni desde form

    // Consultar la base de datos
    $query = "SELECT * FROM personas WHERE DNI = '$dni'";
    $result = mysqli_query($con, $query) or die("ERROR DE CONSULTA");

    // Verificar si se encontró el DNI
    if (mysqli_num_rows($result) > 0) {
        echo "El DNI ESTÁ";
        echo "<button><a href='item-prestamo.php'>ITEM PRESTAMO</a></button>";

    } else {
        echo "El DNI NO ESTÁ";
        ?>
        <h3>A DONDE QUIERES IR???!!! </h3>
        <br> <a href="../usuario/listarUsuario.php"><button>LISTAR</button></a>
        <br> <a href="../usuario/form-alta-usuario.html"><button>AGREGAR</button></a>
        <p> <a href="../index.html"><button>INICIO</button></a> </p>
    	<?php
    }

    // Cerrar la conexión
    mysqli_close($con);
?>



