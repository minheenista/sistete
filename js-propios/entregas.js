

function subirArchivo() {
        //alert('Hola');

        nombreEntrega= document.getElementById("nombre-entrega").value;
        fechaEntrega="2022-05-15";

        var formData = new FormData();
        var files = $('#archivo')[0].files[0];
        formData.append('file',files);
        formData.append('accion','create');
        formData.append('nombre-entrega', nombreEntrega);
        formData.append('fecha', fechaEntrega);
        $.ajax({
            url: 'php-propios/entregas.php',
            type: 'POST',
            data: formData,            
            contentType: false,
            processData: false,
            success: function(respuesta) {

                alert(respuesta);

               let miObjetoJSON = JSON.parse(respuesta);

                if(miObjetoJSON.estado==1){
                    
                    //agregamos el registro a la tabla
                    let tabla = $("#entregas").DataTable();

                    let botones = '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".modal-ver" onclick="identificarMostrar('+miObjetoJSON.id+');"> <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</button>';
                     botones += ' <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".modal-edit" onclick="identificarActualizar('+miObjetoJSON.id+')"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button>';
                     botones += ' <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target=".modal-eliminar" onclick="identificarEliminar('+miObjetoJSON.id+');"><span class="glyphicon glyphicon-trash" aria-hidden="true" ></span> Del</button> </td>';
    
                    tabla.row.add([nombreEntrega, fechaEntrega, botones]).draw().node().id="renglon_"+miObjetoJSON.id;
                     
    
                    
                    //mostramos mensaje al usuario
                    toastr.success(miObjetoJSON.mensaje);
                }
                else{
                    //alert(respuesta);
                    //mandamos un error al usuario
                    toastr.error(miObjetoJSON.mensaje);
                }
            }
        });
        
    }
            

function actionRead(){

    $.ajax({
        method: 'POST',
        url: 'php-propios/entregas.php',
        data:{
            accion:'read'
        },      
        success: function(respuesta) {

            //alert("success");

            //alert(respuesta);
        
            let miObjetoJSON = JSON.parse(respuesta);

 
            if(miObjetoJSON.estado==1){
                //agregamos el registro a la tabla
                let tabla = $("#entregas").DataTable();

                miObjetoJSON.entregas.forEach(entrega =>{
                    let botones = '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".modal-ver" onclick="identificarMostrar('+entrega.id_entrega+')"> <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</button>';
                    botones += ' <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".modal-edit" onclick="identificarActualizar('+entrega.id_entrega+');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button>';
                    botones += ' <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target=".modal-eliminar" onclick="identificarEliminar('+entrega.id_entrega+');"><span class="glyphicon glyphicon-trash" aria-hidden="true" ></span> Del</button> </td>';

                    tabla.row.add([entrega.nombre_entrega, entrega.fecha, botones]).draw().node().id="renglon_"+entrega.id_entrega;
                  

                });
            }
            
        }
    });

}

function identificarActualizar(id) {
    idActualizar = id;

    $.ajax({
        method: 'POST',
        url: 'php-propios/entregas.php',
        data:{
            id: idActualizar,
            accion:'read-id'
        },      
        success: function(respuesta) {

            alert(respuesta);
        
            let miObjetoJSON = JSON.parse(respuesta);
            if(miObjetoJSON.estado==1){
                //mostramos en la ventana de actualizar los datos recuperados
               let nombreEntregaActualizar = document.getElementById("nombre-entrega-actualizar");
               nombreEntregaActualizar.value = miObjetoJSON.nombre_entrega;

            }
            else{
                toastr.error(miObjetoJSON.mensaje);
            }
            
        }
    });
    
    
}


function actionUpdate(){
    //alert("hola");

    let nombreEntregaActualizar = document.getElementById("nombre-entrega-actualizar").value;

    let fechaEntregaActualizar = "2022-01-03"

    var formData = new FormData();
    var files = $('#archivo-actualizar')[0].files[0];
    formData.append('file',files);
    formData.append('accion','update');
    formData.append('nombre-entrega-actualizar', nombreEntregaActualizar);
    formData.append('fecha', fechaEntregaActualizar);
    $.ajax({
        url: 'php-propios/entregas.php',
        type: 'POST',
        data: {id: idActualizar, 
            formData,
            accion: "update", 
        },

        contentType: false,
        processData: false,
        success: function(respuesta) {

            let miObjetoJSON = JSON.parse(respuesta);

            alert(respuesta);

            if(miObjetoJSON.estado==1){
                
                //agregamos el registro a la tabla
                let tabla = $("#entregas").DataTable();

                //1. 
                let temp = tabla.row("#renglon_"+idActualizar).data();
                //2. 
                temp[0] = nombreEntregaActualizar;
                //3. 
                tabla.row("#renglon_"+idActualizar).data(temp).draw();

                
                //mostramos mensaje al usuario
                toastr.success(miObjetoJSON.mensaje);
            }
            else{
                //alert(respuesta);
                //mandamos un error al usuario
                toastr.error(miObjetoJSON.mensaje);
            }
        }
    });

}
     
function identificarEliminar(id){
    //alert(id);
    idEliminar = id;
}

function actionDelete(){
    $.ajax({
        url: 'php-propios/entregas.php',
        type: 'POST',
        data: {id: idEliminar, 
            accion: "delete", 
        },
        success: function(respuesta) {

            //alert("holaaa");

            let miObjetoJSON = JSON.parse(respuesta);

            //alert(respuesta);

            if(miObjetoJSON.estado==1){
                
                let tabla= $("#entregas").DataTable();
                tabla.row("#renglon_"+idEliminar).remove().draw();
                
                //mostramos mensaje al usuario
                toastr.success(miObjetoJSON.mensaje);
            }
            else{
                //alert(respuesta);
                //mandamos un error al usuario
                toastr.error(miObjetoJSON.mensaje);
            }
        }
    });

}

function identificarMostrar(id){
    //alert(id);
    idArchivo = id;

    $.ajax({
        method: "POST",
        url: "php-propios/entregas.php",
        data: {
            id: idArchivo,
            //ruta: rutaArchivo,
            accion: "show",
        },
        success: function(respuesta){

            alert(respuesta);
            let miObjetoJSON = JSON.parse(respuesta);
            if(miObjetoJSON.estado == 1){
               //mostrar en el frame 
                /* $("#modal-ver").modal('show');
                $("#mostrarPdf").attr('src', ''+miObjetoJSON.ruta)

                $("#modal-ver .modal-body") */

                let frame = "<iframe id='mostrarPdf' src="+miObjetoJSON.ruta+"></iframe>";

                $("#modal-ver .modal-body").draw(frame) ;

            }
            else{
                toastr.error(miObjetoJSON.mensaje);
            }

        }
});
}
