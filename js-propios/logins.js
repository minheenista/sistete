function loginAlumno(){
    //obtener usuario y contraseña
    let boleta = document.getElementById('boleta').value;
    let password = document.getElementById('password').value;


    $.ajax({
        method: "POST",
        url: "php-propios/login_alumno.php",
        data: {
          boleta: boleta,
          password: password,
          action: "validar"
        },
        success: function( respuesta ) {
          let miObjetoJSON = JSON.parse(respuesta);
            
          alert(respuesta);
          if(miObjetoJSON.estado==1){
            window.location.href = "index_alumno.php";
          } else{
            alert("Usuario y/o contraseña incorrectos");
          }
        }
      });
}

