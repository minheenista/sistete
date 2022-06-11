<?php

 $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'sistete2'; 

    /* $server = 'sql307.byethost5.com';
    $username = 'b5_31930404';
    $password = 'Youngjae333';
    $database = 'b5_31930404_sistete'; */

// COnectamos a la base de datos
$conexion = mysqli_connect('localhost', 'root', '', 'sistete2');
// Para el servidor en internet
//$conexion = mysqli_connect('sql307.byethost5.com', 'b5_31930404', 'Youngjae333', 'b5_31930404_sistete');

//revisar la conexion
if (!$conexion){
    die ("Error al conectarse: ". mysqli_connect_error());
}

try{
    $conn = new PDO("mysql:host=$server;dbname=$database;",$username,$password);
} catch(PDOException $e){
    die('Connected failed: '.$e->getMessage());
}

function nombre_archivo($string) {

    // Tranformamos todo a minusculas

    $string = strtolower($string);

    //Rememplazamos caracteres especiales latinos

    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

    $repl = array('a', 'e', 'i', 'o', 'u', 'n');

    $string = str_replace($find, $repl, $string);

    // Añadimos los guiones

    $find = array(' ', '&', '\r\n', '\n', '+');
    $string = str_replace($find, '-', $string);

    // Eliminamos y Reemplazamos otros carácteres especiales

    $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

    $repl = array('', '-', '');

    $string = preg_replace($find, $repl, $string);

    return $string;
}

?>