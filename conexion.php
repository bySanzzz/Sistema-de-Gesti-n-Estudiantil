
<?php
    $host = "localhost"; // Host de la base de datos
    $user = "root";      // Nombre de usuario de la base de datos
    $pwd = "";      // Contrasena de la base de datos
    $BD = "escuela1"; // Nombre de la base de datos
    
    // Crear la conexion
    $conex = mysqli_connect($host, $user, $pwd, $BD);
    
    // Verificar la conexion
    if (!$conex) {
        die("Error en la conexión: " . mysqli_connect_error());
    }



    function conex(){
        $host = "localhost"; // Host de la base de datos
        $user = "root";      // Nombre de usuario de la base de datos
        $pwd = "";      // Contrasena de la base de datos
        $BD = "escuela1"; // Nombre de la base de datos
        
        // Crear la conexion
        $conex = mysqli_connect($host, $user, $pwd, $BD);
        
        // Verificar la conexion
        if (!$conex) die("Error en la conexión: " . mysqli_connect_error());
        return $conex;
    }

?>
