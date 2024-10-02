<?php
    include("../conexion.php");
    
    $con=mysqli_connect($host,$user,$pwd,$BD) or die("FALLO DE CONEXION"); //variables login de conexion.php
    $query="SELECT * FROM personas WHERE DNI='$_POST[DNI]'"; //obtiene solo el DNI
    $result=mysqli_query($con, $query) or die ("ERROR DE CONSULTA");


?>