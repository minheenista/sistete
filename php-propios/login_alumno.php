<?php

include 'conexion.php';
$respuesta = array();

//print_r( $_POST);
//print_r ($_GET);
//print_r ($_REQUEST);


if(isset($_POST["action"])){
    validarphp($conexion);
    } else{
    print_r($_POST);
    $respuesta["estado"] =0;
    $respuesta["mensaje"]="Sin accion definida";
    echo json_encode($respuesta);
}



function validarphp($conexion){
    $boleta = $_POST["boleta"];
    $password = $_POST["password"];
    $query="SELECT * FROM usuarios WHERE boleta='".$boleta."' AND password='".$password."'";
    $resultado = mysqli_query($conexion,$query);
    $numRes = mysqli_num_rows($resultado);
    if($numRes>0){
        $Respuesta["estado"]        =1;
        $Respuesta["mensaje"]       ="Accedio correctamente";
    } else{
        $Respuesta["estado"]        =0;
        $Respuesta["mensaje"]       ="Ocurrio un error desconocido";
    }
    
    echo json_encode($Respuesta);
    mysqli_close($conexion);
}
