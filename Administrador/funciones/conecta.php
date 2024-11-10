<?php  
    //funciones conecta
    define("HOST", 'localhost');
    define("BD", 'proyecto');  // Nombre de la base de datos
    define("USER_BD", 'root'); // Usuario de la base de datos
    define("PASS_BD", '');    // Contraseña de la base de datos

    function conecta(){
        $con = new mysqli(HOST,USER_BD,PASS_BD,BD); 
        return $con;
    }
?>
