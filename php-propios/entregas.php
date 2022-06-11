<?php

include 'conexion.php';
$respuesta = array();

//print_r( $_POST);
//print_r ($_GET);
//print_r ($_REQUEST);


if(isset($_POST["accion"])){

    $accion = $_POST["accion"];

    switch ($accion) {
        case "create":
            accionCreatePhp($conexion);
            break;

        case "update":
            accionUpdatePhp($conexion);
            break;
        
        case "delete":
            accionDeletePhp($conexion);
            break;

        case "read":
            accionReadPhp($conexion);
            break;

        case "read-id":
            accionReadByIdPhp($conexion);
            break;

        case 'show':
            accionVerPhp($conexion);
            break;
        
        default:
            accionErrorPhp();
            break;
    }
}else{
    //print_r($_POST);
    $respuesta["estado"] =0;
    $respuesta["mensaje"]="Sin accion definida";
    echo json_encode($respuesta);
}

function accionErrorPhp(){
    $respuesta["estado"] =0;
    $respuesta["mensaje"]="Accion no valida";
    echo json_encode($respuesta);
    mysqli_close($conexion);
}

function accionCreatePhp($conexion){

    
     $nombre = $_POST["nombre-entrega"];

    //$new_name_file =null;

    $extension = null;

    if (($_FILES["file"]["type"] == "application/pdf")) {

        $nombre_archivo     = basename($_FILES["file"]["name"]);
        $new_name_file      = nombre_archivo($nombre_archivo) . '.' . $extension;
        $ruta               ="files/" . $new_name_file;


        if (move_uploaded_file($_FILES["file"]["tmp_name"], "../files/".$_FILES['file']['name'])) {
            $insertar       = "INSERT INTO entregas(nombre_entrega, ruta, archivo) VALUES ('$nombre', '$ruta', '$new_name_file')";
                // $respuesta = mysqli_query($conexion, $insertar);
                if(mysqli_query($conexion, $insertar)){
                    $respuesta["estado"]   = 1;
                    $respuesta["id"]       = mysqli_insert_id($conexion);
                    $respuesta["mensaje"]  = "La entrega se agrego correctamente";
                }
                else{
                    $respuesta["estado"]    = 0;
                    $respuesta["id"]        = -1;
                    $respuesta["mensaje"]   = "Ocurrio un error desconocido";
                }
           // echo "files/".$_FILES['file']['name'];
        } 
        else {
            $respuesta["estado"]    = 0;
                    $respuesta["id"]        = -1;
                    $respuesta["mensaje"]   = "No se puso subir el archivo";
        }
    } 
    else {    
        $respuesta["estado"]    = 0;
        $respuesta["id"]        = -1;
        $respuesta["mensaje"]   = "No es un archivo valido";
}

echo json_encode($respuesta);


}

function accionReadPhp($conexion){
    //consulta
    $queryRead = "SELECT * FROM entregas";
    //ejecutamos consulta
    $ResultadoRead = mysqli_query($conexion, $queryRead);
    //obtenemos numero de registros
    $numEntregas = mysqli_num_rows($ResultadoRead);

    if($numEntregas>0){
        $respuesta["estado"]=1;
        $respuesta["mensaje"]= "Registros encontrados";
        $respuesta["entregas"] = array();
        while ($renglonEntrega = mysqli_fetch_assoc($ResultadoRead)) {
            $entrega = array();
            $entrega["id_entrega"]      = $renglonEntrega["id_entrega"];
            $entrega["nombre_entrega"]  = $renglonEntrega["nombre_entrega"];
            $entrega["fecha"]           = $renglonEntrega["fecha_entrega"];
            array_push($respuesta["entregas"], $entrega);
        }
    }
    else{
        $respuesta["estado"] = 0;
        $respuesta["mensaje"] = "Registros no encontrados";
    }

        
    echo json_encode($respuesta);

    //print_r($_POST);

}

function accionReadByIdPhp($conexion){
    
    //print_r($_POST);

    $id_entrega = $_POST['id'];

    $queryReadById = "SELECT * FROM entregas WHERE id_entrega=".$id_entrega;
    $resultadoReadById = mysqli_query($conexion, $queryReadById);

    $numRegistros = mysqli_num_rows($resultadoReadById);

    if($numRegistros>0){
        $respuesta["estado"] = 1;
        $respuesta["mensaje"] ="Registro encontrado";

        $renglonEntregaById = mysqli_fetch_assoc($resultadoReadById);
        $respuesta["id_entrega"] = $renglonEntregaById["id_entrega"];
        $respuesta["nombre_entrega"] = $renglonEntregaById["nombre_entrega"];
        $respuesta["fecha_entrega"] = $renglonEntregaById["fecha_entrega"];
    }
    else{
        $respuesta["estado"] = 0;
        $respuesta["mensaje"] = "Registro no encontrado";
    }

    echo json_encode($respuesta);
    mysqli_close($conexion);

    
}

function accionUpdatePhp($conexion){

   print_r($_POST);

    $nombre = $_POST["nombre-entrega-actualizar"];

    //$new_name_file =null;

    $extension = null;

    if (($_FILES["file"]["type"] == "application/pdf")) {

        $nombre_archivo     = basename($_FILES["file"]["name"]);
        $new_name_file      = nombre_archivo($nombre_archivo) . '.' . $extension;
        $ruta               ="files/" . $new_name_file;


        if (move_uploaded_file($_FILES["file"]["tmp_name"], "../files/".$_FILES['file']['name'])) {
            $queryUpdate       = "UPDATE entregas SET nombre_entrega = '".$nombre."', ruta=".$ruta.", archivo=".$new_name_file."WHERE id=".$id_entrega;
                // $respuesta = mysqli_query($conexion, $insertar);
                if(mysqli_query($conexion, $queryUpdate)>0){
                    $respuesta["estado"]   = 1;
                    $respuesta["id"]       = mysqli_insert_id($conexion);
                    $respuesta["mensaje"]  = "La entrega se actualizo correctamente";
                }
                else{
                    $respuesta["estado"]    = 0;
                    $respuesta["id"]        = -1;
                    $respuesta["mensaje"]   = "Ocurrio un error desconocido";
                }
           // echo "files/".$_FILES['file']['name'];
        } 
        else {
            $respuesta["estado"]    = 0;
                    $respuesta["id"]        = -1;
                    $respuesta["mensaje"]   = "No se pudo subir el archivo";
        }
    } 
    else {    
        $respuesta["estado"]    = 0;
        $respuesta["id"]        = -1;
        $respuesta["mensaje"]   = "No es un archivo valido";
}

echo json_encode($respuesta);

}

function accionDeletePhp($conexion){

    //print_r($_POST);
    $id_entrega = $_POST['id'];

    $queryDelete = "DELETE FROM entregas WHERE id_entrega =". $id_entrega;

    mysqli_query($conexion, $queryDelete);

    if(mysqli_affected_rows($conexion)>0){
        $respuesta["estado"] =1;
        $respuesta["mensaje"] = "La entrega se elimino correctamente";
    }
    else {
        $respuesta["estado"] =0;
        $respuesta["mensaje"] = "Ocurrio un error desconocido";
    }

    echo json_encode($respuesta);
    mysqli_close($conexion);
}

function accionVerPhp($conexion){

    print_r($_POST);

    $id_entrega = $_POST['id'];

    $queryShow = "SELECT ruta FROM entregas WHERE id_entrega=".$id_entrega;

   $resultadoShow = mysqli_query($conexion, $queryShow);

   $numEntrega = mysqli_num_rows($resultadoShow);

   $ruta = mysqli_fetch_assoc($resultadoShow);

   if($numEntrega>0){
       $entregaruta = array();
       $respuesta["estado"] = 1;
       $respuesta["mensaje"] = "Entrega encontrada";
       $respuesta["ruta"] = $ruta;

   }
   else{
       $respuesta["estado"]= 0;
       $respuesta["mensaje"] = "No se encontro el archivo";
   }

   echo json_encode($respuesta);
}

?>